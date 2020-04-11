<?php

require __DIR__.'/vendor/autoload.php';


try {
    if (!isset($argv[1])) {
        exit('Enter path to input file!' . PHP_EOL);
    }
    
    $filePath = $argv[1];
    $parser = new \App\Parser();
    $operations = $parser->loadCsvFile($filePath);
    
    $currencyPrecisions = ['EUR' => 2, 'USD' => 2, 'JPY' => 0];
    $supportedCurrencies = ['EUR', 'USD', 'JPY'];
    $conversionRates = ['EUR:USD' => '1.1497', 'EUR:JPY' => '129.53'];
    $mConfigurationProvider = new \App\Configuration\MoneyConfigurationProvider($currencyPrecisions, $supportedCurrencies, $conversionRates);
    
    $validator = new \App\Validation\InputValidator($mConfigurationProvider->getSupportedCurrencies());
    $moneyCalculator = new \App\MoneyCalculator($mConfigurationProvider);
    
    $configurationProvider = new \App\Configuration\FeeConfigurationProvider('0.3', '0.03', ['5', 'EUR'], ['0.5', 'EUR'], ['1000', 'EUR'], 3);
    $personRepo = new \App\Repositories\PersonRepository();
    $personFactory = new App\Factories\PersonFactory();
    
    $calculator = new \App\Calculator($validator, $configurationProvider, $moneyCalculator, $personRepo, $personFactory);

    //calculate fees
    $fees = $calculator->calculateFees($operations);

    $writer = new \App\IO\OutputWriter();
    $writer->writeToStandardOutput($fees);
} catch (\Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}
