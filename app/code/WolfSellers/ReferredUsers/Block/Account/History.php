<?php

namespace WolfSellers\ReferredUsers\Block\Account;

use Magento\Customer\Model\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Template\Context;
use Magento\Theme\Block\Html\Pager;
use WolfSellers\ReferredUsers\Model\ResourceModel\Referral\Collection;
use WolfSellers\ReferredUsers\Model\ResourceModel\Referral\CollectionFactory;

class History extends \Magento\Framework\View\Element\Template
{
    /** @var string template */
    protected $_template = 'WolfSellers_ReferredUsers::myreferrals.phtml';

    /** @var Session */
    protected $_customerSession;

    /** @var CollectionFactory */
    protected $_referralCollectionFactory;

    /** @var Collection */
    protected $referrals;

    /**
     * Constructor.
     *
     * @param Context $context
     * @param CollectionFactory $referralCollectionFactory
     * @param Session $customerSession
     * @param array $data
     */
    public function __construct(
        Context $context,
        CollectionFactory $referralCollectionFactory,
        Session $customerSession,
        array $data = []
    ) {
        $this->_referralCollectionFactory = $referralCollectionFactory;
        $this->_customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__('My Referrals'));
    }

    /**
     * Get customer referrals
     *
     * @return Collection|bool|\Magento\Sales\Model\ResourceModel\Order\Collection
     */
    public function getReferrals(): Collection|bool|\Magento\Sales\Model\ResourceModel\Order\Collection
    {
        if (!($customerId = $this->_customerSession->getCustomerId())) {
            return false;
        }

        if (!$this->referrals) {
            $this->referrals = $this->_referralCollectionFactory->create()->addCustomerFilter($customerId);
        }
        return $this->referrals;
    }

    /**
     * @inheritDoc
     * @throws LocalizedException
     */
    protected function _prepareLayout(): History|AbstractBlock|static
    {
        parent::_prepareLayout();
        if ($this->getReferrals()) {
            $pager = $this
                ->getLayout()
                ->createBlock(Pager::class, 'sales.referrals.history.pager')
                ->setCollection($this->getReferrals());
            $this->setChild('pager', $pager);
            $this->getReferrals()->load();
        }
        return $this;
    }

    /**
     * Get Pager child block output.
     *
     * @return string
     */
    public function getPagerHtml(): string
    {
        return $this->getChildHtml('pager');
    }

    /**
     * Get customer account URL.
     *
     * @return string
     */
    public function getBackUrl(): string
    {
        return $this->getUrl('customer/account/');
    }
}
