<?php

namespace MarketTradeProcessor\MessageConsumption\Service;

use \Mockery as m;

class TradeMessageServiceTest extends \PHPUnit_Framework_TestCase
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

    public function testHandleCreate()
    {
        $model = m::mock('MarketTradeProcessor\Model\TradeMessage');
        $model->shouldReceive('getAttribute');
        $model->shouldReceive('setAttribute');
        $model->shouldReceive('save')->once();

        $service = new TradeMessageService($model, $this->logger);
        $result = $service->handle($this->message);

        $this->assertTrue($result);
    }

    public function testHandleCreateBreakDown()
    {
        $model = m::mock('MarketTradeProcessor\Model\TradeMessage');
        $model->shouldReceive('getAttribute');
        $model->shouldReceive('setAttribute');
        $model->shouldReceive('save')->once()->andThrow(new m\Exception());

        $service = new TradeMessageService($model, $this->logger);
        $this->logger->shouldReceive('err')->once();
        $result = $service->handle($this->message);

        $this->assertFalse($result);
    }

    public function tearDown()
    {
        m::close();
    }
}