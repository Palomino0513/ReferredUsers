<?php

namespace WolfSellers\ReferredUsers\Block\Account;

use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use WolfSellers\ReferredUsers\Model\Referral;
use WolfSellers\ReferredUsers\Model\ReferralFactory;

class Edit extends Template
{
    /** @var string template */
    protected $_template = "WolfSellers_ReferredUsers::edit.phtml";

    /** @var ReferralFactory */
    protected $_referralFactory;

    /**  @var Session */
    protected $_customerSession;

    /**
     * Constructor.
     *
     * @param Context $context
     * @param ReferralFactory $referralFactory
     * @param Session $customerSession
     * @param array $data
     */
    public function __construct(
        Context $context,
        ReferralFactory $referralFactory,
        Session $customerSession,
        array $data = []
    ) {
        $this->_referralFactory = $referralFactory;
        $this->_customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    /**
     * Get referred user by id in the param.
     *
     * @return Referral|false
     */
    public function getReferral(): false|Referral
    {
        $referralId = $this->getRequest()->getParam('id');
        $referral = $this->_referralFactory->create()->load($referralId);

        if ($referral->getCustomerId() == $this->_customerSession->getCustomerId()) {
            return $referral;
        }

        return false;
    }

    /**
     * Function to prepare layout.
     *
     * @return $this
     */
    protected function _prepareLayout(): static
    {
        $this->pageConfig->getTitle()->set(__('Update Referred User'));
        return parent::_prepareLayout();
    }
}
