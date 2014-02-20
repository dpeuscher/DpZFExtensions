<?php
/**
 * User: dpeuscher
 * Date: 12.03.13
 */
namespace DpZFExtensions\Validator;

use Zend\Validator\ValidatorInterface;

/**
 * A model that implements this interface is able to be validated
 */
interface IValidatorAware {
	/**
	 * @param \Zend\Validator\ValidatorInterface $validator
	 */
	public function setValidator(ValidatorInterface $validator);

	/**
	 * @return \Zend\Validator\ValidatorInterface
	 */
	public function getValidator();
	/**
	 * @return boolean
	 */
	public function isValid();

	/**
	 * @param string $field
	 * @param mixed  $value
	 * @throws \Exception
	 */
	public function checkValidChange($field,$value);
}