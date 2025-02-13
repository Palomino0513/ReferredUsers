<?php

namespace WolfSellers\ReferredUsers\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Customer\Controller\AccountInterface;
use WolfSellers\ReferredUsers\Model\ReferralFactory;

class Delete extends Action implements AccountInterface
{
    /** @var ReferralFactory */
    protected ReferralFactory $referralFactory;

    /** @var Session */
    protected Session $customerSession;

    /**
     * Constructor.
     *
     * @param Context $context
     * @param ReferralFactory $referralFactory
     * @param Session $customerSession
     */
    public function __construct(
        Context $context,
        ReferralFactory $referralFactory,
        Session $customerSession
    ) {
        $this->referralFactory = $referralFactory;
        $this->customerSession = $customerSession;
        parent::__construct($context);
    }

    /**
     * Deletes a referral.
     *
     * @return ResponseInterface
     * @throws \Exception
     */
    public function execute(): ResponseInterface
    {
        // It's validated that the user is logged in.
        if (!$this->customerSession->isLoggedIn()) {
            $this->messageManager->addErrorMessage(__('You must log in to delete a referral.'));
            return $this->_redirect('customer/account/login');
        }

        // The id of the referred user is obtained.
        $referralId = $this->getRequest()->getParam('id');
        if ($referralId) {
            try {
                // The referred user's record is obtained and validated.
                $referral = $this->referralFactory->create()->load($referralId);
                if (!$referral->getId() || $referral->getCustomerId() != $this->customerSession->getCustomerId()) {
                    throw new LocalizedException(__('You do not have permission to delete this referral.'));
                }

                // The referred user is deleted.
                $referral->delete();
                // A message is displayed that the referred user was successfully deleted.
                $this->messageManager->addSuccessMessage(__('Referral has been successfully deleted.'));
            } catch (LocalizedException $e) {
                // An error is displayed to the user that they do not have permissions to delete referred users.
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                // If any error occurs, the user is informed of the problem.
                $this->messageManager->addExceptionMessage($e, __('An error occurred while deleting the referral.'));
            }
        } else {
            // A message is displayed that the referred user was not found.
            $this->messageManager->addErrorMessage(__('Referral not found.'));
        }

        return $this->_redirect('referrals/index/index');
    }
}
