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

namespace Spec\Doyo\Bridge\CodeCoverage\Report;

use Doyo\Bridge\CodeCoverage\Report\AbstractReportProcessor;

class TestAbstractReportProcessor extends AbstractReportProcessor
{
    public function setProcessor($processor)
    {
        $this->processor = $processor;
    }

    public function getOutputType(): string
    {
        return static::OUTPUT_FILE;
    }

    public function getType(): string
    {
        return 'test';
    }

    public function getProcessorClass(): string
    {
        return TestReportProcessor::class;
    }
}
