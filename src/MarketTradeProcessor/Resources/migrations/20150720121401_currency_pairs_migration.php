<?php

use Phinx\Migration\AbstractMigration;

class CurrencyPairsMigration extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     *
    public function change()
    {
    }
    */
    
    /**
     * Migrate Up.
     */
    public function up()
    {
        $currencyPair = $this->table('currency_pairs');
        $currencyPair
            ->addColumn('currency_from', 'string', array('limit' => 3))
            ->addColumn('currency_to', 'string', array('limit' => 3))
            ->addColumn('amount_sell', 'decimal', array('precision' => 10, 'scale' => 2))
            ->addColumn('amount_buy', 'decimal', array('precision' => 10, 'scale' => 2))
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
    }
}
