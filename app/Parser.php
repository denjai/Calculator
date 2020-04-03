<?php

namespace App;

/**
 * Description of Parser
 *
 * @author Doncho Toromanov
 */
class Parser
{
    private $data;
    
    public function loadCsvFile($path, $delimiter = ',', $enclosure = '')
    {
        if (!file_exists($path)) {
            throw new \Exception('File not found: ' . $path);
        }
        
        $this->data = array_map('str_getcsv', file($path), [$delimiter, $enclosure]);
    }
    
    public function getData()
    {
        return $this->data;
    }
}
