<?php

namespace WolfSellers\ReferredUsers\Controller\Adminhtml\Import;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Filesystem\DirectoryListFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\File\Csv;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface as Logger;
use Magento\Framework\App\ResourceConnection;
use WolfSellers\ReferredUsers\Api\ReferralRepositoryInterface as RepositoryInterface;

class Save extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session.
     *
     * @see _isAllowed()
     */
    const string ADMIN_RESOURCE = 'WolfSellers_ReferredUsers::referrals_view';

    /** @var Magento\Backend\App\Action\Context */
    private $context;

    /** @var PageFactory */
    private $pageFactory;

    /** @var \Magento\Framework\App\Filesystem\DirectoryList */
    private $directoryList;

    /** @var WriteInterface */
    protected $directory;

    /** @var \Magento\Framework\Filesystem */
    private $filesystem;

    /** @var \Magento\Framework\File\Csv */
    private $csvParser;

    /** @var \Magento\Framework\App\Filesystem\DirectoryListFactory */
    private $directoryListFactory;

    /** @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface */
    private $timezone;

    /** @var \Magento\Framework\Filesystem\Io\File */
    private $file;

    /** @var RepositoryInterface */
    protected $repository;

    /** @var Logger */
    protected $logger;

    /** @var SearchCriteriaBuilder */
    private $searchCriteriaBuilder;

    /** @var ResourceConnection */
    private $resourceConnection;

    /** @var CategorySorter */
    protected $categorySorter;

    /**
     * Save constructor.
     *
     * @param Context $context
     * @param PageFactory $pageFactory
     * @param DirectoryListFactory $directoryListFactory
     * @param DirectoryList $directoryList
     * @param File $file
     * @param Filesystem $filesystem
     * @param Csv $csvParser
     * @param TimezoneInterface $timezone
     * @param RepositoryInterface $repository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ResourceConnection $resourceConnection
     * @param CategorySorter $categorySorter
     * @param Logger $logger
     * @throws FileSystemException
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Framework\App\Filesystem\DirectoryListFactory $directoryListFactory,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Filesystem\Io\File $file,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\File\Csv $csvParser,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        RepositoryInterface $repository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ResourceConnection $resourceConnection,
        CategorySorter $categorySorter,
        Logger $logger
    )
    {
        parent::__construct($context);
        $this->context = $context;
        $this->pageFactory = $pageFactory;
        $this->filesystem = $filesystem;
        $this->directoryList = $directoryList;
        $this->directory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR);
        $this->csvParser = $csvParser;
        $this->directoryListFactory = $directoryListFactory;
        $this->timezone = $timezone;
        $this->file = $file;
        $this->repository = $repository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->resourceConnection = $resourceConnection;
        $this->categorySorter = $categorySorter;
        $this->logger = $logger;
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute(): ResultInterface|ResponseInterface|Redirect
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        $deleteInformation = $this->getRequest()->getParam('DeletePositionExists');
        $requestedFile = $_FILES['files'];

        if (empty($requestedFile['tmp_name'][0]) && isset($requestedFile['tmp_name'][0])) {
            $this->messageManager->addErrorMessage(__('The file has not been imported, please try again.'));
        } else {
            try {

                if (!isset($requestedFile['tmp_name'][0])) {
                    $this->messageManager->addErrorMessage('Invalid file upload attempt.');
                    return $resultRedirect->setPath('*/*/');
                }

                $mimetypes = array(
                    'text/x-comma-separated-values',
                    'text/comma-separated-values',
                    'application/vnd.ms-excel',
                    'text/csv'
                );

                if (!in_array($requestedFile['type'][0], $mimetypes)) {
                    $this->messageManager->addErrorMessage('The file contains an invalid format, only csv files are allowed.');
                    return $resultRedirect->setPath('*/*/');
                }

                //Reading the csv file
                $this->csvParser->setDelimiter(',');
                $this->csvParser->setEnclosure('"');
                $rawData = $this->csvParser->getData($requestedFile['tmp_name'][0]);

                //Convert csv in array
                $formattedData = $this->convertCsvToArray($rawData);

                if( $deleteInformation == 'on' ){
                    $this->exportDataCsv();
                    $this->deleteRoutes();
                }

                $this->save($formattedData);
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException|\RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e);
            }
        }

        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @param $rawData
     * @return array
     */
    protected function convertCsvToArray($rawData): array
    {
        $data = array();
        foreach ($rawData as $rowIndex => $dataRow) {
            // skip headers
            if ($rowIndex == 0) {
                continue;
            }

            $data[] = [
                'customer_id'   => $dataRow[0],
                'first_name'    => $dataRow[1],
                'last_name'     => $dataRow[2],
                'email'         => $dataRow[3],
                'phone'         => $dataRow[4],
                'status'        => $dataRow[5],
            ];
        }

        return $data;
    }

    /**
     * @param $array
     * @param bool $xml
     * @return string|bool
     */
    protected function convertArrayToXml($array, bool $xml = false): string|bool
    {
        if($xml === false) {
            $xml = new \SimpleXMLElement('<items/>');
        }

        foreach($array as $key => $value) {
            if(is_array($value)) {
                $this->convertArrayToXml($value, $xml->addChild('item'));
            } else {
                $xml->addChild($key, $value);
            }
        }

        return $xml->asXML();
    }

    /**
     * @param $arrayData
     * @return boolean
     */
    protected function save($arrayData = null): bool
    {

        $count          = 0;
        if( sizeof($arrayData) > 0 ) {
            for( $i = 0; $i <= (sizeof($arrayData)-1); $i++ ){
                $this->categorySorter->updateProductPosition(
                    $arrayData[$i]['customer_id'],
                    $arrayData[$i]['first_name'],
                    $arrayData[$i]['last_name'],
                    $arrayData[$i]['email'],
                    $arrayData[$i]['phone'],
                    $arrayData[$i]['status']
                );
                $count++;
            }
            $this->messageManager->addSuccessMessage(__('It was added ' . $count . ' positions.'));
        }

        return true;
    }

    /**
     * Create backup to routes.
     *
     * @return void
     */
    protected function exportDataCsv(): void
    {
        $filename = 'backup-positions-' . date('Ymd') . '.csv';
        $dir = BP . DIRECTORY_SEPARATOR . 'var/export/routes/';

        if (!is_dir($dir)) {
            $old = umask(0);
            mkdir($dir, 0777, true);
            umask($old);
        }

        $collection = $this->repository->getCollection();
        if( $collection->getSize() > 0 ){

            $file = fopen($dir . $filename,"w");
            $line = [
                'customer_id',
                'first_name',
                'last_name',
                'email',
                'phone',
                'status'
            ];

            fputcsv($file,$line);
            foreach( $collection->getItems() as $index => $row ){
                $line = [
                    $row['customer_id'],
                    $row['first_name'],
                    $row['last_name'],
                    $row['email'],
                    $row['phone'],
                    $row['status']
                ];
                fputcsv($file,$line);
            }
            fclose($file);
        }
    }
}
