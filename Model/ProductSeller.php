<?php
declare(strict_types=1);

namespace Learning\ProductSellers\Model;

use Learning\ProductSellers\Api\Data\ProductSellerInterface;
use Learning\ProductSellers\Api\Data\ProductSellerInterfaceFactory;
use Learning\ProductSellers\Model\ResourceModel\ProductSeller as ProductSellerResource;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

class ProductSeller extends AbstractModel
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'learning_product_seller';

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var ProductSellerInterfaceFactory
     */
    protected $productSellerDataFactory;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param DataObjectHelper $dataObjectHelper
     * @param ProductSellerInterfaceFactory $productSellerDataFactory
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        DataObjectHelper $dataObjectHelper,
        ProductSellerInterfaceFactory $productSellerDataFactory,
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
        $this->productSellerDataFactory = $productSellerDataFactory;
    }

    /**
     * {@inheritDoc}
     */
    protected function _construct()
    {
        $this->_init(ProductSellerResource::class);
    }

    /**
     * @return ProductSellerInterface
     */
    public function getDataModel(): ProductSellerInterface
    {
        $productSellersData = $this->getData();

        $productSellersDataObject = $this->productSellerDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $productSellersDataObject,
            $productSellersData,
            ProductSellerInterface::class
        );

        return $productSellersDataObject;
    }
}
