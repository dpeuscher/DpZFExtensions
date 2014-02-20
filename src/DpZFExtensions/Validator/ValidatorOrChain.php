<?php
/**
 * User: Dominik
 * Date: 10.03.13
 */
namespace DpZFExtensions\Validator;

use Zend\Validator\ValidatorChain;
use Zend\Validator\ValidatorInterface;

class ValidatorOrChain extends ValidatorChain {
    protected $_validatorInits = array();
    public function __construct() {
        $this->_validatorInits = func_get_args();
    }
    protected function _getValidators() {
        while ($validator = array_pop($this->_validatorInits))
            if (is_array($validator))
                $this->attachByName($validator['name'],$validator['options']);
            else
                $this->attach($validator);
    }
    /**
     * Returns true if and only if $value passes all validations in the chain
     *
     * Validators are run in the order in which they were added to the chain (FIFO).
     *
     * @param  mixed $value
     * @param  mixed $context Extra "context" to provide the validator
     * @return bool
     */
    public function isValid($value, $context = null)
    {
        $this->_getValidators();
        $this->messages = array();
        $result         = false;
        foreach ($this->validators as $element) {
            /** @var $validator ValidatorInterface */
            $validator = $element['instance'];
            if (!$validator->isValid($value, $context)) {
                $messages       = $validator->getMessages();
                $this->messages = array_replace_recursive($this->messages, $messages);
                continue;
            }
            $result         = true;
        }
        return $result;
    }

}