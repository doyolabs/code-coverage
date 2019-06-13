<?php

/*
 * This file is part of the doyo/code-coverage project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Doyo\Bridge\CodeCoverage\Driver;

use SebastianBergmann\CodeCoverage\Version;

if (version_compare(Version::id(), '6.0', '<')) {
    /** @codeCoverageIgnoreStart */
    class BaseDummy extends \Doyo\Bridge\CodeCoverage\Driver\Compat\BaseDummy5
    {
    }
    // @codeCoverageIgnoreEnd
} else {
    /** @codeCoverageIgnoreStart */
    class BaseDummy extends \Doyo\Bridge\CodeCoverage\Driver\Compat\BaseDummy6
    {
    }
    // @codeCoverageIgnoreEnd
}

/**
 * A dumb driver to be used during testing.
 */
class Dummy extends BaseDummy
{
}
