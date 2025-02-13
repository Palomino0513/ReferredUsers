<?php

namespace WolfSellers\ReferredUsers\Model\ResourceModel\Referral;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use WolfSellers\ReferredUsers\Model\Referral as ReferralModel;
use WolfSellers\ReferredUsers\Model\ResourceModel\Referral as ReferralResource;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(ReferralModel::class, ReferralResource::class);
    }

    /**
     * Add customer filter.
     *
     * @param int $customerId
     * @return $this
     */
    public function addCustomerFilter(int $customerId): static
    {
        return $this->addFieldToFilter('customer_id', $customerId);
    }
}
