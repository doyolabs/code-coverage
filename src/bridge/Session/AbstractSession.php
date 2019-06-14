<?php

namespace Doyo\Bridge\CodeCoverage\Session;

use Doyo\Bridge\CodeCoverage\ContainerFactory;
use Doyo\Bridge\CodeCoverage\ProcessorInterface;
use Doyo\Bridge\CodeCoverage\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

abstract class AbstractSession implements SessionInterface
{
    const CACHE_KEY = 'session';

    /**
     * @var string
     */
    protected $name;

    /**
     * @var TestCase|null
     */
    protected $testCase;

    /**
     * @var FilesystemAdapter|null
     */
    protected $adapter;

    /**
     * @var ProcessorInterface|null
     */
    protected $processor;

    /**
     * @var array
     */
    protected $exceptions = [];

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var ContainerInterface|null
     */
    protected $container;

    /**
     * @var ProcessorInterface|null
     */
    protected $currentProcessor;

    protected $cachedProperties = [
        'name',
        'processor',
        'exceptions',
        'config',
        'testCase',
    ];

    /**
     * AbstractSession constructor.
     * @param string $name
     * @param array $config
     */
    public function __construct(string $name)
    {
        $dir = sys_get_temp_dir() . '/doyo/code-coverage/sessions';
        $this->adapter = new FilesystemAdapter($name, 0, $dir);
        $this->refresh();

        $this->name = $name;

        register_shutdown_function([$this,'shutdown']);
    }

    public function init(array $config)
    {
        $this->config = $config;
        $this->save();
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getProcessor()
    {
        // TODO: Implement getProcessor() method.
    }

    public function refresh()
    {
        $adapter = $this->adapter;

        $cached = $adapter->getItem(static::CACHE_KEY)->get();

        foreach($this->cachedProperties as $name){
            $this->{$name} = $cached[$name];
        }

        if(empty($this->config)){
            return;
        }

        $container = (new ContainerFactory($this->config))->getContainer();
        if(is_null($this->processor)){
            $this->processor = $container->get('factory')->createProcessor();
        }
        $this->container = $container;
    }

    public function save()
    {
        $adapter = $this->adapter;
        $item = $adapter->getItem(static::CACHE_KEY);

        $data = [];
        foreach($this->cachedProperties as $property){
            $data[$property] = $this->{$property};
        }

        $item->set($data);
        $adapter->save($item);
    }

    public function reset()
    {
        $this->testCase   = null;
        $this->exceptions = [];

        $this->processor->clear();
    }

    public function hasExceptions()
    {
        // TODO: Implement hasExceptions() method.
    }

    public function getExceptions()
    {
        // TODO: Implement getExceptions() method.
    }

    public function addException(\Exception $exception)
    {
        $message = $exception->getMessage();
        $id = md5($message);

        if(!isset($this->exceptions[$id])){
            $this->exceptions[$id] = $exception;
        }
    }

    public function setTestCase(TestCase $testCase)
    {
        // TODO: Implement setTestCase() method.
    }

    public function start()
    {
        try{
            $container = $this->container;
            $testCase = $this->testCase;
            $processor = $container->get('factory')->createProcessor();
            $processor->setCurrentTestCase($testCase);
            $this->currentProcessor = $processor;
        }catch (\Exception $exception){
            $this->addException($exception);
        }
    }

    public function stop()
    {
        $this->currentProcessor->stop();
        $this->processor->merge($this->currentProcessor);
    }

    public function shutdown()
    {
        if(!is_null($this->currentProcessor)){
            $this->stop();
        }
        $this->save();
    }
}
