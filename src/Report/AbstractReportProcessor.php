<?php


namespace Doyo\Bridge\CodeCoverage\Report;


use Doyo\Bridge\CodeCoverage\Console\ConsoleIO;
use Doyo\Bridge\CodeCoverage\Exception\ReportException;
use Doyo\Bridge\CodeCoverage\ProcessorInterface;

abstract class AbstractReportProcessor implements ReportProcessorInterface
{
    protected $processor;

    /**
     * @var string
     */
    protected $target;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $fileSystemType;

    public function __construct(array $options = array())
    {
        foreach($options as $name => $value){
            $method = 'set'.ucfirst($name);
            if(method_exists($this,$method)){
                unset($options[$name]);
                call_user_func_array([$this,$method],[$value]);
            }
        }
        $this->processor = $this->createProcessor($options);
    }

    abstract protected function getProcessorClass();

    /**
     * @return string
     */
    public function getFileSystemType(): string
    {
        return $this->fileSystemType;
    }

    /**
     * @param string $target
     */
    public function setTarget(string $target)
    {
        $this->target = $target;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * @param string $fileSystemType
     */
    public function setFileSystemType(string $fileSystemType)
    {
        $this->fileSystemType = $fileSystemType;
    }

    public function getType(): string
    {
        return $this->type;
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

        $constructorParams = $r->getConstructor()->getParameters();
        if(!is_null($constructorParams)){
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

        return $r->newInstanceArgs($args);
    }
}
