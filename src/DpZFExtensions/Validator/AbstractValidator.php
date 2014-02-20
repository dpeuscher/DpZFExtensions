<?php
/**
 * User: dpeuscher
 * Date: 15.03.13
 */
namespace DpZFExtensions\Validator;

use Zend\Validator\AbstractValidator as ZendAbstractValidator;

/**
 * Class AbstractValidator
 */
abstract class AbstractValidator extends ZendAbstractValidator {
	protected $_types = array();
	abstract protected function _isValidByTypes($value);
	abstract protected function _isValidByDependencies($value);
	final public function isValid($value) {
		return $this->_isValidByTypes($value) && $this->_isValidByDependencies($value);
	}
	protected function _checkTypes($types,$state) {
		foreach ($types as $varName => $type) {
			if (isset($type['atomic']) && $type['atomic']) {
				$atomic = true;
				$class = false;
				if (!isset($this->abstractOptions['messageTemplates'][$varName.'Invalid']))
					$this->abstractOptions['messageTemplates'][$varName.'Invalid'] =
						"Invalid value for ".$varName.": %value% is not of type ".$type['type'];
			}
			else {
				$class = true;
				$atomic = false;
				if (!isset($this->abstractOptions['messageTemplates'][$varName.'Invalid']))
					$this->abstractOptions['messageTemplates'][$varName.'Invalid'] =
						"Invalid value for ".$varName.": %value% is not an instance of ".$type['type'];
			}
			if (isset($type['required']) && $type['required'] && !isset($state[$varName]))
				$this->error($varName.'Invalid','NULL');
			if ($atomic && isset($state[$varName]) &&
				strtolower(gettype($state[$varName])) != strtolower($type['type']))
				$this->error($varName.'Invalid',
				             (!isset($type['serialization']) || !is_callable($type['serialization']))?
					             var_export($state[$varName],true):
					                call_user_func($type['serialization'],$state[$varName])
				);
			elseif ($class && isset($state[$varName]) && !($state[$varName] instanceof $type['type']))
				$this->error($varName.'Invalid',
				             (!isset($type['serialization']) || !is_callable($type['serialization']))?
					             var_export($state[$varName],true):
					                call_user_func($type['serialization'],$state[$varName])
				);
		}
	}
	protected function _checkValid($types,$state) {
		foreach ($types as $value => $type) {
			if (!isset($this->abstractOptions['messageTemplates'][$value])) {
				if (isset($type['entity']))
					$this->abstractOptions['messageTemplates'][$value] = 'Wrong value for '.$type['entity'].': '.
						$type['message'];
				else
					$this->abstractOptions['messageTemplates'][$value] = 'Invalid state: '.
						$type['message'];
			}
			if (isset($type['check']) && is_callable($type['check']) &&
				(!isset($type['entity']) || isset($state[$type['entity']]))) {
					if (!(isset($type['entity'])?call_user_func($type['check'],$state[$type['entity']],$state):
						call_user_func($type['check'],$state)
					))
						$this->error($value,isset($type['entity'])?
							((!isset($type['serialization']) || !is_callable($type['serialization']))?
								var_export($state[$type['entity']],true):
								call_user_func($type['serialization'],$state[$type['entity']],$state)
							):null);
			}
		}
	}
	protected function _checkChangeValid($types,$state,$key,$changeValue) {
		foreach ($types as $value => $type) {
			if (!isset($this->abstractOptions['messageTemplates'][$value]))
				$this->abstractOptions['messageTemplates'][$value] = 'Cannot change value for '.
					(is_array($type['entity'])?implode(',',$type['entity']):$type['entity']).': '.
					$type['message'];
			if ((is_array($type['entity']) && in_array($key,$type['entity']) || $type['entity'] == $key) &&
					isset($type['check']) && is_callable($type['check'])) {
				if (!call_user_func($type['check'],$changeValue,$state,$key))
					$this->error($value,
						(!isset($type['serialization']) || !is_callable($type['serialization']))?
							var_export($changeValue,true):
							call_user_func($type['serialization'],$changeValue,$state));
			}
		}
	}
}