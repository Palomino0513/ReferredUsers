<?php

namespace WolfSellers\ReferredUsers\Block\Adminhtml\Referrals\Edit;

use Magento\Backend\Block\Widget\Context;
use Magento\Catalog\Api\Data\CategoryLinkInterface as DataInterface;
use Magento\Framework\UrlInterface;

/**
 * Class GenericButton
 */
class GenericButton
{
    /** @var UrlInterface */
    protected UrlInterface $urlBuilder;

    /** @var DataInterface */
    protected DataInterface $model;

    /**
     * Constructor
     *
     * @param Context $context
     * @param DataInterface $model
     */
    public function __construct(
        Context $context,
        DataInterface $model
    ) {
        $this->urlBuilder = $context->getUrlBuilder();
        $this->model = $model;
    }

    /**
     * Return the model id.
     *
     * @return int|null
     */
    public function getModelId(): ?int
    {
        return $this->model?->getId();
    }

    /**
     * Generate url by route and parameters.
     *
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl(string $route = '', array $params = []): string
    {
        return $this->urlBuilder->getUrl($route, $params);
    }
}
