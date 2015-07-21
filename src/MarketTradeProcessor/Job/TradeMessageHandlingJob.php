<?php
namespace MarketTradeProcessor\Job;

use Grom\SilexResque\BaseJob;
use Silex\Application;

class TradeMessageHandlingJob extends BaseJob
{
    /**
     * Execute job
     *
     * @param array $args
     * @param Application $app
     */
    protected function execute(array $args, Application $app)
    {
        $currencyPairId = $app['currency_pair_service']->handle($args['message']);
        $app['country_message_service']->handle($args['message'], $currencyPairId);
        $app['trade_message_service']->handle($args['message']);
    }
}
