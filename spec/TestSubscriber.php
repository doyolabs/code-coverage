<?php


namespace spec\Doyo\Bridge\CodeCoverage;


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
            CoverageEvent::refresh => 'refresh',
            CoverageEvent::beforeStart => 'beforeStart',
            CoverageEvent::start => 'start',
            CoverageEvent::stop => 'stop',
            CoverageEvent::complete => 'complete'
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
