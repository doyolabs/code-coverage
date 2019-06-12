<?php

namespace Doyo\Bridge\CodeCoverage\Report;

class Crap4j extends AbstractReportProcessor
{
    protected $defaultOptions = [
        'target' => 'build/logs/crap4j.xml',
    ];

    public function __construct(array $options = array())
    {
        parent::__construct($options);
    }

    public function getProcessorClass(): string
    {
        return \SebastianBergmann\CodeCoverage\Report\Crap4j::class;
    }

    public function getOutputType(): string
    {
        return static::OUTPUT_FILE;
    }

    public function getType(): string
    {
        return 'crap4j';
    }
}
