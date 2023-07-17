<?php declare(strict_types=1);

namespace Kochedikov\Blog\Controller\Adminhtml\Post;

use Kochedikov\Blog\Api\Data\PostInterface;
use Kochedikov\Blog\Api\PostRepositoryInterface;
use Kochedikov\Blog\Model\ResourceModel\Post\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Ui\Component\MassAction\Filter;

class MassDelete extends Action
{
    public function __construct(
        Context $context,
        private Filter $filter,
        private CollectionFactory $collectionFactory,
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
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();

        try {
            /** @var PostInterface $post */
            foreach ($collection as $post){
                $this->postRepository->delete($post);
            }

            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $collectionSize));
        }catch (\Throwable $exception) {
            $this->messageManager->addErrorMessage(__('Something went wrong while processing the operation '));

        }

        $result = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $result->setPath('kochedikov_blog/post/index');
    }
}
