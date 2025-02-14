<?php

namespace WolfSellers\ReferredUsers\Controller\Adminhtml\Export;

use Magento\Ui\Controller\Adminhtml\Export\GridToXml as MagentoGridToXml;

/**
 * Controller to perform export
 */
class GridToXml extends MagentoGridToXml
{

    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const string ADMIN_RESOURCE = 'WolfSellers_ReferredUsers::referrals_view';

}
