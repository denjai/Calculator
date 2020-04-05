<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Parser;
/**
 * Test Parser class.
 *
 * @author Doncho Toromanov
 */
class ParserTest extends TestCase
{
    private $parser;
    
    public function setUp() {
        $this->parser = new Parser();
    }
    
    /**
     * 
     * @param string $filePath
     * @param array $expected
     * 
     * @dataProvider dataProviderLoadCsvFile
     */
    public function testLoadCsvFile($filePath, $expected)
    { 
        $this->parser->loadCsvFile($filePath);
        
        $this->assertSame($expected, $this->parser->getData());
    }
    
    public function testCanNotLoadFromIvalidFilePath()
    { 
        $this->expectException(\InvalidArgumentException::class);
        
        $this->parser->loadCsvFile('./invalidpath/nofile.csv');
    }
    
    public function dataProviderLoadCsvFile()
    {
        return [
            [
                'input.csv' , 
                [
                    ['2014-12-31', '4', 'natural', 'cash_out', '1200.00', 'EUR'],
                    ['2015-01-01', '4', 'natural', 'cash_out', '1000.00', 'EUR'],
                    ['2016-01-05', '4', 'natural', 'cash_out', '1000.00' , 'EUR'],
                    ['2016-01-05', '1', 'natural', 'cash_in', '200.00', 'EUR'],
                    ['2016-01-06', '2', 'legal', 'cash_out', '300.00', 'EUR'],
                    ['2016-01-06', '1', 'natural', 'cash_out', '30000', 'JPY'],
                    ['2016-01-07', '1', 'natural', 'cash_out', '1000.00', 'EUR'],
                    ['2016-01-07', '1', 'natural', 'cash_out', '100.00', 'USD'],
                    ['2016-01-10', '1', 'natural', 'cash_out', '100.00', 'EUR'],
                    ['2016-01-10', '2', 'legal', 'cash_in', '1000000.00', 'EUR'],
                    ['2016-01-10', '3', 'natural', 'cash_out', '1000.00', 'EUR'],
                    ['2016-02-15', '1', 'natural', 'cash_out', '300.00', 'EUR'],
                    ['2016-02-19', '5', 'natural', 'cash_out', '3000000', 'JPY']
                ]
            ]
        ];
    }
}
