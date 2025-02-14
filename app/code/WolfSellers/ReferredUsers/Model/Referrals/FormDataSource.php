<?php

namespace WolfSellers\ReferredUsers\Model\Referrals;

use Magento\Ui\DataProvider\AbstractDataProvider;
use WolfSellers\ReferredUsers\Model\ResourceModel\Referral\CollectionFactory;
use Magento\Framework\App\RequestInterface;

class FormDataSource extends AbstractDataProvider
{
    protected $collection;

    /** @var RequestInterface */
    protected $request;

    public function __construct(
        CollectionFactory $collectionFactory,
        RequestInterface $request,
                          $name,
                          $primaryFieldName,
                          $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->request = $request;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Function to get data for form.
     *
     * @return array
     */
    public function getData(): array
    {
        $data = [];
        $id = $this->request->getParam('entity_id');

        if ($id) {
            $item = $this->collection->getItemById($id);
            if ($item) {
                $data[$id] = $item->getData();
            }
        }

        return $data;
    }
}
