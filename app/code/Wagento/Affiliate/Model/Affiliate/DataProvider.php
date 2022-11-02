<?php

namespace Wagento\Affiliate\Model\Affiliate;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Wagento\Affiliate\Model\ResourceModel\Affiliate\Collection;

/**
 * Class DataProvider
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param Collection $collection
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        Collection $collection,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $meta,
            $data
        );
        $this->collection = $collection;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        foreach ($items as $item) {
            $this->loadedData[$item->getId()] = $item->getData();
            $this->loadedData[$item->getId()]['image'] = \json_decode($item->getData('image'));
        }
        return $this->loadedData;
    }
}
