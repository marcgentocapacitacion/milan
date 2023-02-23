<?php

namespace Wagento\Sales\Ui\Component\Listing\Column;

use Wagento\Sales\Model\System\Config\Source\OrderOrigin as OrderOriginSource;

/**
 * Class OrderOrigin
 */
class OrderOrigin extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (!isset($item[$this->getData('name')])) {
                    continue;
                }
                $label = '';
                if ($item[$this->getData('name')] == OrderOriginSource::ORDER_ORIGIN_STORE) {
                    $label = OrderOriginSource::ORDER_ORIGIN_STORE_LABEL;
                } else if ($item[$this->getData('name')] == OrderOriginSource::ORDER_ORIGIN_ADMIN) {
                    $label = OrderOriginSource::ORDER_ORIGIN_ADMIN_LABEL;
                }

                $item[$this->getData('name')] = $label;
            }
        }
        return $dataSource;
    }
}
