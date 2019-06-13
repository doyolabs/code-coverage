<?php

/*
 * This file is part of the doyo/code-coverage project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Doyo\Bridge\CodeCoverage\Report;

use SebastianBergmann\CodeCoverage\Report\Clover as ReportCloverProcessor;

class Clover extends AbstractReportProcessor
{
    protected $defaultOptions = [
        'target' => 'build/logs/clover.xml',
    ];

    public function getProcessorClass(): string
    {
        return ReportCloverProcessor::class;
    }

    public function getOutputType(): string
    {
        return self::OUTPUT_FILE;
    }

    public function getType(): string
    {
        return 'clover';
    }
}
