<?php


namespace Doyo\Bridge\CodeCoverage\Event;


use Doyo\Bridge\CodeCoverage\ProcessorInterface;
use Doyo\Bridge\CodeCoverage\TestCase;
use Doyo\Symfony\Bridge\EventDispatcher\Event;

class CoverageEvent extends Event
{
    /**
     * @var ProcessorInterface
     */
    private $processor;

    /**
     * @var TestCase
     */
    private $testCase;

    public function __construct(
        ProcessorInterface $processor,
        TestCase $testCase
    )
    {
        $this->processor = $processor;
        $this->testCase = $testCase;
    }


}
