<?php

namespace MarketTradeProcessor\MessageConsumption\Service;

use \Mockery as m;

class CountryMessageServiceTest extends \PHPUnit_Framework_TestCase
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
        $model = m::mock('MarketTradeProcessor\Model\CountryMessage');
        $model->shouldReceive('setAttribute');
        $model->shouldReceive('isRelatedCountryMessageExist')->once()->andReturn(false);
        $model->shouldReceive('save')->once();

        $service = new CountryMessageService($model, $this->logger);
        $result = $service->handle($this->message, 1);

        $this->assertTrue($result);
    }

    public function testHandleWithNotExistFailsBreakDown()
    {
        $model = m::mock('MarketTradeProcessor\Model\CountryMessage');
        $model->shouldReceive('setAttribute');
        $model->shouldReceive('isRelatedCountryMessageExist')->once()->andReturn(false);
        $model->shouldReceive('save')->once()->andThrow(new m\Exception());

        $service = new CountryMessageService($model, $this->logger);
        $this->logger->shouldReceive('err')->once();
        $result = $service->handle($this->message, 1);

        $this->assertFalse($result);
    }

    public function testHandleWithAlreadyExist()
    {
        $countryMessage = m::mock('MarketTradeProcessor\Model\CountryMessage');
        $countryMessage->shouldReceive('setAttribute');
        $countryMessage->shouldReceive('incrementMessage');
        $countryMessage->shouldReceive('save');

        $model = m::mock('MarketTradeProcessor\Model\CountryMessage');
        $model->shouldReceive('setAttribute');
        $model->shouldReceive('isRelatedCountryMessageExist')->once()->andReturn(true);
        $model->shouldReceive('findByCountryAndPairId')->once()->andReturn($countryMessage);

        $service = new CountryMessageService($model, $this->logger);
        $result = $service->handle($this->message, 1);

        $this->assertTrue($result);
    }

    public function testHandleWithAlreadyExistBreakDown()
    {
        $countryMessage = m::mock('MarketTradeProcessor\Model\CountryMessage');
        $countryMessage->shouldReceive('setAttribute');
        $countryMessage->shouldReceive('incrementMessage');
        $countryMessage->shouldReceive('save')->andThrow(new m\Exception());

        $model = m::mock('MarketTradeProcessor\Model\CountryMessage');
        $model->shouldReceive('setAttribute');
        $model->shouldReceive('isRelatedCountryMessageExist')->once()->andReturn(true);
        $model->shouldReceive('findByCountryAndPairId')->once()->andReturn($countryMessage);

        $service = new CountryMessageService($model, $this->logger);
        $this->logger->shouldReceive('err')->once();
        $result = $service->handle($this->message, 1);

        $this->assertFalse($result);
    }

    public function tearDown()
    {
        m::close();
    }
}
