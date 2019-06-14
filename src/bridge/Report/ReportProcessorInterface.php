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

namespace Doyo\Bridge\CodeCoverage\Report;

use Doyo\Bridge\CodeCoverage\Console\ConsoleIO;
use Doyo\Bridge\CodeCoverage\ProcessorInterface;

interface ReportProcessorInterface
{
    public function getType(): string;

    public function process(ProcessorInterface $processor, ConsoleIO $consoleIO);

    public function getTarget(): string;

    /**
     * Get report processor object.
     *
     * @return object
     */
    public function getProcessor();
}
