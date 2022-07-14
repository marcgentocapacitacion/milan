<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ITM\MagB1\Api\ConfigurableProduct;

/**
 * Manage options of configurable product
 *
 * @api
 * @since 100.0.2
 */
interface OptionRepositoryInterface
{

    /**
     * Get all options for configurable product
     *
     * @param string $productId
     * @return \Magento\ConfigurableProduct\Api\Data\OptionInterface[]
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\InputException
     */
    public function getListById($productId);


}
