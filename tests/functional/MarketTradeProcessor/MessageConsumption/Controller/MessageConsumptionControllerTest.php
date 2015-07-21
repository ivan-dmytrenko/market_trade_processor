<?php

use Silex\WebTestCase;

class MessageConsumptionControllerTest extends WebTestCase
{
    public function createApplication()
    {
        $app = require __DIR__.'/../../../../app.php';
        $app['debug'] = true;
        unset($app['exception_handler']);

        return $app;
    }

    public function testPostMessage()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/messages',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"userId": "134256", "currencyFrom": "uah", "currencyTo": "usd", "amountSell": 1000, '.
            '"amountBuy": 747.10, "rate": 0.7471, "timePlaced" : "31-JAN-15 10:27:44", "originatingCountry" : "UA"}'
        );

        $this->assertTrue($client->getResponse()->isOk());
    }
}
