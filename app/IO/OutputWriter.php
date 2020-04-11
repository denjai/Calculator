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
    public function writeToStandartOutput(array $data)
    {
        foreach ($data as $row) {
            $this->writeLineToStandartOutput($row);
        }
    }
    
    /**
     * Outputs single line to stdout.
     *
     * @param string $text
     */
    public function writeLineToStandartOutput(string $text)
    {
        fwrite(STDOUT, $text . PHP_EOL);
    }
}
