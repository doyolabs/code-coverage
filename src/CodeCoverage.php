<?php


namespace Doyo\Bridge\CodeCoverage;

use Doyo\Bridge\CodeCoverage\Console\ConsoleIO;
use Doyo\Bridge\CodeCoverage\Environment\RuntimeInterface;
use Doyo\Bridge\CodeCoverage\Event\CoverageEvent;
use Doyo\Symfony\Bridge\EventDispatcher\EventDispatcher;

/**
 * A main code coverage actions that contain main processor
 * for collecting code coverage
 */
class CodeCoverage extends EventDispatcher
{
    /**
     * @var CoverageEvent
     */
    private $coverageEvent;

    public function __construct(
        ProcessorInterface $processor,
        ConsoleIO $consoleIO,
        RuntimeInterface $runtime
    )
    {
        $this->coverageEvent = new CoverageEvent($processor, $consoleIO, $runtime);
        parent::__construct();
    }

    public function refresh()
    {
        $coverageEvent = $this->coverageEvent;

        if($coverageEvent->canCollectCodeCoverage()){
            $coverageEvent->getProcessor()->clear();
            $this->dispatch($coverageEvent, CoverageEvent::refresh);
        }

        return $coverageEvent;
    }

    public function start(TestCase $testCase)
    {
        $coverageEvent = $this->coverageEvent;

        if($coverageEvent->canCollectCodeCoverage()){
            $coverageEvent->getProcessor()->start($testCase);
            $this->dispatch($coverageEvent, CoverageEvent::beforeStart);
            $this->dispatch($coverageEvent, CoverageEvent::start);
        }

        return $coverageEvent;
    }

    public function stop()
    {
        $coverageEvent = $this->coverageEvent;
        if($coverageEvent->canCollectCodeCoverage()){
            $coverageEvent->getProcessor()->stop();
            $this->dispatch($coverageEvent, CoverageEvent::stop);
        }

        return $coverageEvent;
    }

    public function complete()
    {
        $coverageEvent = $this->coverageEvent;
        $consoleIO = $coverageEvent->getConsoleIO();

        if($coverageEvent->canCollectCodeCoverage()){
            $coverageEvent->getProcessor()->complete();
            $this->dispatch($coverageEvent, CoverageEvent::complete);
            $this->dispatch($coverageEvent, CoverageEvent::report);
        }else{
            $consoleIO->coverageError('Can not create coverage report. No code coverage driver available');
        }

        return $coverageEvent;
    }

    public function setResult(int $result)
    {
        $coverageEvent = $this->coverageEvent;
        if($coverageEvent->canCollectCodeCoverage()){
            $coverageEvent->getProcessor()->getCurrentTestCase()->setResult($result);
        }
    }
}
