<?php
declare(strict_types=1);

namespace Learning\ProductSellers\Api;

interface ProductSellerRepositoryInterface
{
    /**
     * @param \Learning\ProductSellers\Api\Data\ProductSellerInterface $productSeller
     * @return \Learning\ProductSellers\Api\Data\ProductSellerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Learning\ProductSellers\Api\Data\ProductSellerInterface $productSeller);

    /**
     * @param int $sellerId
     * @return \Learning\ProductSellers\Api\Data\ProductSellerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get(int $sellerId);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Learning\ProductSellers\Api\Data\ProductSellerSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * @param \Learning\ProductSellers\Api\Data\ProductSellerInterface $productSeller
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Learning\ProductSellers\Api\Data\ProductSellerInterface $productSeller);

    /**
     * @param int $sellerId
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById(int $sellerId);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteByList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
