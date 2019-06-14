<?php

/*
 * This file is part of the doyo/code-coverage project.
 *
 * (c) Anthonius Munthi <https://itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Doyo\Bridge\CodeCoverage\Environment;

/**
 * Define interface for environment handling.
 */
interface RuntimeInterface
{
    /**
     * Returns true when code coverage can be collected.
     *
     * @return bool
     */
    public function canCollectCodeCoverage(): bool;

    /**
     * @return string
     */
    public function getDriverClass(): string;
}
