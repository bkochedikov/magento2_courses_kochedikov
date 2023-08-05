<?php declare(strict_types=1);

namespace Kochedikov\Blog\Controller\Adminhtml\Post;

use Kochedikov\Blog\Api\Data\PostInterface;
use Kochedikov\Blog\Api\Data\PostInterfaceFactory;
use Kochedikov\Blog\Api\PostRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Save CMS block action.
 */
class Save extends Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'Kochedikov_Blog::post';

    /**
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param PostInterfaceFactory $postFactory
     * @param PostRepositoryInterface $postRepository
     */
    public function __construct(
        Context                                  $context,
        private readonly DataPersistorInterface  $dataPersistor,
        private readonly PostInterfaceFactory    $postFactory,
        private readonly PostRepositoryInterface $postRepository
    )
    {
        parent::__construct($context);
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            if (isset($data['is_active']) && $data['is_active'] === 'true') {
                $data['is_active'] = PostInterface::STATUS_ENABLED;
            }
            if (empty($data['post_id'])) {
                $data['post_id'] = null;
            }

            $model = $this->postFactory->create();

            $id = (int)$this->getRequest()->getParam('post_id');
            if ($id) {
                try {
                    $model = $this->postRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This post no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            if (!empty($data['featured_image'][0]['name']) && isset($data['featured_image'][0]['tmp_name'])) {
                $data['featured_image'] = $data['featured_image'][0]['file'];
            } else {
                unset($data['featured_image']);
            }

            $model->setData($data);

            try {
                $this->postRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the post.'));
                $this->dataPersistor->clear('kochedikov_blog_post');
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the post.'));
            }

            $this->dataPersistor->set('kochedikov_blog_post', $data);
            return $resultRedirect->setPath('*/*/edit', ['post_id' => $id]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
