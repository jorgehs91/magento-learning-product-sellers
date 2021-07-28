<?php
declare(strict_types=1);

namespace Learning\ProductSellers\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface ProductSellerItemSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \Learning\ProductSellers\Api\Data\ProductSellerItemInterface[]
     */
    public function getItems();

    /**
     * @param \Learning\ProductSellers\Api\Data\ProductSellerItemInterface[] $items
     * @return ProductSellerItemSearchResultsInterface
     */
    public function setItems(array $items);
}
