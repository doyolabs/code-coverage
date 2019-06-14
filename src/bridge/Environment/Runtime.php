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

namespace Doyo\Bridge\CodeCoverage\Environment;

use Doyo\Bridge\CodeCoverage\Driver\Dummy;
use Doyo\Bridge\CodeCoverage\Driver\PCOV;
use SebastianBergmann\CodeCoverage\Driver\HHVM;
use SebastianBergmann\CodeCoverage\Driver\PHPDBG;
use SebastianBergmann\CodeCoverage\Driver\Xdebug;
use SebastianBergmann\Environment\Runtime as RuntimeEnvironment;

/**
 * Class Runtime.
 *
 * @method bool isHHVM()
 * @method bool isPHPDBG()
 * @method bool hasXdebug()
 * @method bool hasPHPDBGCodeCoverage()
 * @method bool isPHP()
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

        if (
            version_compare(PHP_VERSION, '7.0', '>')
            && $this->hasPCOV()
        ) {
            //$driverClass = PCOV::class;
        }

        if ($this->hasXdebug()) {
            $driverClass =  Xdebug::class;
        }
        // @codeCoverageIgnoreEnd

        return $driverClass;
    }

    public function hasPCOV()
    {
        return $this->isPHP() && \extension_loaded('pcov') && ini_get('pcov.enabled');
    }

    /**
     * Returns true when Xdebug is supported or
     * the runtime used is PHPDBG.
     */
    public function canCollectCodeCoverage(): bool
    {
        return $this->hasXdebug() || $this->hasPCOV() || $this->hasPHPDBGCodeCoverage();
    }

    public function __call($name, $arguments)
    {
        return \call_user_func_array([$this->runtime, $name], $arguments);
    }
}
