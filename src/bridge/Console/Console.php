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

namespace Doyo\Bridge\CodeCoverage\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\StyleInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Console implements ConsoleIO
{
    /**
     * @var StyleInterface
     */
    private $style;

    /**
     * Console constructor.
     *
     * @todo Change this to only accept style interface
     *
     * @param InputInterface|StyleInterface $style
     * @param OutputInterface|null          $output
     */
    public function __construct($style, OutputInterface $output = null)
    {
        if ($style instanceof InputInterface) {
            $style = new SymfonyStyle($style, $output);
        }
        $this->style = $style;
    }

    public function coverageSection(string $section)
    {
        $this->style->section('coverage: '.$section);
    }

    public function coverageInfo(string $message)
    {
        $this->style->text($message);
    }

    public function coverageError(string $message)
    {
        $this->style->error($message);
    }
}
