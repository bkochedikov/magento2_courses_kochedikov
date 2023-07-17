<?php declare(strict_types=1);

namespace Kochedikov\Blog\Controller\Adminhtml\Post;

use Kochedikov\Blog\Api\Data\PostInterface;
use Kochedikov\Blog\Api\PostRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

class InlineEdit extends Action
{
    public function __construct(
        Context                         $context,
        private PostRepositoryInterface $postRepository
    )
    {
        parent::__construct($context);
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $items = $this->getRequest()->getParam('items');
        $messages = [];
        $error = false;

        if (!count($items)) {
            $messages[] = __('Please send correct data.');
            $error = true;
        } else {
            foreach (array_keys($items) as $post_id) {
                try {
                    $post = $this->postRepository->getById((int)$post_id);
                    $post->setData(array_merge($post->getData(), $items[$post_id]));
                    $this->postRepository->save($post);
                } catch (\Throwable $exception) {
                    $messages[] = '[Post ID: ' . $post_id . '] ' . $exception->getMessage();
                    $error = true;
                }
            }
        }

        return $result->setData(
            [
                'messages' => $messages,
                'error' => $error
            ]
        );
    }
}
