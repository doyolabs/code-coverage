<?php


namespace Doyo\Bridge\CodeCoverage\Report;

use SebastianBergmann\CodeCoverage\Report\Clover as ReportCloverProcessor;

class Clover extends AbstractReportProcessor
{
    protected $defaultOptions = [
        'target' => 'build/logs/clover.xml'
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
