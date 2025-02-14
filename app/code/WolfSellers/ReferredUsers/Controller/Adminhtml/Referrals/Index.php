<?php

namespace WolfSellers\ReferredUsers\Controller\Adminhtml\Referrals;

use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    /**
     * Resource var for acl control.
     */
    const ADMIN_RESOURCE = 'WolfSellers_ReferredUsers::referred_users';

    /** @var PageFactory */
    protected PageFactory $resultPageFactory;

    /**
     * Construction function.
     *
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Function to render Grid page.
     *
     * @return ResponseInterface|ResultInterface|Page
     */
    public function execute(): Page|ResultInterface|ResponseInterface
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Referred users'));

        return $resultPage;
    }
}
