<?php declare(strict_types=1);

namespace Kochedikov\Blog\Ui\Post;

use Kochedikov\Blog\Model\ResourceModel\Post\CollectionFactory;
use Kochedikov\Blog\Model\ResourceModel\Post\Collection;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\ReadInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;

/**
 * Class DataProvider
 */
class DataProvider extends ModifierPoolDataProvider
{
    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected DataPersistorInterface $dataPersistor;

    /**
     * @var array
     */
    private array $loadedData = [];

    private ReadInterface $mediaDirectory;


    /**
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param CollectionFactory $blockCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param StoreManagerInterface $storeManager
     * @param Filesystem $filesystem
     * @param array $meta
     * @param array $data
     * @param PoolInterface|null $pool
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $blockCollectionFactory,
        DataPersistorInterface $dataPersistor,
        private StoreManagerInterface $storeManager,
        private Filesystem $filesystem,
        private Filesystem\Driver\File\Mime $mime,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null
    ) {
        $this->collection = $blockCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
        $this->mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (!empty($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();

        foreach ($items as $post) {
            $this->loadedData[$post->getId()] = $post->getData();
        }

        $data = $this->dataPersistor->get('kochedikov_blog_post');
        if (!empty($data)) {
            $post = $this->collection->getNewEmptyItem();
            $post->setData($data);

            $postData = $post->getData();

            if ($postData['featured_image']) {
                $image = $postData['featured_image'];

                $imgDir = 'tmp/imageUploader/images';
                $baseUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);

                $fullImagePath = $this->mediaDirectory->getAbsolutePath($imgDir . '/' . $image);

                $imageUrl = $baseUrl . $imgDir . '/' . $image;
                $stat = $this->mediaDirectory->stat($fullImagePath);

                $postData['featured_image'] = null;
                $postData['featured_image'][0]['url'] = $imageUrl;
                $postData['featured_image'][0]['name'] = $image;
                $postData['featured_image'][0]['size'] = $stat['size'];
                $postData['featured_image'][0]['type'] = $this->mime->getMimeType($fullImagePath);
            }

            $this->loadedData[$post->getId()] = $postData;
            $this->dataPersistor->clear('kochedikov_blog_post');
        }

        return $this->loadedData;
    }
}
