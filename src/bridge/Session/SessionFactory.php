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

use Doyo\Bridge\CodeCoverage\Driver\Dummy;
use Doyo\Bridge\CodeCoverage\Processor;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SessionFactory
{
    public function createLocalSession(ContainerInterface $container, $name)
    {
        $session = new LocalSession($name);
        $this->decorateSession($container, $session);
    }

    protected function decorateSession(ContainerInterface $container, SessionInterface $session)
    {
        $filter = $container->get('coverage.filter');
        $dummy  = $container->getParameter(new Dummy());

        $processor = new Processor($dummy, $filter);
        $session->setProcessor($processor);
        $session->setContainer($container);
        $session->save();
    }
}
