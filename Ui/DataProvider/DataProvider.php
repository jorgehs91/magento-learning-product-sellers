<?php
declare(strict_types=1);

namespace Learning\ProductSellers\Ui\DataProvider;

use Learning\ProductSellers\Api\Data\ProductSellerItemInterface;
use Learning\ProductSellers\Api\ProductSellerItemRepositoryInterface;
use Learning\ProductSellers\Model\ResourceModel\ProductSeller\Collection;
use Learning\ProductSellers\Model\ResourceModel\ProductSeller\CollectionFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    protected $loadedData;

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var ProductSellerItemRepositoryInterface
     */
    protected $productSellerItemRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collection
     * @param DataPersistorInterface $dataPersistor
     * @param ProductSellerItemRepositoryInterface $productSellerItemRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collection,
        DataPersistorInterface $dataPersistor,
        ProductSellerItemRepositoryInterface $productSellerItemRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $meta,
            $data
        );
        $this->collection = $collection->create();
        $this->dataPersistor = $dataPersistor;
        $this->productSellerItemRepository = $productSellerItemRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();

        foreach ($items as $model) {
            $temp = $model->getData();
            $this->loadedData[$model->getId()] = $temp;
            $this->loadedData[$model->getId()]['product_ids'] = $this->getRelatedProducts((int)$temp['seller_id']);
        }

        $data = $this->dataPersistor->get('learning_product_seller');

        if (!empty($data)) {
            $model = $this->collection->getNewEmptyItem();
            $model->setData($data);
            $this->loadedData[$model->getSellerId()] = $model->getData();
            $this->dataPersistor->clear('learning_product_seller');
        }

        return $this->loadedData;
    }

    /**
     * @param int $sellerId
     * @return array
     * @throws LocalizedException
     */
    protected function getRelatedProducts(int $sellerId): array
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(ProductSellerItemInterface::SELLER_ID, $sellerId)
            ->create();

        $results = $this->productSellerItemRepository->getList($searchCriteria)->getItems();

        $productsIds = [];
        foreach ($results as $item) {
            $productsIds[] = (string)$item->getProductId();
        }

        return $productsIds;
    }
}
