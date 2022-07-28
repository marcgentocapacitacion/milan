<?php
    
namespace ITM\MagB1\Model;
    
class Productfiles extends \Magento\Framework\Model\AbstractModel
{

    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('ITM\MagB1\Model\ResourceModel\Productfiles');
    }
    /**
     * Return the Selected Products of Attachment
     *
     * @param int $attachmentid Current Attachment Id
     *
     * @return mixed
     */
    public function getSelectedProducts($attachmentid)
    {
        $productsids = $this->getResource()->getSelectedProducts($attachmentid);
        return $productsids;
    }
}
