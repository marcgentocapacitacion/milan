<?php

namespace Wagento\ImportData\Api;

/**
 * Interface TabsInterface
 */
interface TabsInterface
{
    /**
     * @param string $label
     *
     * @return string
     */
    public function getHeader(string $label): string;

    /**
     * @param array $description
     *
     * @return string
     */
    public function getBody(array $description): string;

    /**
     * @return string
     */
    public function getStyle(): string;
}
