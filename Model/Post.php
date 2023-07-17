<?php declare(strict_types=1);

namespace Kochedikov\Blog\Model;

use Magento\Framework\Model\AbstractModel;
use Kochedikov\Blog\Api\Data\PostInterface;
use Kochedikov\Blog\Model\ResourceModel\Post as PostResource;

class Post extends AbstractModel implements PostInterface
{
    private const POST_ID = 'post_id';
    private const TITLE = 'title';
    private const CONTENT = 'content';
    private const CREATED_AT = 'created_at';
    private const UPDATED_AT = 'updated_at';
    private const IS_ACTIVE = 'is_active';

    protected function _construct()
    {
        $this->_eventPrefix = 'kochedikov_post';
        $this->_eventObject = 'post';
        $this->_idFieldName = 'post_id';
        $this->_init(PostResource::class);
    }

    public function getPostId(): int
    {
        return (int)$this->getData(self::POST_ID);
    }

    public function setPostId(int $postId): Post
    {
        return $this->setData(self::POST_ID, $postId);
    }

    public function getPostTitle(): string
    {
        return (string)$this->getData(self::TITLE);
    }

    public function setPostTitle(string $title): Post
    {
        return $this->setData(self::TITLE, $title);
    }

    public function getPostContent(): string
    {
        return (string)$this->getData(self::CONTENT);
    }

    public function setPostContent($content): Post
    {
        return $this->setData(self::CONTENT, $content);
    }

    public function getCreatedAt(): string
    {
        return $this->getData(self::CREATED_AT);
    }

    public function setCreatedAt($createdAt): Post
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    public function getUpdatedAt(): string
    {
        return $this->getData(self::UPDATED_AT);
    }

    public function setUpdatedAt($updatedAt): Post
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

    public function getIsActive(): int
    {
        return (int)$this->getData(self::IS_ACTIVE);
    }

    public function setIsActive($status): Post
    {
        return $this->setData(self::IS_ACTIVE, $status);
    }
}
