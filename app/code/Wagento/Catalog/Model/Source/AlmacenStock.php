<?php

namespace Wagento\Catalog\Model\Source;

use Magento\InventoryApi\Api\StockRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

/**
 * Class AlmacenStock
 */
class AlmacenStock extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * @var StockRepositoryInterface
     */
    protected StockRepositoryInterface $stockRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected SearchCriteriaBuilder $criteriaBuilder;

    /**
     * @param StockRepositoryInterface $stockRepository
     * @param SearchCriteriaBuilder    $criteriaBuilder
     */
    public function __construct(
        StockRepositoryInterface $stockRepository,
        SearchCriteriaBuilder $criteriaBuilder
    ) {
        $this->stockRepository = $stockRepository;
        $this->criteriaBuilder = $criteriaBuilder;
    }

    /**
     * Retrieve all options array
     *
     * @return array
     */
    public function getAllOptions()
    {
        if ($this->_options === null) {
            $criteriaBuilder = $this->criteriaBuilder->create();
            $data = $this->stockRepository->getList($criteriaBuilder);
            $this->_options[] = [
                'label' => __('Select stock'),
                'value' => ''
            ];
            /** @var \Magento\InventoryApi\Api\Data\StockInterface $item */
            foreach ($data->getItems() as $item) {
                $this->_options[] = [
                    'label' => $item->getName(),
                    'value' => $item->getStockId()
                ];
            }
        }
        return $this->_options;
    }

    /**
     * Retrieve option array
     *
     * @return array
     */
    public function getOptionArray()
    {
        $_options = [];
        foreach ($this->getAllOptions() as $option) {
            $_options[$option['value']] = $option['label'];
        }
        return $_options;
    }

    /**
     * Get a text for option value
     *
     * @param string|int $value
     * @return string|false
     */
    public function getOptionText($value)
    {
        $options = $this->getAllOptions();
        foreach ($options as $option) {
            if ($option['value'] == $value) {
                return $option['label'];
            }
        }
        return false;
    }
}
