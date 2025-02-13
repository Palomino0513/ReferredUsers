<?php

namespace WolfSellers\ReferredUsers\Controller\Index;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use WolfSellers\ReferredUsers\Model\ReferralFactory;

class Edit implements ActionInterface, HttpGetActionInterface
{
    /** @var PageFactory */
    protected PageFactory $resultPageFactory;

    /** @var ReferralFactory */
    protected ReferralFactory $referralFactory;

    /** @var Session */
    protected $customerSession;

    /** @var RequestInterface */
    protected $request;

    /** @var RedirectFactory */
    protected $resultRedirectFactory;

    /** @var ManagerInterface */
    protected $messageManager;

    /**
     * Constructor.
     *
     * @param PageFactory $resultPageFactory
     * @param ReferralFactory $referralFactory
     * @param Session $customerSession
     * @param RequestInterface $request
     * @param RedirectFactory $resultRedirectFactory
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        PageFactory $resultPageFactory,
        ReferralFactory $referralFactory,
        Session $customerSession,
        RequestInterface $request,
        RedirectFactory $resultRedirectFactory,
        ManagerInterface $messageManager
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->referralFactory = $referralFactory;
        $this->customerSession = $customerSession;
        $this->request = $request;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->messageManager = $messageManager;
    }

    /**
     * Edits a referral page form.
     *
     * @return Page|Redirect
     */
    public function execute(): Page|Redirect
    {
        // The referred user's record is obtained.
        $referralId = $this->request->getParam('id');
        // It's validated that the user is logged in.
        if (!$this->customerSession->isLoggedIn()) {
            $this->messageManager->addErrorMessage(__('You must be logged in to edit a referral.'));
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('customer/account/login');
            return $resultRedirect;
        }
        // The existence of the referred user id is validated.
        if (!$referralId) {
            $this->messageManager->addErrorMessage(__('Referral not found.'));
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('customer/account/login');
            return $resultRedirect;
        }
        // It's validated that the referred user's record exists.
        $referral = $this->referralFactory->create()->load($referralId);
        if (!$referral->getId() || $referral->getCustomerId() != $this->customerSession->getCustomerId()) {
            $this->messageManager->addErrorMessage(__('You do not have permission to edit this referral.'));
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('referrals/index/index');
            return $resultRedirect;
        }
        // The view to edit the referred user is displayed.
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('Update Referral'));

        return $resultPage;
    }
}
