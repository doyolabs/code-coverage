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
use Doyo\Bridge\CodeCoverage\Http\ClientInterface;
use Doyo\Bridge\CodeCoverage\Session\SessionInterface;

class RemoteListener extends AbstractSessionListener
{
    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @var array
     */
    private $config;

    /**
     * @var string
     */
    private $coverageUrl;

    /**
     * @var \Exception[]
     */
    private $exceptions;

    public function __construct(
        SessionInterface $session,
        ClientInterface $httpClient,
        string $coverageUrl,
        array $config
    ) {
        parent::__construct($session);

        $this->httpClient  = $httpClient;
        $this->coverageUrl = $coverageUrl;
        $this->config      = $config;
        $this->exceptions  = [];
    }

    public static function getSubscribedEvents()
    {
        return [
            CoverageEvent::refresh  => 'refresh',
            CoverageEvent::complete => 'complete',
        ];
    }

    public function refresh()
    {
        $client      = $this->httpClient;
        $session     = $this->session;
        $body        = json_encode($this->config);
        $coverageUrl = $this->coverageUrl;

        $options = [
            'body'  => $body,
            'query' => [
                'action'  => 'init',
                'session' => $session->getName(),
            ],
        ];

        try {
            $client->request('POST', $coverageUrl, $options);
        } catch (\Exception $exception) {
            $this->exceptions[] = $exception;
        }
    }

    public function complete(CoverageEvent $event)
    {
        $coverageUrl = $this->coverageUrl;
        $client      = $this->httpClient;
        $session     = $this->session;
        $consoleIO   = $event->getConsoleIO();

        $options = [
            'query' => [
                'action'  => 'read',
                'session' => $session->getName(),
            ],
        ];

        try {
            /** @var SessionInterface $remoteSession */
            $response      = $client->request('GET', $coverageUrl, $options);
            $remoteSession = $response->getBody()->getContents();
            $remoteSession = unserialize($remoteSession);
            $event->getProcessor()->merge($remoteSession->getProcessor());
        } catch (\Exception $exception) {
            $this->exceptions[] = $exception;
        }

        $exceptions = array_merge($this->exceptions, $remoteSession->getExceptions());

        $ids = [];
        foreach ($exceptions as $exception) {
            $message = $exception->getMessage();
            $id      = md5($message);
            if (!\in_array($id, $ids, true)) {
                $ids[] = $id;
                $consoleIO->coverageInfo($message);
            }
        }
    }
}
