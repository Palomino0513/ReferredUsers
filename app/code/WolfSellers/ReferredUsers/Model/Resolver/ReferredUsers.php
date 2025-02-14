<?php

namespace WolfSellers\ReferredUsers\Model\Resolver;

use Magento\Framework\GraphQl\Query\Resolver\Argument\SearchCriteria\Builder as SearchCriteriaResolver;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use WolfSellers\ReferredUsers\Model\ResourceModel\Referral\CollectionFactory;

class ReferredUsers implements ResolverInterface
{
    /** @var CollectionFactory */
    protected CollectionFactory $collectionFactory;

    /** @var SearchCriteriaResolver */
    protected SearchCriteriaResolver $searchCriteriaResolver;

    /**
     * Constructor.
     *
     * @param CollectionFactory $collectionFactory
     * @param SearchCriteriaResolver $searchCriteriaResolver
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        SearchCriteriaResolver $searchCriteriaResolver
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->searchCriteriaResolver = $searchCriteriaResolver;
    }

    /**
     * Function to return referred users by filter.
     *
     * @param Field $field
     * @param $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array[]
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null): array
    {
        $collection = $this->collectionFactory->create();

        if (!empty($args['filter'])) {
            foreach ($args['filter'] as $field => $condition) {
                foreach ($condition as $operator => $value) {
                    $collection->addFieldToFilter($field, [$operator => $value]);
                }
            }
        }

        $items = [];
        foreach ($collection as $referral) {
            $items[] = [
                'entity_id' => $referral->getId(),
                'firstname' => $referral->getFirstname(),
                'lastname' => $referral->getLastname(),
                'email' => $referral->getEmail(),
                'phone' => $referral->getPhone(),
                'status' => $referral->getStatus(),
                'customer_id' => $referral->getCustomerId(),
            ];
        }

        return ['items' => $items];
    }
}
