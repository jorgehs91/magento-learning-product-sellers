<?php
declare(strict_types=1);

namespace Learning\ProductSellers\Controller\Adminhtml\Index;

use Exception;
use Learning\ProductSellers\Api\Data\ProductSellerInterface;
use Learning\ProductSellers\Api\Data\ProductSellerInterfaceFactory;
use Learning\ProductSellers\Api\Data\ProductSellerItemInterface;
use Learning\ProductSellers\Api\Data\ProductSellerItemInterfaceFactory;
use Learning\ProductSellers\Api\ProductSellerItemRepositoryInterface;
use Learning\ProductSellers\Api\ProductSellerRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Api\AbstractSimpleObject;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends Action
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var ProductSellerRepositoryInterface
     */
    protected $productSellerRepository;

    /**
     * @var ProductSellerInterfaceFactory
     */
    protected $productSellerInterfaceFactory;

    /**
     * @var ProductSellerItemRepositoryInterface
     */
    protected $productSellerItemRepository;

    /**
     * @var ProductSellerItemInterfaceFactory
     */
    protected $productSellerItemInterfaceFactory;

    /**
     * @var SearchCriteriaInterface
     */
    protected $searchCriteria;

    /**
     * @var FilterGroupBuilder
     */
    protected $filterGroupBuilder;

    /**
     * @var FilterBuilder
     */
    protected $filterBuilder;

    /**
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param ProductSellerRepositoryInterface $productSellerRepository
     * @param ProductSellerInterfaceFactory $productSellerInterfaceFactory
     * @param ProductSellerItemRepositoryInterface $productSellerItemRepository
     * @param ProductSellerItemInterfaceFactory $productSellerItemInterfaceFactory
     * @param SearchCriteriaInterface $searchCriteria
     * @param FilterGroupBuilder $filterGroupBuilder
     * @param FilterBuilder $filterBuilder
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        ProductSellerRepositoryInterface $productSellerRepository,
        ProductSellerInterfaceFactory $productSellerInterfaceFactory,
        ProductSellerItemRepositoryInterface $productSellerItemRepository,
        ProductSellerItemInterfaceFactory $productSellerItemInterfaceFactory,
        SearchCriteriaInterface $searchCriteria,
        FilterGroupBuilder $filterGroupBuilder,
        FilterBuilder $filterBuilder
    ) {
        parent::__construct($context);
        $this->dataPersistor = $dataPersistor;
        $this->productSellerRepository = $productSellerRepository;
        $this->productSellerInterfaceFactory = $productSellerInterfaceFactory;
        $this->productSellerItemRepository = $productSellerItemRepository;
        $this->productSellerItemInterfaceFactory = $productSellerItemInterfaceFactory;
        $this->searchCriteria = $searchCriteria;
        $this->filterGroupBuilder = $filterGroupBuilder;
        $this->filterBuilder = $filterBuilder;
    }

    /**
     * {@inheritDoc}
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            $sellerId = (int)$this->getRequest()->getParam('seller_id') ?: 0;
            $productSeller = $this->getProductSellerModel($sellerId);

            if (!$productSeller->getSellerId() && $sellerId != 0) {
                $this->messageManager->addErrorMessage(__('This Seller no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            $productSeller->setSellerName((isset($data['seller_name'])) ? $data['seller_name'] : '');
            $productSeller->setSellerTelephone((isset($data['seller_telephone'])) ? $data['seller_telephone'] : '');

            try {
                $productSeller = $this->productSellerRepository->save($productSeller);
                $this->messageManager->addSuccessMessage(__('You have saved the Seller.'));
                $this->dataPersistor->clear('learning_product_seller');

                if (isset($data['product_ids'])) {
                    $this->saveProductId((int)$productSeller->getSellerId(), $data['product_ids']);
                }

                if ($this->getRequest()->getParam('back') === 'edit') {
                    $this->dataPersistor->set('learning_product_seller', $data);
                    return $resultRedirect->setPath('*/*/edit', ['seller_id' => $productSeller->getSellerId()]);
                }

                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
            } catch (Exception $exception) {
                $this->messageManager->addExceptionMessage($exception, __('Something went wrong while saving Seller'));
            }

            $this->dataPersistor->set('learning_product_seller', $data);
            return $resultRedirect->setPath('*/*/edit', ['seller_id' => $this->getRequest()->getParam('seller_id')]);
        }

        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @param int $sellerId
     * @return ProductSellerInterface
     */
    protected function getProductSellerModel(int $sellerId): ProductSellerInterface
    {
        try {
            return $this->productSellerRepository->get($sellerId);
        } catch (Exception $exception) {
            return $this->productSellerInterfaceFactory->create();
        }
    }

    /**
     * @param int $sellerId
     * @param array $productIds
     * @throws LocalizedException
     */
    protected function saveProductId(int $sellerId, array $productIds)
    {
        $filters[] = $this->addSellerIdFilter($sellerId);
        $filters[] = $this->addProductIdFilter($productIds);

        $searchCriteria = $this->searchCriteria->setFilterGroups($filters);
        $result = $this->productSellerItemRepository->getList($searchCriteria);

        foreach ($productIds as $productId) {
            $productSellerItemExists = false;

            foreach ($result->getItems() as $item) {
                if ($productId == $item->getProductId()) {
                    $productSellerItemExists = true;
                    break;
                }
            }

            if (!$productSellerItemExists) {
                $productSellerItem = $this->productSellerItemInterfaceFactory->create();
                $productSellerItem->setSellerId($sellerId);
                $productSellerItem->setProductId((int)$productId);

                try {
                    $this->productSellerItemRepository->save($productSellerItem);
                } catch (LocalizedException $exception) {
                    $this->messageManager->addErrorMessage($exception->getMessage());
                }
            }
        }

        $deleteFilters = $this->getDeleteFilter($productIds, $sellerId);
        $deleteCriteria = $this->searchCriteria->setFilterGroups($deleteFilters);

        $this->productSellerItemRepository->deleteByList($deleteCriteria);
    }

    /**
     * @param array $productIds
     * @return AbstractSimpleObject
     */
    protected function addProductIdFilter(array $productIds): AbstractSimpleObject
    {
        $filters = [];
        foreach ($productIds as $productId) {
            $filters[] = $this->filterBuilder
                ->setField(ProductSellerItemInterface::PRODUCT_ID)
                ->setValue($productId)
                ->create();
        }
        return $this->filterGroupBuilder->setFilters($filters)->create();
    }

    /**
     * @param int $sellerId
     * @return AbstractSimpleObject
     */
    protected function addSellerIdFilter(int $sellerId): AbstractSimpleObject
    {
        $filter = $this->filterBuilder
            ->setField(ProductSellerItemInterface::SELLER_ID)
            ->setValue($sellerId)
            ->create();
        return $this->filterGroupBuilder->setFilters([$filter])->create();
    }

    /**
     * @param $productIds
     * @param $sellerId
     * @return array
     */
    protected function getDeleteFilter($productIds, $sellerId): array
    {
        $filters = [];

        foreach ($productIds as $productId) {
            $filter = $this->filterBuilder
                ->setField(ProductSellerItemInterface::PRODUCT_ID)
                ->setValue($productId)
                ->setConditionType('neq')
                ->create();
            $filterOr = $this->filterGroupBuilder->setFilters([$filter])->create();
            $filters[] = $filterOr;
        }

        $filters[] = $this->addSellerIdFilter($sellerId);

        return $filters;
    }
}
