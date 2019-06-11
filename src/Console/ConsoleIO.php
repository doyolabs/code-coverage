<?php


namespace Doyo\Bridge\CodeCoverage\Console;


interface ConsoleIO
{
    public function coverageInfo(string $message);

    /**
     * Display error during coverage
     *
     * @param   string $message
     * @return  void
     */
    public function coverageError(string $message);

    /**
     * Display error during generate report
     *
     * @param string $message
     * @return void
     */
    public function coverageReportError(string $message);
}
