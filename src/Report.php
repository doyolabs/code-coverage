<?php

namespace Doyo\Bridge\CodeCoverage;

use Doyo\Bridge\CodeCoverage\Event\CoverageEvent;
use Doyo\Bridge\CodeCoverage\Report\ReportProcessorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class Report implements EventSubscriberInterface
{
    /**
     * @var ReportProcessorInterface[]
     */
    private $processors = [];

    public static function getSubscribedEvents()
    {
        return [
            CoverageEvent::report => 'generate'
        ];
    }

    /**
     * @return ReportProcessorInterface[]
     */
    public function getProcessors(): array
    {
        return $this->processors;
    }

    public function addProcessor(ReportProcessorInterface $processor)
    {
        $type = $processor->getType();
        if(!$this->hasProcessor($type)){
            $this->processors[$type] = $processor;
        }
    }

    public function hasProcessor(string $type): bool
    {
        return isset($this->processors[$type]);
    }

    public function generate(CoverageEvent $event)
    {
        $consoleIO = $event->getConsoleIO();
        $processor = $event->getProcessor();

        $consoleIO->coverageSection('processing code coverage reports');

        foreach($this->processors as $reportProcessor){
            $reportProcessor->process($processor, $consoleIO);
        }
    }
}
