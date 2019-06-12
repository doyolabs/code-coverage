<?php


namespace Doyo\Bridge\CodeCoverage\Report;


use Doyo\Bridge\CodeCoverage\Console\ConsoleIO;
use Doyo\Bridge\CodeCoverage\Exception\ReportException;
use Doyo\Bridge\CodeCoverage\ProcessorInterface;

abstract class AbstractReportProcessor implements ReportProcessorInterface
{
    const OUTPUT_FILE = 'file';
    const OUTPUT_DIR = 'dir';
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
     * A default options for this report processor
     *
     * @var array
     */
    protected $defaultOptions = [];

    public function __construct(array $options = array())
    {
        $options = array_merge($this->defaultOptions, $options);
        foreach($options as $name => $value){
            $method = 'set'.ucfirst($name);
            if(method_exists($this,$method)){
                unset($options[$name]);
                call_user_func_array([$this,$method],[$value]);
            }
        }

        $this->processor = $this->createProcessor($options);
    }

    abstract public function getProcessorClass(): string;

    /**
     * Get the output type of this report
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
     * @inheritDoc
     */
    public function getProcessor()
    {
        return $this->processor;
    }

    public function process(ProcessorInterface $processor, ConsoleIO $consoleIO)
    {
        try{
            $reportProcessor = $this->processor;
            $reportProcessor->process($processor->getCodeCoverage(), $this->target);
            $info = sprintf(
                'generated <comment>%s</comment> to: %s',
                $this->getType(),
                $this->getTarget()
            );
            $consoleIO->coverageInfo($info);
        }catch (\Exception $exception){
            $message = sprintf(
                "Failed to generate report type: <comment>%s</comment>. Error message:\n%s",
                $this->getType(),
                $exception->getMessage()
            );
            $consoleIO->coverageError($message);
        }
    }

    protected function configure(array $options)
    {

    }

    private function createProcessor(array $options)
    {
        $r = new \ReflectionClass($this->getProcessorClass());
        $args = [];

        $constructor= $r->getConstructor();
        if(
            !is_null($constructor)
            && is_array($constructorParams = $constructor->getParameters())
        ){
            foreach($constructorParams as $parameter){
                if(!$parameter->isDefaultValueAvailable()){
                    break;
                }
                $name = $parameter->getName();
                $value = $parameter->getDefaultValue();
                if(isset($options[$name])){
                    $value = $options[$name];
                }
                $args[] = $value;
            }
        }

        $outputType = $this->getOutputType();
        $dir = $this->getTarget();

        if(static::OUTPUT_FILE === $outputType){
            $dir = dirname($dir);
        }

        if(static::OUTPUT_CONSOLE !== $outputType && !is_dir($dir)){
            mkdir($dir, 0775, true);
        }

        return $r->newInstanceArgs($args);
    }
}
