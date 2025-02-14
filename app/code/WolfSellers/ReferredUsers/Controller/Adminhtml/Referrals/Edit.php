<?php

namespace WolfSellers\ReferredUsers\Controller\Adminhtml\Referrals;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\AbstractResult;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface as Logger;
use WolfSellers\ReferredUsers\Api\ReferralRepositoryInterface as RepositoryInterface;
use WolfSellers\ReferredUsers\Api\Data\ReferralInterface as DataInterface;

class Edit extends \Magento\Backend\App\Action
{
    /** @var PageFactory */
    protected PageFactory $resultPageFactory;

    /** @var RepositoryInterface */
    protected RepositoryInterface $repository;

    /** @var DataInterface */
    protected DataInterface $model;

    /** @var Logger $logger */
    protected Logger $logger;

    /**
     * Constructor.
     *
     * @param Action\Context      $context
     * @param PageFactory         $resultPageFactory
     * @param RepositoryInterface $repository
     * @param DataInterface       $model,
     * @param Logger $logger
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        RepositoryInterface $repository,
        DataInterface $model,
        Logger $logger
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->repository = $repository;
        $this->model = $model;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * @return true
     */
    protected function _isAllowed(): true
    {
        return true;
    }

    /**
     * Init actions
     *
     * @return Page
     */
    protected function _initAction(): Page
    {
        // load layout and set active menu.
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        return $resultPage;
    }

    /**
     * Edit referred user.
     *
     * @return AbstractResult|Page
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute(): AbstractResult|Page
    {
        /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        try {
            $id = $this->getRequest()->getParam('entity_id');
            if ($id) {
                $this->repository->loadModel($this->model, $id);
                if (!$this->model->getId()) {
                    $this->messageManager->addErrorMessage(__('This item no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            if (!empty($data = $this->_session->getFormData())) {
                $this->model
                    ->setId($data['entity_id'] ?? null)
                    ->setFirstName($data['first_name'] ?? '')
                    ->setLastName($data['last_name'] ?? '')
                    ->setEmail($data['email'] ?? '')
                    ->setPhone($data['phone'] ?? '')
                    ->setStatus($data['status'] ?? 'pendiente')
                    ->setCustomerId($data['customer_id'] ?? null);
            }

            $resultPage = $this->_initAction();
            $resultPage->addBreadcrumb(__('Referral Management'), __('Referred Users'));

            $name = '';
            if ($this->model->getFirstName()) {
                $name = $this->model->getFirstName() . ' ';
            }
            if ($this->model->getLastName())
                $name .= $this->model->getLastName();

            $label = __($id ? 'Edit %1 - %2' : 'New %1' , 'Referred User', $name);
            $prefix = $title = $label;

            $resultPage->addBreadcrumb($label, $title);
            $resultPage->getConfig()->getTitle()->prepend($prefix);

            return $resultPage;

        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->logger->error($e->getMessage());
            $this->logger->error($e->getTraceAsString());
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving %1.', 'Referred User'));
        } catch (\RuntimeException $e) {
            $this->logger->error($e->getMessage());
            $this->logger->error($e->getTraceAsString());
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving %1.', 'Referred User'));
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            $this->logger->error($e->getTraceAsString());
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving %1.', 'Referred User'));
        }

        return $resultRedirect->setPath('*/*/');
    }
}
