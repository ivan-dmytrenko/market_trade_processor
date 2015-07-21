<?php

namespace MarketTradeProcessor\MessageConsumption\Service;

use MarketTradeProcessor\Model\CurrencyPair;

class CurrencyPairService
{
    protected $model;
    protected $logger;

    /**
     * @param CurrencyPair $currencyPair
     * @param $logger
     */
    public function __construct(CurrencyPair $currencyPair, $logger)
    {
        $this->model = $currencyPair;
        $this->logger = $logger;
    }

    /**
     * Create a new CurrencyPair entity or update if it's already exist
     *
     * @param array $message
     * @return bool|integer
     */
    public function handle(Array $message)
    {
        $this->model->currency_from = $message['currencyFrom'];
        $this->model->currency_to = $message['currencyTo'];

        if ($this->model->isAlreadyExist()) {
            $currencyPair = $this->model->findByPairsLetters();

            $currencyPair->amount_sell += $message['amountSell'];
            $currencyPair->amount_buy += $message['amountBuy'];

            try {
                $currencyPair->save();
            } catch (\Exception $e) {
                $this->logger->err(sprintf("Error occurred when updating CurrencyPair. %s", $e->getMessage()));
                return false;
            }

            return $currencyPair->id;
        }

        try {
            $this->model->amount_sell = $message['amountSell'];
            $this->model->amount_buy = $message['amountBuy'];
            $this->model->save();
        } catch (\Exception $e) {
            $this->logger->err(sprintf("Error occurred when creating CurrencyPair. %s", $e->getMessage()));
            return false;
        }

        return $this->model->id;
    }
}
