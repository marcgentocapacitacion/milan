<?php

namespace Wagento\OfflineShipping\Model\Config;

/**
 * Interface ConfigInterface
 */
interface ConfigInterface
{
    /**
     * @const string
     */
    public const COST_TYPE_HANDLING_TYPE = 'carriers/tablerate/cost_type_handling_type';

    /**
     * @return string
     */
    public function getCostTypeHandlingType(): string;
}
