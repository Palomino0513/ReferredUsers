<?php

namespace WolfSellers\ReferredUsers\Controller\Index;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;

class Index implements ActionInterface, HttpGetActionInterface
{
    /** @var PageFactory */
    protected PageFactory $resultPageFactory;

    /** @var Session */
    protected Session $_customerSession;

    /** @var RedirectFactory */
    protected RedirectFactory $resultRedirectFactory;

    /** @var ManagerInterface */
    protected ManagerInterface $messageManager;

    /**
     * Constructor.
     *
     * @param PageFactory $resultPageFactory
     * @param Session $customerSession
     * @param RedirectFactory $resultRedirectFactory
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        PageFactory $resultPageFactory,
        Session $customerSession,
        RedirectFactory $resultRedirectFactory,
        ManagerInterface $messageManager
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_customerSession = $customerSession;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->messageManager = $messageManager;
    }

    /**
     * Customer referrals history.
     *
     * @return Page|Redirect
     */
    public function execute(): Page|Redirect
    {
        // It's validated that the user is logged in.
        if (!$this->_customerSession->getCustomerId()) {
            $this->messageManager->addErrorMessage(__('You must be logged in to view the referrals.'));
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('customer/account/login');
            return $resultRedirect;
        }
        // The table of users referred to the customer is displayed.
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('My Referrals'));

        return $resultPage;
    }
}
