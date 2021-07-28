<?php
declare(strict_types=1);

namespace Learning\ProductSellers\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Registry;

abstract class ProductSeller extends Action
{
    const ADMIN_RESOURCE = 'Learning_ProductSellers::top_level';

    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry
    ) {
        parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
    }

    /**
     * @param Page $resultPage
     * @return Page
     */
    public function initPage(Page $resultPage): Page
    {
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE)
            ->addBreadcrumb(__('Learning'), __('Learning'))
            ->addBreadcrumb(__('Product Sellers'), __('Product Sellers'));

        return $resultPage;
    }
}
