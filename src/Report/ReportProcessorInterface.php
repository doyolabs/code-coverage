<?php


namespace Doyo\Bridge\CodeCoverage\Report;

use Doyo\Bridge\CodeCoverage\Console\ConsoleIO;
use Doyo\Bridge\CodeCoverage\ProcessorInterface;

interface ReportProcessorInterface
{
    public function getType(): string;

    public function process(ProcessorInterface $processor, ConsoleIO $consoleIO);

    public function getTarget(): string;

    /**
     * Get report processor object
     *
     * @return object
     */
    public function getProcessor();
}
