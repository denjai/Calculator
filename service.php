<?php

require __DIR__.'/vendor/autoload.php';


try {
    if (!isset($argv[1])) {
        exit('Enter path to input file!' . PHP_EOL);
    }
    
    $filePath = $argv[1];
    $parser = new \App\Parser();
    $parser->loadCsvFile($filePath);
        
    $calculator = new \App\Calculator($parser->getData());

    $fees = $calculator->getFees();

    $calculator->printResult($fees);
} catch (\Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}
