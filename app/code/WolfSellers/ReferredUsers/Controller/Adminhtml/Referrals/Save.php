<?php

namespace WolfSellers\ReferredUsers\Controller\Adminhtml\Referrals;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Psr\Log\LoggerInterface as Logger;
use WolfSellers\ReferredUsers\Api\ReferralRepositoryInterface as RepositoryInterface;

class Save extends Action
{
    /** @var RepositoryInterface */
    protected RepositoryInterface $repository;

    /**
     * @var Logger $logger
     */
    protected Logger $logger;

    /**
     * @param Context $context
     * @param RepositoryInterface $repository
     * @param Logger $logger
     */
    public function __construct(
        Action\Context $context,
        RepositoryInterface $repository,
        Logger $logger
    ) {
        parent::__construct($context);
        $this->repository = $repository;
        $this->logger = $logger;
    }

    /**
     * Save action
     *
     * @return ResultInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(): ResultInterface
    {
        $data = $this->getRequest()->getPostValue();

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {

            $model = $this->repository->create('entity_id');

            try {
                if ($model->getId()) {
                    $this->messageManager->addSuccessMessage(__('Position has been saved.'));
                    $this->_session->setData('wolfsellers_referredusers_referrals_data_form_data', false);

                    if ($this->getRequest()->getParam('back')) {
                        return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                    }

                    return $resultRedirect->setPath('*/*/');

                }

                $this->_session->setData('wolfsellers_referredusers_referrals_data_form_data', $data);
                return $resultRedirect->setPath('*/*/new');

            } catch (\Magento\Framework\Exception\LocalizedException|\Exception $e) {
                $this->logger->error($e->getMessage());
                $this->logger->error($e->getTraceAsString());
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while delete %1.', 'Route'));
            }

            $this->_session->setData('wolfsellers_referredusers_referralsdata_form_data', $data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }

        return $resultRedirect->setPath('*/*/');
    }
}
