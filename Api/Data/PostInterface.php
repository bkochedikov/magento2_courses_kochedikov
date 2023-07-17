<?php declare(strict_types=1);

namespace Kochedikov\Blog\Api\Data;

interface PostInterface
{
    public const STATUS_DISABLED = 0;
    public const STATUS_ENABLED = 1;

    /**
     * @return int
     */
    public function getPostId(): int;

    /**
     * @param int $post_id
     * @return $this
     */
    public function setPostId(int $post_id);

    /**
     * @return string
     */
    public function getPostTitle(): string;

    /**
     * @param string $title
     * @return $this
     */
    public function setPostTitle(string $title);

    /**
     * @return string
     */
    public function getPostContent(): string;

    /**
     * @param string $content
     * @return $this
     */
    public function setPostContent(string $content);

    /**
     * @return string
     */
    public function getCreatedAt(): string;

    /**
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt(string $createdAt);

    /**
     * @return string
     */
    public function getUpdatedAt(): string;

    /**
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt(string $updatedAt);

    /**
     * @return int
     */
    public function getIsActive(): int;

    /**
     * @param int $status
     * @return $this
     */
    public function setIsActive(int $status);
}
