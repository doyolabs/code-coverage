<?php


namespace spec\Doyo\Bridge\CodeCoverage\Report;


use Doyo\Bridge\CodeCoverage\Report\AbstractReportProcessor;

class TestAbstractReportProcessor extends AbstractReportProcessor
{
    public function setProcessor($processor)
    {
        $this->processor = $processor;
    }

    protected function getProcessorClass()
    {
        return TestReportProcessor::class;
    }
}
