<?php

namespace Doyo\Bridge\CodeCoverage\Report;

use SebastianBergmann\CodeCoverage\Report\Html\Facade as HtmlReportProcessor;

class Html extends AbstractReportProcessor
{
    protected function getProcessorClass()
    {
        return HtmlReportProcessor::class;
    }
}
