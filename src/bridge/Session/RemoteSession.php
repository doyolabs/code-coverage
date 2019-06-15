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

        if(!isset($_SERVER[static::HEADER_TEST_CASE_KEY])){
            return false;
        }

        $sessionName = $_SERVER[static::HEADER_SESSION_KEY];
        $session = new static($sessionName);
        $testCaseName = $_SERVER[static::HEADER_TEST_CASE_KEY];
        $testCase = new TestCase($testCaseName);

        try{
            $session->setTestCase($testCase);
            $session->start();
            $session->save();
            return true;
        }catch (\Exception $e){
            $session->addException($e);
            $session->save();
            return false;
        }
    }
}
