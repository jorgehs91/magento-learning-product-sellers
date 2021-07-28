<?php
declare(strict_types=1);

namespace Learning\ProductSellers\Controller\Adminhtml\Index;

use Exception;
use Learning\ProductSellers\Api\ProductSellerRepositoryInterface;
use Learning\ProductSellers\Controller\Adminhtml\ProductSeller;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;

class Delete extends ProductSeller
{
    /**
     * @var ProductSellerRepositoryInterface
     */
    protected $productSellerRepository;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param ProductSellerRepositoryInterface $productSellerRepository
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        ProductSellerRepositoryInterface $productSellerRepository
    ) {
        parent::__construct(
            $context,
            $coreRegistry
        );
        $this->productSellerRepository = $productSellerRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $sellerId = (int)$this->getRequest()->getParam('seller_id');

        if ($sellerId) {
            try {
                $productSeller = $this->productSellerRepository->get($sellerId);
                $this->productSellerRepository->delete($productSeller);
                $this->messageManager->addSuccessMessage(__('You have deleted the Seller.'));
                return $resultRedirect->setPath('*/*/');
            } catch (Exception $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['seller_id', $sellerId]);
            }
        }

        $this->messageManager->addErrorMessage(__('We can\'t find a Seller to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
