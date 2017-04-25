<?php
/**
 * Created by PhpStorm.
 * User: Lozingle
 * Date: 25/04/2017
 * Time: 02:57 PM
 */
namespace Lts\CacheReindex\Controller\Adminhtml\Indexer;

use Magento\Backend\App\Action\Context;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

class Reindex extends Action
{
    protected $_indexerFactory;

    protected $_indexerCollectionFactory;


    public function __construct(
        Context $context,
        \Magento\Indexer\Model\IndexerFactory $indexerFactory,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Indexer\Model\Indexer\CollectionFactory $indexerCollectionFactory
    ) {
        parent::__construct($context);
        $this->_indexerFactory = $indexerFactory;
        $this->_indexerCollectionFactory = $indexerCollectionFactory;
    }

    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $indexerCollection = $this->_indexerCollectionFactory->create();
        $ids = $indexerCollection->getAllIds();
        foreach ($ids as $id) {
            $idx = $this->_indexerFactory->create()->load($id);
            $idx->reindexAll($id);
        }
        $this->messageManager->addSuccess(__('Total %1 indexer reindexed successfully.', $indexerCollection->getSize()));
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
