<?php
declare(strict_types=1);

namespace Learning\ProductSellers\Controller\Adminhtml\Index;

use Learning\ProductSellers\Controller\Adminhtml\ProductSeller;
use Learning\ProductSellers\Model\ResourceModel\ProductSeller\CollectionFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Ui\Component\MassAction\Filter;

class MassDelete extends ProductSeller
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param CollectionFactory $collectionFactory
     * @param Filter $filter
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        CollectionFactory $collectionFactory,
        Filter $filter
    ) {
        parent::__construct(
            $context,
            $coreRegistry
        );
        $this->collectionFactory = $collectionFactory;
        $this->filter = $filter;
    }

    /**
     * {@inheritDoc}
     * @throws LocalizedException
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();

        foreach ($collection as $productSeller) {
            $productSeller->delete();
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        $this->messageManager->addSuccessMessage(__('A total of %1 element(s) have been deleted.', $collectionSize));

        return $resultRedirect->setPath('*/*/');
    }
}
