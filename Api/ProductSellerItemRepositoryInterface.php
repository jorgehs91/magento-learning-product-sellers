<?php
declare(strict_types=1);

namespace Learning\ProductSellers\Api;

interface ProductSellerItemRepositoryInterface
{
    /**
     * @param \Learning\ProductSellers\Api\Data\ProductSellerItemInterface $productSellerItem
     * @return \Learning\ProductSellers\Api\Data\ProductSellerItemInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Learning\ProductSellers\Api\Data\ProductSellerItemInterface $productSellerItem);

    /**
     * @param int $itemId
     * @return \Learning\ProductSellers\Api\Data\ProductSellerItemInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get(int $itemId);

    /**
     * @param int $sellerId
     * @return \Learning\ProductSellers\Api\Data\ProductSellerItemInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getBySellerId(int $sellerId);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Learning\ProductSellers\Api\Data\ProductSellerItemSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * @param \Learning\ProductSellers\Api\Data\ProductSellerItemInterface $productSellerItem
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Learning\ProductSellers\Api\Data\ProductSellerItemInterface $productSellerItem);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteByList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
