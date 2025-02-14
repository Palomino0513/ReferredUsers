<?php

namespace WolfSellers\ReferredUsers\Controller\Adminhtml\Referrals;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\Redirect;
use Psr\Log\LoggerInterface as Logger;

/**
 * Class MassDelete
 */
class MassDelete extends Action
{
    /** @var Logger $logger */
    protected Logger $logger;

    /**
     * MassDelete constructor.
     *
     * @param Action\Context $context
     * @param Logger         $logger
     */
    public function __construct(
        Action\Context $context,
        Logger $logger
    ) {
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * Function to delete multiple records.
     *
     * @return Redirect
     */
    public function execute(): Redirect
    {
        //throw new \Exception('Not implemented');
        $itemIds = $this->getRequest()->getParam('selected');
        if (!is_array($itemIds) || empty($itemIds)) {
            $this->messageManager->addErrorMessage(__('Please select item(s).'));
        } else {
            try {
                foreach ($itemIds as $itemId) {
                    $post = $this->_objectManager->get('WolfSellers\ReferredUsers\Api\Data\ReferralInterface')->load($itemId);
                    $post->delete();
                }
                $this->messageManager->addSuccessMessage(
                    __('A total of %1 record(s) have been deleted.', count($itemIds))
                );
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->logger->error($e->getMessage());
                $this->logger->error($e->getTraceAsString());
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while delete %1.', 'Route'));
            }
        }

        return $this->resultRedirectFactory->create()->setPath('wolf/referrals/index');
    }
}
