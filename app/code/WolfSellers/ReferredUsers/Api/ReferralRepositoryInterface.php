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

    /**
     * Delete record of referred user.
     *
     * @param int $referralId
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(int $referralId);
}
