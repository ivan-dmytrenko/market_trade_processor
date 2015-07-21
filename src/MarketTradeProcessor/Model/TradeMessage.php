<?php

namespace MarketTradeProcessor\Model;

use Illuminate\Database\Eloquent\Model;

class TradeMessage extends Model
{
    protected $table = 'trade_messages';

    public $timestamps = false;

    /**
     * Sanitize data before save a record
     */
    public function sanitize()
    {
        $this->currency_from = strtoupper($this->currency_from);
        $this->currency_to = strtoupper($this->currency_to);
        $this->amount_sell = number_format($this->amount_sell, 2, '.', '');
        $this->amount_buy = number_format($this->amount_buy, 2, '.', '');
        $this->time_placed = \DateTime::createFromFormat('d-M-y H:i:s', $this->time_placed);
        $this->originating_country = strtoupper($this->originating_country);
    }

    public function save()
    {
        $this->sanitize();

        parent::save();
    }
}
