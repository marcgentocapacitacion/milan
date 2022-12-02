<?php

namespace ITM\Pricing\Model\Observer\Sales;

use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;
use \Magento\Framework\App\RequestInterface;

class QuoteItemSetProduct  implements ObserverInterface
{


    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var \ITM\Pricing\Helper\Setting
     */
    protected $_setting;

    /**
     * @param  \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \ITM\Pricing\Helper\Setting $setting
     */

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \ITM\Pricing\Helper\Setting $setting
    )
    {
        $this->_objectManager = $objectManager;
        $this->_setting = $setting;
    }

    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        //\Magento\Framework\App\ObjectManager::getInstance()->create('\ITM\MagB1\Helper\Data')->_log("SalesQuoteItemSetProduct");
        if ($this->_setting->isDisable() == true) {
            return $this;
        }
        if (!$this->_setting->enableWeight()) {
            return $this;
        }

        $quote_item = $observer->getQuoteItem();

        if(in_array($quote_item->getSku(), $this->_setting->getExcludedSkus())) {
            return;
        }

        if ($quote_item->getSku() == "sap_invoice") {
            return;
        }
        $product_weight = $quote_item->getProduct()->getWeight();
        $sku = $quote_item->getSku();
        $uom_entry = $quote_item->getBuyRequest()->getData("itm_uom_entry");

        $weight = $this->_helper->getProductUomWeight($sku, $uom_entry);

        if ($weight == -1) {
            $quote_item->setWeight($product_weight);
        } else {
            $quote_item->setWeight($weight);
        }
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
}
