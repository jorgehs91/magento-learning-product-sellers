<?php
declare(strict_types=1);

namespace Learning\ProductSellers\Model\Data;

use Learning\ProductSellers\Api\Data\ProductSellerItemInterface;
use Magento\Framework\Api\AbstractExtensibleObject;

class ProductSellerItem extends AbstractExtensibleObject implements ProductSellerItemInterface
{
    /**
     * {@inheritDoc}
     */
    public function getItemId()
    {
        return $this->_get(self::ITEM_ID);
    }

    /**
     * {@inheritDoc}
     */
    public function getSellerId()
    {
        return $this->_get(self::SELLER_ID);
    }

    /**
     * {@inheritDoc}
     */
    public function getProductId()
    {
        return $this->_get(self::PRODUCT_ID);
    }

    /**
     * {@inheritDoc}
     */
    public function setItemId(int $itemId)
    {
        return $this->setData(self::ITEM_ID, $itemId);
    }

    /**
     * {@inheritDoc}
     */
    public function setSellerId(int $sellerId)
    {
        return $this->setData(self::SELLER_ID, $sellerId);
    }

    /**
     * {@inheritDoc}
     */
    public function setProductId(int $productId)
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }
}
