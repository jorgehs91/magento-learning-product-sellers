<?php
declare(strict_types=1);

namespace Learning\ProductSellers\Api\Data;

interface ProductSellerInterface
{
    const SELLER_ID = 'seller_id';
    const SELLER_NAME = 'seller_name';
    const SELLER_TELEPHONE = 'seller_telephone';

    /**
     * @return int|null
     */
    public function getSellerId();

    /**
     * @return string|null
     */
    public function getSellerName();

    /**
     * @return string|null
     */
    public function getSellerTelephone();

    /**
     * @param int $sellerId
     * @return $this
     */
    public function setSellerId(int $sellerId);

    /**
     * @param string $sellerName
     * @return $this
     */
    public function setSellerName(string $sellerName);

    /**
     * @param string $sellerTelephone
     * @return $this
     */
    public function setSellerTelephone(string $sellerTelephone);
}
