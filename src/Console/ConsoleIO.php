<?php


namespace Doyo\Bridge\CodeCoverage\Console;


interface ConsoleIO
{
    /**
     * Display error during coverage
     *
     * @param   string $message
     * @return  void
     */
    public function coverageError($message);
}
