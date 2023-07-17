<?php declare(strict_types=1);

namespace Kochedikov\Blog\Controller\Adminhtml\Post;

use Kochedikov\Blog\Api\Data\PostInterface;
use Kochedikov\Blog\Api\PostRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

class Delete extends Action
{
    public function __construct(
        Context $context,
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
        $post_id = (int) $this->getRequest()->getParam('post_id', 0);

        $result = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if (!$post_id) {
            $this->messageManager->addWarningMessage(__('The post with provided id was not found.'));
            return $result->setPath('kochedikov_blog/post/index');
        }

        try {
            $post = $this->postRepository->getById($post_id);
            if (!$post->getPostId()){
                $this->messageManager->addWarningMessage(__('The post with provided id was not found.'));
            } else {

                /** @var PostInterface $post */
                $this->postRepository->delete($post);

                $this->messageManager->addSuccessMessage(__('The post has been deleted.'));
            }

        }catch (\Throwable $exception) {
            $this->messageManager->addErrorMessage(__('Something went wrong while processing the operation '));
        }

        return $result->setPath('kochedikov_blog/post/index');
    }
}
