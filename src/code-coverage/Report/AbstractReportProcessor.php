<?php

/*
 * This file is part of the doyo/code-coverage project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Doyo\Bridge\CodeCoverage\Report;

use Doyo\Bridge\CodeCoverage\Console\ConsoleIO;
use Doyo\Bridge\CodeCoverage\ProcessorInterface;

abstract class AbstractReportProcessor implements ReportProcessorInterface
{
    const OUTPUT_FILE    = 'file';
    const OUTPUT_DIR     = 'dir';
    const OUTPUT_CONSOLE = 'console';

    protected $processor;

    /**
     * @var string
     */
    protected $target;

    /**
     * @var string
     */
    protected $fileSystemType;

    /**
     * A default options for this report processor.
     *
     * @var array
     */
    protected $defaultOptions = [];

    public function __construct(array $options = [])
    {
        $options = array_merge($this->defaultOptions, $options);
        foreach ($options as $name => $value) {
            $method = 'set'.ucfirst($name);
            if (method_exists($this, $method)) {
                unset($options[$name]);
                \call_user_func_array([$this, $method], [$value]);
            }
        }

        $this->processor = $this->createProcessor($options);
    }

    abstract public function getProcessorClass(): string;

    /**
     * Get the output type of this report.
     *
     * @return string
     */
    abstract public function getOutputType(): string;

    /**
     * @param string $target
     */
    public function setTarget(string $target)
    {
        $this->target = $target;
    }

    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * {@inheritdoc}
     */
    public function getProcessor()
    {
        return $this->processor;
    }

    public function process(ProcessorInterface $processor, ConsoleIO $consoleIO)
    {
        try {
            $reportProcessor = $this->processor;
            $reportProcessor->process($processor->getCodeCoverage(), $this->target);
            $info = sprintf(
                '<info>generated <comment>%s</comment> to <comment>%s</comment></info>',
                $this->getType(),
                $this->getTarget()
            );
            $consoleIO->coverageInfo($info);
        } catch (\Exception $exception) {
            $message = sprintf(
                "Failed to generate report type: <comment>%s</comment>. Error message:\n%s",
                $this->getType(),
                $exception->getMessage()
            );
            $consoleIO->coverageInfo($message);
        }
    }

    protected function configure(array $options)
    {
    }

    protected function createProcessor(array $options)
    {
        $r    = new \ReflectionClass($this->getProcessorClass());
        $args = [];

        $constructor= $r->getConstructor();
        if (
            null !== $constructor
            && \is_array($constructorParams = $constructor->getParameters())
        ) {
            foreach ($constructorParams as $parameter) {
                $name    = $parameter->getName();
                $value   = null;
                $default = null;
                if (
                    !$parameter->isDefaultValueAvailable()
                    && !isset($options[$name])
                ) {
                    break;
                }

                if ($parameter->isDefaultValueAvailable()) {
                    $default = $parameter->getDefaultValue();
                }

                if (isset($options[$name])) {
                    $value = $options[$name];
                }
                $args[] = null !== $value ? $value : $default;
            }
        }

        $outputType = $this->getOutputType();
        $dir        = $this->getTarget();

        if (static::OUTPUT_FILE === $outputType) {
            $dir = \dirname($dir);
        }

        if (static::OUTPUT_CONSOLE !== $outputType && !is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        return $r->newInstanceArgs($args);
    }
}
