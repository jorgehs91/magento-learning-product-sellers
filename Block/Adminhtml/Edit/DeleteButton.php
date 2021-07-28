<?php
declare(strict_types=1);

namespace Learning\ProductSellers\Block\Adminhtml\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteButton extends Button implements ButtonProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function getButtonData()
    {
        $data = [];

        if ($this->getModelId()) {
            $data = [
                'label' => __('Delete Seller'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\'' . __(
                        'Are you sure you want to do this?'
                    ) . '\', \'' . $this->getDeleteUrl() . '\')',
                'sort_order' => 20
            ];
        }

        return $data;
    }

    /**
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', ['seller_id' => $this->getModelId()]);
    }
}
