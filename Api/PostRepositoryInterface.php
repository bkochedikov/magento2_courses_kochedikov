<?php declare(strict_types=1);

namespace Kochedikov\Blog\Api;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Kochedikov\Blog\Api\Data\PostInterface;

interface PostRepositoryInterface
{
    /**
     * @param int $post_id
     * @return PostInterface
     * @throws LocalizedException
     */
    public function getById(int $post_id): PostInterface;

    /**
     * @param PostInterface $post
     * @return PostInterface
     * @throws LocalizedException
     */
    public function save(PostInterface $post): PostInterface;

    /**
     * @param PostInterface $post
     * @return bool
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function delete(PostInterface $post): bool;
}
