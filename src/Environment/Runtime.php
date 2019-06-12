<?php


namespace Doyo\Bridge\CodeCoverage\Environment;

use Doyo\Bridge\CodeCoverage\Driver\Dummy;
use SebastianBergmann\CodeCoverage\Driver\HHVM;
use SebastianBergmann\CodeCoverage\Driver\PHPDBG;
use SebastianBergmann\CodeCoverage\Driver\Xdebug;
use SebastianBergmann\Environment\Runtime as RuntimeEnvironment;

/**
 * Class Runtime
 *
 * @method bool isHHVM()
 * @method bool isPHPDBG()
 * @method bool hasXdebug()
 * @method bool hasPHPDBGCodeCoverage()
 */
final class Runtime implements RuntimeInterface
{
    private $runtime;

    public function __construct()
    {
        $this->runtime = new RuntimeEnvironment();
    }

    public function getDriverClass(): string
    {
        $driverClass = Dummy::class;

        // @codeCoverageIgnoreStart
        if ($this->isHHVM()) {
            $driverClass = HHVM::class;
        }

        if ($this->isPHPDBG()) {
            $driverClass = PHPDBG::class;
        }

        if ($this->hasXdebug()){
            $driverClass =  Xdebug::class;
        }
        // @codeCoverageIgnoreEnd

        return $driverClass;
    }

    /**
     * Returns true when Xdebug is supported or
     * the runtime used is PHPDBG.
     */
    public function canCollectCodeCoverage(): bool
    {
        return $this->runtime->canCollectCodeCoverage();
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->runtime, $name], $arguments);
    }
}
