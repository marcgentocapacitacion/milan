<?php

/**
 * Copyright © Wagento, Inc. All rights reserved.
 */
namespace Wagento\StoreLocator\Model;

use Magento\Framework\Model\AbstractModel;
use Wagento\StoreLocator\Api\Data\StoreLocatorInterface;

/**
 * Class StoreLocator
 */
class StoreLocator extends AbstractModel implements StoreLocatorInterface
{
    /**
     * Initialize resources
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(\Wagento\StoreLocator\Model\ResourceModel\StoreLocator::class);
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->getData(self::CODE);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->getData(self::NAME);
    }

    /**
     * @return string
     */
    public function getFullAddress(): string
    {
        return $this->getData(self::FULL_ADDRESS);
    }

    /**
     * @return string
     */
    public function getLongitude(): string
    {
        return $this->getData(self::LONGITUDE);
    }

    /**
     * @return string
     */
    public function getLatitude(): string
    {
        return $this->getData(self::LATITUDE);
    }

    /**
     * @return int
     */
    public function getActive(): int
    {
        return $this->getData(self::ACTIVE);
    }
}
