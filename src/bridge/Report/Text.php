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

use Doyo\Bridge\CodeCoverage\Console\ConsoleIO;
use Doyo\Bridge\CodeCoverage\ProcessorInterface;

class Text extends AbstractReportProcessor
{
    protected $defaultOptions = [
        'target' => 'console',
    ];

    public function getProcessorClass(): string
    {
        return \SebastianBergmann\CodeCoverage\Report\Text::class;
    }

    public function getOutputType(): string
    {
        return 'console' !== $this->getTarget() ? static::OUTPUT_FILE : static::OUTPUT_CONSOLE;
    }

    public function getType(): string
    {
        return 'text';
    }

    public function process(ProcessorInterface $processor, ConsoleIO $consoleIO)
    {
        $reportProcessor = $this->processor;
        $coverage        = $processor->getCodeCoverage();
        $target          = $this->target;

        $output = $reportProcessor->process($coverage);
        if ('console' === $target) {
            $consoleIO->coverageInfo($output);
        } else {
            file_put_contents($target, $output);
        }
    }
}
