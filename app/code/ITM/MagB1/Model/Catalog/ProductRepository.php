<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ITM\MagB1\Model\Catalog;


/**
 * @inheritdoc
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class ProductRepository extends \Magento\Catalog\Model\ProductRepository implements \ITM\MagB1\Api\Catalog\ProductRepositoryInterface
{
    /**
     * @inheritdoc
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function saveById(\Magento\Catalog\Api\Data\ProductInterface $product, $saveOptions = false)
    {
        try {
            $existingProduct = $product->getId() ?
                $this->getById($product->getId()) :
                $this->get($product->getSku());

        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            $existingProduct = null;
        }
        if($existingProduct) {
            $product->setSku($existingProduct->getSku());
        }
       return parent::save($product, $saveOptions);
    }
}