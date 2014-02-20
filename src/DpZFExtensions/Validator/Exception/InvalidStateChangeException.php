<?php
/**
 * User: dpeuscher
 * Date: 12.03.13
 */
namespace DpZFExtensions\Validator\Exception;

/**
 * Gets thrown if a state change was not valid
 */
use Exception;

class InvalidStateChangeException extends Exception {
	/**
	 * @var array
	 */
	protected $_messages;
	/**
	 * @param array $messages
	 */
	public function __construct(array $messages) {
		$this->_messages = $messages;
	}
	/**
	 * @return string
	 */
	public function __toString() {
		return implode("\n",$this->_messages);
	}
}