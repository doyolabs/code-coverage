<?php

namespace Spec\Doyo\Bridge\CodeCoverage\Listener;

use Doyo\Bridge\CodeCoverage\Console\ConsoleIO;
use Doyo\Bridge\CodeCoverage\Driver\Dummy;
use Doyo\Bridge\CodeCoverage\Event\CoverageEvent;
use Doyo\Bridge\CodeCoverage\Http\ClientInterface;
use Doyo\Bridge\CodeCoverage\Listener\RemoteListener;
use Doyo\Bridge\CodeCoverage\Processor;
use Doyo\Bridge\CodeCoverage\ProcessorInterface;
use Doyo\Bridge\CodeCoverage\Session\RemoteSession;
use Doyo\Bridge\CodeCoverage\Session\Session;
use Doyo\Bridge\CodeCoverage\Session\SessionInterface;
use Doyo\Bridge\CodeCoverage\TestCase;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use SebastianBergmann\CodeCoverage\CodeCoverage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RemoteListenerSpec extends ObjectBehavior
{
    function let(
        SessionInterface $session,
        CoverageEvent $event,
        ProcessorInterface $processor,
        ConsoleIO $consoleIO,
        TestCase $testCase,
        ClientInterface $client
    )
    {
        $config = [
            'some'=>'config',
        ];

        $this->beConstructedWith($session, $client,'url', $config);
        $event->getProcessor()->willReturn($processor);
        $event->getConsoleIO()->willReturn($consoleIO);
        $processor->getCurrentTestCase()->willReturn($testCase);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RemoteListener::class);
    }

    function it_should_subscribe_to_coverage_event()
    {
        $this->shouldImplement(EventSubscriberInterface::class);

        $this::getSubscribedEvents()->shouldHaveKey(CoverageEvent::refresh);
        $this::getSubscribedEvents()->shouldHaveKey(CoverageEvent::complete);
    }

    function its_refresh_should_initialize_remote_request(
        ClientInterface $client,
        ResponseInterface $response
    )
    {

        $client
            ->request('POST','url', Argument::any())
            ->willReturn($response)
            ->shouldBeCalledOnce()
        ;
        $this->refresh();
    }

    function its_refresh_should_handle_http_client_error(
        ClientInterface $client
    )
    {

        $e = new \Exception('some error');
        $client
            ->request('POST','url', Argument::any())
            ->willThrow($e)
            ->shouldBeCalledOnce()
        ;
        $this->refresh();
    }

    function its_complete_should_merge_coverage_data(
        ProcessorInterface $processor,
        CoverageEvent $event,
        ClientInterface $client,
        ResponseInterface $response,
        StreamInterface $body,
        ConsoleIO $consoleIO
    )
    {
        $session = new RemoteSession('spec-remote');
        $session->init([]);
        $sessionProcessor = $session->getProcessor();
        $data = serialize($session);

        $client->request('POST', Argument::cetera())->willReturn($response);
        $client->request('GET','url', Argument::any())
            ->shouldBeCalled()
            ->willReturn($response);
        $response->getBody()
            ->shouldBeCalled()
            ->willReturn($body);
        $body->getContents()->willReturn($data);

        $e = new \Exception('some error');
        $processor
            ->merge($sessionProcessor)
            ->shouldBeCalled()
            ->willThrow($e)
        ;

        $consoleIO->coverageInfo('some error')->shouldBeCalledOnce();

        $this->refresh();
        $this->complete($event);
    }
}
