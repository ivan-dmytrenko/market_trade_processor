<?php

namespace MarketTradeProcessor\Model;

use Illuminate\Database\Eloquent\Model;

class CountryMessage extends Model
{
    protected $table = 'countries_messages';

    public $timestamps = false;

    protected $hidden = array('id', 'currency_pair_id');

    /**
     * Sanitize data before save a record
     */
    public function sanitize()
    {
        $this->country = strtoupper($this->country);
    }

    /**
     * Set a relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currencyPair()
    {
        return $this->belongsTo('MarketTradeProcessor\Model\CurrencyPair');
    }

    /**
     * Check is CountryMessage already exist
     *
     * @return bool
     */
    public function isRelatedCountryMessageExist()
    {
        $countryMessage = $this->findByCountryAndPairId();

        if ($countryMessage) {
            return true;
        }

        return false;
    }

    /**
     * Find CountryPair by country letters and pair id
     *
     * @return Model|null
     */
    public function findByCountryAndPairId()
    {
        $this->sanitize();

        return $this
            ->where('country', $this->country)
            ->where('currency_pair_id', $this->currency_pair_id)
            ->first();
    }

    /**
     * Increment 'messages_count'
     *
     * @param int $scale
     */
    public function incrementMessage($scale = 1)
    {
        $this->messages_count += $scale;
    }

    public function save(array $options = array())
    {
        $this->sanitize();

        parent::save();
    }
}
