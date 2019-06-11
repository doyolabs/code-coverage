<?php


namespace Doyo\Bridge\CodeCoverage\Event;


use Doyo\Bridge\CodeCoverage\Environment\RuntimeInterface;
use Doyo\Bridge\CodeCoverage\ProcessorInterface;
use Doyo\Bridge\CodeCoverage\TestCase;
use Doyo\Symfony\Bridge\EventDispatcher\Event;
use Doyo\Bridge\CodeCoverage\Console\ConsoleIO;

/**
 * Class CoverageEvent
 *
 * @method bool canCollectCodeCoverage()
 */
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
     * @var ConsoleIO
     */
    private $consoleIO;

    /**
     * @var RuntimeInterface
     */
    private $runtime;

    public function __construct(
        ProcessorInterface $processor,
        ConsoleIO $consoleIO,
        RuntimeInterface $runtime
    )
    {
        $this->processor = $processor;
        $this->consoleIO = $consoleIO;
        $this->runtime = $runtime;
    }

    /**
     * @return ProcessorInterface
     */
    public function getProcessor(): ProcessorInterface
    {
        return $this->processor;
    }

    /**
     * @return ConsoleIO
     */
    public function getConsoleIO(): ConsoleIO
    {
        return $this->consoleIO;
    }

    /**
     * @return RuntimeInterface
     */
    public function getRuntime(): RuntimeInterface
    {
        return $this->runtime;
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->runtime, $name], $arguments);
    }
}
