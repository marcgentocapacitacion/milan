<?php
namespace ITM\Pricing\Block\System\Config;

class Information extends \Magento\Config\Block\System\Config\Form\Field
{
    const MODULE_NAME = 'ITM_Pricing';
    protected $_moduleList;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Module\ModuleListInterface $moduleList,
        array $data = []
    ) {
        $this->_moduleList = $moduleList;
        parent::__construct($context, $data);
    }


    /**
     * @return string
     */
    protected function getAuthProviderLink()
    {
        return '';
    }
    /**
     * @return string
     */
    protected function getAuthProviderLinkHref()
    {
        return '';
    }
    /**
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element) {
       $html = "";
       $html = "<ul  style='padding-left:2em'>
                    <li>".$this->getVersion()."</li>
                </ul>";
        
        return $html;
    }
    public function getVersion()
    {
        $version = "Pricing Version: " . $this->_moduleList
                ->getOne(self::MODULE_NAME)['setup_version'];


        return $version;
    }
}