<?php
/**
 * User: Dominik
 * Date: 25.06.13
 */

namespace DpZFExtensions\Validator;


class AllValidator extends AbstractValidator {
	protected function _isValidByTypes($value) {
		return true;
	}

	protected function _isValidByDependencies($value) {
		return true;
	}
}