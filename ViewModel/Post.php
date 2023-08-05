<?php declare(strict_types=1);

namespace Kochedikov\Blog\ViewModel;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Kochedikov\Blog\Api\Data\PostInterface;
use Kochedikov\Blog\Api\PostRepositoryInterface;
use Kochedikov\Blog\Model\ResourceModel\Post\Collection;
use Kochedikov\Blog\Service\PostsProvider;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Theme\Block\Html\Pager;

class Post implements ArgumentInterface
{
    private Collection $collection;
    private PostRepositoryInterface $postRepository;
    private RequestInterface $request;
    private PostsProvider $postsProvider;
    private StoreManagerInterface $storeManager;

    public function __construct(
        Collection $collection,
        PostRepositoryInterface $postRepository,
        RequestInterface $request,
        PostsProvider $postsProvider,
        StoreManagerInterface $storeManager
    ){
        $this->collection = $collection;
        $this->postRepository = $postRepository;
        $this->request = $request;
        $this->postsProvider = $postsProvider;
        $this->storeManager = $storeManager;
    }

    public function getPosts(int $limit): Collection
    {
        return $this->postsProvider->getPosts($limit, $this->getCurrentPage());
    }

    public function getCurrentPage(): int
    {
        return (int) $this->request->getParam('p');
    }

    /**
     * @throws LocalizedException
     */
    public function getDetail(): PostInterface
    {
        $post_id = (int) $this->request->getParam('post_id');
        return $this->postRepository->getById($post_id);
    }

    public function getPager(Collection $collection, Pager $pagerBlock): string
    {
        $pagerBlock->setUseContainer(false)
            ->setShowPerPage(false)
            ->setShowAmount(false)
            ->setFrameLength(3)
            ->setLimit($collection->getPageSize())
            ->setCollection($collection);

        return $pagerBlock->toHtml();
    }

    /**
     * @throws NoSuchEntityException
     */
    public function getFeaturedImageUrl(PostInterface $post): string
    {
        $fileName = $post->getFeaturedImage();

        $imgPath = 'tmp/imageUploader/images';
        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);

        return $mediaUrl . $imgPath . '/' . $fileName;
    }
}
