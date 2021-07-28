<?php
declare(strict_types=1);

namespace Learning\ProductSellers\Controller\Adminhtml\Index;

use Learning\ProductSellers\Api\ProductSellerRepositoryInterface;
use Learning\ProductSellers\Controller\Adminhtml\ProductSeller;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

class Edit extends ProductSeller
{
    /**
     * @var ProductSellerRepositoryInterface
     */
    protected $productSellerRepository;

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param ProductSellerRepositoryInterface $productSellerRepository
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        ProductSellerRepositoryInterface $productSellerRepository,
        PageFactory $resultPageFactory
    ) {
        parent::__construct(
            $context,
            $coreRegistry
        );
        $this->productSellerRepository = $productSellerRepository;
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function execute()
    {
        $sellerId = (int)$this->getRequest()->getParam('seller_id');
        $productSeller = null;

        if ($sellerId) {
            $productSeller = $this->productSellerRepository->get($sellerId);

            if (!$productSeller) {
                $this->messageManager->addErrorMessage(__('This Seller no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->coreRegistry->register('learning_product_seller', $productSeller);

        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $sellerId ? __('Edit Seller') : __('Create Seller'),
            $sellerId ? __('Edit Seller') : __('Create Seller')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Product Seller'));

        return $resultPage;
    }
}
