<?php
namespace DpZFExtensions\Validator;
/**
 * User: Dominik
 * Date: 21.04.13
 */

interface IValueObject {
	public function markExchanged();
	public function isExchanged();
}