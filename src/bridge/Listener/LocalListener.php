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

use Doyo\Bridge\CodeCoverage\Event\CoverageEvent;

class LocalListener extends AbstractSessionListener
{
    public static function getSubscribedEvents()
    {
        return [
            CoverageEvent::refresh  => 'refresh',
            CoverageEvent::start    => 'start',
            CoverageEvent::complete => 'complete',
        ];
    }

    public function refresh()
    {
        $this->session->reset();
    }

    public function start(CoverageEvent $event)
    {
        $session  = $this->session;
        $testCase = $event->getProcessor()->getCurrentTestCase();

        $session->setTestCase($testCase);
        $session->save();
    }

    public function complete(CoverageEvent $event)
    {
        $session   = $this->session;
        $processor = $event->getProcessor();
        $consoleIO = $event->getConsoleIO();

        // need to refresh session first
        $session->refresh();

        $processor->merge($session->getProcessor());

        if ($session->hasExceptions()) {
            foreach ($session->getExceptions() as $exception) {
                $consoleIO->coverageInfo($exception->getMessage());
            }
        }
    }
}
