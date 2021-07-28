<?php
declare(strict_types=1);

namespace Learning\ProductSellers\Block\Adminhtml\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveButton extends Button implements ButtonProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function getButtonData(): array
    {
        return [
            'label' => __('Save Seller'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 90,
        ];
    }
}
