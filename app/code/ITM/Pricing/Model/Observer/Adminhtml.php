<?php
namespace ITM\Pricing\Model\Observer;

use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;
use \Magento\Framework\App\RequestInterface;

class Adminhtml implements ObserverInterface
{
    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * @var \ITM\Pricing\Helper\Data
     */
    protected $_helper;

    /**
     * @var RequestInterface
     */
    protected $_request;
    
    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $_resource;

    /**
     * @var bool
     */
    protected $use_index_tables = false;


    /**
     * @var  \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * Adminhtml constructor.
     * @param LoggerInterface $logger
     * @param \ITM\Pricing\Helper\Data $dataHelper
     * @param RequestInterface $request
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Eav\Model\AttributeRepository $attributeRepository
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Framework\Event\Manager $eventManager
     */
    public function __construct(
        LoggerInterface $logger,
        \ITM\Pricing\Helper\Data $dataHelper,
        RequestInterface $request,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Eav\Model\AttributeRepository $attributeRepository,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\Event\Manager $eventManager
        ) {
            $this->_logger = $logger;
            $this->_helper = $dataHelper;
            $this->_request = $request;
            $this->_objectManager = $objectManager;
            $this->_scopeConfig = $scopeConfig;
            $this->_resource = $resource;
            $this->use_index_tables = (bool) $this->_helper->getConfiguration('itm_pricing_section/general/use_index_tables');
            $this->_messageManager = $messageManager;
            $this->productRepository = $productRepository;
            $this->eventManager = $eventManager;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    private function pricing_groupprice_save_after(\Magento\Framework\Event\Observer $observer)
    {
        if($this->use_index_tables == true) {
            $groupPrice = $observer->getObject();
            $connection = $this->_resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
            $main_table = $this->_resource->getTableName('itm_pricing_groupprice');
            $child_table_name = $main_table . "_".$this->_helper->getGroupID($groupPrice->getData("group_id")) ;
            
            if ($connection->isTableExists($this->_resource->getTableName($child_table_name)) != true) {
                $this->_messageManager->addNotice(__("Please execute group price index command, The command is [php bin/magento pricing:index-group-tables]"));
                return;
            }
            
            $id = $groupPrice->getData("id");
            $price = $groupPrice->getData("price");
            $status = $groupPrice->getData("status");
            $discount= $groupPrice->getData("discount");

            $select = $connection->select();
            $select->from($child_table_name, 'COUNT(*)');
            $select->where("id = $id");
            $result = (int)$connection->fetchOne($select);

            
            if($result == 0) {
                $query = "INSERT INTO `$child_table_name`  (SELECT *  FROM `$main_table`  WHERE `id` = $id)";
                $connection->query($query);
                
            } else {
                $connection->update(
                    $child_table_name,
                    array('price' => $price,'status'=>$status,'discount'=>$discount),
                    array('id = ?' => $id ));
            }
        }
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    private function pricing_groupprice_delete_after(\Magento\Framework\Event\Observer $observer)
    {
        if($this->use_index_tables == true) {
            $groupPrice = $observer->getObject();
            $connection = $this->_resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
            $main_table = $this->_resource->getTableName('itm_pricing_groupprice');
            $child_table_name = $main_table . "_".$this->_helper->getGroupID($groupPrice->getData("group_id")) ;
            
            if(count($groupPrice->getData()) == 0) {
                return;
            }
            
            // get the id
            $partial_id = 0;
            $bind = [];
            $bind[':group_id'] = $groupPrice->getData("group_id");
            $bind[':website_id'] = $groupPrice->getData("website_id");
            $bind[':sku'] = $groupPrice->getData("sku");;
            $bind[':qty'] = $groupPrice->getData("qty");
            $bind[':uom_entry'] = $groupPrice->getData("uom_entry");
            $bind[':status'] = $groupPrice->getData("status");
            $start_date = $groupPrice->getData("start_date");
            $end_date = $groupPrice->getData("end_date");
            
            
            $select = $connection->select();
            $select->from($child_table_name,["id","price"]);
            $select->where('group_id = :group_id');
            $select->where('website_id = :website_id');
            $select->where('sku = :sku');
            $select->where('qty = :qty');
            if($start_date =="") {
                $select->where('start_date is NULL');
            }else{
                $bind[':start_date'] = date("Y-m-d", strtotime($start_date));
                $select->where('start_date = :start_date');
            }
            
            if($end_date =="") {
                $select->where('end_date is NULL');
                
            }else{
                $bind[':end_date'] = date("Y-m-d", strtotime($end_date));
                $select->where('end_date = :end_date');
            }
            
            
            $select->where('uom_entry = :uom_entry');
            $select->where('status = :status');
            $select->order('qty DESC');
            
            $result = $connection->fetchRow($select, $bind);
            if($result) {
                $partial_id = $result["id"];
            }
            //
            
            
            if($partial_id >0 ){
                $connection->delete(
                    $child_table_name,
                    array('id = ?' => $partial_id )
                    );
            }
        }
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    private function pricing_customerprice_save_after(\Magento\Framework\Event\Observer $observer)
    {
        if($this->use_index_tables == true) {
            $customerPrice = $observer->getObject();
            $connection = $this->_resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
            $customer_id = $customerPrice->getData("customer_id") ;
            
            $customer_index_tables =  $this->_resource->getTableName('itm_pricing_customer_index_tables');
            $main_table = $this->_resource->getTableName('itm_pricing_customerprice');
            
            $select_query = $connection->select()->from([
                'entity' => $customer_index_tables
            ], [
                'table_name'
            ]);
            
            $bind[':customer_id'] = $customer_id;
            $select_query->where('entity.customer_id = :customer_id');
            $child_table_name = $connection->fetchOne($select_query, $bind);
            
            if ($connection->isTableExists($this->_resource->getTableName($child_table_name)) != true) {
                $this->_messageManager->addNotice(__("Please execute group price index command, The command is [php bin/magento pricing:index-group-tables]"));
                return;
            }
            
            $id = $customerPrice->getData("id");
            $price = $customerPrice->getData("price");
            $status = $customerPrice->getData("status");
            $discount= $customerPrice->getData("discount");

            $select = $connection->select();
            $select->from($child_table_name, 'COUNT(*)');
            $select->where("id = $id");
            $result = (int)$connection->fetchOne($select);
            
            
            if($result == 0) {
                $query = "INSERT INTO `$child_table_name`  (SELECT *  FROM `$main_table`  WHERE `id` = $id)";
                $connection->query($query);
                
            } else {
                $connection->update(
                    $child_table_name,
                    array('price' => $price,'status'=>$status,'discount'=>$discount),
                    array('id = ?' => $id ));
            }
        }
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    private function pricing_customerprice_delete_after(\Magento\Framework\Event\Observer $observer)
    {
        if($this->use_index_tables == true) {
            $customerPrice = $observer->getObject();
            $connection = $this->_resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
            $customer_index_tables =  $this->_resource->getTableName('itm_pricing_customer_index_tables');
            $main_table = $this->_resource->getTableName('itm_pricing_customerprice');
            
            if(count($customerPrice->getData()) == 0) {
                return;
            }
           
            $customer_id = $customerPrice->getData("customer_id");
            $select_query = $connection->select()->from([
                'entity' => $customer_index_tables
            ], [
                'table_name'
            ]);
            
            $bind[':customer_id'] = $customer_id;
            $select_query->where('entity.customer_id = :customer_id');
            $child_table_name = $connection->fetchOne($select_query, $bind);
            
            if($child_table_name == "") {
                return ;
            }
            
            
            // get the id
            $partial_id = 0;
            $bind = [];
            $bind[':customer_id'] = $customer_id;
            $bind[':website_id'] = $customerPrice->getData("website_id");
            $bind[':sku'] = $customerPrice->getData("sku");;
            $bind[':qty'] = $customerPrice->getData("qty");
            $bind[':uom_entry'] = $customerPrice->getData("uom_entry");
            $bind[':status'] = $customerPrice->getData("status");
            $start_date = $customerPrice->getData("start_date");
            $end_date = $customerPrice->getData("end_date");
            
            
            $select = $connection->select();
            $select->from($child_table_name,["id","price"]);
            $select->where('customer_id = :customer_id');
            $select->where('website_id = :website_id');
            $select->where('sku = :sku');
            $select->where('qty = :qty');
            if($start_date =="") {
                $select->where('start_date is NULL');
            }else{
                $bind[':start_date'] = date("Y-m-d", strtotime($start_date));
                $select->where('start_date = :start_date');
            }
            
            if($end_date =="") {
                $select->where('end_date is NULL');
                
            }else{
                $bind[':end_date'] = date("Y-m-d", strtotime($end_date));
                $select->where('end_date = :end_date');
            }
            
            
            $select->where('uom_entry = :uom_entry');
            $select->where('status = :status');
            $select->order('qty DESC');
            
            $result = $connection->fetchRow($select, $bind);
            if($result) {
                $partial_id = $result["id"];
            }
            //
            
            
            if($partial_id >0 ){
                $connection->delete(
                    $child_table_name,
                    array('id = ?' => $partial_id )
                    );
            }
        }
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->_helper->isDisable() == true) {
            return $this;
        }
        
        $event_name = $observer->getEvent()->getName();
        // $this->_logger->debug ($event_name);
        switch ($event_name) {
            case "pricing_groupprice_save_after":
                $this->pricing_groupprice_save_after($observer);
                break;
            case "pricing_groupprice_delete_after":
                $this->pricing_groupprice_delete_after($observer);
                break;
            case "pricing_customerprice_save_after":
                $this->pricing_customerprice_save_after($observer);
                break;
            case "pricing_customerprice_delete_after":
                $this->pricing_customerprice_delete_after($observer);
                break;
        }
        if( $this->_helper->cleanCache()) {
            $oData = $observer->getObject();
            $sku = $oData->getData("sku");
            if (!empty($sku)) {
                try {
                    $product = $this->productRepository->get($sku);
                    $product->cleanCache();
                    $this->eventManager->dispatch('clean_cache_by_tags', ['object' => $product]);
                } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {

                }
            }
        }
        return $this;
    }
}
