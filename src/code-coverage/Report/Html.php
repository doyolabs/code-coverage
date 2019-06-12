<?php

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
