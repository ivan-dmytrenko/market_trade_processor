<?php

namespace MarketTradeProcessor\Model;

use Illuminate\Database\Eloquent\Model;

class CurrencyPair extends Model
{
    protected $table = 'currency_pairs';

    public $timestamps = false;

    protected $hidden = array('id');

    /**
     * Sanitize data before save a record
     */
    public function sanitize()
    {
        $this->currency_from = strtoupper($this->currency_from);
        $this->currency_to = strtoupper($this->currency_to);
        $this->amount_sell = number_format($this->amount_sell, 2, '.', '');
        $this->amount_buy = number_format($this->amount_buy, 2, '.', '');
    }

    /**
     * Set a relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function country()
    {
        return $this->hasMany('MarketTradeProcessor\Model\CountryMessage', 'currency_pair_id', 'id');
    }

    /**
     * Check is CurrencyPair already exist
     *
     * @return bool
     */
    public function isAlreadyExist()
    {
        $currencyPair = $this->findByPairsLetters();

        if ($currencyPair) {
            return true;
        }

        return false;
    }

    /**
     * Find CurrencyPair by currency letters
     *
     * @return Model|null
     */
    public function findByPairsLetters()
    {
        $this->sanitize();

        return $this
            ->where('currency_from', $this->currency_from)
            ->where('currency_to', $this->currency_to)
            ->first();
    }

    /**
     * Get data for frontend rendering
     *
     * @param int $limit
     * @return Model|null
     */
    public function getCurrencyPairsForGraph($limit = 1000)
    {
        return $this
            ->with(['country'])
            ->take($limit)
            ->get();
    }

    public function save(array $options = array())
    {
        $this->sanitize();

        parent::save($options);
    }
}
