<?php declare(strict_types=1);

namespace Kochedikov\Blog\Block\Adminhtml\Post\Edit;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;

/**
 *
 */
class GenericButton
{
    /**
     * @param UrlInterface $url
     * @param RequestInterface $request
     */
    public function __construct(
        private readonly UrlInterface $url,
        private readonly RequestInterface $request
    ) {}


    /**
     * @return int
     */
    public function getPostId(): int
    {
        return (int) $this->request->getParam('post_id', 0);
    }

    /**
     * Generate url by route and parameters
     *
     * @param string $route
     * @param array $params
     * @return  string
     */
    public function getUrl(string $route = '', array $params = []): string
    {
        return $this->url->getUrl($route, $params);
    }
}
