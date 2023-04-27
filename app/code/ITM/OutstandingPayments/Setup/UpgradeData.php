<?php

namespace ITM\OutstandingPayments\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;

class UpgradeData implements UpgradeDataInterface
{

    /**
     *
     * @var  \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    private $eavSetupFactory;

    protected $state;
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $_productRepository;
    /**
     * Constructor
     *
     * @param \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\App\State $state,
        \Magento\Framework\ObjectManagerInterface $objectManager
    )
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->_productRepository = $productRepository;
        $this->state = $state;
        $this->_objectManager = $objectManager;
    }

    /**
     * {@inheritdoc}
     */
    public function upgrade(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        if (version_compare($context->getVersion(), "1.0.2", "<")) {

            //\Magento\Framework\App\ObjectManager::getInstance()->create('\ITM\MagB1\Helper\Data')->_log("insider");
            try{
                $this->state->getAreaCode();
            }
            catch (\Magento\Framework\Exception\LocalizedException $ex) {
                $this->state->setAreaCode('adminhtml');
            }
            $sku = "sap_invoice";
            $product = $this->_productRepository->get($sku);
            $customOptions = $product->getOptions();
            $save = false;
            $typeOptionFound = false;
            foreach ($customOptions as $customOption) {
                if($customOption->getTitle() == "Type") {
                    $typeOptionFound = true;
                }
            }

            if($typeOptionFound == false) {
                $customOption_type = $this->_objectManager->create('Magento\Catalog\Api\Data\ProductCustomOptionInterface');
                $customOption_type_option1 = $this->_objectManager->create('Magento\Catalog\Api\Data\ProductCustomOptionValuesInterface');

                $customOption_type_option1->setTitle("Invoice");
                $customOption_type_option1->setPrice("0");
                $customOption_type_option1->setPriceType("fixed");
                $customOption_type_option1->setProductSku($sku);

                $customOption_type_option2 = $this->_objectManager->create('Magento\Catalog\Api\Data\ProductCustomOptionValuesInterface');
                $customOption_type_option2->setTitle("Down Payment");
                $customOption_type_option2->setPrice("0");
                $customOption_type_option2->setPriceType("fixed");
                $customOption_type_option2->setProductSku($sku);

                $values[] = $customOption_type_option1;
                $values[] = $customOption_type_option2;
                $customOption_type->setTitle('Type')
                    ->setType('drop_down')
                    ->setIsRequire(true)
                    ->setSortOrder(3)
                    ->setProductSku($sku)
                    ->setValues($values);;
                $customOptions[] = $customOption_type;
                $product->setOptions($customOptions);
                $save = true;
            }
            if($save) {
                $this->_productRepository->save($product);
            }
        }
    }
}
