<?php
declare(strict_types=1);

namespace Learning\ProductSellers\Model;

use Exception;
use Learning\ProductSellers\Api\Data\ProductSellerItemInterface;
use Learning\ProductSellers\Api\Data\ProductSellerItemSearchResultsInterfaceFactory;
use Learning\ProductSellers\Api\ProductSellerItemRepositoryInterface;
use Learning\ProductSellers\Model\ResourceModel\ProductSellerItem as ProductSellerItemResourceModel;
use Learning\ProductSellers\Model\ResourceModel\ProductSellerItem\CollectionFactory;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class ProductSellerItemRepository implements ProductSellerItemRepositoryInterface
{
    /**
     * @var ProductSellerItemResourceModel
     */
    private $resource;

    /**
     * @var ProductSellerItemFactory
     */
    private $productSellerItemFactory;

    /**
     * @var CollectionFactory
     */
    private $productSellerItemCollectionFactory;

    /**
     * @var ProductSellerItemSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var ExtensibleDataObjectConverter
     */
    private $extensibleDataObjectConverter;

    /**
     * @param ProductSellerItemResourceModel $resource
     * @param ProductSellerItemFactory $productSellerItemFactory
     * @param CollectionFactory $productSellerItemCollectionFactory
     * @param ProductSellerItemSearchResultsInterfaceFactory $searchResultsFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param CollectionProcessorInterface $collectionProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ProductSellerItemResourceModel $resource,
        ProductSellerItemFactory $productSellerItemFactory,
        CollectionFactory $productSellerItemCollectionFactory,
        ProductSellerItemSearchResultsInterfaceFactory $searchResultsFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        CollectionProcessorInterface $collectionProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->productSellerItemFactory = $productSellerItemFactory;
        $this->productSellerItemCollectionFactory = $productSellerItemCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritDoc}
     */
    public function save(ProductSellerItemInterface $productSellerItem)
    {
        $productSellerItemData = $this->extensibleDataObjectConverter->toNestedArray(
            $productSellerItem,
            [],
            ProductSellerItemInterface::class
        );

        $productSellerItemModel = $this->productSellerItemFactory->create()->setData($productSellerItemData);

        try {
            $this->resource->save($productSellerItemModel);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__('Could not save the Product Seller %1.', $exception->getMessage()));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function get(int $itemId)
    {
        $productSellerItem = $this->productSellerItemFactory->create();
        $this->resource->load($productSellerItem, $itemId);

        if (!$productSellerItem->getId()) {
            throw new NoSuchEntityException(__('Product Seller with id %1 not found.', $itemId));
        }

        return $productSellerItem->getDataModel();
    }

    /**
     * {@inheritDoc}
     */
    public function getBySellerId(int $sellerId)
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(ProductSellerItemInterface::SELLER_ID, $sellerId)
            ->create();

        return $this->getList($searchCriteria)->getItems();
    }

    /**
     * {@inheritDoc}
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->productSellerItemCollectionFactory->create();
        $searchResults = $this->searchResultsFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResults->setSearchCriteria($searchCriteria);

        $items = [];
        foreach ($collection as $model) {
            $items[] = $model->getDataModel();
        }

        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * {@inheritDoc}
     */
    public function delete(ProductSellerItemInterface $productSellerItem)
    {
        try {
            $productSellerItemModel = $this->productSellerItemFactory->create();
            $this->resource->load($productSellerItemModel, $productSellerItem->getItemId());
            $this->resource->delete($productSellerItemModel);
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(__('Could not delete Product Seller: %1', $exception->getMessage()));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function deleteByList(SearchCriteriaInterface $searchCriteria)
    {
        $results = $this->getList($searchCriteria)->getItems();

        foreach ($results as $item) {
            $this->delete($item);
        }

        return true;
    }
}
