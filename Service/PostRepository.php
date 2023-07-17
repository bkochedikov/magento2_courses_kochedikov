<?php declare(strict_types=1);

namespace Kochedikov\Blog\Service;

use Kochedikov\Blog\Api\Data\PostInterface;
use Kochedikov\Blog\Api\PostRepositoryInterface;
use Kochedikov\Blog\Model\PostFactory;
use Kochedikov\Blog\Model\Post;
use Kochedikov\Blog\Model\ResourceModel\Post as PostResource;
use Magento\Framework\Exception\NoSuchEntityException;

class PostRepository implements PostRepositoryInterface
{
    public function __construct(
        private readonly PostResource $resource,
        private readonly PostFactory $factory
    ){}
    public function getById(int $post_id): PostInterface
    {
        $post = $this->factory->create();
        $this->resource->load($post, $post_id);
        if (!$post->getId()){
            throw new NoSuchEntityException(__('The post with id %1 does not exist.', $post_id));
        }
        return $post;
    }

    public function save(PostInterface $post): PostInterface
    {
        $this->resource->save($post);
        return $post;
    }

    public function delete(PostInterface $post): bool
    {
        $this->resource->delete($post);
        return true;
    }
}
