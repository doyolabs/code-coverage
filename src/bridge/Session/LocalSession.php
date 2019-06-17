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

class LocalSession extends AbstractSession
{
    public static function startSession($name): bool
    {
        $self = new static($name);
        try {
            $self->start();
            return true;
        } catch (\Exception $exception) {
            $self->addException($exception);
            $self->save();
            return false;
        }
    }
}
