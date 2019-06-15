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

    private $started = false;

    /**
     * AbstractSession constructor.
     *
     * @param string $name
     * @param array  $config
     */
    public function __construct(string $name)
    {
        $dir           = sys_get_temp_dir().'/doyo/code-coverage/sessions';
        $this->adapter = new FilesystemAdapter($name, 0, $dir);
        $this->refresh();

        $this->name = $name;

        register_shutdown_function([$this, 'shutdown']);
    }

    public function init(array $config)
    {
        $this->config = $config;
        $this->createContainer($config);
        $this->processor = $this->container->get('factory')->createProcessor(true);
        $this->reset();
        $this->save();
    }

    public function serialize()
    {
        $data = $this->toCache();

        return serialize($data);
    }

    public function unserialize($serialized)
    {
        $cache = unserialize($serialized);
        $this->fromCache($cache);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getProcessor()
    {
        return $this->processor;
    }

    public function refresh()
    {
        $adapter = $this->adapter;

        $cached = $adapter->getItem(static::CACHE_KEY)->get();

        if(!is_null($cached)){
            $this->fromCache($cached);
        }

        $this->createContainer($this->config);
    }

    private function createContainer($config)
    {
        $container       = (new ContainerFactory($config))->getContainer();
        $this->container = $container;
    }

    private function toCache()
    {
        $data = [];

        foreach ($this->cachedProperties as $property) {
            $data[$property] = $this->{$property};
        }

        return $data;
    }

    private function fromCache(array $cache)
    {
        foreach ($cache as $name => $value) {
            $this->{$name} = $value;
        }
    }

    public function save()
    {
        $adapter = $this->adapter;
        $item    = $adapter->getItem(static::CACHE_KEY);
        $data    = $this->toCache();

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
        return \count($this->exceptions) > 0;
    }

    public function getExceptions()
    {
        return $this->exceptions;
    }

    public function addException(\Exception $exception)
    {
        $message = $exception->getMessage();
        $id      = md5($message);

        if (!isset($this->exceptions[$id])) {
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
        if (null === $this->testCase) {
            throw new SessionException('Can not start coverage with null TestCase');
        }

        try {
            $container = $this->container;
            $testCase  = $this->testCase;
            $processor = $container->get('factory')->createProcessor();

            $processor->start($testCase);

            $this->currentProcessor = $processor;
            $this->started = true;
        } catch (\Exception $exception) {
            $this->started = false;
            $message = sprintf(
                "Can not start coverage on session %s. Error message:\n%s",
                $this->getName(),
                $exception->getMessage()
            );
            $exception = new  SessionException($message);
            $this->addException($exception);
        }
    }

    public function stop()
    {
        try{
            $this->currentProcessor->stop();
            $this->processor->merge($this->currentProcessor);
            $this->started = false;
        }catch (\Exception $exception){
            $message = sprintf(
                "Can not stop coverage on session <comment>%s</comment>. Error message:\n<error>%s</error>",
                $this->name,
                $exception->getMessage()
            );
            $e = new SessionException($message);
            $this->addException($e);
        }
    }

    public function shutdown()
    {
        if ($this->started) {
            $this->stop();
        }
        $this->save();
    }
}
