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

namespace Doyo\Bridge\CodeCoverage\Listener;

use Doyo\Bridge\CodeCoverage\Session\SessionInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

abstract class AbstractSessionListener implements EventSubscriberInterface
{
    protected $session;

    public function __construct(
        SessionInterface $session
    ) {
        $this->session = $session;
    }
}
