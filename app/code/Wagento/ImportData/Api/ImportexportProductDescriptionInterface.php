<?php

namespace Wagento\ImportData\Api;

/**
 * Interface ImportexportProductDescriptionInterface
 */
interface ImportexportProductDescriptionInterface
{
    /**
     * @const string
     */
    public const ENTITY_ID = 'entity_id';

    /**
     * @const string
     */
    public const SKU = 'sku';

    /**
     * @const string
     */
    public const DATA_DESCRIPTION = 'data_description';

    /**
     * @return string
     */
    public function getSku(): string;

    /**
     * @param string $sku
     *
     * @return mixed
     */
    public function setSku(string $sku);

    /**
     * @return array
     */
    public function getDataDescription(): array;

    /**
     * @param string $data
     *
     * @return mixed
     */
    public function setDataDescription(string $data);

}
