<?php


namespace Doyo\Bridge\CodeCoverage\Event;


use Doyo\Bridge\CodeCoverage\ProcessorInterface;
use Doyo\Bridge\CodeCoverage\TestCase;
use Doyo\Symfony\Bridge\EventDispatcher\Event;
use Doyo\Bridge\CodeCoverage\Console\ConsoleIO;

class CoverageEvent extends Event
{
    const refresh = 'refresh';
    const beforeStart = 'beforeStart';
    const start = 'start';
    const stop = 'stop';
    const complete = 'complete';

    /**
     * @var ProcessorInterface
     */
    private $processor;

    /**
     * @var TestCase
     */
    private $testCase;

    /**
     * @var ConsoleIO
     */
    private $consoleIO;

    public function __construct(
        ProcessorInterface $processor,
        ConsoleIO $consoleIO,
        TestCase $testCase
    )
    {
        $this->processor = $processor;
        $this->testCase = $testCase;
        $this->consoleIO = $consoleIO;
    }

    /**
     * @return ProcessorInterface
     */
    public function getProcessor(): ProcessorInterface
    {
        return $this->processor;
    }

    /**
     * @return TestCase
     */
    public function getTestCase(): TestCase
    {
        return $this->testCase;
    }

    /**
     * @return ConsoleIO
     */
    public function getConsoleIO(): ConsoleIO
    {
        return $this->consoleIO;
    }
}
