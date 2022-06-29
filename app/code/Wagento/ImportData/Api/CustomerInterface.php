<?php

namespace Wagento\ImportData\Api;

/**
 * Interface CustomerInterface
 */
interface CustomerInterface
{
    /**
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function import(): bool;

    /**
     * @param string $file
     */
    public function setFile(string $file): void;
}
