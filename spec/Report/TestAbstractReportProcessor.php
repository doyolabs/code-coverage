<?php


namespace spec\Doyo\Bridge\CodeCoverage\Report;


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
