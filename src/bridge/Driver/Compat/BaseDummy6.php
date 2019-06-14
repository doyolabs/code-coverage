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

namespace Doyo\Bridge\CodeCoverage\Driver\Compat;

use SebastianBergmann\CodeCoverage\Driver\Driver;

/**
 * Class BaseDummy6.
 *
 * @codeCoverageIgnore
 */
class BaseDummy6 implements Driver
{
    public function start(bool $determineUnusedAndDead = true): void
    {
    }

    public function stop(): array
    {
        return [];
    }
}
