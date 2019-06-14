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

class BasePCOV6 implements Driver
{
    /**
     * {@inheritdoc}
     */
    public function start(bool $determineUnusedAndDead = true): void
    {
        \pcov\start();
    }

    /**
     * {@inheritdoc}
     */
    public function stop(): array
    {
        \pcov\stop();
        $waiting = \pcov\waiting();
        $collect = [];
        if ($waiting) {
            $collect = \pcov\collect(\pcov\inclusive, $waiting);
            \pcov\clear();
        }

        return $collect;
    }
}
