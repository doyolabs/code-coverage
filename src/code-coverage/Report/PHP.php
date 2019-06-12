<?php

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
