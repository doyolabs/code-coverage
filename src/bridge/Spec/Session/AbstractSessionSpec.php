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

namespace Spec\Doyo\Bridge\CodeCoverage\Session;

use Doyo\Bridge\CodeCoverage\Session\AbstractSession;
use PhpSpec\ObjectBehavior;

class AbstractSessionSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beAnInstanceOf(TestSession::class);
        $this->beConstructedWith('abstract');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(AbstractSession::class);
    }

    public function it_should_init_session()
    {
        $config = [
            'filter' => [
                'whitelist' => [
                    'directory' => __DIR__,
                ],
            ],
        ];

        $this->init($config);

        $this->save();
        $this->refresh();

        $this->getName()->shouldReturn('abstract');
        $this->getConfig()->shouldReturn($config);
    }
}
