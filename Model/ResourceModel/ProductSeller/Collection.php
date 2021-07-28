<?php
declare(strict_types=1);

namespace Learning\ProductSellers\Model\ResourceModel\ProductSeller;

use Learning\ProductSellers\Model\ProductSeller;
use Learning\ProductSellers\Model\ResourceModel\ProductSeller as ProductSellerResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'seller_id';

    /**
     * {@inheritDoc}
     */
    protected function _construct()
    {
        $this->_init(ProductSeller::class, ProductSellerResourceModel::class);
    }
}
