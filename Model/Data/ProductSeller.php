<?php
declare(strict_types=1);

namespace Learning\ProductSellers\Model\Data;

use Learning\ProductSellers\Api\Data\ProductSellerInterface;
use Magento\Framework\Api\AbstractExtensibleObject;

class ProductSeller extends AbstractExtensibleObject implements ProductSellerInterface
{
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
    public function getSellerName()
    {
        return $this->_get(self::SELLER_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getSellerTelephone()
    {
        return $this->_get(self::SELLER_TELEPHONE);
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
    public function setSellerName(string $sellerName)
    {
        return $this->setData(self::SELLER_NAME, $sellerName);
    }

    /**
     * {@inheritDoc}
     */
    public function setSellerTelephone(string $sellerTelephone)
    {
        return $this->setData(self::SELLER_TELEPHONE, $sellerTelephone);
    }
}
