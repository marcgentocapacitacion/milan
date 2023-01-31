<?php

namespace Wagento\ImportData\Api;

/**
 * Interface ProductDescriptionInterface
 */
interface ProductDescriptionInterface
{
    /**
     * @param array $description
     *
     * @return string
     */
    public function getHtml(array $description): string;
}
