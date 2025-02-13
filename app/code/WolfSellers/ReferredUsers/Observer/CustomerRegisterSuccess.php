<?php

namespace WolfSellers\ReferredUsers\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\Message\ManagerInterface;
use WolfSellers\ReferredUsers\Model\ReferralFactory;

class CustomerRegisterSuccess implements ObserverInterface
{
    /** @var ReferralFactory */
    protected $referralFactory;

    /** @var CustomerFactory */
    protected $customerFactory;

    /** @var ManagerInterface */
    protected $messageManager;

    /**
     * @param ReferralFactory $referralFactory
     * @param CustomerFactory $customerFactory
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        ReferralFactory $referralFactory,
        CustomerFactory $customerFactory,
        ManagerInterface $messageManager
    ) {
        $this->referralFactory = $referralFactory;
        $this->customerFactory = $customerFactory;
        $this->messageManager = $messageManager;
    }

    /**
     * Function that checks if the new customer has been referred. If so, the customer status is updated in the
     * referral model and a welcome message is given for being a referred customer.
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        $customer = $observer->getEvent()->getCustomer();
        $customerEmail = $customer->getEmail();

        // It's verified if the client's email is in the referral model and that it has a pending status.
        $referralCollection = $this->referralFactory->create()->getCollection()
            ->addFieldToFilter('email', $customerEmail)
            ->addFieldToFilter('status', 'pendiente');

        if ($referralCollection->getSize()) {
            foreach ($referralCollection as $referral) {
                // The customer status is updated in the referral model.
                $referral->setStatus('registrado');
                $referral->save();

                /**
                 * TODO:
                 *  - notify the customer that their referral has successfully registered.
                 */
            }

            $this->messageManager->addSuccessMessage(__('Thank you for registering through an invitation.'));
        }
    }
}
