<?php
    
namespace ITM\MagB1\Model\Api;
    
use ITM\MagB1\Api\ProductfilesInterface;

class Productfiles implements ProductfilesInterface
{

    /**
     * @var \Magento\Framework\Api\SearchResultsInterfaceFactory
     */
    protected $_searchResultsFactory;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var \ITM\MagB1\Helper\Data
     */
    protected $helper;

    /**
     * @var \ITM\MagB1\Model\ResourceModel\Productfiles
     */
    protected $resourceModel;

    /**
     * Productfiles constructor.
     * @param \Magento\Framework\Api\SearchResultsInterfaceFactory $searchResultsFactory
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \ITM\MagB1\Model\ResourceModel\Productfiles $resourceModel
     * @param \ITM\MagB1\Helper\Data $dataHelper
     */
    public function __construct(
        \Magento\Framework\Api\SearchResultsInterfaceFactory $searchResultsFactory,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \ITM\MagB1\Model\ResourceModel\Productfiles $resourceModel,

        \ITM\MagB1\Helper\Data $dataHelper
    ) {
        $this->_searchResultsFactory = $searchResultsFactory;
        $this->_objectManager = $objectManager;
        $this->productRepository = $productRepository;
        $this->resourceModel = $resourceModel;
        $this->helper = $dataHelper;
    }
                
    /**
     *
     *
     * {@inheritdoc}
     *
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->_objectManager->create('\ITM\MagB1\Model\ResourceModel\Productfiles\Collection');
        $result=$collection->getData();
        $searchResult = $this->_searchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($result);
        $searchResult->setTotalCount(count($result));
        return $searchResult;
    }

    private function loadProductBy($sku)
    {
        return $this->productRepository->get($sku);
    }

    /**
     *
     *
     * {@inheritdoc}
     *
     */
    public function save($entity, $fileName)
    {
        $model = $this->_objectManager->create('ITM\MagB1\Model\Productfiles');
        if (empty($entity->getPath())) {
            return  "No file is selected";
        }
        $product = $this->loadProductBy($entity->getSku());
        $productId = $product->getEntityId();
        if(empty($productId)) {
            return  "The product that was requested doesn't exist. Verify the product and try again.";
        }


        $file = base64_decode($entity->getPath());
        $hashCode =  md5($file);
        $attachmentId =  $this->resourceModel->getIdByHashCode($hashCode,$entity->getStoreId());
        if ($attachmentId) {
            $product = $this->loadProductBy($entity->getSku());
            $productId = $product->getEntityId();
            return $this->resourceModel->addProductToAttachement($attachmentId, $productId);
        }else {

            $collection = $this->_objectManager->create('\ITM\MagB1\Model\ResourceModel\Productfiles\Collection');
            $collection->addFieldToFilter("entity_id", $entity->getEntityId());
            $item = $collection->getFirstItem();



            if ($item->getEntityId()) {
                $model->load($item->getEntityId());
            }
            // save File
            if ($entity->getPath() != "") {
                $file = base64_decode($entity->getPath());

                $timeStamp = time();
                $file_name = $timeStamp . "_" . $fileName;
                $destination = $this->getDestinationPath() . $entity->getSku() . "/";
                $path = $destination . $file_name;

                //Create the folder if not existed.
                if (!is_dir($destination)) {
                    mkdir($destination, 0777, true);
                }
                $result = file_put_contents($path, $file, FILE_APPEND);
                $model->setPath($file_name);
            }
            // End File
            $model->setSku($entity->getSku());
            $model->setHashCode($hashCode);
            $model->setDescription($entity->getDescription());
            $model->setStoreId($entity->getStoreId());
            $model->setPosition($entity->getPosition());
            $model->setStatus($entity->getStatus());
            $model->save();
        }
        return $model;
    }
    
    /**
     *
     *
     * {@inheritdoc}
     *
     */
    public function delete($sku, $store_id)
    {
        try {
            $product = $this->loadProductBy($sku);
            $productId = $product->getEntityId();

            // remove the relation from all attachments
           $this->resourceModel->removeProductFromAttachements($productId, $store_id);
            $pArray = [$productId];

            $model = $this->_objectManager->create('ITM\MagB1\Model\Productfiles');
            $collection=$model->getCollection()->addFieldToFilter("sku", $sku)
                                   ->addFieldToFilter("store_id", $store_id);
            $destination=$this->getDestinationPath().$sku."/";
            foreach ($collection as $item) {
                $attachmentId = $item->getEntityId();
                $assignedProducts =  $this->resourceModel->getAssignedProductIds($attachmentId);



                if(count($assignedProducts) == 0)  {
                    $file_name_path = $destination . $item->getPath();
                    $file_name = $item->getPath();
                    if (file_exists($file_name_path) && $file_name != "") {
                        unlink($file_name_path);
                    }
                    $item->delete();
                }
            }
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return true;
    }
    
    private function getDestinationPath()
    {
        return $this->helper->getProductFilesPath();
    }
}
