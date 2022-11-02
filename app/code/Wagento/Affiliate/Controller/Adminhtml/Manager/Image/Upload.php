<?php

namespace Wagento\Affiliate\Controller\Adminhtml\Manager\Image;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Upload
 */
class Upload extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Wagento_Affiliate::manager';

    /**
     * @var Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * @var WriteInterface
     */
    protected WriteInterface $mediaDirectory;

    /**
     * @var UploaderFactory
     */
    protected UploaderFactory $uploaderFactory;

    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;

    /**
     * @param Context               $context
     * @param Filesystem            $filesystem
     * @param UploaderFactory       $uploaderFactory
     * @param StoreManagerInterface $storeManager
     *
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        Context $context,
        Filesystem $filesystem,
        UploaderFactory $uploaderFactory,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->filesystem = $filesystem;
        $this->uploaderFactory = $uploaderFactory;
        $this->storeManager = $storeManager;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        try {
            $imageId = $this->_request->getParam('param_name', 'image');
            $uploadDir = $this->mediaDirectory->getAbsolutePath('affiliate');
            $uploader = $this->uploaderFactory->create(['fileId' => $imageId]);
            $uploader->setAllowRenameFiles(true);
            $uploader->addValidateCallback('size', $this, 'validateMaxSize');
            $result = $uploader->save($uploadDir);
            if (!$result) {
                throw new LocalizedException(__('File can not be saved to the destination folder.'));
            }
            unset($result['path']);
            $result['tmp_name'] = isset($result['tmp_name']) ? str_replace('\\', '/', $result['tmp_name']) : '';
            $result['url'] = $this->storeManager
                    ->getStore()
                    ->getBaseUrl(
                        \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                    ) . 'affiliate/' . $result['file'];
            $result['name'] = $result['file'];
        } catch (\Exception $e) {
            throw new LocalizedException(__('%1', $e->getMessage()));
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
