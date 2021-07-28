<?php
declare(strict_types=1);

namespace Learning\ProductSellers\Controller\Adminhtml\Index;

use Learning\ProductSellers\Controller\Adminhtml\ProductSeller;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\Registry;

class NewAction extends ProductSeller
{
    /**
     * @var ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param ForwardFactory $resultForwardFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        ForwardFactory $resultForwardFactory
    ) {
        parent::__construct(
            $context,
            $coreRegistry
        );
        $this->resultForwardFactory = $resultForwardFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function execute()
    {
        $resultForward = $this->resultForwardFactory->create();
        return $resultForward->forward('edit');
    }
}
