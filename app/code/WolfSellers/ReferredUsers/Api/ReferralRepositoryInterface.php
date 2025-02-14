<?php
namespace WolfSellers\ReferredUsers\Api;

use Magento\Framework\Exception\LocalizedException;
use WolfSellers\ReferredUsers\Api\Data\ReferralInterface;
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
     * Get referral by ID.
     * @param int $id
     * @return ReferralInterface
     * @throws LocalizedException
     */
    public function getById(int $id);

    /**
     * Delete record of referred user.
     *
     * @param int $referralId
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(int $referralId);
}
