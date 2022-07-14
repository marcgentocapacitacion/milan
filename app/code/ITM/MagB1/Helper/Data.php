<?php

namespace ITM\MagB1\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    const MODULE_NAME = 'ITM_MagB1';

    /**
     * @var \Magento\Framework\Module\ModuleListInterface
     */
    protected $_moduleList;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $fileSystem;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $_urlInterface;

    /**
     * @var \ITM\MagB1\Model\ResourceModel\Orderfiles\CollectionFactory
     */
    protected $_orderCollectionFactory;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var bool
     */
    protected $_send_order_email;

    /**
     * @var bool
     */
    protected $_clear_product_cache;

    /**
     * @var bool
     */
    protected $_global_display_all_orders;

    /**
     * @var
     */
    protected $_customer_display_all_orders;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory
     */
    protected $_customerFactory;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var \Magento\Framework\Phrase|string
     */
    protected $files_tab_label;

    /**
     * @var bool
     */
    protected $display_general_infomration = false;

    /**
     * @var array|false|string[]
     */
    protected $dgi_groups = [];

    /**
     * @var bool|string
     */
    protected $allow_delete_orders = false;

    /**
     * @var int
     */
    protected $_customer_group_id = 0;

    /**
     * @var bool
     */
    protected $_ignoreUom = false;

    /**
     * @var bool
     */
    protected $hide_files_icon = false;

    /**
     * @var bool
     */
    protected $delete_media_external = false;
    /**
     * @var \Magento\Customer\Model\GroupFactory
     */
    protected $_groupFactory;

    /**
     * @var \Magento\Framework\Pricing\Helper\Data
     */
    protected $_priceHelper;

    /**
     * @var array
     */
    protected $_display_customer_attributes = [];

    /**
     * @var bool
     */
    protected $useIncrementId = false;

    /**
     * @var bool
     */
    protected $updateInventoryReservation = false;

    /**
     * @var \Magento\Payment\Model\PaymentMethodList
     */
    protected $paymentMethodList;

    /**
     * @param \Magento\Framework\Filesystem $fileSystem
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\UrlInterface $urlInterface
     * @param \ITM\MagB1\Model\ResourceModel\Orderfiles\CollectionFactory $collectionFactory
     * @param \Magento\Framework\Module\ModuleListInterface $moduleList
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerFactory
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Customer\Model\GroupFactory $groupFactory
     * @param \Magento\Framework\Pricing\Helper\Data $priceHelper
     * @param \Magento\Payment\Model\PaymentMethodList $paymentMethodList
     */
    public function __construct(
        \Magento\Framework\Filesystem $fileSystem,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\UrlInterface $urlInterface,
        \ITM\MagB1\Model\ResourceModel\Orderfiles\CollectionFactory $collectionFactory,
        \Magento\Framework\Module\ModuleListInterface $moduleList,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerFactory,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Customer\Model\GroupFactory $groupFactory,
        \Magento\Framework\Pricing\Helper\Data $priceHelper,
        \Magento\Payment\Model\PaymentMethodList $paymentMethodList
    )
    {
        $this->fileSystem = $fileSystem;
        $this->_storeManager = $storeManager;
        $this->_objectManager = $objectManager;
        $this->customerSession = $customerSession;
        $this->_urlInterface = $urlInterface;
        $this->_orderCollectionFactory = $collectionFactory;
        $this->_moduleList = $moduleList;
        $this->_scopeConfig = $scopeConfig;
        $this->_customerFactory = $customerFactory;
        $this->request = $request;
        $this->_send_order_email = (bool)$this->getConfiguration('itm_magb1_section/general/send_order_email');
        $this->_clear_product_cache = (bool)$this->getConfiguration('itm_magb1_section/general/clear_product_cache');
        $this->_global_display_all_orders = (bool)$this->getConfiguration('itm_magb1_section/general/display_all_orders');
        $this->hide_files_icon = (bool)$this->getConfiguration('itm_magb1_section/general/hide_files_icon', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->delete_media_external = (bool)$this->getConfiguration('itm_magb1_section/general/delete_media_external', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->files_tab_label = $this->getConfiguration('itm_magb1_section/general/files_tab_label', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->allow_delete_orders = $this->getConfiguration('itm_magb1_section/general/allow_delete_orders');
        $this->check_disabled_payment_methods = (bool)$this->getConfiguration('itm_magb1_section/general/check_disabled_payment_methods');
        $this->display_general_infomration = (bool)$this->getConfiguration('itm_magb1_section/general/display_general_infomration');
        $dgi_customer_attributes = $this->getConfiguration('itm_magb1_section/general/dgi_customer_attributes');
        $this->paymentMethodList = $paymentMethodList;
        // coming from the pricing ext.
        $this->_ignoreUom = (bool)$this->getConfiguration('itm_pricing_section/general/ignore_uom');

        $this->useIncrementId = (bool) $this->getConfiguration('itm_magb1_section/general/use_increment_id');
        $this->updateInventoryReservation = (bool)$this->getConfiguration('itm_magb1_section/general/update_inventory_reservation');


        $dgi_groups = $this->getConfiguration('itm_magb1_section/general/dgi_customer_groups');
        if(!empty($dgi_groups)) {
            $this->dgi_groups = explode(",", $dgi_groups);
        }
        if (empty($this->files_tab_label)) {
            $this->files_tab_label = __("Files");
        }

        // $this->customerSession = $this->_objectManager->create('\Magento\Customer\Model\Session');
        $customer_id = $this->customerSession->getCustomer()->getId();
        if ($customer_id > 0) {
            $this->_customer_group_id = $this->customerSession->getCustomer()->getGroupId();
        }

        $this->_groupFactory = $groupFactory;
        $this->_priceHelper = $priceHelper;


        $dgi_customer_attributes_array = [];
        if (!empty($dgi_customer_attributes)) {
            $dgi_customer_attributes_array = explode(PHP_EOL, $dgi_customer_attributes);
        }
        if (!empty($dgi_customer_attributes_array)) {
            foreach ($dgi_customer_attributes_array as $_item) {
                $item = explode("|", $_item);
                $value = $this->getCustomer()->getData($item[1]);
                if (isset($item[2])) {
                    switch (trim($item[2])) {
                        case "#" :
                        {
                            $value = "#" . $value;
                            break;
                        }
                        case "price" :
                        {
                            $value = $this->_priceHelper->currency($value, true, false);;
                            break;
                        }
                    }
                }
                $this->_display_customer_attributes[] = [
                    "label" => __($item[0]),
                    "value" => $value
                ];
            }
        }
        //
    }
    public function getConfiguration($key, $scope = '')
    {
        $value = '';
        if(!empty($scope)) {
            $value = $this->_scopeConfig->getValue($key,$scope);
        }else {
            $value = $this->_scopeConfig->getValue($key);
        }
        if(empty($value)) {
            return $value;
        }
        return trim($value);
    }
    public function updateInventoryTable()
    {
        return $this->updateInventoryReservation;
    }
    public function useIncrementId()
    {
        return $this->useIncrementId;
    }
    public function hideFilesIcon()
    {
        return $this->hide_files_icon;
    }
    public  function  deleteMediaExternal(){
        return $this->delete_media_external;
    }
    public function ignoreUom()
    {
        return $this->_ignoreUom;
    }

    public function getDisplayAttributes()
    {
        return $this->_display_customer_attributes;
    }

    public function displayGeneralInfomration()
    {
        if ($this->display_general_infomration == true) {
            if (in_array($this->_customer_group_id, $this->dgi_groups) | in_array("32000", $this->dgi_groups)) {
                return $this->display_general_infomration;
            }
        }
        return false;
    }

    public function getCustomerCollection()
    {
        return $this->_customerFactory->create();
    }

    public function getDisabledPaymentMethods()
    {

        $_customerSession = $this->_objectManager->create('\Magento\Customer\Model\Session');
        $customer_id = $_customerSession->getCustomer()->getId();
        $group_id = 0;
        if ($customer_id > 0) {
            $group_id = $_customerSession->getCustomer()->getGroupId();
        }
        $customerGroup = $this->_groupFactory->create();
        $customerGroup->load($group_id);
        $value = $customerGroup->getData("itm_payment_methods");

        $methods = explode(",", $value);
        return $methods;
    }

    public function checkDisabledPaymentMethods()
    {

        return $this->check_disabled_payment_methods;
    }

    public function getPaymentMethodList()
    {
        $newMethod = $this->paymentMethodList->getActiveList(0);
        $result = [];
        foreach ($newMethod as $value) {
            if($value->getIsActive()) {
                $result[] = [
                    "code" => $value->getCode(),
                    "model" => [
                        "active" => true,
                        "title"=>$value->getTitle(),
                        "model"=>$value->getTitle(),
                    ]
                ];
            }
        }
        return $result;
        $result = [];
        foreach ($this->_scopeConfig->getValue('payment', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, null) as $code => $data) {
            if (isset($data['active']) && (bool)$data['active'] && isset($data['model'])) {
                $result[] = [
                    "code" => $code,
                    "model" => $data
                ];
            }
        }
        return $result;
    }

    public function displayAllOrders()
    {
        $customer_display_all_orders = $this->customerSession->getCustomer()->getData("display_all_orders");
        if (empty($customer_display_all_orders)) {
            return $this->_global_display_all_orders;
        } else {
            if ($customer_display_all_orders == 1) {
                return true;
            } else {
                return false;
            }
        }
        return $this->_global_display_all_orders;
    }

    public function allowDeleteOrders()
    {
        return $this->allow_delete_orders;
    }

    public function getFilesTabTitle()
    {
        return $this->files_tab_label;
    }

    public function getReport($type = "var", $id="")
    {
        $var = $this->fileSystem
            ->getDirectoryWrite(DirectoryList::VAR_DIR)
            ->getAbsolutePath('/');
        $path = "";

        // /V1/magb1/report/report/id/0000b098c4c9885126bef7331ef482e5c3a0ecd631d956d9b6d115c6611af296
        if (strtolower($type) == "report") {
            $path = $var . "report/";
        }

        // /V1/magb1/report/api/id/323645804657
        if (strtolower($type) == "api") {
            $path = $var . "/report/api/";
        }
        $file_path = $path . $id;

        // /V1/magb1/report/exception/id/webapi-5f3d5c429352a
        if (strtolower($type) == "exception") {
            return $this->getWebApi($id);
        }

        if (file_exists($file_path)) {
            return file_get_contents($file_path);
        } else {
            return "No such file";
        }
    }

    public function getWebApi($id)
    {
        //less system.log | grep -a 'xxxxxxx'
        $var = $this->fileSystem
            ->getDirectoryWrite(DirectoryList::VAR_DIR)
            ->getAbsolutePath('/');
        $folder = $var . "log";
        $search = $id;
        $command = "grep -nri '$search' $folder";
        $output = shell_exec($command);
        return $output;
    }
    public function getVersion()
    {
        $version = "MagB1 Version: " . $this->_moduleList
                ->getOne(self::MODULE_NAME)['setup_version'];

        $current_version = $this->_objectManager->get('\Magento\Framework\App\ProductMetadataInterface')->getVersion();
        $current_edition = $this->_objectManager->get('\Magento\Framework\App\ProductMetadataInterface')->getEdition();

        $version .= ", " . $current_edition . ": " . $current_version;
        return $version;
    }

    public function isDisable()
    {
        return $module_status = (boolean)$this->_scopeConfig->getValue('advanced/modules_disable_output/ITM_MagB1');
    }

    public function canViewOrder(\Magento\Sales\Model\Order $order)
    {
        $full_name = $this->request->getFullActionName();

        $names = ["magb1_order_view", "magb1_order_print"];
        if (!in_array($full_name, $names)) {
            return false;
        }
        if ($this->displayAllOrders()) {
            $cardCode = $this->customerSession->getCustomer()->getData("itm_cardcode");
            if (in_array($order->getCustomerId(), $this->getCustomerIdsByCardCode($cardCode))) {
                return true;
            }
        }
        return false;
    }

    public function getCustomerId()
    {
        $customerId = $this->customerSession->getCustomerId();
        return $customerId;
    }

    public function getCurrentCustomerCardCode()
    {
        return $this->getCustomer()->getData("itm_cardcode");
    }

    public function getCurrentCustomerCreditBalance()
    {
        return $this->_priceHelper->currency($this->getCustomer()->getData("itm_credit_balance"),true,false);
    }
    public function getCurrentCustomerCreditLimit()
    {

        return $this->_priceHelper->currency($this->getCustomer()->getData("itm_credit_limit"),true,false);
    }

    public function getCustomer()
    {
        return $this->customerSession->getCustomer();
    }

    public function getCustomerIdsByCardCode($cardCode)
    {
        $collection = $this->getCustomerCollection();
        // $cardCode = $this->getCustomer()->getData("itm_cardcode");
        $collection->addAttributeToFilter("itm_cardcode", $cardCode);
        return $collection->getColumnValues("entity_id");
    }

    public function sendOrderEmail()
    {
        return $this->_send_order_email;

    }

    public function clearProductCache()
    {
        return $this->_clear_product_cache;

    }

    public function _log($message, $file_name = "magb1.log")
    {
        $current_version = $this->_objectManager->get('\Magento\Framework\App\ProductMetadataInterface')->getVersion();
        if (version_compare($current_version, '2.4.2') == 0){
            $writer = new \Laminas\Log\Writer\Stream(BP . '/var/log/'.$file_name);
            $logger = new  \Laminas\Log\Logger();
        }
        if (version_compare($current_version, '2.4.2') < 0){
            $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/'.$file_name);
            $logger = new \Zend\Log\Logger();
        }
        if (version_compare($current_version, '2.4.2') > 0){
            $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/'.$file_name);
            $logger = new \Zend_Log();
        }

        $logger->addWriter($writer);
        $logger->info($message);
    }

    public function getCategoryFileById($id)
    {
        $url = $this->_urlInterface->getUrl('magb1/download/index', [
                "type" => "catalog_category",
                "id" => $id,
                '_nosid' => true
            ]
        );
        return $url;
    }

    public function getCategoryFileLinkById($id)
    {
        $files_collection = $this->_objectManager->get('\ITM\MagB1\Model\ResourceModel\Categoryfiles\CollectionFactory')->create();
        $files_collection->addFieldToFilter("entity_id", $id);
        $first_item = $files_collection->getFirstItem()->getData();

        $destinationUrl = $this->getCategoryfilesUrl();

        $destinationPath = $destinationUrl . "/store_" . $first_item["store_id"] . "/" . md5($first_item["code"]) . "/" . $first_item["path"];

        $url = $destinationPath;
        return $url;
    }

    public function getCategoriesFiles($category_ids = [], $store_id = null)
    {
        $collectionFactory = $this->_objectManager->get('\ITM\MagB1\Model\ResourceModel\Categoryfiles\CollectionFactory');
        $collection = $collectionFactory->create();
        $collection->addFieldToFilter('status', 1);
        $find_in_set = [];

        foreach ($category_ids as $category_id) {
            $find_in_set[] = ['finset' => [$category_id]];
        }

        if (count($find_in_set) > 0) {
            $collection->addFieldToFilter('category_id', $find_in_set);
        }

        if (!empty($store_id)) {
            $store_ids = [0, $store_id];
            $collection->addFieldToFilter("store_id", ["in" => $store_ids]);
        }
        $results = [];
        foreach ($collection as $item) {
            $result["url"] = $this->_storeManager->getStore()->getUrl('magb1/download/index', [
                    "type" => "catalog_category",
                    "id" => $item->getData("entity_id")
                ]
            );
            $result["description"] = $item->getData("description");
            $results[] = $result;
        }

        return $results;
    }

    public function allowedToSeeTheFiles()
    {

        $group_id = $this->customerSession->getCustomer()->getGroupId();
        $hide_files_tab_customer_group = $this->getConfiguration('itm_magb1_section/general/hide_files_tab_customer_group', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if (empty($hide_files_tab_customer_group)) {
            return true;
        }
        $group_array = explode(",", $hide_files_tab_customer_group);
        if (!in_array($group_id, $group_array) && !in_array(32000, $group_array)) {
            return true;
        }
        return false;
    }
    public function getProductFiles($sku, $product_id = 0)
    {
        if($this->allowedToSeeTheFiles()) {
            $model = $this->_objectManager->create('ITM\MagB1\Model\Productfiles');
            $stores = [];
            $stores[] = 0;
            $store_id = $this->_storeManager->getStore()->getStoreId();
            $stores[] = $store_id;
            $collection = $model->getCollection();
	    
	    $collection
                ->addFieldToFilter("main_table.sku", $sku)
                ->addFieldToFilter("main_table.store_id", array("in" => $stores));

            if($product_id>0) {
                $collection->getSelect()->joinLeft(
                    array('children' => $collection->getTable('itm_magb1_productfile_products')),
                    'main_table.entity_id = children.attachment_id',
                    []
                );
                $collection->getSelect()->orwhere('`children`.product_id = '.$product_id);
                $collection->getSelect()->order('main_table.position desc');
                $collection->getSelect()->group('main_table.path');
            }
            return $collection;
        }
        return  [];
    }

    public function getOrderFiles($customer_id, $increment_id)
    {
        $collection = $this->_orderCollectionFactory->create();
        $collection->addFieldToFilter("increment_id", $increment_id);
        $store_id = $this->_storeManager->getStore()->getId();

        $collection->addFieldToFilter("store_id", [
            "in" => [
                $store_id,
                0
            ]
        ]);

        $collection->addFieldToSelect("entity_id");
        $collection->addFieldToSelect("path");
        $collection->addFieldToSelect("description");

        return $collection->getData();
    }

    public function getInvoiceFiles($customer_id, $increment_id)
    {
        $collection = $this->_objectManager->create('ITM\MagB1\Model\ResourceModel\Invoicefiles\Collection');
        $collection->addFieldToFilter("increment_id", $increment_id);

        $store_id = $this->_storeManager->getStore()->getId();

        $collection->addFieldToFilter("store_id", [
            "in" => [
                $store_id,
                0
            ]
        ]);

        $collection->addFieldToSelect("entity_id");
        $collection->addFieldToSelect("path");
        $collection->addFieldToSelect("description");

        return $collection->getData();
    }

    public function getShipmentFiles($customer_id, $increment_id)
    {
        $collection = $this->_objectManager->create('ITM\MagB1\Model\ResourceModel\Shipmentfiles\Collection');
        $collection->addFieldToFilter("increment_id", $increment_id);

        $store_id = $this->_storeManager->getStore()->getId();

        $collection->addFieldToFilter("store_id", [
            "in" => [
                $store_id,
                0
            ]
        ]);

        $collection->addFieldToSelect("entity_id");
        $collection->addFieldToSelect("path");
        $collection->addFieldToSelect("description");

        return $collection->getData();
    }

    public function getCustomerFiles($customer_id)
    {
        $collection = $this->_objectManager->create('ITM\MagB1\Model\ResourceModel\Customerfiles\Collection');
        $collection->addFieldToFilter("customer_id", $customer_id);

        $collection->addFieldToSelect("entity_id");
        $collection->addFieldToSelect("path");
        $collection->addFieldToSelect("description");

        return $collection->getData();
    }

    public function getMediaPath()
    {
        return $this->fileSystem
                ->getDirectoryWrite(DirectoryList::MEDIA)
                ->getAbsolutePath('/') . "catalog/product/";
    }

    public function getModuleFilesPath()
    {
        return $this->fileSystem
                ->getDirectoryWrite(DirectoryList::MEDIA)
                ->getAbsolutePath('/') . "itm/magb1/";
    }

    public function getModuleFilesUrl()
    {
        return $this->_storeManager->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . "itm/magb1/";
    }

    public function getProductFilesPath()
    {
        return $this->getModuleFilesPath() . "Productfiles/";
    }

    public function getCategoryFilesPath()
    {
        return $this->getModuleFilesPath() . "Categoryfiles/";
    }

    public function getOrderfilesPath()
    {
        return $this->getModuleFilesPath() . "Orderfiles/";
    }

    public function getInvoicefilesPath()
    {
        return $this->getModuleFilesPath() . "Invoicefiles/";
    }

    public function getShipmentfilesPath()
    {
        return $this->getModuleFilesPath() . "Shipmentfiles/";
    }

    public function getCustomerfilesPath()
    {
        return $this->getModuleFilesPath() . "Customerfiles/";
    }

    public function getProductFilesURL()
    {
        $mediaUrl = $this->getModuleFilesUrl() . "Productfiles/";

        return $mediaUrl;
    }

    public function getCategoryFilesURL()
    {
        $mediaUrl = $this->getModuleFilesUrl() . "Categoryfiles/";

        return $mediaUrl;
    }

    public function getOrderfilesURL()
    {
        $mediaUrl = $this->getModuleFilesUrl() . "Orderfiles/";

        return $mediaUrl;
    }

    public function getInvoicefilesURL()
    {
        $mediaUrl = $this->getModuleFilesUrl() . "Invoicefiles/";

        return $mediaUrl;
    }

    public function getShipmentfilesURL()
    {
        $mediaUrl = $this->getModuleFilesUrl() . "Shipmentfiles/";

        return $mediaUrl;
    }

    public function getCustomerfilesURL()
    {
        $mediaUrl = $this->getModuleFilesUrl() . "Customerfiles/";

        return $mediaUrl;
    }
}
