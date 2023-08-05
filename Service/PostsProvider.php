<?php declare(strict_types=1);

namespace Kochedikov\Blog\Service;

use Kochedikov\Blog\Model\ResourceModel\Post\Collection;
use Kochedikov\Blog\Model\ResourceModel\Post\CollectionFactory;
use Zend_Db_Select;

class PostsProvider
{
    public function __construct(
        private CollectionFactory $collectionFactory
    ) {

    }
    public function getPosts(int $limit, int $currentPage): Collection
    {
        $collection = $this->getCollection();
        $collection->setOrder('created_at', Zend_Db_Select::SQL_DESC);
        $collection->setPageSize($limit);
        $collection->setCurPage($currentPage);

        return $collection;
    }

    private function getCollection() : Collection
    {
        return $this->collectionFactory->create();
    }
}
