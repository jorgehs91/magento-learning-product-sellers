<?php
declare(strict_types=1);

namespace Learning\ProductSellers\Model;

use Exception;
use Learning\ProductSellers\Api\Data\ProductSellerInterface;
use Learning\ProductSellers\Api\Data\ProductSellerSearchResultsInterfaceFactory;
use Learning\ProductSellers\Api\ProductSellerRepositoryInterface;
use Learning\ProductSellers\Model\ResourceModel\ProductSeller as ProductSellerResourceModel;
use Learning\ProductSellers\Model\ResourceModel\ProductSeller\CollectionFactory;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class ProductSellerRepository implements ProductSellerRepositoryInterface
{
    /**
     * @var ProductSellerResourceModel
     */
    private $resource;

    /**
     * @var ProductSellerFactory
     */
    private $productSellerFactory;

    /**
     * @var CollectionFactory
     */
    private $productSellerCollectionFactory;

    /**
     * @var ProductSellerSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var ExtensibleDataObjectConverter
     */
    private $extensibleDataObjectConverter;

    /**
     * @param ProductSellerResourceModel $resource
     * @param ProductSellerFactory $productSellerFactory
     * @param CollectionFactory $productSellerCollectionFactory
     * @param ProductSellerSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ProductSellerResourceModel $resource,
        ProductSellerFactory $productSellerFactory,
        CollectionFactory $productSellerCollectionFactory,
        ProductSellerSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->productSellerFactory = $productSellerFactory;
        $this->productSellerCollectionFactory = $productSellerCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritDoc}
     */
    public function save(ProductSellerInterface $productSeller)
    {
        $productSellerData = $this->extensibleDataObjectConverter->toNestedArray(
            $productSeller,
            [],
            ProductSellerInterface::class
        );

        $productSellerModel = $this->productSellerFactory->create()->setData($productSellerData);

        try {
            $this->resource->save($productSellerModel);
            return $productSellerModel;
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__('Could not save the Product Seller %1.', $exception->getMessage()));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function get(int $sellerId)
    {
        $productSeller = $this->productSellerFactory->create();
        $this->resource->load($productSeller, $sellerId);

        if (!$productSeller->getId()) {
            throw new NoSuchEntityException(__('Product Seller with id %1 not found.', $sellerId));
        }

        return $productSeller->getDataModel();
    }

    /**
     * {@inheritDoc}
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->productSellerCollectionFactory->create();
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
    public function delete(ProductSellerInterface $productSeller)
    {
        try {
            $productSellerModel = $this->productSellerFactory->create();
            $this->resource->load($productSellerModel, $productSeller->getSellerId());
            $this->resource->delete($productSellerModel);
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(__('Could not delete Product Seller: %1', $exception->getMessage()));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function deleteById(int $sellerId)
    {
        try {
            $productSeller = $this->get($sellerId);
            $this->delete($productSeller);
        } catch (LocalizedException $exception) {
            throw new LocalizedException(__($exception->getMessage()));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function deleteByList(SearchCriteriaInterface $searchCriteria): bool
    {
        $results = $this->getList($searchCriteria)->getItems();

        foreach ($results as $item) {
            $this->delete($item);
        }

        return true;
    }
}
