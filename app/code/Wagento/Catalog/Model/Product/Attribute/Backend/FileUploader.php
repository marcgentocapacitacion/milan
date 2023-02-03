<?php

namespace Wagento\Catalog\Model\Product\Attribute\Backend;

use Magento\Catalog\Model\ImageUploader;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Wagento\Catalog\Model\Product\File\Config as ProductFileConfig;

/**
 * Class FileUploader
 */
class FileUploader extends \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
{
    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @var StoreManagerInterface
     */
    //protected StoreManagerInterface $storeManager;

    /**
     * @var ImageUploader
     */
    protected ImageUploader $imageUploader;

    /**
     * @var ProductFileConfig
     */
    protected ProductFileConfig $productFileConfig;

    /**
     * @param LoggerInterface   $logger
     * @param ImageUploader     $imageUploader
     * @param ProductFileConfig $productFileConfig
     */
    public function __construct(LoggerInterface $logger, ImageUploader $imageUploader, ProductFileConfig $productFileConfig)
    {
        $this->logger = $logger;
        $this->imageUploader = $imageUploader;
        $this->productFileConfig = $productFileConfig;
    }


    /**
     * @param array $value Attribute value
     * @return string
     */
    private function getUploadedFile($value)
    {
        if (is_array($value) && isset($value[0]['file'])) {
            return $value[0]['file'];
        }

        return '';
    }

    /**
     * @param $object
     *
     * @return FileUploader
     */
    public function beforeSave($object)
    {
        try {
            $attributeName = $this->getAttribute()->getName();
            $value = $object->getData($attributeName);

            if ($fileName = $this->getUploadedFile($value)) {
                /** @var StoreInterface $store */
                //$store = $this->storeManager->getStore();
                //$baseMediaDir = $store->getBaseMediaDir();
                $newImgRelativePath = $this->imageUploader->moveFileFromTmp($fileName, true);
                $value[0]['url'] = $newImgRelativePath;
                $value[0]['file'] = $value[0]['url'];
            }

            if ($fileName = $this->getUploadedFile($value)) {
                $object->setData($attributeName, $fileName);
            } elseif (!is_string($value)) {
                $object->setData($attributeName, null);
            }
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }
        return parent::beforeSave($object);
    }
}
