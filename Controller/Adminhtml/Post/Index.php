<?php declare(strict_types=1);

namespace Kochedikov\Blog\Controller\Adminhtml\Post;


use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;

class Index extends Action
{
    const ADMIN_RESOURCE = 'Kochedikov_Blog::post';

    public function execute(): ResultInterface
    {
        /** @var Page $page */
        $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $page->setActiveMenu('Kochedikov_Blog::post');
        $page->addBreadcrumb(__('Posts'), __('Posts'));
        $page->addBreadcrumb(__('Manage Posts'), __('Manage Posts'));
        $page->getConfig()->getTitle()->prepend(__('Posts'));

        return $page;
    }
}
