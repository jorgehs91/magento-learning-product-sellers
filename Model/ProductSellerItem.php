<?php
declare(strict_types=1);

namespace Learning\ProductSellers\Model;

use Learning\ProductSellers\Api\Data\ProductSellerItemInterface;
use Learning\ProductSellers\Api\Data\ProductSellerItemInterfaceFactory;
use Learning\ProductSellers\Model\ResourceModel\ProductSellerItem as ProductSellerItemResource;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

class ProductSellerItem extends AbstractModel
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'learning_product_seller_item';

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var ProductSellerItemInterfaceFactory
     */
    protected $productSellerItemDataFactory;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param DataObjectHelper $dataObjectHelper
     * @param ProductSellerItemInterfaceFactory $productSellerItemDataFactory
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        DataObjectHelper $dataObjectHelper,
        ProductSellerItemInterfaceFactory $productSellerItemDataFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection,
            $data
        );
        $this->dataObjectHelper = $dataObjectHelper;
        $this->productSellerItemDataFactory = $productSellerItemDataFactory;
    }

    /**
     * {@inheritDoc}
     */
    protected function _construct()
    {
        $this->_init(ProductSellerItemResource::class);
    }

    /**
     * @return ProductSellerItemInterface
     */
    public function getDataModel(): ProductSellerItemInterface
    {
        $productSellerItemData = $this->getData();

        $productSellerItemDataObject = $this->productSellerItemDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $productSellerItemDataObject,
            $productSellerItemData,
            ProductSellerItemInterface::class
        );

        return $productSellerItemDataObject;
    }
}
