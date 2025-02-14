<?php

namespace WolfSellers\ReferredUsers\Block\Adminhtml\Referrals\Import;

use Magento\Backend\Block\Template\Context;

class Index extends \Magento\Backend\Block\Template
{
    /**
     * Constructor
     *
     * @param Context  $context
     * @param array $data
     */
    public function __construct(
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }
}
