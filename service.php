<?php

require __DIR__.'/vendor/autoload.php';


try {
    if (!isset($argv[1])) {
        exit('Enter path to input file!' . PHP_EOL);
    }
    
    $filePath = $argv[1];
    $parser = new \App\Parser();
    $parser->loadCsvFile($filePath);
    
    $mConfigurationProvider = new \App\Configuration\MoneyConfigurationProvider();
    $validator = new \App\Validation\InputValidator($mConfigurationProvider->getSupportedCurrencies());
    $calculator = new \App\Calculator($parser->getData(), $validator);

    $fees = $calculator->getFees();

    $calculator->outputResult($fees);
} catch (\Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}
