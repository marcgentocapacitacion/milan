<?php
namespace ITM\Pricing\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Customer\Model\Session;

/**
 * Class Data
 *
 * @package Gielberkers\Example\Helper
 */
class Data extends AbstractHelper
{

    /**
     * Group Id code
     */
    const GROUP_ID_CODE = 'group_id';

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resource;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * @var Session
     */
    protected $_customerSession;

    /**
     * @var string
     */
    protected $_group_price_main_table = "itm_pricing_groupprice";

    /**
     * @var string
     */
    protected $_customer_price_main_table = "itm_pricing_customerprice";

    /**
     * @var string
     */
    protected $_customer_price_index_table = "itm_pricing_customerprice";

    /**
     * @var int
     */
    protected $_group_id = 0;  //default_group_id in system

    /**
     * @var int
     */
    protected $_customer_group_id = 0;

    /**
     * @var int
     */
    protected $_customer_id = 0;

    /**
     * @var array
     */
    protected $_group_attribute_codes = array();

    /**
     * @var array
     */
    protected $_customer_attribute_codes = array();

    /**
     * @var array
     */
    protected $_available_qty = array();

    /**
     * @var string
     */
    protected $_pricing_type = "priority";  // priority/lowest/highest

    /**
     * @var array|false|string[]
     */
    protected $_another_modules = array();

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var int
     */
    protected $_website_id = 0;

    /**
     * @var string
     */
    protected $activation_code = "";

    /**
     * @var \ITM\Pricing\Model\Cache
     */
    protected $cache;

    /**
     * @var bool
     */
    protected $use_index_tables = false;

    /**
     * @var string
     */
    private $current_edition;

    /**
     * @var string
     */
    private $current_version;

    /**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $_cacheTypeList;

    /**
     * @var \Magento\Framework\App\Cache\Frontend\Pool
     */
    protected $_cacheFrontendPool;

    /**
     * @var bool
     */
    protected $_useLimit = false;

    /**
     * @var bool
     */
    protected $_ignoreUom = false;

    /**
     * @var bool
     */
    private $cleanCache = true;

    /**
     * @var bool
     */
    private $allowBundle = false;

    /**
     * @var bool
     */
    private $priceWhenSpecialHigher = false;

    /**
     * @var bool
     */
    private $priceEqualtoSpecialPrice = false;

    /**
     * @var array|false|string[]
     */
    private $price_equalto_special_price_groups = [];
    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $_timezone;

    /**
     * @var array|false|string[]
     */
    private  $excluded_skus = [];

    /**
     * @var array|false|string[]
     */
    private  $ignored_pricing_tables = [];
    /**
     * @var \Magento\Framework\Session\SessionManagerInterface
     */
    private $_helperSession;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $_request;

    /**
     *
     * @var \Magento\Authorization\Model\CompositeUserContext
     */
    protected $userContext;

    /**
     * @var \Magento\Framework\App\State
     */
    protected $state;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    private $customerRepository;


    /**
     * @var bool
     */
    private $enableTestDate = false;


    /**
     * @var string
     */
    private $testDate = "";


    /**
     * @var \Magento\Eav\Model\AttributeRepository
     */
    private $attributeRepository;

    /**
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param \Psr\Log\LoggerInterface $logger
     * @param Session $customerSession
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \ITM\Pricing\Model\Cache $cache
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
     * @param \Magento\Framework\Session\SessionManagerInterface $session
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Authorization\Model\CompositeUserContext $userContext
     * @param \Magento\Framework\App\State $state
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Eav\Model\AttributeRepository $attributeRepository
     */
    public function __construct(
        \Magento\Framework\App\ResourceConnection $resource,
        \Psr\Log\LoggerInterface $logger,
        Session $customerSession,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \ITM\Pricing\Model\Cache $cache,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Magento\Framework\Session\SessionManagerInterface $session,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Authorization\Model\CompositeUserContext $userContext,
        \Magento\Framework\App\State $state,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Eav\Model\AttributeRepository $attributeRepository

    ) {
        $this->resource = $resource;
        $this->_logger = $logger;
        $this->_customerSession = $customerSession;
        $this->_scopeConfig = $scopeConfig;
        $this->_objectManager = $objectManager;
        $this->_storeManager = $storeManager;
        $this->cache = $cache;
        $this->_messageManager = $messageManager;
        $this->_cacheTypeList = $cacheTypeList;
        $this->_cacheFrontendPool = $cacheFrontendPool;
        $this->_timezone = $timezone;
        $this->_helperSession = $session;
        $this->_request = $request;
        $this->userContext = $userContext;
        $this->state = $state;
        $this->customerRepository = $customerRepository;
        $this->attributeRepository = $attributeRepository;

        $this->current_edition =  $this->_objectManager->get('\Magento\Framework\App\ProductMetadataInterface')->getEdition();
        $this->current_version = $this->_objectManager->get('\Magento\Framework\App\ProductMetadataInterface')->getVersion();

        //$this->setGroupCustomerId();
        //\Magento\Framework\App\ObjectManager::getInstance()->create('\ITM\MagB1\Helper\Data')->_log("Data");

        $connection = $this->resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);


        if (empty($this->_group_attribute_codes)) {
            $this->_group_attribute_codes = $this->getGroupDiscountPriceAttributes();
        }
        if (empty($this->_customer_attribute_codes)) {
            $this->_customer_attribute_codes = $this->getCustomerDiscountPriceAttributes();
        }
        if ($this->_pricing_type != "") {
            $this->_pricing_type = $this->_scopeConfig->getValue('itm_pricing_section/general/price_type');
        }
        if (empty($this->_another_modules)) {
            $_modules = $this->getConfiguration('itm_pricing_section/general/modules_prices');
            if ($_modules != "") {
                $this->_another_modules = explode("\n", $_modules);
            }
        }

        $ignore_websites = $this->getConfiguration('itm_pricing_section/general/ignore_websites');
        if ($ignore_websites != 1) {
            $this->_website_id = $this->_storeManager->getStore()->getWebsiteId();
        }
        $this->activation_code = $this->getConfiguration('itm_pricing_section/general/activation_code');

        $this->use_index_tables = (bool) $this->getConfiguration('itm_pricing_section/general/use_index_tables');


        $this->_useLimit = !(bool) $this->getConfiguration('itm_pricing_section/general/ignore_limit');
        $this->_ignoreUom = (bool)$this->getConfiguration('itm_pricing_section/general/ignore_uom');
        $this->cleanCache = (bool)$this->getConfiguration('itm_pricing_section/general/clean_cache');
        $this->allowBundle = (bool)$this->_scopeConfig->getValue('itm_pricing_section/general/allow_bundle');
        $this->enableTestDate = (bool)$this->_scopeConfig->getValue('itm_pricing_section/test_pricing/enable_test_date');

        $this->testDate = $this->getConfiguration('itm_pricing_section/test_pricing/test_date');

        $this->priceWhenSpecialHigher = (bool)$this->_scopeConfig->getValue('itm_pricing_section/general/set_price_when_special_higher');

