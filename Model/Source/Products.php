<?php
declare(strict_types=1);

namespace Learning\ProductSellers\Model\Source;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Option\ArrayInterface;

class Products implements ArrayInterface
{
    /**
     * @var CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @param CollectionFactory $productCollectionFactory
     */
    public function __construct(CollectionFactory $productCollectionFactory)
    {
        $this->productCollectionFactory = $productCollectionFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function toOptionArray(): array
    {
        $collection = $this->productCollectionFactory->create();

        $options = [];

        foreach ($collection as $product) {
            $options[] = ['label' => $product->getSku(), 'value' => $product->getId()];
        }

        return $options;
    }
}
