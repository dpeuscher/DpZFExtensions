<?php
/**
 * User: dpeuscher
 * Date: 12.03.13
 */
namespace DpZFExtensions\Validator;

// Module usage
use DpZFExtensions\Validator\Exception\InvalidStateChangeException;
// Framework usage
use Zend\Validator\ValidatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
// PHP usage
use Exception;

/**
 * Trait for implementing a basic use of IValidatorAware
 */
trait TValidatorAware {
	/**
	 * @var \Zend\Validator\ValidatorInterface
	 */
	protected $_validator;
	/**
	 * @param \Zend\Validator\ValidatorInterface $validator
	 */
	public function setValidator(ValidatorInterface $validator) {
		$this->_validator = $validator;
	}

	/**
	 * @return \Zend\Validator\ValidatorInterface
	 */
	public function getValidator() {
		/** @var TValidatorAware|ServiceLocatorAwareInterface $this */
		if (!isset($this->_validator) && $this instanceof ServiceLocatorAwareInterface &&
			!is_null($this->getServiceLocator()) && isset($this->_validatorClassName))
			$this->_validator = clone $this->getServiceLocator()->get($this->_validatorClassName);
		return $this->_validator;
	}

	/**
	 * @return bool
	 * @throws \Exception
	 */
	public function isValid() {
		if (!$this instanceof IExchangeState)
			throw new Exception("Cannot check if state is valid, ".get_called_class()." does not implement
				IExchangeState");
		/** @var TValidatorAware|IExchangeState $this */
		return $this->getValidator()->isValid($this->getArrayCopyVO());
	}
	/**
	 * @param string $field
	 * @param mixed $value
	 * @return boolean
	 * @throws \Exception
	 * @throws \DpZFExtensions\Validator\Exception\InvalidStateChangeException
	 */
	public function checkValidChange($field,$value) {
		/** @var TValidatorAware|IExchangeState $this */
		if (!$this instanceof IExchangeState || !$this->getValidator() instanceof IChangeValidator)
			throw new Exception("Cannot check if state is valid, ".get_called_class()." does not implement
				IExchangeState or Validator does not implement IChangeValidator");
		/** @var TValidatorAware|IExchangeState $this */
		$validator = $this->getValidator();
		/** @var \DpZFExtensions\Validator\IChangeValidator $validator */
		if (!$validator->isValidChange($this->getArrayCopyVO(),$field,$value))
			throw new InvalidStateChangeException($validator->getMessages());
	}
}