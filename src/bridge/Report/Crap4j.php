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

namespace Doyo\Bridge\CodeCoverage\Report;

class Crap4j extends AbstractReportProcessor
{
    protected $defaultOptions = [
        'target' => 'build/logs/crap4j.xml',
    ];

    public function __construct(array $options = [])
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
