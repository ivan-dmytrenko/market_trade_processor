<?php

namespace MarketTradeProcessor\MessageConsumption\Service;

use MarketTradeProcessor\Model\TradeMessage;

class TradeMessageService
{
    protected $model;
    protected $logger;

    /**
     * @param TradeMessage $tradeMessage
     * @param $logger
     */
    public function __construct(TradeMessage $tradeMessage, $logger)
    {
        $this->model = $tradeMessage;
        $this->logger = $logger;
    }

    /**
     * Create a new TradeMessage entity
     *
     * @param array $message
     * @return bool
     */
    public function handle(Array $message)
    {
        $this->model->user_id = $message['userId'];
        $this->model->currency_from = $message['currencyFrom'];
        $this->model->currency_to = $message['currencyTo'];
        $this->model->amount_sell = $message['amountSell'];
        $this->model->amount_buy = $message['amountBuy'];
        $this->model->rate = $message['rate'];
        $this->model->time_placed = $message['timePlaced'];
        $this->model->originating_country = $message['originatingCountry'];

        try {
            $this->model->save();
        } catch (\Exception $e) {
            $this->logger->err(sprintf("Error occurred when creating TradeMessage. %s", $e->getMessage()));
            return false;
        }

        return true;
    }
}
