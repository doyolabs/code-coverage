<?php


namespace Doyo\Bridge\CodeCoverage\Listener;


use Doyo\Bridge\CodeCoverage\Session\SessionInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

abstract class AbstractSessionListener implements EventSubscriberInterface
{
    protected $session;

    public function __construct(
        SessionInterface $session
    )
    {
        $this->session = $session;
    }

}
