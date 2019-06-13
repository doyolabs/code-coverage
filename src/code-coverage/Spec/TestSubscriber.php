<?php

/*
 * This file is part of the doyo/code-coverage project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spec\Doyo\Bridge\CodeCoverage;

use Doyo\Bridge\CodeCoverage\Event\CoverageEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TestSubscriber implements EventSubscriberInterface
{
    /**
     * @var CoverageEvent
     */
    private $coverageEvent;

    final public static function getSubscribedEvents()
    {
        return [
            CoverageEvent::refresh     => 'refresh',
            CoverageEvent::beforeStart => 'beforeStart',
            CoverageEvent::start       => 'start',
            CoverageEvent::stop        => 'stop',
            CoverageEvent::complete    => 'complete',
        ];
    }

    public function beforeStart(CoverageEvent $event)
    {
    }

    public function refresh(CoverageEvent $event)
    {
        $this->coverageEvent = $event;
    }

    public function start(CoverageEvent $event)
    {
        $this->coverageEvent = $event;
    }

    public function stop(CoverageEvent $event)
    {
        $this->coverageEvent = $event;
    }

    /**
     * @return CoverageEvent
     */
    public function getCoverageEvent(): CoverageEvent
    {
        return $this->coverageEvent;
    }

    public function complete()
    {
    }
}
