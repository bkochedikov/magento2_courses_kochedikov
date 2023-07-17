<?php declare(strict_types=1);

namespace Kochedikov\Blog\Controller\Adminhtml\Post;


use Kochedikov\Blog\Api\PostRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Result\Page;

class Edit extends Action
{

    public function __construct(
        Context $context,
        private readonly PostRepositoryInterface $postRepository,
        private readonly DataPersistorInterface  $dataPersistor

    ){
        parent::__construct($context);
    }

    public function execute(): ResultInterface
    {
        /** @var Page $page */
        $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $post_id = (int) $this->getRequest()->getParam('post_id');
        try {
            $post = $this->postRepository->getById($post_id);
            $this->dataPersistor->set('kochedikov_blog_post', $post->getData());
        } catch (LocalizedException){
            $this->messageManager->addErrorMessage(__('The post with the given id does not exist'));
        }

        $page->setActiveMenu('Kochedikov_Blog::post');
        $page->addBreadcrumb(__('Posts'), __('Posts'));
        $page->addBreadcrumb($post->getPostId() ? $post->getTitle() : __('New Post'),$post->getPostId() ? $post->getTitle() :  __('New Post'));
        $page->getConfig()->getTitle()->prepend($post->getPostId() ? $post->getTitle() : __('New Post'));

        return $page;
    }
}
