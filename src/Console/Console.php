<?php


namespace Doyo\Bridge\CodeCoverage\Console;


use Symfony\Component\Console\Style\SymfonyStyle;

class Console extends SymfonyStyle implements ConsoleIO
{
    public function coverageInfo(string $message)
    {
        parent::writeln($message);
    }

    public function coverageError(string $message)
    {
        parent::error($message);
    }

    public function coverageReportError(string $message)
    {
        parent::error($message);
    }
}
