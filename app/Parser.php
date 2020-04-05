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
     * @var array
     */
    private $data;
    
    /**
     * Load csv file into an array.
     *
     * @param string $path
     * @param string $delimiter
     * @param string $enclosure
     * @throws \Exception
     */
    public function loadCsvFile($path, $delimiter = ',', $enclosure = '')
    {
        if (!file_exists($path)) {
            throw new \Exception('File not found: ' . $path);
        }
        
        $this->data = array_map('str_getcsv', file($path), [$delimiter, $enclosure]);
    }
    
    /**
     * Get parsed data.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
