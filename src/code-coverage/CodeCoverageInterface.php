<?php

namespace Doyo\Bridge\CodeCoverage;


use Doyo\Bridge\CodeCoverage\Event\CoverageEvent;

/**
 * A main code coverage actions that contain main processor
 * for collecting code coverage
 */
interface CodeCoverageInterface
{
    /**
     * Refresh code coverage and remove all data
     *
     * @return CoverageEvent
     */
    public function refresh(): CoverageEvent;

    /**
     *
     *
     * @param TestCase $testCase
     *
     * @return CoverageEvent
     */
    public function start(TestCase $testCase): CoverageEvent;

    /**
     * Stop code coverage
     *
     * @return CoverageEvent
     */
    public function stop(): CoverageEvent;

    /**
     * Complete code coverage and process report
     *
     * @return CoverageEvent
     */
    public function complete(): CoverageEvent;

    public function setResult(int $result): CoverageEvent;
}
