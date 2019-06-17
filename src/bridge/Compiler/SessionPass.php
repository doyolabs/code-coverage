<?php


namespace Doyo\Bridge\CodeCoverage\Compiler;


use Doyo\Bridge\CodeCoverage\Listener\LocalListener;
use Doyo\Bridge\CodeCoverage\Session\LocalSession;
use Doyo\Bridge\CodeCoverage\Session\RemoteSession;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class SessionPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $sessions = $container->getParameterBag()->get('sessions');

        foreach($sessions as $name => $config){
            $driver = $config['driver'];
            if('local' === $driver){
                $this->createLocalSession($container, $name, $config);
            }
        }
    }

    private function createLocalSession(ContainerBuilder $container, string $name, array $config)
    {
        $id = 'sessions.'.$name;
        $sessionId = $id.'.session';
        $listenerId = $id.'.listener';

        $session = new Definition(LocalSession::class);
        $session->addArgument($name);
        $session->addTag('coverage.session');
        $session->addMethodCall('init',[$container->getParameter('config')]);
        $container->setDefinition($sessionId, $session);

        $listener = new Definition(LocalListener::class);
        $listener->addArgument(new Reference($sessionId));
        $container->setDefinition($listenerId, $listener);

        $dispatcher = $container->findDefinition('coverage');
        $dispatcher->addMethodCall('addSubscriber',[new Reference($listenerId)]);
    }
}
