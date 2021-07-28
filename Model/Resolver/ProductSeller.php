<?php
declare(strict_types=1);

namespace Learning\ProductSellers\Model\Resolver;

use Learning\ProductSellers\Api\Data\ProductSellerItemInterface;
use Learning\ProductSellers\Api\Data\ProductSellerInterface;
use Learning\ProductSellers\Api\ProductSellerItemRepositoryInterface;
use Learning\ProductSellers\Api\ProductSellerRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Api\SearchCriteriaBuilder;

class ProductSeller implements ResolverInterface
{
    /**
     * @var ProductSellerRepositoryInterface
     */
    protected $productSellerRepository;

    /**
     * @var ProductSellerItemRepositoryInterface
     */
    protected $productSellerItemRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @param ProductSellerRepositoryInterface $productSellerRepository
     * @param ProductSellerItemRepositoryInterface $productSellerItemRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        ProductSellerRepositoryInterface $productSellerRepository,
        ProductSellerItemRepositoryInterface $productSellerItemRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->productSellerRepository = $productSellerRepository;
        $this->productSellerItemRepository = $productSellerItemRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * {@inheritDoc}
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        $productSellers = [];

        if (isset($args['seller_id'])) {
            $productSeller = $this->productSellerRepository->get($args['seller_id']);
            $productSellers[] = $this->populateGraphQlReturn($productSeller);
        } else {
            $searchCriteria = $this->searchCriteriaBuilder->create();
            $productSellersList = $this->productSellerRepository->getList($searchCriteria)->getItems();

            foreach ($productSellersList as $productSeller) {
                $productSellers[] = $this->populateGraphQlReturn($productSeller);
            }
        }

        return $productSellers;
    }

    /**
     * @param ProductSellerInterface $productSeller
     * @return array
     * @throws LocalizedException
     */
    protected function populateGraphQlReturn(ProductSellerInterface $productSeller)
    {
        $productSellerItem = $this->productSellerItemRepository->getBySellerId($productSeller->getSellerId());
        $items = [];

        foreach ($productSellerItem as $item) {
            /** @var ProductSellerItemInterface $item */
            $items[] = [
                'item_id' => $item->getItemId(),
                'product_id' => $item->getProductId()
            ];
        }

        return [
            'seller_id' => $productSeller->getSellerId(),
            'seller_name' => $productSeller->getSellerName(),
            'seller_telephone' => $productSeller->getSellerTelephone(),
            'seller_products' => $items
        ];
    }
}
