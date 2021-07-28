<?php
declare(strict_types=1);

namespace Learning\ProductSellers\Model\ResourceModel;

use Learning\ProductSellers\Api\Data\ProductSellerItemInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ProductSellerItem extends AbstractDb
{
    /**
     * {@inheritDoc}
     */
    protected function _construct()
    {
        $this->_init('learning_product_seller_item', ProductSellerItemInterface::ITEM_ID);
    }
}
