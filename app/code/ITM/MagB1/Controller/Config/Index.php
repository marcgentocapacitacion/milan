<?php
namespace ITM\MagB1\Controller\Config;

use Psr\Log\LoggerInterface;
use \Magento\Framework\App\RequestInterface;

class Index extends \Magento\Framework\App\Action\Action
{

    protected $objectManager;


    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager

    ) {
        parent::__construct($context);
        $this->objectManager = $objectManager;
    }


    /**
     * say hello text
     */
    public function execute()
    {
        $this->_productRepository  = $this->objectManager->get('\Magento\Catalog\Model\ProductRepository');


        $product = $this->_productRepository->getById(2575);
//
        $url =  $product->getProductUrl();


        $this->_productCollectionFactory = $this->objectManager->get('\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addUrlRewrite();
        //$collection->setPageSize(30); // fetching
        foreach ($collection as $product) {
            print_r($product->getProductUrl());
            echo "<br>";
        }

        //echo  $url;//$product->getUrlModel()->getUrl($product);
    }
}