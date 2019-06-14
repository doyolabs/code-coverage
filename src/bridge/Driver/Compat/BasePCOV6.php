<?php


namespace Doyo\Bridge\CodeCoverage\Driver\Compat;


use SebastianBergmann\CodeCoverage\Driver\Driver;

class BasePCOV6 implements Driver
{
    /**
     * @inheritDoc
     */
    public function start(bool $determineUnusedAndDead = true): void
    {
        \pcov\start();
    }

    /**
     * @inheritDoc
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
