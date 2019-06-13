<?php


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
        $dummy = $container->getParameter(new Dummy());

        $processor = new Processor($dummy, $filter);
        $session->setProcessor($processor);
        $session->setContainer($container);
        $session->save();
    }
}
