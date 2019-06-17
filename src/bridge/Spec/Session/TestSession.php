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

namespace Spec\Doyo\Bridge\CodeCoverage\Session;

use Doyo\Bridge\CodeCoverage\ProcessorInterface;
use Doyo\Bridge\CodeCoverage\Session\AbstractSession;

class TestSession extends AbstractSession
{
    public function setProcessor(
        ProcessorInterface $processor
    )
    {
        $this->processor = $processor;
    }

    public function getConfig()
    {
        return $this->config;
    }
}
