<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace ITM\OutstandingPayments\Plugin\Frontend\Magento\Catalog\Helper\Product;

class Configuration
{

    public function afterGetOptions(
        \Magento\Catalog\Helper\Product\Configuration $subject,
        $result,
        $item
    ) {

        return $result;
    }
}
