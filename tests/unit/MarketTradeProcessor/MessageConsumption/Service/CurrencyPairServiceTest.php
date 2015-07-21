<?php

namespace MarketTradeProcessor\MessageConsumption\Service;

use \Mockery as m;

class CurrencyPairServiceTest extends \PHPUnit_Framework_TestCase
{

    public $message;
    public $logger;

    public function __construct()
    {
        $this->message = array(
            "userId" => 1,
            "currencyFrom" => 1,
            "currencyTo" => 1,
            "amountSell" => 1,
            "amountBuy" => 1,
            "rate" => 1,
            "timePlaced" => 1,
            "originatingCountry" => 1
        );

        $this->logger = m::mock('Monolog\Logger');

        parent::__construct();
    }

    public function testHandleWithNotExist()
    {
        $modelId = 1;

        $model = m::mock('MarketTradeProcessor\Model\CurrencyPair');
        $model->shouldReceive('setAttribute');
        $model->shouldReceive('isAlreadyExist')->once()->andReturn(false);
        $model->shouldReceive('save')->once();
        $model->shouldReceive('getAttribute')->with('id')->andReturn($modelId);

        $service = new CurrencyPairService($model, $this->logger);
        $resultId = $service->handle($this->message);

        $this->assertEquals($modelId, $resultId);
    }

    public function testHandleWithNotExistBreakDown()
    {
        $model = m::mock('MarketTradeProcessor\Model\CurrencyPair');
        $model->shouldReceive('setAttribute');
        $model->shouldReceive('isAlreadyExist')->once()->andReturn(false);
        $model->shouldReceive('save')->once()->andThrow(new m\Exception());

        $service = new CurrencyPairService($model, $this->logger);
        $this->logger->shouldReceive('err')->once();
        $result = $service->handle($this->message);

        $this->assertFalse($result);
    }

    public function testHandleWithAlreadyExist()
    {
        $modelId = 1;

        $currencyPair = m::mock('MarketTradeProcessor\Model\CurrencyPair');
        $currencyPair->shouldReceive('getAttribute')->andReturn($modelId);
        $currencyPair->shouldReceive('setAttribute');
        $currencyPair->shouldReceive('save');

        $model = m::mock('MarketTradeProcessor\Model\CurrencyPair');
        $model->shouldReceive('setAttribute');
        $model->shouldReceive('isAlreadyExist')->once()->andReturn(true);
        $model->shouldReceive('findByPairsLetters')->once()->andReturn($currencyPair);

        $service = new CurrencyPairService($model, $this->logger);
        $resultId = $service->handle($this->message);

        $this->assertEquals($modelId, $resultId);
    }

    public function testHandleWithAlreadyExistBreakDown()
    {
        $modelId = 1;

        $currencyPair = m::mock('MarketTradeProcessor\Model\CurrencyPair');
        $currencyPair->shouldReceive('getAttribute')->andReturn($modelId);
        $currencyPair->shouldReceive('setAttribute');
        $currencyPair->shouldReceive('save')->andThrow(new m\Exception());

        $model = m::mock('MarketTradeProcessor\Model\CurrencyPair');
        $model->shouldReceive('setAttribute');
        $model->shouldReceive('isAlreadyExist')->once()->andReturn(true);
        $model->shouldReceive('findByPairsLetters')->once()->andReturn($currencyPair);

        $service = new CurrencyPairService($model, $this->logger);
        $this->logger->shouldReceive('err')->once();
        $result = $service->handle($this->message);

        $this->assertFalse($result);
    }

    public function tearDown()
    {
        m::close();
    }
}