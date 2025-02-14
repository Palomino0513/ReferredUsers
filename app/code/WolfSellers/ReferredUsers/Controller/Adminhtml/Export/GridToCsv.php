<?php

namespace WolfSellers\ReferredUsers\Controller\Adminhtml\Export;

use Magento\Ui\Controller\Adminhtml\Export\GridToCsv as MagentoGridToCsv;

/**
 * Controller to perform export
 */
class GridToCsv extends MagentoGridToCsv
{

    /**
     * Authorization level of a basic admin session.
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'WolfSellers_ReferredUsers::referrals_view';

}
