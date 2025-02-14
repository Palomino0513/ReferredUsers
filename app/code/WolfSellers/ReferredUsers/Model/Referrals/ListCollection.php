<?php

namespace WolfSellers\ReferredUsers\Model\Referrals;

use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;
use Psr\Log\LoggerInterface as Logger;
use WolfSellers\ReferredUsers\Model\ResourceModel\Referral as ResourceModel;

class ListCollection extends SearchResult
{
    /**
     * Constructor function for Grid.
     *
     * @param EntityFactory $entityFactory
     * @param Logger $logger
     * @param FetchStrategy $fetchStrategy
     * @param EventManager $eventManager
     * @param ResourceModel $resourceModel
     * @param $identifierName
     * @param $connectionName
     * @throws LocalizedException
     */
    public function __construct(
        EntityFactory $entityFactory,
        Logger $logger,
        FetchStrategy $fetchStrategy,
        EventManager $eventManager,
        ResourceModel $resourceModel,
                      $identifierName = null,
                      $connectionName = null
    ) {
        parent::__construct($entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $resourceModel->getMainTable(),
            ResourceModel::class,
            $identifierName, $connectionName);
    }

    /**
     * Construct function.
     *
     * @return void
     */
    protected function _construct(): void
    {
        parent::_construct();

    }

    /**
     * Init function.
     *
     * @return void
     */
    protected function _initSelect(): void
    {
        parent::_initSelect();

    }
}
