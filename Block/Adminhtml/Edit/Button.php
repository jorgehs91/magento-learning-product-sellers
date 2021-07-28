<?php
declare(strict_types=1);

namespace Learning\ProductSellers\Block\Adminhtml\Edit;

use Magento\Backend\Block\Widget\Context;

abstract class Button
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @return mixed
     */
    public function getModelId()
    {
        return $this->context->getRequest()->getParam('seller_id');
    }

    /**
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl(string $route = '', array $params = []): string
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
