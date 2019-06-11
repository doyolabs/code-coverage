<?php


namespace Doyo\Bridge\CodeCoverage;

use Doyo\Bridge\CodeCoverage\Console\ConsoleIO;
use Doyo\Bridge\CodeCoverage\Event\CoverageEvent;
use Doyo\Symfony\Bridge\EventDispatcher\EventDispatcher;

/**
 * Class Dispatcher
 */
class Dispatcher extends EventDispatcher
{
    /**
     * @var ProcessorInterface $processor
     */
    private $processor;

    /**
     * @var ConsoleIO
     */
    private $consoleIO;

    public function __construct(
        ProcessorInterface $processor,
        ConsoleIO $consoleIO
    )
    {
        $this->processor = $processor;
        $this->consoleIO = $consoleIO;
        parent::__construct();
    }

    public function refresh()
    {

    }

    public function start(TestCase $testCase)
    {
        $this->processor->start($testCase);
        $coverageEvent = new CoverageEvent($this->processor, $this->consoleIO, $testCase);
        $this->dispatch($coverageEvent, CoverageEvent::beforeStart);
        $this->dispatch($coverageEvent, CoverageEvent::start);

        return $coverageEvent;
    }

    public function stop()
    {

    }

    public function complete()
    {

    }
}
