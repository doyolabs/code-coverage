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

namespace Doyo\Bridge\CodeCoverage\Console;

interface ConsoleIO
{
    /**
     * Print current report section.
     *
     * @param string $section
     */
    public function coverageSection(string $section);

    /**
     * Display info message during coverage.
     *
     * @param string $message
     */
    public function coverageInfo(string $message);

    /**
     * Display error during coverage.
     *
     * @param string $message
     */
    public function coverageError(string $message);
}
