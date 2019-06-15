<?php


namespace Doyo\Bridge\CodeCoverage\Spec\Session;


use Doyo\Bridge\CodeCoverage\ProcessorInterface;
use Doyo\Bridge\CodeCoverage\Session\RemoteSession;

class TestRemoteSession extends RemoteSession
{
    public function setProcessor(ProcessorInterface $processor)
    {
        $this->processor = $processor;
    }
}
