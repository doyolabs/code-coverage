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

use SebastianBergmann\CodeCoverage\Report\Html\Facade as HtmlReportProcessor;

class Html extends AbstractReportProcessor
{
    public function getType(): string
    {
        return 'html';
    }

    public function getOutputType(): string
    {
        return static::OUTPUT_DIR;
    }

    public function getProcessorClass(): string
    {
        return HtmlReportProcessor::class;
    }
}
