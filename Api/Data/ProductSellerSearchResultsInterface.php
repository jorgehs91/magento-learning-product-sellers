<?php
declare(strict_types=1);

namespace Learning\ProductSellers\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface ProductSellerSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \Learning\ProductSellers\Api\Data\ProductSellerInterface[]
     */
    public function getItems();

    /**
     * @param \Learning\ProductSellers\Api\Data\ProductSellerInterface[] $items
     * @return ProductSellerSearchResultsInterface
     */
    public function setItems(array $items);
}
