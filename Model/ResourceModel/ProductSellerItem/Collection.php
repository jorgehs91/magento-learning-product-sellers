<?php
declare(strict_types=1);

namespace Learning\ProductSellers\Model\ResourceModel\ProductSellerItem;

use Learning\ProductSellers\Model\ProductSellerItem;
use Learning\ProductSellers\Model\ResourceModel\ProductSellerItem as ProductSellerItemResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'item_id';

    /**
     * {@inheritDoc}
     */
    protected function _construct()
    {
        $this->_init(ProductSellerItem::class, ProductSellerItemResourceModel::class);
    }
}
