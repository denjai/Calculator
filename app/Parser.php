<?php

namespace App;

/**
 * Parse data to array.
 *
 * @author Doncho Toromanov
 */
class Parser
{
    /**
     * Load csv file into an array.
     *
     * @param string $path
     * @param string $delimiter
     * @param string $enclosure
     * 
     * @return array
     * 
     * @throws \Exception
     */
    public function loadCsvFile($path, $delimiter = ',', $enclosure = '')
    {
        if (!file_exists($path)) {
            throw new \InvalidArgumentException('File path not found: ' . $path);
        }
        
        return array_map('str_getcsv', file($path), [$delimiter, $enclosure]);
    }
}
