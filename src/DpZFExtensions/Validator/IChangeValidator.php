<?php
/**
 * User: dpeuscher
 * Date: 12.03.13
 */
namespace DpZFExtensions\Validator;

// Framework usage
use Zend\Validator\ValidatorInterface;

/**
 * Interface that gives the ability to lookup if a state-change is valid
 */
interface IChangeValidator extends ValidatorInterface {
	/**
	 * Method that gives the ability to lookup if a state-change is valid
	 *
	 * @param array $state the entity state
	 * @param string $field the field to change
	 * @param mixed $value the value to which the field might be changed
	 * @return boolean true if change is valid
	 */
	public function isValidChange($state,$field,$value);
}