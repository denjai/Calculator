<?php

namespace App\IO;

/**
 * OutputWriter
 *
 * @author Doncho Toromanov
 */
class OutputWriter
{
    
    /**
     * Outputs result to stdout.
     *
     * @param array $data
     */
    public function writeToStandardOutput(array $data)
    {
        foreach ($data as $row) {
            $this->writeLineToStandardOutput($row);
        }
    }
    
    /**
     * Outputs single line to stdout.
     *
     * @param string $text
     */
    public function writeLineToStandardOutput(string $text)
    {
        fwrite(STDOUT, $text . PHP_EOL);
    }
}
