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

namespace Doyo\Bridge\CodeCoverage\Session;

use Doyo\Bridge\CodeCoverage\TestCase;

class RemoteSession extends AbstractSession
{
    const HEADER_SESSION_KEY   = 'HTTP_DOYO_COVERAGE_SESSION';
    const HEADER_TEST_CASE_KEY = 'HTTP_DOYO_COVERAGE_TESTCASE';

    public static function startSession()
    {
        if (!isset($_SERVER[static::HEADER_SESSION_KEY])) {
            return false;
        }

        $name    = $_SERVER[static::HEADER_SESSION_KEY];
        $session = new static($name);

        if (isset($_SERVER[static::HEADER_TEST_CASE_KEY])) {
            $session->doStartSession();
        } else {
            return false;
        }
        $session->save();

        return true;
    }

    public function doStartSession()
    {
        $name     = $_SERVER[static::HEADER_TEST_CASE_KEY];
        $testCase = new TestCase($name);
        $this->setTestCase($testCase);

        try {
            $this->start();
            register_shutdown_function([$this, 'shutdown']);
        } catch (\Exception $e) {
            $this->reset();
            $this->exceptions[] = $e;
        }
    }
}
