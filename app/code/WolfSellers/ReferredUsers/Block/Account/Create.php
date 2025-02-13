<?php

namespace WolfSellers\ReferredUsers\Block\Account;

use Magento\Framework\View\Element\Template;

class Create extends Template
{
    /** @var Template */
    protected $_template = 'WolfSellers_ReferredUsers::create.phtml';

    /**
     * Constructor
     *
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(Template\Context $context, array $data = [])
    {
        parent::__construct($context, $data);
    }

    /**
     * Function to prepare layout.
     *
     * @return $this
     */
    protected function _prepareLayout(): static
    {
        return parent::_prepareLayout();
    }

}
