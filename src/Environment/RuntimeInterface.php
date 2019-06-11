<?php

namespace Doyo\Bridge\CodeCoverage\Environment;


/**
 * Define interface for environment handling
 */
interface RuntimeInterface
{
    /**
     * Returns true when code coverage can be collected
     *
     * @return bool
     */
    public function canCollectCodeCoverage(): bool;

    /**
     * @return string
     */
    public function getDriverClass(): string;
}
