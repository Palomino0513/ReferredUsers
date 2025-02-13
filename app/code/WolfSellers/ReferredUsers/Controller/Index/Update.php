<?php

namespace WolfSellers\ReferredUsers\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Customer\Model\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Customer\Controller\AccountInterface;
use WolfSellers\ReferredUsers\Model\ReferralFactory;

class Update extends Action implements AccountInterface
{
    /** @var Validator */
    protected Validator $formKeyValidator;

    /** @var ReferralFactory */
    protected ReferralFactory $referralFactory;

    /** @var Session */
    protected Session $customerSession;

    /**
     * @param Context $context
     * @param Validator $formKeyValidator
     * @param ReferralFactory $referralFactory
     * @param Session $customerSession
     */
    public function __construct(
        Context $context,
        Validator $formKeyValidator,
        ReferralFactory $referralFactory,
        Session $customerSession
    ) {
        parent::__construct($context);
        $this->formKeyValidator = $formKeyValidator;
        $this->referralFactory = $referralFactory;
        $this->customerSession = $customerSession;
    }

    /**
     * Updates a referral
     *
     * @return ResponseInterface
     * @throws LocalizedException
     * @throws \Exception
     */
    public function execute(): ResponseInterface
    {
        // It's validated that the information comes from a secure source.
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            $this->messageManager->addErrorMessage(__('Invalid session. Please reload the page.'));
            return $this->_redirect('*/*/edit', ['id' => $this->getRequest()->getParam('entity_id')]);
        }

        // It's validated that the client is logged in.
        if (!$this->customerSession->isLoggedIn()) {
            $this->messageManager->addErrorMessage(__('You must log in to edit a referral.'));
            return $this->_redirect('customer/account/login');
        }

        // The information of the referred user is obtained.
        $data = $this->getRequest()->getPostValue();

        if (!empty($data)) {
            try {
                // The referred user's record is obtained, and it's validated that it belongs to the client.
                $referral = $this->referralFactory->create()->load($data['entity_id']);
                if (!$referral->getId() || $referral->getCustomerId() != $this->customerSession->getCustomerId()) {
                    throw new LocalizedException(__('You do not have permission to edit this referral.'));
                }
                // The referred customer's information is updated and saved.
                $referral->setData($data);
                $referral->save();
                // A message is displayed stating that the referred user's information has been updated.
                $this->messageManager->addSuccessMessage(__('Referral has been successfully updated.'));
                return $this->_redirect('referrals/index/index');
            } catch (LocalizedException $e) {
                // If the customer don't have permissions to update referred users, an error message is displayed.
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                // In case of any error, the error message is displayed.
                $this->messageManager->addExceptionMessage($e, __('An error occurred while updating the referral.'));
            }
        } else {
            // If there is no information, an error about this problem is displayed.
            $this->messageManager->addErrorMessage(__('No data has been received to update.'));
        }

        return $this->_redirect('*/*/edit', ['id' => $data['entity_id']]);
    }
}
