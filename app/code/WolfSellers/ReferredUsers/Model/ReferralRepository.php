<?php

namespace WolfSellers\ReferredUsers\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\AuthorizationException;
use Magento\Authorization\Model\UserContextInterface;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\Exception\LocalizedException;
use WolfSellers\ReferredUsers\Api\Data\ReferralSearchResultsInterface;
use WolfSellers\ReferredUsers\Api\ReferralRepositoryInterface;
use WolfSellers\ReferredUsers\Api\Data\ReferralInterfaceFactory;
use WolfSellers\ReferredUsers\Api\Data\ReferralSearchResultsInterfaceFactory;
use WolfSellers\ReferredUsers\Model\ResourceModel\Referral\CollectionFactory as ReferralCollectionFactory;

class ReferralRepository implements ReferralRepositoryInterface
{
    /** @var ReferralInterfaceFactory */
    protected ReferralInterfaceFactory $referralFactory;

    /** @var ReferralCollectionFactory */
    protected ReferralCollectionFactory $referralCollectionFactory;

    /** @var ReferralSearchResultsInterfaceFactory */
    protected ReferralSearchResultsInterfaceFactory $searchResultsFactory;

    /** @var CollectionProcessorInterface */
    protected CollectionProcessorInterface $collectionProcessor;

    /** @var UserContextInterface */
    protected UserContextInterface $userContext;

    /** @var AuthorizationInterface */
    protected AuthorizationInterface $authorization;

    /**
     * Constructor.
     *
     * @param ReferralInterfaceFactory $referralFactory
     * @param ReferralCollectionFactory $referralCollectionFactory
     * @param ReferralSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param UserContextInterface $userContext
     * @param AuthorizationInterface $authorization
     */
    public function __construct(
        ReferralInterfaceFactory $referralFactory,
        ReferralCollectionFactory $referralCollectionFactory,
        ReferralSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor,
        UserContextInterface $userContext,
        AuthorizationInterface $authorization
    ) {
        $this->referralFactory = $referralFactory;
        $this->referralCollectionFactory = $referralCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->userContext = $userContext;
        $this->authorization = $authorization;
    }

    /**
     * Function to get referrals by customer id.
     *
     * {@inheritdoc}
     */
    public function getListByCustomerId(int $customerId): ReferralSearchResultsInterface
    {
        // It's validated that the user id is correct.
        if (!is_numeric($customerId) || (int)$customerId <= 0) {
            throw new LocalizedException(__('Invalid customer ID'));
        }
        // It validates that we're in the context of the Magento administrator.
        if ($this->userContext->getUserType() != UserContextInterface::USER_TYPE_ADMIN) {
            throw new AuthorizationException(__('Access denied.'));
        }
        // It's validated that the user has the permissions to access the referred users.
        if (!$this->authorization->isAllowed('WolfSellers_ReferredUsers::referrals_view')) {
            throw new AuthorizationException(__('You do not have permission to view referrals.'));
        }
        // The collection of referred users from the client is obtained.
        $collection = $this->referralCollectionFactory->create();
        $collection->addFieldToFilter('customer_id', (int)$customerId);
        // Pagination data is calculated for the collection.
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        // The collection is returned.
        return $searchResults;
    }
}
