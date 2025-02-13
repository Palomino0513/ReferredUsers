<?php
namespace WolfSellers\ReferredUsers\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for Referral search results.
 *
 * @api
 */
interface ReferralSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get list of referrals.
     *
     * @return ReferralInterface[]
     */
    public function getItems();

    /**
     * Set list of referrals.
     *
     * @param ReferralInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
