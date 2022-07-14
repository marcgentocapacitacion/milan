<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ITM\MagB1\Model\ConfigurableProduct;

/**
 * Class OptionRepository
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class OptionRepository extends \Magento\ConfigurableProduct\Model\OptionRepository implements \ITM\MagB1\Api\ConfigurableProduct\OptionRepositoryInterface
{


    /**
     * @inheritdoc
     */
    public function getListById($productId)
    {
        $product = $this->getProductById($productId);

        return parent::getList($product->getSku());
    }
    /**
     * Retrieve product instance by id
     *
     * @param int $id
     * @return ProductInterface
     * @throws InputException
     */
    private function getProductById($id)
    {
        $product = $this->productRepository->getById($id);
        if (\Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE !== $product->getTypeId()) {
            throw new \Magento\Framework\Exception\InputException(
                __('This is implemented for the "%1" configurable product only.', $id)
            );
        }
        return $product;
    }
}
