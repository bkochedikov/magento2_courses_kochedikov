<?php declare(strict_types=1);

namespace Kochedikov\Blog\Controller\Post;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Detail implements HttpGetActionInterface
{
    private $pageFactory;
    private $eventManager;
    private $request;

    public function __construct(
        PageFactory $pageFactory,
        EventManager $eventManager,
        RequestInterface $request,
    ){
        $this->pageFactory = $pageFactory;
        $this->eventManager = $eventManager;
        $this->request = $request;
    }

    public function execute(): Page
    {
        $this->eventManager->dispatch('kochedikov_blog_post_detail_view', [
            'request' => $this->request,
        ]);

        return $this->pageFactory->create();
    }
}
