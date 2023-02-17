<?php

namespace Wagento\Catalog\Controller\Adminhtml\Product\File;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Wagento\Catalog\Model\Product\File\Config as ProductMediaConfig;
use Magento\Framework\Serialize\SerializerInterface;

/**
 * Class Upload
 */
class Upload extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Magento_Catalog::products';

    /**
     * @var RawFactory
     */
    protected RawFactory $resultRawFactory;

    /**
     * @var array
     */
    protected array $allowedMimeTypes = [
        'pdf' => 'image/pdf'
    ];

    /**
     * @var Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * @var UploaderFactory
     */
    protected UploaderFactory $uploaderFactory;

    /**
     * @var ProductMediaConfig
     */
    private ProductMediaConfig $productMediaConfig;

    /**
     * @var SerializerInterface
     */
    protected SerializerInterface $serializer;

    /**
     * @param Context             $context
     * @param RawFactory          $resultRawFactory
     * @param UploaderFactory     $uploaderFactory
     * @param ProductMediaConfig  $productMediaConfig
     * @param Filesystem          $filesystem
     * @param SerializerInterface $serializer
     */
    public function __construct(
        Context $context,
        RawFactory $resultRawFactory,
        UploaderFactory $uploaderFactory,
        ProductMediaConfig $productMediaConfig,
        Filesystem $filesystem,
        SerializerInterface $serializer
    ) {
        parent::__construct($context);
        $this->resultRawFactory = $resultRawFactory;
        $this->uploaderFactory = $uploaderFactory;
        $this->productMediaConfig = $productMediaConfig;
        $this->filesystem = $filesystem;
        $this->serializer = $serializer;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        try {
            $uploader = $this->uploaderFactory->create(['fileId' => 'product[documents]']);
            $uploader->setAllowedExtensions($this->getAllowedExtensions());
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
            $result = $uploader->save(
                $mediaDirectory->getAbsolutePath($this->productMediaConfig->getBaseTmpMediaPath())
            );

            if (is_array($result)) {
                unset($result['tmp_name']);
                $result['path'] = $this->productMediaConfig->getTmpMediaPath($result['file']);
                $result['url'] = $this->productMediaConfig->getTmpMediaUrl($result['file']);
                $result['file'] = $result['file'];
            } else {
                $result = ['error' => 'Something went wrong while saving the file(s).'];
            }
        } catch (\Exception $e) {
            $result = ['error' => 'Something went wrong while saving the file(s).', 'errorcode' => 0];
        }

        /** @var \Magento\Framework\Controller\Result\Raw $response */
        $response = $this->resultRawFactory->create();
        $response->setHeader('Content-type', 'text/plain');
        $response->setContents($this->serializer->serialize($result));
        return $response;
    }

    /**
     * Get the set of allowed file extensions.
     *
     * @return array
     */
    private function getAllowedExtensions()
    {
        return array_keys($this->allowedMimeTypes);
    }
}
