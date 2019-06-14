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

namespace Doyo\Bridge\CodeCoverage;

use Doyo\Bridge\CodeCoverage\Compiler\CoveragePass;
use Doyo\Bridge\CodeCoverage\Compiler\ReportPass;
use Doyo\Bridge\CodeCoverage\DependencyInjection\CodeCoverageExtension;
use Doyo\Bridge\CodeCoverage\Driver\Dummy;
use Doyo\Bridge\CodeCoverage\Exception\SessionException;
use Doyo\Bridge\CodeCoverage\Session\LocalSession;
use Doyo\Bridge\CodeCoverage\Session\RemoteSession;
use Doyo\Bridge\CodeCoverage\Session\SessionInterface;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;

class ContainerFactory
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $class;

    /**
     * @var array
     */
    private $config;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(array $config = [])
    {
        $id    = md5(serialize($config));
        $class = 'CodeCoverageContainer'.$id;

        $this->id     = $id;
        $this->class  = $class;
        $this->config = $config;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        if(is_null($this->container)){
            $this->doCreateContainer();
        }

        return $this->container;
    }

    /**
     * @param bool $useDummyDriver
     * @return ProcessorInterface
     */
    public function createProcessor(bool $useDummyDriver = false): ProcessorInterface
    {
        $container = $this->container;
        $driverClass = Dummy::class;
        if(!$useDummyDriver){
            $driverClass = $container->getParameter('coverage.driver.class');
        }

        $filter = $container->get('coverage.filter');
        $driver = new $driverClass;
        $processor = new Processor($driver, $filter);

        return $processor;
    }

    /**
     * @param   string $type
     * @param   string $name
     * @return  SessionInterface
     * @throws SessionException
     */
    public function createSession($type, $name): SessionInterface
    {
        $container = $this->container;

        $map = [
            'local' => LocalSession::class,
            'remote' => RemoteSession::class
        ];

        if(!isset($map[$type])){
            throw new SessionException('Unknown session type: '.$type, ' for: '.$name);
        }

        $codeCoverage = $this->createCodeCoverage();
        $patchXdebug = $container->getParameter('coverage.patch_xdebug');
        $filter = $container->get('coverage.filter');
        $processor = new Processor(new Dummy(), $filter);
        $class = $map[$type];

        /* @var SessionInterface $session */
        $session = new $class($name, $codeCoverage, $patchXdebug);
        $session->setProcessor($processor);

        return $session;
    }

    public function createCodeCoverage()
    {
        $container = $this->container;
        $driverClass = $container->getParameter('coverage.driver.class');
        $driver = new $driverClass;
        $filter = $container->get('coverage.filter');
        $coverage = new \SebastianBergmann\CodeCoverage\CodeCoverage($driver, $filter);

        return $coverage;
    }

    private function doCreateContainer()
    {
        $id    = $this->id;
        $class = $this->class;
        $file  = sys_get_temp_dir().'/doyo/coverage/'.$id.'.php';
        //$config = ['config' => $this->config];
        $config = $this->config;
        $debug = isset($config['debug']) ? $config['debug']:false;

        $cachedContainer = new ConfigCache($file, $debug);
        if (!$cachedContainer->isFresh() || $debug) {
            //$this->dumpConfig();
            $builder = new ContainerBuilder();

            $builder->registerExtension(new CodeCoverageExtension());
            $builder->loadFromExtension('coverage', $config);

            $builder->addCompilerPass(new CoveragePass());
            $builder->addCompilerPass(new ReportPass());
            $builder->compile(true);

            $dumper = new PhpDumper($builder);
            $cachedContainer->write(
                $dumper->dump([
                    'class' => $class,
                ]),
                $builder->getResources()
            );
        }

        require_once $file;

        /* @var \Symfony\Component\DependencyInjection\ContainerInterface $container */
        $container =  new $class();
        $container->set('factory', $this);

        $this->container = $container;
    }
}
