<?php
namespace ITM\Pricing\Model\Api;

use ITM\Pricing\Api\CacheInterface;


class System implements CacheInterface
{

    protected $_cacheTypeList;

    protected $_cacheFrontendPool;

    public function __construct(
            \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
            \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool)
    {
        $this->_cacheTypeList = $cacheTypeList;
        $this->_cacheFrontendPool = $cacheFrontendPool;
    }
   

    /**
     *
     * {@inheritdoc}
     *
     */
    public function refresh()
    {
        // $types = array('config','layout','block_html','collections','reflection','db_ddl','eav','config_integration','config_integration_api','full_page','translate','config_webservice');
        $types = [
            'block_html',
            'collections',
            'full_page',
            'itm_pricing'
        ];
        foreach ($types as $type) {
            $this->_cacheTypeList->cleanType($type);
        }
        foreach ($this->_cacheFrontendPool as $cacheFrontend) {
            $cacheFrontend->getBackend()->clean();
        }
        return true;
    }
}
