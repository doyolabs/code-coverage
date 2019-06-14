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

use SebastianBergmann\CodeCoverage\Report\PHP as ReportProcessorPHP;

class PHP extends AbstractReportProcessor
{
    public function getProcessorClass(): string
    {
        return ReportProcessorPHP::class;
    }

    public function getOutputType(): string
    {
        return static::OUTPUT_FILE;
    }

    public function getType(): string
    {
        return 'php';
    }
}
