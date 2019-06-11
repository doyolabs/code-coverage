<?php


namespace Doyo\Bridge\CodeCoverage\Report;


use Doyo\Bridge\CodeCoverage\ProcessorInterface;

abstract class AbstractReportProcessor implements ReportProcessorInterface
{
    protected $processor;

    public function __construct(array $options = array())
    {
        $this->processor = $this->createProcessor($options);
    }

    abstract protected function getProcessorClass();

    public function getType(): string
    {
        // TODO: Implement getType() method.
    }

    public function getTarget(): string
    {
        // TODO: Implement getTarget() method.
    }

    /**
     * @inheritDoc
     */
    public function getProcessor()
    {
        return $this->processor;
    }

    public function process(ProcessorInterface $processor)
    {
        // TODO: Implement process() method.
    }

    protected function configure()
    {

    }

    private function createProcessor(array $options)
    {
        $r = new \ReflectionClass($this->getProcessorClass());
    }
}
