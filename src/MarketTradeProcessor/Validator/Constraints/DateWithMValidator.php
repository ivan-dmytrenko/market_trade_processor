<?php

namespace MarketTradeProcessor\Validator\Constraints;

use DateTime;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class DateWithMValidator extends ConstraintValidator
{
    /**
     * Validate provided date
     * Expected date format(d-M-y H:i:s)
     *
     * @param mixed $date
     * @param Constraint $constraint
     */
    public function validate($date, Constraint $constraint)
    {
        $expectedDateFormat = "/^(([0-9])|([0-2][0-9])|([3][0-1]))" .
            "-(JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC)-\d{2}" .
            " (([0-1][0-9])|([2][0-3])):([0-5][0-9]):([0-5][0-9])$/";

        $isMatching = preg_match($expectedDateFormat, $date);

        DateTime::createFromFormat('d-M-y H:i:s', $date);
        $dateErrors = DateTime::getLastErrors();

        if ($isMatching != 0 && $dateErrors['warning_count'] > 0 && $dateErrors['warning_count'] > 0) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
