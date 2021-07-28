<?php
declare(strict_types=1);

namespace Learning\ProductSellers\Model\ResourceModel;

use Learning\ProductSellers\Api\Data\ProductSellerInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ProductSeller extends AbstractDb
{
    /**
     * {@inheritDoc}
     */
    protected function _construct()
    {
        $this->_init('learning_product_seller', ProductSellerInterface::SELLER_ID);
    }
}
