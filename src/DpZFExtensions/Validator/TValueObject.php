<?php
namespace DpZFExtensions\Validator;
/**
 * User: Dominik
 * Date: 21.04.13
 */

trait TValueObject {
	private $_exchanged = false;
	public function markExchanged() {
		$this->_exchanged = true;
	}

	public function isExchanged() {
		return $this->_exchanged;
	}
}