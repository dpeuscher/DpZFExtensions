<?php
/**
 * User: Dominik
 * Date: 10.03.13
 */
namespace DpZFExtensions\Validator;

use Zend\Validator\AbstractValidator;

class Null extends AbstractValidator {
    const NOT_NULL = 'notNull';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates = array(
        self::NOT_NULL => "The input is not null",
    );

    /**
     * Returns true if and only if $value passes all validations in the chain
     *
     * Validators are run in the order in which they were added to the chain (FIFO).
     *
     * @param  mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        if (is_null($value)) {
            $result = false;
            $this->error(self::NOT_NULL);
        }
        else
            $result = true;
        return $result;
   }
}