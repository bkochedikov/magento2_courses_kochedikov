<?php declare(strict_types=1);

namespace Kochedikov\Blog\Block\Adminhtml\Post\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveButton extends GenericButton implements ButtonProviderInterface
{
    public function getButtonData(): array
    {
        return [
            'label' => __('Save'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => [
                    'buttonAdapter' => [
                        'actions' => [
                            [
                                'targetName' => 'kochedikov_blog_post_form.kochedikov_blog_post_form',
                                'actionName' => 'save'
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}