        $this->priceEqualtoSpecialPrice = (bool)$this->_scopeConfig->getValue('itm_pricing_section/general/set_price_eq_special_price');

        if (empty($this->price_equalto_special_price_groups)) {
            $_pespg = (string)$this->getConfiguration('itm_pricing_section/general/set_price_eq_special_price_groups');
            if (!empty($_pespg) || $_pespg == 0) {
                $this->price_equalto_special_price_groups = explode(",", $_pespg);
            }
        }

        if($this->_customer_id > 0) {
            if(empty($this->_helperSession->getSessionCustomerPriceIndexTable())) {
                //\Magento\Framework\App\ObjectManager::getInstance()->create('\ITM\MagB1\Helper\Data')->_log("get customer index table");
                $customer_index_tables = $this->resource->getTableName('itm_pricing_customer_index_tables');
                $connection = $this->resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
                $select_query = $connection->select()->from([
                    'entity' => $customer_index_tables
                ], [
                    'table_name'
                ]);

                $bind[':customer_id'] = $this->_customer_id;
                $select_query->where('entity.customer_id = :customer_id');
                $table_name = $connection->fetchOne($select_query, $bind);
                $this->_customer_price_index_table = $table_name;

                if ($table_name == "") {
                    $this->_customer_price_index_table = $this->_customer_price_main_table;
                }
                $this->_helperSession->setSessionCustomerPriceIndexTable($this->_customer_price_index_table);
            }
        }

        if (empty($this->excluded_skus)) {
            $_skus = $this->getConfiguration('itm_pricing_section/general/excluded_skus');
            if ($_skus != "") {
                $this->excluded_skus = explode("\n", $_skus);
            }
        }

        if (empty($this->ignored_pricing_tables)) {
            $_tbls = $this->getConfiguration('itm_pricing_section/pricing_tables/ignored_pricing_tables');
            if (!empty($_tbls)) {
                $this->ignored_pricing_tables = explode(",", $_tbls);
            }
        }
        //\Magento\Framework\App\ObjectManager::getInstance()->create('\ITM\MagB1\Helper\Data')->_log(print_r($this->ignored_pricing_tables,true));

        if( $this->state->getAreaCode() == "graphql") {
            $userType = $this->userContext->getUserType();
            if($userType == \Magento\Authorization\Model\UserContextInterface::USER_TYPE_CUSTOMER) {
                $customerId = $this->userContext->getUserId();
                $this->_customer_id = $customerId;
                $customer = $this->customerRepository->getById($customerId);
                $this->_customer_group_id = $customer->getGroupId();
                $this->_group_id = $customer->getGroupId();
            }
        }

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
    public  function  getExcludedSkus(){

        return $this->excluded_skus;
    }

    public  function  setPriceWhenSpecialHigher(){
        return $this->priceWhenSpecialHigher;
    }

    public  function  setPriceEqualtoSpecialPrice(){

        if(in_array($this->_group_id, $this->price_equalto_special_price_groups) || in_array("32000", $this->price_equalto_special_price_groups)) {
            return $this->priceEqualtoSpecialPrice;
        }
    }
    public function useIndexTables() {
        return $this->use_index_tables;
    }

    /*
         * Set Store Timezone
         */
    public function setStoreTimezone()
    {
        date_default_timezone_set(
            $this->_timezone->getConfigTimezone('store', $this->_storeManager->getStore())
        );
    }
    public function getGroupID($group_id)
    {
        if ($group_id == "-1") {
            return "all";
        }
        return $group_id;
    }

    public function allowBundleProduct()
    {
        // price_type = 1 => fixed price
        return $this->allowBundle;
    }
    public function getPercentage($total, $number)
    {
        if ($total > 0) {
            return round($number / ($total / 100), 3) ;
        } else {
            return "0";
        }
    }
    public function cleanCache() {
        return $this->cleanCache;
    }
    public function ignoreUom() {
        return $this->_ignoreUom;
    }
    private function getGroupPriceTableName() {
        if ($this->useIndexTables() == true) {
            return trim($this->_group_price_main_table."_".$this->getGroupID($this->_group_id));
        } else {
            return trim($this->_group_price_main_table);
        }


    }
    private function getCustomerPriceTableName() {

        if ($this->useIndexTables() == true) {
            return trim($this->_customer_price_index_table);
        } else {
            return trim($this->_customer_price_main_table);
        }

    }
    public function disablePriceBoxCache(){

        $disablePriceBoxCache = (bool) $this->getConfiguration('itm_pricing_section/general/disable_pricebox_cache');
        return $disablePriceBoxCache;
    }
    public function isDisable()
    {
        $enabled = (bool) $this->getConfiguration('itm_pricing_section/general/enabled', \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITES);
        return !$enabled;
    }
    public function itmUnserialize($obj)
    {

        if (version_compare($this->current_version, '2.2.0') < 0) {
            return unserialize($obj);
        }else {
            $serializer =  \Magento\Framework\App\ObjectManager::getInstance()
                ->get(\Magento\Framework\Serialize\Serializer\Json::class);
            return $serializer->unserialize($obj);
        }


    }
    public function itmSerialize($obj)
    {
        if (version_compare($this->current_version, '2.2.0') < 0) {
            return serialize($obj);
        }else {
            $serializer =  \Magento\Framework\App\ObjectManager::getInstance()
                ->get(\Magento\Framework\Serialize\Serializer\Json::class);
            return $serializer->serialize($obj);
        }
    }

    public function getCacheListTypes() {
        $cache_atts =  $this->getConfiguration('itm_pricing_section/general/cache_types');
        $cache_atts_array = [];
        if(!empty($cache_atts)) {
            $cache_atts = $cache_atts;
            $cache_atts = trim($cache_atts, ",");

            if ($cache_atts != "") {
                $cache_atts_array = explode(",", $cache_atts);
            }
        }
        return $cache_atts_array;

    }

    public function getFrontendCachePoolList() {

        $cache_atts =  $this->getConfiguration('itm_pricing_section/general/cache_pool_types');
        $cache_atts_array = [];
        if(!empty($cache_atts)) {
            $cache_atts = $cache_atts;
            $cache_atts = trim($cache_atts, ",");

            if ($cache_atts != "") {
                $cache_atts_array = explode(",", $cache_atts);
            }
        }
        return $cache_atts_array;
    }

    public function refreshCache() {

        $types =  $this->getCacheListTypes();
        $poolTypes =  $this->getFrontendCachePoolList();

        foreach ($types as $type) {
            $this->_cacheTypeList->cleanType($type);
        }

        foreach ($this->_cacheFrontendPool as $key=>$cacheFrontend) {
            if (in_array($key, $poolTypes)) {
                $cacheFrontend->getBackend()->clean();
            }
        }
        return true;
    }

    public function getPriceAttributes() {
        $price_atts =  $this->_scopeConfig->getValue('itm_pricing_section/general/price_attributes');

        $price_atts_array = [];
        if(!empty($price_atts)) {
            $price_atts_array =  explode(",", trim($price_atts));
        }
        return $price_atts_array;

    }
    public function getProductAttributeValueNew($product_id, $attribute_id, $sku = "", $suffix = "varchar" ) {
        $connection = $this->resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);

