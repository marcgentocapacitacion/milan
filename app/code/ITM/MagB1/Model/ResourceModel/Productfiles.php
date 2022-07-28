<?php
    
namespace ITM\MagB1\Model\ResourceModel;

use ITM\MagB1\Model\ProductFiles as AttachmentModel;
class Productfiles extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Model Initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('itm_magb1_productfiles', 'entity_id');
    }

    /**
     * After Save Fucntion Call
     *
     * @param AbstractModel $object AttachmentObject
     *
     * @return $this
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object )
    {
        $this->saveAttachmentRelation($object);
        return parent::_afterSave($object);
    }

    public function getAssignedProductIds($attachmentId){
        return $this->lookupProductIds($attachmentId);
    }

    /**
     * Get Productids with attachmennt
     *
     * @param int $attachmentId attachmentid
     *
     * @return array
     */
    protected function lookupProductIds($attachmentId)
    {
        $adapter = $this->getConnection();
        $select = $adapter->select()->from(
            $this->getTable('itm_magb1_productfile_products'),
            'product_id'
        )->where(
            'attachment_id = ?',
            (int)$attachmentId
        );
        return $adapter->fetchCol($select);
    }
    /**
     * Save New Selected productids to attachment
     *
     * @param AttachmentModel $attachment Attachment
     *
     * @return $this
     */
    protected function saveAttachmentRelation(AttachmentModel $attachment)
    {
        $oldProductIds = $this->lookupProductIds($attachment->getId());
        $attachProductIds = $attachment->getAttachmentProducts();

        if (isset($attachProductIds) && is_string($attachProductIds)) {
            $attachProductIds = (array)json_decode($attachment->getAttachmentProducts());
            $newProductIds = [];
            foreach ($attachProductIds as $key => $value) {
                if (!empty($key)) {
                    $newProductIds[] = $key;
                }
            }
        } else {
            return $this;
        }

        $table = $this->getTable('itm_magb1_productfile_products');

        $insert = array_diff($newProductIds, $oldProductIds);
        $delete = array_diff($oldProductIds, $newProductIds);



        if ($delete) {
            $where = [
                'attachment_id = ?' => (int)$attachment->getId(),
                'product_id IN (?)' => $delete
            ];
            $this->getConnection()->delete($table, $where);
        }

        if ($insert) {
            $data = [];
            foreach ($insert as $productId) {
                $data[] = [
                    'attachment_id' => (int)$attachment->getId(),
                    'product_id' => (int)$productId
                ];
            }
            $this->getConnection()->insertMultiple($table, $data);
        }

        return $this;
    }
    public  function removeProductFromAttachements($productId,$storeId){

        $select = $this->getConnection()->select()->from($this->getTable('itm_magb1_productfile_products'), 'attachment_id')->where('product_id = :product_id');
        $bind = [
            ':product_id' => (string)$productId
        ];
        $attachments = $this->getConnection()->fetchAll($select, $bind);
        $attachment_ids = [];
        foreach ($attachments as $attachment) {
            $attachment_ids[] = $attachment["attachment_id"];
        }

        $select = $this->getConnection()->select()
            ->from($this->getTable('itm_magb1_productfiles'),'entity_id')
            ->where('entity_id IN(?)', $attachment_ids)
            ->where('store_id = ?', $storeId);
            //->where('store_id =:store_id');
        //\Magento\Framework\App\ObjectManager::getInstance()->create('\ITM\MagB1\Helper\Data')->_log($select);

        $attachmentsStores = $this->getConnection()->fetchAll($select);

        $delete_attachment_ids = [];
        foreach ($attachmentsStores as $attachment) {
            $delete_attachment_ids[] = $attachment["entity_id"];
        }

        $table = $this->getTable('itm_magb1_productfile_products');
        $delete = [$productId];
        $where = [
            'product_id IN (?)' => $delete,
            'attachment_id IN (?)' => $delete_attachment_ids
        ];

        //\Magento\Framework\App\ObjectManager::getInstance()->create('\ITM\MagB1\Helper\Data')->_log(print_r($delete_attachment_ids,true)." = ".$storeId);

        //\Magento\Framework\App\ObjectManager::getInstance()->create('\ITM\MagB1\Helper\Data')->_log(print_r($attachmentsStores,true)." = ".$storeId." - ".$productId);

        $this->getConnection()->delete($table, $where);
    }
    /**
     * Get product identifier by sku
     *
     * @param  string $hashCode
     * @return int|false
     */
    public function getIdByHashCode($hashCode, $storeId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from($this->getTable('itm_magb1_productfiles'), 'entity_id')->where('hash_code = :hash_code and store_id= :store_id');
        $bind = [
            ':hash_code' => (string)$hashCode,
            ':store_id' => (string)$storeId
        ];

        return $connection->fetchOne($select, $bind);
    }

    /**
     * Check if the product is assigned
     *
     * @param  string $attachmentId
     * @return int|false
     */
    public function isProductAssigned($attachmentId, $productId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from($this->getTable('itm_magb1_productfile_products'), 'entity_id')->where('attachment_id = :attachment_id and product_id = :product_id');
        $bind = [
            ':attachment_id' => (string)$attachmentId,
            ':product_id' => (string)$productId
        ];
        return $connection->fetchOne($select, $bind);
    }

    /**
     * @param $attachmentId
     * @param $productId
     * @return $this
     */
    protected function saveProductAttachmentRelation($attachmentId, $productId)
    {
        $table = $this->getTable('itm_magb1_productfile_products');

        $insert = [$productId];
        if ($insert) {
            $data = [];
            foreach ($insert as $productId) {
                $data[] = [
                    'attachment_id' => (int)$attachmentId,
                    'product_id' => (int)$productId
                ];
            }
            $this->getConnection()->insertMultiple($table, $data);
        }

        return true;
    }

    public  function addProductToAttachement($attachmentId, $productId){
        $isAssigned = $this->isProductAssigned($attachmentId, $productId);
        if(!$isAssigned) {
            return $this->saveProductAttachmentRelation($attachmentId, $productId);
        }else
        {
            return  true;
        }
    }

    /**
     * Get list of productids with Attachment
     *
     * @param int $attachmentId Attachmentid
     *
     * @return array
     */
    public function getSelectedProducts($attachmentId)
    {
        $adapter = $this->getConnection();
        $select = $adapter->select()->from(
            $this->getTable('itm_magb1_productfile_products'),
            'product_id'
        )->where(
            'attachment_id = ?',
            (int)$attachmentId
        );
        return $adapter->fetchCol($select);
    }
}
