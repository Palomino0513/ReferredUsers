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

class Save extends Action implements AccountInterface
{
    /** @var Validator */
    protected Validator $formKeyValidator;

    /** @var ReferralFactory */
    protected ReferralFactory $referralFactory;

    /** @var Session */
    protected Session $customerSession;

    /**
     * Constructor.
     *
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
     * Create a new referral from the given POST data.
     *
     * @return ResponseInterface
     *@throws \Exception
     * @throws LocalizedException
     */
    public function execute(): ResponseInterface
    {
        // It's validated that the information comes from a secure source.
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            $this->messageManager->addErrorMessage(__('Invalid session. Please reload the page.'));
            return $this->_redirect('*/*/create');
        }

        // It's validated that the client is logged in.
        if (!$this->customerSession->isLoggedIn()) {
            $this->messageManager->addErrorMessage(__('You must log in to create a referral.'));
            return $this->_redirect('customer/account/login');
        }

        // The information of the referred user is obtained.
        $data = $this->getRequest()->getPostValue();

        if (!empty($data)) {
            try {
                // The email is searched for in the referred users table.
                $existingReferral = $this->referralFactory->create()->getCollection()
                    ->addFieldToFilter('email', $data['email'])
                    ->addFieldToFilter('customer_id', $this->customerSession->getCustomerId())
                    ->getFirstItem();
                // It's validated that the referred user doesn't exist.
                if ($existingReferral->getId()) {
                    throw new LocalizedException(__('You have already referred this email address.'));
                }
                // The referred user record is created.
                $referral = $this->referralFactory->create();
                $referral->setData($data);
                $referral->setCustomerId($this->customerSession->getCustomerId());
                $referral->save();
                // A message is displayed that the referred user has been created.
                $this->messageManager->addSuccessMessage(__('Referral has been successfully created.'));
                return $this->_redirect('referrals/index/index');
            } catch (LocalizedException $e) {
                // If the customer don't have permissions to create referred users, an error message is displayed.
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                // In case of any error, the error message is displayed.
                $this->messageManager->addExceptionMessage($e, __('An error occurred while saving the referral.'));
            }
        } else {
            // There is no information to create the referred user.
            $this->messageManager->addErrorMessage(__('No data has been received to save.'));
        }

        return $this->_redirect('*/*/create');
    }
}
