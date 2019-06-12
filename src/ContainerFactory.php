<?php


namespace Doyo\Bridge\CodeCoverage;

use Doyo\Bridge\CodeCoverage\DependencyInjection\CodeCoverageExtension;
use Doyo\Bridge\CodeCoverage\DependencyInjection\DriverPass;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;

class ContainerFactory
{
    private $builder;

    private $configCache;

    private $id;

    private $class;

    private $config;

    /**
     * @var bool
     */
    private $debug;

    public function __construct(array $config = [], bool $debug = false)
    {
        $id = md5(serialize($config));
        $class = 'CodeCoverageContainer'.$id;

        $this->id = $id;
        $this->class = $class;
        $this->config = $config;
        $this->debug = $debug;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        $id = $this->id;
        $class = $this->class;
        $file = sys_get_temp_dir().'/doyo/coverage/'.$id.'.php';
        $config = ['coverage' => $this->config];

        $cachedContainer = new ConfigCache($file, $this->debug);
        if(!$cachedContainer->isFresh() || $this->debug){
            //$this->dumpConfig();
            $builder = new ContainerBuilder();
            $builder->registerExtension(new CodeCoverageExtension());
            $builder->addCompilerPass(new DriverPass());

            $builder->loadFromExtension('coverage',$config);
            $builder->compile();

            $dumper = new PhpDumper($builder);
            $cachedContainer->write(
                $dumper->dump([
                    'class' => $class
                ]),
                $builder->getResources()
            );
        }

        require_once $file;
        return new $class();
    }
}