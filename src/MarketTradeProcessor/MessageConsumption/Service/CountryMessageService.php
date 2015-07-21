<?php

namespace MarketTradeProcessor\MessageConsumption\Service;

use MarketTradeProcessor\Model\CountryMessage;

class CountryMessageService
{
    protected $model;
    protected $logger;

    /**
     * @param CountryMessage $countryMessage
     * @param $logger
     */
    public function __construct(CountryMessage $countryMessage, $logger)
    {
        $this->model = $countryMessage;
        $this->logger = $logger;
    }

    /**
     * Create a new CountryMessage entity or update if it's already exist
     *
     * @param array $message
     * @param $currencyPairId
     * @return bool
     */
    public function handle(Array $message, $currencyPairId)
    {
        $this->model->country = $message['originatingCountry'];
        $this->model->currency_pair_id = $currencyPairId;

        if ($this->model->isRelatedCountryMessageExist()) {
            $countryMessage = $this->model->findByCountryAndPairId();
            $countryMessage->incrementMessage();

            try {
                $countryMessage->save();
            } catch (\Exception $e) {
                $this->logger->err(sprintf("Error occurred when updating CountryMessage. %s", $e->getMessage()));
                return false;
            }

            return true;
        }

        $this->model->messages_count = 1;

        try {
            $this->model->save();
        } catch (\Exception $e) {
            $this->logger->err(sprintf("Error occurred when creating CountryMessage. %s", $e->getMessage()));
            return false;
        }

        return true;
    }
}
