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

use Doyo\Bridge\CodeCoverage\Compiler\CoveragePass;
use Doyo\Bridge\CodeCoverage\Compiler\ReportPass;
use Doyo\Bridge\CodeCoverage\Console\Application;
use Doyo\Bridge\CodeCoverage\DependencyInjection\CodeCoverageExtension;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\Yaml\Yaml;

class ContainerFactory
{
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
        $this->config = $config;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        if (null === $this->container) {
            $this->doCreateContainer();
        }

        return $this->container;
    }

    /**
     * @param bool $useDummyDriver
     *
     * @return ProcessorInterface
     */
    public function createProcessor(bool $useDummyDriver = false): ProcessorInterface
    {
        $coverage = $this->createCodeCoverage($useDummyDriver);

        return new Processor($coverage);
    }

    public function createCodeCoverage(bool $useDummyDriver = false)
    {
        $container   = $this->container;
        $driverClass = $container->getParameter('coverage.driver.class');

        if ($useDummyDriver) {
            $driverClass = $container->getParameter('coverage.driver.dummy.class');
        }

        $driver = new $driverClass();
        $filter = $container->get('coverage.filter');

        return new \SebastianBergmann\CodeCoverage\CodeCoverage($driver, $filter);
    }

    public function createApplication($version = 'dev')
    {
        return new Application('code-coverage', $version);
    }

    private function doCreateContainer()
    {
        $config  = $this->config;
        $configs = [];

        if (isset($config['imports'])) {
            $configs = $this->normalizeConfig($config);
            unset($config['imports']);
        }

        $configs[] = $config;

        $debug = false;
        foreach ($configs as $config) {
            if (isset($config['debug'])) {
                $debug = $config['debug'];
            }
        }

        $id              = md5(serialize($configs));
        $file            = sys_get_temp_dir().'/doyo/coverage/container'.$id.'.php';
        $class           = 'CodeCoverageContainer'.$id;
        $cachedContainer = new ConfigCache($file, $debug);
        $debug           = true;
        if (!$cachedContainer->isFresh() || $debug) {
            //$this->dumpConfig();
            $builder = new ContainerBuilder();

            $builder->registerExtension(new CodeCoverageExtension());
            foreach ($configs as $config) {
                $builder->loadFromExtension('coverage', $config);
            }

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

        /** @var \Symfony\Component\DependencyInjection\ContainerInterface $container */
        $container =  new $class();
        $container->set('factory', $this);

        $this->container = $container;
    }

    private function normalizeConfig($configuration)
    {
        $configs = [];
        foreach ($configuration['imports'] as $file) {
            $configs[] = $this->importFile($file);
        }

        return $configs;
    }

    private function importFile($file)
    {
        return Yaml::parseFile($file);
    }
}