        if($sku != "") {
            $bind_sku = [];
            $bind_sku[':sku'] = $sku;
            $attribute_value_query_sku = $connection->select()
                ->from(['entity' => $this->resource->getTableName('catalog_product_entity')], ['entity_id']
                );
            $attribute_value_query_sku->where('entity.sku = :sku');
            $_product_id = $connection->fetchOne($attribute_value_query_sku, $bind_sku);
            $product_id = $_product_id;
        }


        $bind = [];
        $bind[':attribute_id'] = $attribute_id;
        $bind[':product_id'] = $product_id;
        $bind[':store_id'] = 0;

        $attribute_value_query = $connection->select()
            ->from(['entity' => $this->resource->getTableName('catalog_product_entity_'.$suffix)], ['value']
            );

        $attribute_value_query->where('entity.attribute_id = :attribute_id');

        if ($this->current_edition =="Community") {
            $attribute_value_query->where('entity.entity_id = :product_id');
        } else {
            $attribute_value_query->where('entity.row_id = :product_id');
        }
        $attribute_value_query->where('entity.store_id = :store_id');

        $result = $connection->fetchOne($attribute_value_query, $bind);

        return $result;
    }

    public function getProductAttributeValue($product_id, $attribute_id, $sku = "") {
        $connection = $this->resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);

        if($sku != "") {
            $bind_sku = [];
            $bind_sku[':sku'] = $sku;
            $attribute_value_query_sku = $connection->select()
                ->from(['entity' => $this->resource->getTableName('catalog_product_entity')], ['entity_id']
                );
            $attribute_value_query_sku->where('entity.sku = :sku');
            $_product_id = $connection->fetchOne($attribute_value_query_sku, $bind_sku);
            $product_id = $_product_id;
        }


        $bind = [];
        $bind[':attribute_id'] = $attribute_id;
        $bind[':product_id'] = $product_id;
        $bind[':store_id'] = 0;

        $attribute_value_query = $connection->select()
            ->from(['entity' => $this->resource->getTableName('catalog_product_entity_varchar')], ['value']
            );

        $attribute_value_query->where('entity.attribute_id = :attribute_id');

        if ($this->current_edition =="Community") {
            $attribute_value_query->where('entity.entity_id = :product_id');
        } else {
            $attribute_value_query->where('entity.row_id = :product_id');
        }
        $attribute_value_query->where('entity.store_id = :store_id');

        $result = $connection->fetchOne($attribute_value_query, $bind);

        return $result;
    }

    public function getSetProductAttributes()
    {
        $attributes_array = [];
        $set_product_attributes =  $this->getConfiguration('itm_pricing_section/general/set_product_attributes',\Magento\Store\Model\ScopeInterface::SCOPE_STORE);;
        if(!empty($set_product_attributes)) {
            $attributes_array = (array)json_decode($set_product_attributes, true);
        }
        return $attributes_array;
    }
    protected function getAvailableQty($params)
    {
        $product = $params["product"];
        $uom_entry = $params["uom_entry"];
        $group_id = $params["group_id"];
        $customer_id = $params["customer_id"];
        $website_id = $params["website_id"];
        $sku = $params["sku"];
        $date = $params["date"];

        $connection = $this->resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        // with date

        $product_sku = $product->getSku();
        // binding array
        $bind = [];
        $bind[':group_id'] =  $group_id;
        $bind[':customer_id'] = $customer_id;
        $bind[':website_id'] = $website_id;
        $bind[':sku'] = $sku;
        $bind[':qty'] = 1;
        if(!$this->ignoreUom()) {
            $bind[':uom'] = $uom_entry;
        }
        $bind[':current_date'] = $date;

        // query customer date
        $select_customer = $connection->select()->from(
            [
                'entity' => $this->resource->getTableName($this->getCustomerPriceTableName())
            ],
            [
                'qty'
            ]
        );

        $select_customer->where('entity.status = 1');
        $select_customer->where('entity.customer_id = :customer_id');
        $select_customer->where('entity.website_id = :website_id');
        $select_customer->where('entity.sku = :sku');
        if(!$this->ignoreUom()) {
            $select_customer->where('entity.uom_entry = :uom');
        }
        $select_customer->where('entity.qty > :qty');

        // query with date
        $select_group = $connection->select()->from(
            [
                'entity' => $this->resource->getTableName($this->getGroupPriceTableName())
            ],
            [
                'qty'
            ]
        );
        $select_group->where('entity.status = 1');
        $select_group->where('entity.group_id = :group_id');
        $select_group->where('entity.website_id = :website_id');
        $select_group->where('entity.sku = :sku');
        if(!$this->ignoreUom()) {
            $select_group->where('entity.uom_entry = :uom');
        }
        $select_group->where('entity.qty > :qty');

        $final_select = $connection->select()->union(array(
            $select_customer,
            $select_group
        ));
        // $final_select->distinct();

        $result = $connection->fetchAll($final_select, $bind);

        return $result;
    }

    public function percentage($val1, $val2, $precision)
    {
        $division = $val1 / $val2;

        $res = $division * 100;

        $res = round($res, $precision);

        return $res;
    }

    public function getItemUOMDetails($_item)
    {
        $uom_entry = $_item->getBuyRequest()->getData("itm_uom_entry");
        $om = \Magento\Framework\App\ObjectManager::getInstance();
        $uom_data = $om->create('\ITM\Pricing\Model\ResourceModel\Uom\Collection')
            ->addFieldToFilter("uom_entry", $uom_entry)
            ->getFirstItem();
        $uom_name = $uom_data->getUomCode() . " : " . $uom_data->getUomName();
        $result = trim($uom_name, " ");
        $result = trim($result, ":");
        if ($result == "") {
            return __("Default");
        }
        return "UOM: " . $uom_name;
    }

    public function getItemUOMDetailsByEntry($uom_entry)
    {
        $om = \Magento\Framework\App\ObjectManager::getInstance();
        $uom_data = $om->create('\ITM\Pricing\Model\ResourceModel\Uom\Collection')
            ->addFieldToFilter("uom_entry", $uom_entry)
            ->getFirstItem();
        $uom_name = $uom_data->getUomCode() . " : " . $uom_data->getUomName();
        return $uom_name;
    }

    public function getFinalTierPrices($params, $new_price)
    {

        $website_id = $params["website_id"];
        $group_id = $params["group_id"];
        $customer_group_id = $params["customer_group_id"];
        $customer_id = $params["customer_id"];
        $sku =  $params["sku"] ;
        $uom_entry = $params["uom_entry"];
        $product = $params["product"];
        $params["date"] =  $date = $this->getPricingDate();

        if (empty($this->_available_qty[$product->getId()][$uom_entry])) {
            $this->_available_qty[$product->getId()][$uom_entry] = $this->getAvailableQty($params); //$product, $uom
        }

        $qtys = $this->_available_qty[$product->getId()][$uom_entry];


        asort($qtys);

        $tierPrices = [];
        foreach ($qtys as $item) {
            $qty = $item["qty"];
            $params["qty"] = $qty;
            $price = $this->getFinalPrice($params);
            // to not add the original
            if( (float)$price == (float)$new_price) {
                continue;
            }

            if ($price > 0) {
                $tierPrices[] = [
                    'cust_group' => $this->_customer_group_id,
                    'price_qty' => $qty,
                    'price' => $price,
                    'website_price' => $price,
                    'website_id' => '1'
                ];
            }
        }

        $prices = [
            $new_price
        ];
        foreach ($tierPrices as $key => $tierPrice) {
            if (in_array($tierPrice["price"], $prices)) {
                unset($tierPrices[$key]);
            }
            $prices[] = $tierPrice["price"];
        }

        return $tierPrices;
    }
    public function getTierPrices($product, $uom_entry, $new_price)
    {
        $this->setGroupCustomerId();
        $product_sku = $product->getSku();
        $params = [];
        $params["website_id"] = $this->_website_id;
        $params["group_id"] = $this->_group_id;          // used for any type of group
        $params["customer_group_id"] =  $this->_customer_group_id; // used for custoemr group
        $params["customer_id"] = $this->_customer_id;
        $params["sku"] = $product_sku;
        $params["uom_entry"] = $uom_entry;
        $params["date"] =  $date = $this->getPricingDate();
        $params["force_attribute_value"] = false;
        $params["event"] = "";
        $params["product"] = $product;

        /*
        // Cache (beta)
        $cache_sku = $product_sku;
        $cache_uom = $uom_entry;
        $cache_website_id = $this->_website_id;
        $cache_group_id = $this->_customer_group_id;

        $cacheId = sprintf(
            '%1$s-%2$s-%3$s-%4$s-tier',
            $cache_sku,
            $cache_uom,
            $cache_website_id,
            $cache_group_id
        );

        $objInfo = null;
        $_objInfo = $this->cache->load($cacheId);
        if ($_objInfo) {
            $objInfo = $this->itmUnserialize($_objInfo);
            return $objInfo;
        }
        // end cache
        */

        $tierPrices = $this->getFinalTierPrices($params, $new_price);

        // for cache
        //$this->cache->save($this->itmSerialize($tierPrices), $cacheId);

        return $tierPrices;
    }

    public function getProductPrice($product, $uom)
    {
        $qty = 1;
        return $this->getPrice($product, $uom, $qty);
    }

    public function getCustomPrice($product, $uom)
    {
        if (!$this->checkLicnese($this->activation_code)) {
            return -1;
        }

        if(in_array("gp",$this->ignored_pricing_tables)) {
            return  -1;
        }

        $this->setGroupCustomerId();


        $product_sku = $product->getSku();
        $params = [];
        $params["product"] = $product;
        $params["website_id"] = $this->_website_id;
        $params["group_id"] = $this->_group_id;          // used for any type of group
        $params["customer_group_id"] =  $this->_customer_group_id; // used for custoemr group
        $params["customer_id"] = $this->_customer_id;
        $params["sku"] = $product_sku;
        $params["uom_entry"] = $uom;
        $params["qty"] = 1;
        $params["date"] =  $date = $this->getPricingDate();
        //$params["product"] = $product;

        /*
        // Cache (beta)
        $cache_sku = $product_sku;
        $cache_uom = $uom;
        $cache_qty = 1;
        $cache_website_id = $this->_website_id;
        $cache_group_id = $this->_group_id;
        $cache_customer_id = $this->_customer_id;
        $cache_current_date = $this->getPricingDate();

        $cacheId = sprintf(
                '%1$s-%2$s-%3$s-%4$s-%5$s-%6$s-%7$s-custom',
                $cache_sku,
                $cache_uom,
                $cache_qty,
                $cache_website_id,
                $cache_group_id,
                $cache_customer_id,
                $cache_current_date
                );

        $objInfo = null;
        $_objInfo = $this->cache->load($cacheId);

        if ($_objInfo) {
            $objInfo = $this->itmUnserialize($_objInfo);
            return $objInfo;
        }

        $final_pricing = $this->getFinalCustomPrice($params);

        // for cache
        $this->cache->save($this->itmSerialize($final_pricing), $cacheId);
        */

        return $this->getFinalCustomPrice($params);
    }

    function getPricingDate(){

        $date = date("Y-m-d");
        if($this->enableTestDate) {
            if(strtolower($this->testDate) == 'url') {
                $test_date= $this->_request->getParam('test_date');
            }else {
                $test_date = $this->testDate;
            }
            if(strtotime($test_date)){
                $date = $test_date;
            }
        }

        return $date;
    }
    // The customer price is the new price for the product from price list table with qty = 1
    public function getFinalCustomPrice($params)
    {
        if(in_array("gp",$this->ignored_pricing_tables)) {
            return  -1;
        }

        $uom_entry = $params["uom_entry"];
        $product = $params["product"];
        $website_id = $params["website_id"];
        $group_id = $params["group_id"];
        $customer_id = $params["customer_id"];
        $sku =  $params["sku"] ;
        $params["date"] =  $date = $this->getPricingDate();

        // End Cache

        $connection = $this->resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        // with date

        // binding array
        $bind = [];
        $bind[':group_id'] = $group_id;
        $bind[':website_id'] = $website_id;
        $bind[':sku'] = $sku;
        if(!$this->ignoreUom()) {
            $bind[':uom'] = $uom_entry;
        }
        $bind[':qty'] = 1;

        // Query without date
        $select_group = $connection->select()->from(
            [
                'entity' => $this->resource->getTableName($this->getGroupPriceTableName())
            ],
            [
                'price'
            ]
        );
        $select_group->where('entity.status = 1');
        $select_group->where('entity.group_id = :group_id');
        $select_group->where('entity.website_id = :website_id');
        $select_group->where('entity.sku = :sku');
        if(!$this->ignoreUom()) {
            $select_group->where('entity.uom_entry = :uom');
        }
        $select_group->where('entity.qty <= :qty');
        $select_group->where('entity.start_date is NULL');
        $select_group->where('entity.end_date is NULL');
        $select_group->order('entity.qty DESC');
        $select_group->limit(1);
        // end query

        // $result = $connection->fetchAll ( $select, $bind );
        $result = $connection->fetchAll($select_group, $bind);

        // $this->_logger->debug(print_r($this->_customerSession->getCustomer()->getID(),true));
        // $this->_logger->debug(print_r($bind,true));
        if (count($result) > 0) {
            $final_pricing =  $result[0]["price"];
        } else {
            $final_pricing = -1;
        }

        return $final_pricing;
    }

    // get the Product Price using params
    public function getBasePrice($product_sku, $group_id, $website_id, $uom = -1)
    {
        if (!$this->checkLicnese($this->activation_code)) {
            return -1;
        }

        $connection = $this->resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        // with date

        // binding array
        $bind = [];
        $bind[':group_id'] = $group_id;
        $bind[':website_id'] = $website_id;
        $bind[':sku'] = $product_sku;
        if(!$this->ignoreUom()) {
            $bind[':uom'] = $uom;
        }
        $bind[':qty'] = 1;

        // Query without date
        $select_group = $connection->select()->from(['entity' => $this->resource->getTableName($this->getGroupPriceTableName())], [
            'price'
        ]);

        $select_group->where('entity.status = 1');
        $select_group->where('entity.group_id = :group_id');
        $select_group->where('entity.website_id = :website_id');
        $select_group->where('entity.sku = :sku');
        if(!$this->ignoreUom()) {
            $select_group->where('entity.uom_entry = :uom');
        }
        $select_group->where('entity.qty <= :qty');
        $select_group->where('entity.start_date is NULL');
        $select_group->where('entity.end_date is NULL');
        $select_group->order('entity.qty DESC');
        $select_group->limit(1);
        // end query

        $result = $connection->fetchAll($select_group, $bind);

        if (count($result) > 0) {
            return $result[0]["price"];
        } else {
            return -1;
        }
    }

    public function getItemPrice($product, $uom, $qty, $forceAttributeValue = false, $event = "")
    {
        return $this->getPrice($product, $uom, $qty, $forceAttributeValue, $event);
    }

    protected function getCustomerSpecialPrice($params)
    {
        if($params["customer_id"] == 0) {
            return [];
        }

        $website_id = $params["website_id"];
        $customer_id = $params["customer_id"];
        $sku =  $params["sku"] ;
        $uom_entry = $params["uom_entry"];
        $qty = $params["qty"];
        $date = $params["date"];


        $connection = $this->resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);

        // binding array
        $bind = [];
        $bind[':customer_id'] = $customer_id;
        $bind[':website_id'] = $website_id;
        $bind[':sku'] = $sku;
        if(!$this->ignoreUom()) {
            $bind[':uom'] = $uom_entry;
        }
        $bind[':qty'] = $qty;
        $bind[':current_date'] = $date;

        // query customer date
        $select_customer_date = $connection->select()->from(
            [
                'entity' => $this->resource->getTableName($this->getCustomerPriceTableName())
            ],
            [
                'price'
            ]
        );
        $select_customer_date->where('entity.status = 1');
        $select_customer_date->where('entity.customer_id = :customer_id');
        $select_customer_date->where('entity.website_id = :website_id');
        $select_customer_date->where('entity.sku = :sku');
        if(!$this->ignoreUom()) {
            $select_customer_date->where('entity.uom_entry = :uom');
        }
        $select_customer_date->where('entity.qty <= :qty');
        $select_customer_date->where('entity.start_date <= :current_date and entity.end_date >= :current_date');
        $select_customer_date->order('entity.qty DESC ');
        $select_customer_date->order('entity.start_date DESC');
        if($this->_useLimit) {
            $select_customer_date->limit(1);
        }
        // with start date and no end date
        $select_customer_start_date = $connection->select()->from(
            [
                'entity' => $this->resource->getTableName($this->getCustomerPriceTableName())
            ],
            [
                'price'
            ]
        );
        $select_customer_start_date->where('entity.status = 1');
        $select_customer_start_date->where('entity.customer_id = :customer_id');
        $select_customer_start_date->where('entity.website_id = :website_id');
        $select_customer_start_date->where('entity.sku = :sku');
        if(!$this->ignoreUom()) {
            $select_customer_start_date->where('entity.uom_entry = :uom');
        }
        $select_customer_start_date->where('entity.qty <= :qty');
        $select_customer_start_date->where('entity.start_date <= :current_date');
        $select_customer_start_date->where('entity.end_date is NULL');
        $select_customer_start_date->order('entity.qty DESC ');
        $select_customer_start_date->order('entity.start_date DESC');
        if($this->_useLimit) {
            $select_customer_start_date->limit(1);
        }

        $final_customer_date_select = $connection->select()->union(
            [
                "(" . $select_customer_date . ")",
                "(" . $select_customer_start_date . ")"
            ]
        );

        // query customer without date
        $select_customer = $connection->select()->from(
            [
                'entity' => $this->resource->getTableName($this->getCustomerPriceTableName())
            ],
            [
                'price'
            ]
        );
        $select_customer->where('entity.status = 1');
        $select_customer->where('entity.customer_id = :customer_id');
        $select_customer->where('entity.website_id = :website_id');
        $select_customer->where('entity.sku = :sku');
        if(!$this->ignoreUom()) {
            $select_customer->where('entity.uom_entry = :uom');
        }
        $select_customer->where('entity.qty <= :qty');
        $select_customer->where('entity.start_date is NULL');
        $select_customer->where('entity.end_date is NULL');
        $select_customer->order('entity.qty DESC');
        if($this->_useLimit) {
            $select_customer->limit(1);
        }

        $final_customer_select = $connection->select()->union(
            [
                $final_customer_date_select,
                "(" . $select_customer . ")"
            ]
        );

        $result = $connection->fetchAll($final_customer_select, $bind);

        return $result;
    }

    protected function getPriceListPrice($params)
    {
        $group_id = $params["group_id"];
        $website_id = $params["website_id"];
        $sku =  $params["sku"] ;
        $uom_entry = $params["uom_entry"];
        $qty = $params["qty"];
        $date = $params["date"];


        $connection = $this->resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);

        // binding array
        $bind = [];
        $bind[':group_id'] = $group_id;
        $bind[':website_id'] = $website_id;
        $bind[':sku'] = $sku;
        if(!$this->ignoreUom()) {
            $bind[':uom'] = $uom_entry;
        }
        $bind[':qty'] = $qty;
        $bind[':current_date'] = $date;

        // query with date
        $select_group_date = $connection->select()->from(
            [
                'entity' => $this->resource->getTableName($this->getGroupPriceTableName())
            ],
            [
                'price'
            ]
        );
        $select_group_date->where('entity.status = 1');
        $select_group_date->where('entity.group_id = :group_id');
        $select_group_date->where('entity.website_id = :website_id');
        $select_group_date->where('entity.sku = :sku');
        if(!$this->ignoreUom()) {
            $select_group_date->where('entity.uom_entry = :uom');
        }
        $select_group_date->where('entity.qty <= :qty');
        $select_group_date->where('entity.start_date <= :current_date and entity.end_date >= :current_date');
        $select_group_date->order('entity.qty DESC ');
        $select_group_date->order('entity.start_date DESC');

        if($this->_useLimit) {
            $select_group_date->limit(1);
        }

        /*
         * end date =null
         */
        // query with only start date and no end date
        $select_group_start_date = $connection->select()->from(
            [
                'entity' => $this->resource->getTableName($this->getGroupPriceTableName())
            ],
            [
                'price'
            ]
        );
        $select_group_start_date->where('entity.status = 1');
        $select_group_start_date->where('entity.group_id = :group_id');
        $select_group_start_date->where('entity.website_id = :website_id');
        $select_group_start_date->where('entity.sku = :sku');
        if(!$this->ignoreUom()) {
            $select_group_start_date->where('entity.uom_entry = :uom');
        }
        $select_group_start_date->where('entity.qty <= :qty');
        $select_group_start_date->where('entity.start_date <= :current_date');
        $select_group_start_date->where('entity.end_date is NULL');
        $select_group_start_date->order('entity.qty DESC ');
        $select_group_start_date->order('entity.start_date DESC');

        if($this->_useLimit) {
            $select_group_start_date->limit(1);
        }
        $final_group_date_select = $connection->select()->union(
            [
                "(" . $select_group_date . ")",
                "(" . $select_group_start_date . ")"
            ]
        );

        // Query without date
        $select_group = $connection->select()->from(
            [
                'entity' => $this->resource->getTableName($this->getGroupPriceTableName())
            ],
            [
                'price'
            ]
        );
        $select_group->where('entity.status = 1');
        $select_group->where('entity.group_id = :group_id');
        $select_group->where('entity.website_id = :website_id');
        $select_group->where('entity.sku = :sku');
        if(!$this->ignoreUom()) {
            $select_group->where('entity.uom_entry = :uom');
        }
        $select_group->where('entity.qty <= :qty');
        $select_group->where('entity.start_date is NULL');
        $select_group->where('entity.end_date is NULL');
        $select_group->order('entity.qty DESC');

        if($this->_useLimit) {
            $select_group->limit(1);
        }
        // end query


        $final_group_select = $connection->select()->union(
            [
                $final_group_date_select,
                "(" . $select_group . ")"
            ]
        );

        $result = $connection->fetchAll($final_group_select, $bind);

        return $result;
    }

    protected function getGroupDiscountPriceAttributes()
    {
        $result = $this->_helperSession->getSessioGroupDiscountPriceAttributes();
        $executedGroupDiscountAttributes = $this->_helperSession->getExecutedGroupDiscountPriceAttributes();
        if($executedGroupDiscountAttributes == false) {
            //\Magento\Framework\App\ObjectManager::getInstance()->create('\ITM\MagB1\Helper\Data')->_log("get group discount attributes");
            $connection = $this->resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
            $select = $connection->select()->from(
                [
                    'entity' => $this->resource->getTableName('itm_pricing_groupdiscount')
                ],
                [
                    'attribute_code'
                ]
            );
            $select->distinct();
            $select->where('entity.status = 1');
            $result = $connection->fetchAll($select);

            $this->_helperSession->setSessioGroupDiscountPriceAttributes($result);
            $this->_helperSession->setExecutedGroupDiscountPriceAttributes(true);
        }

        return $result;
    }

    protected function getCustomerDiscountPriceAttributes()
    {
        $result = $this->_helperSession->getSessionCustomerDiscountPriceAttributes();
        $executedCustomerDiscountAttributes = $this->_helperSession->getExecutedCustomerDiscountPriceAttributes();
        if($executedCustomerDiscountAttributes == false) {
            //\Magento\Framework\App\ObjectManager::getInstance()->create('\ITM\MagB1\Helper\Data')->_log("get customer discount attributes");
            $connection = $this->resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
            $select = $connection->select()->from(
                [
                    'entity' => $this->resource->getTableName('itm_pricing_customerdiscount')
                ],
                [
                    'attribute_code'
                ]
            );
            $select->distinct();
            $select->where('entity.status = 1');
            $result = $connection->fetchAll($select);
            $this->_helperSession->setSessionCustomerDiscountPriceAttributes($result);
            $this->_helperSession->setExecutedCustomerDiscountPriceAttributes(true);
        }

        return $result;
    }

    protected function getDiscountCustomer($params, $attribute_codes)
    {
        $product = $params["product"];
        $website_id = $params["website_id"];
        $customer_id = $params["customer_id"];
        $date = $params["date"];
        $force_attribute_value = $params["force_attribute_value"];

        $connection = $this->resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);

        // set attribute vlaue
        $att_value = [];
        $select_text = "";
        foreach ($attribute_codes as $item) {
            $attribute_code = $item["attribute_code"];
            $attribute_value = $product->getData($attribute_code);

            $entityTypeId = 4 ;/*$this->_objectManager
            ->create('Magento\Eav\Model\Config')
            ->getEntityType(\Magento\Catalog\Api\Data\ProductAttributeInterface::ENTITY_TYPE_CODE)
            ->getEntityTypeId();*/
            if (empty($attribute_value) && $force_attribute_value) {
                //\Magento\Framework\App\ObjectManager::getInstance()->create('\ITM\MagB1\Helper\Data')->_log("before-".$attribute_value);
                $attribute = $this->attributeRepository->get($entityTypeId, $attribute_code);
                $attribute_value = $this->getProductAttributeValueNew($product->getEntityId(), $attribute->getAttributeId(), "", $attribute->getBackendType());
                //\Magento\Framework\App\ObjectManager::getInstance()->create('\ITM\MagB1\Helper\Data')->_log("after-".$attribute_value);
            }
            //\Magento\Framework\App\ObjectManager::getInstance()->create('\ITM\MagB1\Helper\Data')->_log($product->getEntityId()." - ".$attribute->getAttributeId()." = ".$attribute_code." = ".$attribute_value." = ".$att_value);

            if ($select_text != "") {
                $select_text .= " or ";
            }
            $select_text .= " (entity.attribute_code = '$attribute_code' and entity.attribute_value='$attribute_value') ";
        }

        // binding array
        $bind = [];
        $bind[':customer_id'] = $customer_id;
        $bind[':website_id'] = $website_id;
        $bind[':current_date'] = $date;

        // query with date
        $select_customer_date = $connection->select()->from(
            [
                'entity' => $this->resource->getTableName('itm_pricing_customerdiscount')
            ],
            [
                'discount'
            ]
        );
        if ($select_text != "") {
            $select_customer_date->where("(" . $select_text . ")");
        }
        $select_customer_date->where('entity.status = 1');
        $select_customer_date->where('entity.customer_id = :customer_id');
        $select_customer_date->where('entity.website_id = :website_id');
        $select_customer_date->where('entity.start_date <= :current_date and entity.end_date >= :current_date');
        $select_customer_date->order('entity.discount DESC ');
        $select_customer_date->order('entity.start_date DESC');
        $select_customer_date->limit(1);


        // query with Start date
        $select_customer_start_date = $connection->select()->from(
            [
                'entity' => $this->resource->getTableName('itm_pricing_customerdiscount')
            ],
            [
                'discount'
            ]
        );
        if ($select_text != "") {
            $select_customer_start_date->where("(" . $select_text . ")");
        }
        $select_customer_start_date->where('entity.status = 1');
        $select_customer_start_date->where('entity.customer_id = :customer_id');
        $select_customer_start_date->where('entity.website_id = :website_id');
        $select_customer_start_date->where('entity.start_date <= :current_date');
        $select_customer_start_date->order('entity.discount DESC ');
        $select_customer_start_date->order('entity.start_date DESC');
        $select_customer_start_date->limit(1);


        // Query without date
        $select_customer = $connection->select()->from(
            [
                'entity' => $this->resource->getTableName('itm_pricing_customerdiscount')
            ],
            [
                'discount'
            ]
        );
        if ($select_text != "") {
            $select_customer->where("(" . $select_text . ")");
        }
        $select_customer->where('entity.status = 1');
        $select_customer->where('entity.customer_id = :customer_id');
        $select_customer->where('entity.website_id = :website_id');
        $select_customer->where('entity.start_date is NULL');
        $select_customer->where('entity.end_date is NULL');
        $select_customer->order('entity.discount DESC');
        $select_customer->limit(1);
        // end query

        $final_group_select = $connection->select()->union(
            [
                "(" . $select_customer_date . ")",
                "(" . $select_customer_start_date . ")",

                "(" . $select_customer . ")"
            ]
        );

        $result = $connection->fetchAll($final_group_select, $bind);

        return $result;
    }

    protected function getDiscountGroup($params, $attribute_codes)
    {
        $product = $params["product"];
        $website_id = $params["website_id"];
        $group_id = $params["group_id"];
        $date = $params["date"];

        $connection = $this->resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);

        // set attribute vlaue
        $att_value = [];
        $select_text = "";
        foreach ($attribute_codes as $item) {
            $attribute_code = $item["attribute_code"];
            $attribute_value = $product->getData($attribute_code);

            if ($select_text != "") {
                $select_text .= " or ";
            }
            $select_text .= " (entity.attribute_code = '$attribute_code' and entity.attribute_value='$attribute_value') ";
        }

        // binding array
        $bind = [];
        $bind[':group_id'] = $group_id;
        $bind[':website_id'] = $website_id;
        $bind[':current_date'] = $date;

        // query with date
        $select_group_date = $connection->select()->from(
            [
                'entity' => $this->resource->getTableName('itm_pricing_groupdiscount')
            ],
            [
                'discount'
            ]
        );
        if ($select_text != "") {
            $select_group_date->where("(" . $select_text . ")");
        }
        $select_group_date->where('entity.status = 1');
        $select_group_date->where('entity.group_id = :group_id');
        $select_group_date->where('entity.website_id = :website_id');
        $select_group_date->where('entity.start_date <= :current_date and entity.end_date >= :current_date');
        $select_group_date->order('entity.discount DESC ');
        $select_group_date->order('entity.start_date DESC');
        $select_group_date->limit(1);

        // query with  start date
        $select_group_start_date = $connection->select()->from(
            [
                'entity' => $this->resource->getTableName('itm_pricing_groupdiscount')
            ],
            [
                'discount'
            ]
        );
        if ($select_text != "") {
            $select_group_start_date->where("(" . $select_text . ")");
        }
        $select_group_start_date->where('entity.status = 1');
        $select_group_start_date->where('entity.group_id = :group_id');
        $select_group_start_date->where('entity.website_id = :website_id');
        $select_group_start_date->where('entity.start_date <= :current_date');
        $select_group_start_date->order('entity.discount DESC ');
        $select_group_start_date->order('entity.start_date DESC');
        $select_group_start_date->limit(1);


        // Query without date
        $select_group = $connection->select()->from(
            [
                'entity' => $this->resource->getTableName('itm_pricing_groupdiscount')
            ],
            [
                'discount'
            ]
        );
        if ($select_text != "") {
            $select_group->where("(" . $select_text . ")");
        }
        $select_group->where('entity.status = 1');
        $select_group->where('entity.group_id = :group_id');
        $select_group->where('entity.website_id = :website_id');
        $select_group->where('entity.start_date is NULL');
        $select_group->where('entity.end_date is NULL');
        $select_group->order('entity.discount DESC');
        $select_group->limit(1);
        // end query

        $final_group_select = $connection->select()->union(
            [
                "(" . $select_group_date . ")",
                "(" . $select_group_start_date . ")",
                "(" . $select_group . ")"
            ]
        );

        $result = $connection->fetchAll($final_group_select, $bind);

        return $result;
    }

    protected function getGroupDiscountPrice($params)
    {

        $product = $params["product"];
        $uom = $params["uom_entry"];

        $result = 0;
        $attribute_codes = $this->_group_attribute_codes;

        $discount_array = $this->getDiscountGroup($params, $attribute_codes);

        $discount_array_prices = array();
        if ($uom == "") {
            $uom = $product->getItmUomEntry();
        }
        $qty = 1;

        // get price for qty = 1
        $price = $this->getFinalCustomPrice($params);

        /*if ($price == 0) {
            $price = $product->getPrice();
        }*/
        if (($price == 0 || $price == -1) && $product->getTypeId() != "bundle") {
            $price = $product->getFinalPrice();
        }else {
            $price = $product->getPrice();
        }

        // calcualte price for two discounts
        foreach ($discount_array as $discount) {
            $discount_value = 0;
            $discount_value = $price * $discount["discount"] / 100;

            $new_price = $price - $discount_value;
            $discount_array_prices[] = [
                "price" => $new_price
            ];
        }

        return $discount_array_prices;
    }

    protected function getCustomerDiscountPrice($params)
    {

        $uom = $params["uom_entry"];
        $product = $params["product"];
        $result = 0;
        $attribute_codes = $this->_customer_attribute_codes;

        $discount_array = $this->getDiscountCustomer($params, $attribute_codes);

        $discount_array_prices = array();
        if ($uom == "") {
            $uom = -1 ;//$product->getItmUomEntry();
        }
        $qty = 1;

        // get price for qty = 1
        $price = $this->getFinalCustomPrice($params);

        /*if ($price == 0) {
            $price = $product->getPrice();
        }*/
        if (($price == 0 || $price == -1) && $product->getTypeId() != "bundle") {
            $price = $product->getFinalPrice();
        }else {
            $price = $product->getPrice();
        }


        // calcualte price for two discounts
        foreach ($discount_array as $discount) {
            $discount_value = 0;
            $discount_value = $price * $discount["discount"] / 100;

            $new_price = $price - $discount_value;
            $discount_array_prices[] = [
                "price" => $new_price
            ];
        }

        return $discount_array_prices;
    }
    protected function setGroupCustomerId() {

        if( $this->state->getAreaCode() == "graphql") {
            //$this->_customer_id = 1;
            ///$this->_group_id = 1;
            return;
        }
        //  \Magento\Framework\App\ObjectManager::getInstance()->create('\ITM\MagB1\Helper\Data')->_log("setGroupCustomerId");
        $_customerSession = $this->_objectManager->create('\Magento\Customer\Model\Session');
        $this->_customer_id = $_customerSession->getCustomer()->getId();
        if ($this->_customer_id > 0) {
            $this->_group_id = $_customerSession->getCustomer()->getGroupId();
        } else {
            $this->_group_id = 0;
        }

        //$default_group_id = $this->_scopeConfig->getValue('itm_pricing_section/general/default_group_id');
        $default_group_id = $this->_scopeConfig->getValue('itm_pricing_section/general/default_group_id', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if ($default_group_id == "") {
            $this->_group_id = 0;
        } else {
            $this->_group_id = $default_group_id;
        }

        //if ($this->_customerSession->isLoggedIn()) {
        if ($this->_customer_id > 0) {
            $this->_customer_group_id = $_customerSession->getCustomer()->getGroupId();
            $group_id = $_customerSession->getCustomer()->getData(self::GROUP_ID_CODE);
            if ($group_id >0) {
                $this->_group_id = $group_id;
            }
        }
    }
    public function getFinalPrice($params){


        $final_price = -1;
        $result_array = [];
        // print_r($params);

        $website_id = $params["website_id"];
        $group_id = $params["group_id"];
        $customer_group_id = $params["customer_group_id"];
        $customer_id = $params["customer_id"];
        $sku =  $params["sku"] ;
        $uom_entry = $params["uom_entry"];
        $qty = $params["qty"];
        $force_attribute_value = $params["force_attribute_value"];

        $product = $params["product"];
        $params["date"] =  $date = $this->getPricingDate();


        ////////////////////////////////////$sku, $uom_entry, $qty///////////////////////////////////////
        // Get Group Price
        if(!in_array("gp",$this->ignored_pricing_tables)) {
            $price_list_price = $this->getPriceListPrice($params);
            if (count($price_list_price) > 0) {
                if ($price_list_price[0]["price"] >= 0) {
                    $final_price = $price_list_price[0]["price"];
                }
            }
            // Add all custom price to result array
            foreach ($price_list_price as $_price) {
                $result_array[] = $_price["price"];
            }
        }
        ////////////////////////////////////////$product, $uom_entry/////////////////////////////////////
        // Get Group Discount
        if(!in_array("gdp",$this->ignored_pricing_tables)) {
            $discount_group_price = $this->getGroupDiscountPrice($params);

            if (count($discount_group_price) > 0) {
                if ($discount_group_price[0]["price"] >= 0) {
                    $final_price = $discount_group_price[0]["price"];
                }
            }
            // Add all Group discount price to result array
            foreach ($discount_group_price as $_price) {
                $result_array[] = $_price["price"];
            }
        }
        ////////////////////////////////////////$product, $uom_entry/////////////////////////////////////
        // GET Customer Discount
        if(!in_array("cdp",$this->ignored_pricing_tables)) {
            if (!$product->getData("ignore_discount_groups")) {
                $discount_customer_price = $this->getCustomerDiscountPrice($params);

                if (count($discount_customer_price) > 0) {
                    if ($discount_customer_price[0]["price"] >= 0) {
                        $final_price = $discount_customer_price[0]["price"];
                    }
                }
                // Add all Customer discount price to result array
                foreach ($discount_customer_price as $_price) {
                    $result_array[] = $_price["price"];
                }
            }
        }

        ////////////////////////////////////////$sku, $uom_entry, $qty/////////////////////////////////////
        // Get Special Price
        if(!in_array("sp",$this->ignored_pricing_tables)) {
            $special_price = $this->getCustomerSpecialPrice($params);
            if (count($special_price) > 0) {
                if ($special_price[0]["price"] >= 0) {
                    $final_price = $special_price[0]["price"];
                }
            }
            // Add all special price to result array
            foreach ($special_price as $_price) {
                $result_array[] = $_price["price"];
            }
        }
        /////////////////////////////////////////////////////////////////////////////

        // Get Price from another modules will be here
        // $this->_another_modules = array("ITM\Test1","ITM\Test2");

        foreach ($this->_another_modules as $_module) {
            $_module = trim($_module);
            $model_name = sprintf('\%s\Helper\Data', $_module);
            $another_module_helper = $this->_objectManager->get($model_name);
            $module_price = $another_module_helper->getPrice($product, $uom_entry, $qty);
            if ($module_price != - 1) {
                $final_price = $module_price;
                $result_array[] = $module_price;
            }
        }
        // end another modules
        if (count($result_array)>0) {
            if ($this->_pricing_type == "lowest") {
                $final_price = min($result_array);
            } elseif ($this->_pricing_type == "highest") {
                $final_price = max($result_array);
            }
        }
        //\Magento\Framework\App\ObjectManager::getInstance()->create('\ITM\MagB1\Helper\Data')->_log($final_price);
        /////////////////////////////////////////////////////////////////////////////


        return $final_price;
    }
    protected function getPrice($product, $uom, $qty, $forceAttributeValue = false, $event = "")
    {
        if (!$this->checkLicnese($this->activation_code)) {
            return -1;
        }

        $this->setGroupCustomerId();

        $result_array = [];
        $result_priority = -1;

        $product_sku = $product->getSku();

        /*
        // Cache (beta)
        $cache_sku = $product_sku;
        $cache_uom = $uom;
        $cache_qty = $qty;
        $cache_website_id = $this->_website_id;
        $cache_group_id = $this->_group_id;
        $cache_customer_id = $this->_customer_id;
        $cache_current_date = $this->getPricingDate();

        $cacheId = sprintf(
            '%1$s-%2$s-%3$s-%4$s-%5$s-%6$s-%7$s',
            $cache_sku,
            $cache_uom,
            $cache_qty,
            $cache_website_id,
            $cache_group_id,
            $cache_customer_id,
            $cache_current_date
        );

        $objInfo = null;
        $_objInfo = $this->cache->load($cacheId);

        if ($_objInfo) {
            $objInfo = $this->itmUnserialize($_objInfo);
            return $objInfo;
        }
        // End Cache
        */
        $params = [];
        $params["website_id"] = $this->_website_id;
        $params["group_id"] = $this->_group_id;          // used for any type of group
        $params["customer_group_id"] =  $this->_customer_group_id; // used for custoemr group
        $params["customer_id"] = $this->_customer_id;
        $params["sku"] = $product_sku;
        $params["uom_entry"] = $uom;
        $params["qty"] = $qty;
        $params["product"] = $product;
        $params["force_attribute_value"] = $forceAttributeValue;
        $params["event"] = $event;


        $final_pricing = $this->getFinalPrice($params);

        // for cache
        //$this->cache->save($this->itmSerialize($final_pricing), $cacheId);


        return $final_pricing;

    }
    public function getProductUomWeight($sku, $uom_entry)
    {
        $collection = $this->_objectManager->create(
            'ITM\Pricing\Model\ResourceModel\Uomweight\Collection'
        );

        $collection->addFieldToFilter("sku", $sku);
        $collection->addFieldToFilter("uom_entry", $uom_entry);

        $product_uom = $collection->getFirstItem();
        //$this->_logger->debug($sku." - ".$uom_entry." - ". $product_uom->getWeight());
        if ($product_uom->getEntityId() > 0) {
            return $product_uom->getWeight();
        }
        return -1;
    }
    public  function enableWeight()
    {
        $enable_weight = $this->getConfiguration('itm_pricing_section/general/enable_weight');
        if ($enable_weight != 1) {
            return false;
        }
        return true;
    }
    public function checkLicnese($activation_code)
    {
        return true;
    }
}
