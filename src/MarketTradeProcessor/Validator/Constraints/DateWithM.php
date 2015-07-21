<?php

namespace MarketTradeProcessor\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class DateWithM extends Constraint
{
    public $message = "Date is not valid";

    public function validateBy()
    {
        return get_class($this) . 'Validator';
    }
}
