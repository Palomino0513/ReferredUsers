<?php
namespace WolfSellers\ReferredUsers\Api;

use Magento\Framework\Exception\LocalizedException;
use WolfSellers\ReferredUsers\Api\Data\ReferralSearchResultsInterface;

/**
 * Interface for Referral Repository.
 *
 * @api
 */
interface ReferralRepositoryInterface
{
    /**
     * Get list of referrals by customer ID.
     *
     * @param int $customerId
     * @return ReferralSearchResultsInterface
     * @throws LocalizedException
     */
    public function getListByCustomerId(int $customerId);
}
