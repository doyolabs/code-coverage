<?php


namespace Doyo\Bridge\CodeCoverage\Report;

use Doyo\Bridge\CodeCoverage\ProcessorInterface;
use phpDocumentor\Reflection\Types\Object_;

interface ReportProcessorInterface
{
    public function getType(): string;

    public function process(ProcessorInterface $processor);

    public function getTarget(): string;

    /**
     * Get report processor object
     *
     * @return object
     */
    public function getProcessor();
}
