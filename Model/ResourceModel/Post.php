<?php declare(strict_types=1);

namespace Kochedikov\Blog\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Post extends AbstractDb
{
    private const MAIN_TABLE = 'kochedikov_blog_post';
    private const ID_FIELD_NAME = 'post_id';
    protected function _construct()
    {
        $this->_init(self::MAIN_TABLE, self::ID_FIELD_NAME);
    }

    protected function _beforeSave(AbstractModel $object): Post
    {
        $object->setData('updated_at', 0);
        return parent::_beforeSave($object);
    }
}
