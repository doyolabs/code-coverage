<?php

/*
 * This file is part of the doyo/code-coverage project.
 *
 * (c) Anthonius Munthi <https://itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Doyo\Bridge\CodeCoverage;

use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Driver\Driver;
use SebastianBergmann\CodeCoverage\Filter;

/**
 * Provide bridge to PHP Code Coverage.
 */
class Processor implements ProcessorInterface
{
    /**
     * @var CodeCoverage
     */
    private $codeCoverage;

    /**
     * @var TestCase[]
     */
    private $testCases = [];

    private $completed = false;

    /**
     * @var Driver
     */
    private $driver;

    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var TestCase
     */
    private $currentTestCase;

    public function __construct(CodeCoverage $codeCoverage)
    {
        $this->codeCoverage = $codeCoverage;
    }

    public function setCurrentTestCase(TestCase $testCase)
    {
        $this->currentTestCase = $testCase;
    }

    public function getCurrentTestCase()
    {
        return $this->currentTestCase;
    }

    public function start(TestCase $testCase, $clear = false)
    {
        $this->setCurrentTestCase($testCase);
        $this->addTestCase($testCase);
        $this->codeCoverage->start($testCase->getName(), $clear);
    }

    public function stop(bool $append = true, $linesToBeCovered = [], array $linesToBeUsed = [], bool $ignoreForceCoversAnnotation = false): array
    {
        return $this->codeCoverage->stop($append, $linesToBeCovered, $linesToBeUsed, $ignoreForceCoversAnnotation);
    }

    public function merge($processor)
    {
        $codeCoverage = $processor;
        if ($processor instanceof self) {
            $codeCoverage = $processor->getCodeCoverage();
        }
        $this->getCodeCoverage()->merge($codeCoverage);
    }

    public function clear()
    {
        $this->codeCoverage->clear();
    }

    /**
     * @return CodeCoverage
     */
    public function getCodeCoverage()
    {
        return $this->codeCoverage;
    }

    public function addTestCase(TestCase $testCase)
    {
        $this->testCases[$testCase->getName()] = $testCase;
    }

    public function complete()
    {
        $coverage  = $this->codeCoverage;
        $testCases = $this->testCases;
        $tests     = $coverage->getTests();

        foreach ($testCases as $testCase) {
            $name                   = $testCase->getName();
            $tests[$name]['status'] = $testCase->getResult();
        }

        $coverage->setTests($tests);
        $this->completed = true;
    }
}
