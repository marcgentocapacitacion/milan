<?php

namespace Wagento\Catalog\Model\Config\Backend\Serialized;

use Magento\Config\Model\Config\Backend\File\RequestData\RequestDataInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class ArraySerialized
 */
class ArraySerialized extends \Magento\Config\Model\Config\Backend\Serialized
{
    /**
     * @var RequestDataInterface
     */
    protected RequestDataInterface $requestData;

    /**
     * @var Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * @var WriteInterface
     */
    protected $mediaDirectory;

    /**
     * @var UploaderFactory
     */
    protected UploaderFactory $uploaderFactory;

    /**
     * @var Json
     */
    protected Json $serializer;

    /**
     * @param Context               $context
     * @param Registry              $registry
     * @param ScopeConfigInterface  $config
     * @param TypeListInterface     $cacheTypeList
     * @param RequestDataInterface  $requestData
     * @param Filesystem            $filesystem
     * @param UploaderFactory       $uploaderFactory
     * @param AbstractResource|null $resource
     * @param AbstractDb|null       $resourceCollection
     * @param array                 $data
     * @param Json|null             $serializer
     *
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        RequestDataInterface $requestData,
        Filesystem $filesystem,
        UploaderFactory $uploaderFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        Json $serializer,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $config,
            $cacheTypeList,
            $resource,
            $resourceCollection,
            $data,
            $serializer
        );
        $this->requestData = $requestData;
        $this->filesystem = $filesystem;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->uploaderFactory = $uploaderFactory;
        $this->serializer = $serializer;
    }

    /**
     * Unset array element with '__empty' key
     *
     * @return $this
     */
    public function beforeSave()
    {
        $value = $this->getValue();
        if (is_array($value)) {
            unset($value['__empty']);
        }
        if ($value) {
            foreach ($value as $id => $item) {
                $result[$id] = $this->prepareUploadFile('image_brand', $item, 'customPagesBrands/brands');
                $result[$id] = $this->prepareUploadFile('image_banner', $result[$id], 'customPagesBrands/banner');
                $this->setValue($result);
            }
        } else {
            $this->setValue(false);
        }
        return parent::beforeSave();
    }

    /**
     * Processing object after load data
     *
     * @return void
     */
    protected function _afterLoad()
    {
        $value = $this->getValue();
        if (!is_array($value)) {
            try {
                $array = $this->serializer->unserialize($value);
                unset($array['__empty']);
                $this->setValue(empty($value) ? false : ($array ?? false));
            } catch (\Exception $e) {
                $this->_logger->critical(
                    sprintf(
                        'Failed to unserialize %s config value. The error is: %s',
                        $this->getPath(),
                        $e->getMessage()
                    )
                );
                $this->setValue(false);
            }
        }
    }

    /**
     * @param string $uploadDir
     * @param array  $file
     *
     * @return mixed
     * @throws LocalizedException
     */
    protected function uploadFile(string $uploadDir, array $file)
    {
        try {
            $uploader = $this->uploaderFactory->create(['fileId' => $file]);
            $uploader->setAllowedExtensions($this->getAllowedExtensions());
            $uploader->setAllowRenameFiles(true);
            $uploader->addValidateCallback('size', $this, 'validateMaxSize');
            return $uploader->save($uploadDir);
        } catch (\Exception $e) {
            throw new LocalizedException(__('%1', $e->getMessage()));
        }
    }

    /**
     * Getter for allowed extensions of uploaded files
     *
     * @return array
     */
    protected function getAllowedExtensions(): array
    {
        return ['jpg', 'jpeg', 'png', 'svg'];
    }

    /**
     * @param array $image
     *
     * @return array
     */
    protected function getFileData(array $image): array
    {
        $file = [];
        $tmpName = $image['tmp_name'] ?? false;
        if ($tmpName) {
            $file['tmp_name'] = $tmpName;
            $file['name'] = $image['name'];
        }
        return $file;
    }

    /**
     * @param string $typeImage
     * @param array  $result
     * @param string $dir
     *
     * @return array
     * @throws LocalizedException
     */
    protected function prepareUploadFile(string $typeImage, array $result, string $dir): array
    {
        if (!isset($result[$typeImage])) {
            return $result;
        }
        $uploadDir = $this->mediaDirectory->getAbsolutePath($dir);
        $file = $this->getFileData($result[$typeImage]);
        if (!isset($file['tmp_name']) || !$file['tmp_name']) {
            if (isset($result[$typeImage]['delete']) && $result[$typeImage]['delete']) {
                $result[$typeImage] = '';
            } else {
                $result[$typeImage] = $result[$typeImage]['hidden'] ?? '';
            }
            return $result;
        }
        $upload = $this->uploadFile($uploadDir, $file);
        if ($upload !== false) {
            $result[$typeImage] = $upload['file'];
        }
        return $result;
    }
}
