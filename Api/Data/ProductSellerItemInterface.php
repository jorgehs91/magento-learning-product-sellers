<?php
declare(strict_types=1);

namespace Learning\ProductSellers\Api\Data;

interface ProductSellerItemInterface
{
    const ITEM_ID = 'item_id';
    const SELLER_ID = 'seller_id';
    const PRODUCT_ID = 'product_id';

    /**
     * @return int|null
     */
    public function getItemId();

    /**
     * @return int|null
     */
    public function getSellerId();

    /**
     * @return int|null
     */
    public function getProductId();

    /**
     * @param int $itemId
     * @return $this
     */
    public function setItemId(int $itemId);

    /**
     * @param int $sellerId
     * @return $this
     */
    public function setSellerId(int $sellerId);

    /**
     * @param int $productId
     * @return $this
     */
    public function setProductId(int $productId);
}
