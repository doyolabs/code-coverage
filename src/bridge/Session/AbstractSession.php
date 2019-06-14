<?php

namespace Doyo\Bridge\CodeCoverage\Session;

use Doyo\Bridge\CodeCoverage\ContainerFactory;
use Doyo\Bridge\CodeCoverage\Exception\SessionException;
use Doyo\Bridge\CodeCoverage\ProcessorInterface;
use Doyo\Bridge\CodeCoverage\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

abstract class AbstractSession implements SessionInterface, \Serializable
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
     * @var ProcessorInterface
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
        $this->createContainer($config);
        $this->processor = $this->container->get('factory')->createProcessor(true);
        $this->save();
    }

    public function serialize()
    {
        $data = $this->toCache();

        return \serialize($data);
    }

    public function unserialize($serialized)
    {
        $cache = \unserialize($serialized);
        $this->fromCache($cache);
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
        return $this->processor;
    }

    public function refresh()
    {
        $adapter = $this->adapter;

        $cached = $adapter->getItem(static::CACHE_KEY)->get();
        $this->fromCache($cached);

        $this->createContainer($this->config);
    }

    private function createContainer($config)
    {
        $container = (new ContainerFactory($config))->getContainer();
        $this->container = $container;
    }

    private function toCache()
    {
        $data = [];

        foreach($this->cachedProperties as $property){
            $data[$property] = $this->{$property};
        }

        return $data;
    }

    private function fromCache($cache)
    {
        if(is_null($cache)){
            return;
        }
        foreach ($cache as $name => $value){
            $this->{$name} = $value;
        }
    }

    public function save()
    {
        $adapter = $this->adapter;
        $item = $adapter->getItem(static::CACHE_KEY);
        $data = $this->toCache();

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
        return count($this->exceptions) > 0;
    }

    public function getExceptions()
    {
        return $this->exceptions;
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
        $this->testCase = $testCase;
    }

    /**
     * @throws SessionException If TestCase is null
     */
    public function start()
    {
        if(is_null($this->testCase)){
            throw new SessionException('Can not start coverage without null TestCase');
        }

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
