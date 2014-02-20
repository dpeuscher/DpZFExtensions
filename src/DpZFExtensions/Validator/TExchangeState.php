<?php
/**
 * User: dpeuscher
 * Date: 12.03.13
 */
namespace DpZFExtensions\Validator;

// PHP usage
use DpZFExtensions\Validator\Exception\InvalidStateException;
use Exception;

/**
 * Trait to exchange states in models
 */
trait TExchangeState {
	/**
	 * @param $fieldName
	 * @return string a private name of the fieldName
	 */
	private static function _privatizeField($fieldName) {
		if (substr($fieldName,0,1) != '_')
			return '_'.$fieldName;
		return $fieldName;
	}
	/**
	 * @return array of all fields that represent the state (only atomic fields and VOs atomic fields - no VO itself)
	 */
	abstract public function getStateVars();

	/**
	 * @return array of all fields that represent the state (only atomic fields and VO)
	 */
	public function getStateVarsVO() {
		return $this->getStateVars();
	}
	/**
	 * @return array of all fields that represent the state (with VO and their fields)
	 */
	public function getStateVarsWithAll() {
		return $this->getStateVars();
	}
	/**
	 * @return array
	 */
	public function getStateVO() {
		return array_diff($this->getStateVarsVO(),$this->getStateVars());
	}
	/**
	 * Returns an array-representation of the state
	 * @return array
	 */
	public function getArrayCopy() {
		$state = array();
		foreach ($this->getStateVars() as $var)
			$state[$var] = $this->{self::_privatizeField($var)};
		return $state;
	}
	/**
	 * Returns an array-representation of the state
	 * @return array
	 */
	public function getArrayCopyVO() {
		$state = array();
		foreach ($this->getStateVarsVO() as $var)
			$state[$var] = $this->{self::_privatizeField($var)};
		return $state;
	}

	/**
	 * Implements a state into an entity
	 *
	 * @param $state array
	 * @throws InvalidStateException if the state is not valid
	 * @throws \Exception
	 */
	public function exchangeArray(array $state) {
		if ($this instanceof IValueObject && $this->isExchanged())
			throw new Exception("Cannot change ValueObjects. Create new object instead");
		if ($this instanceof IValidatorAware && !is_null($this->getValidator()) &&
			!$this->getValidator()->isValid($state)) {
			$stateRep = array();
			foreach ($state as $key => $val)
				$stateRep[] = $key.' => '.
					(is_object($val)?'[Object]':(is_resource($val)?'[Resource]':var_export($val,true)));
			throw new InvalidStateException("ExchangeArray called with an invalid state: ".
				             var_export($this->getValidator()->getMessages(),true)."\n\n(".implode(',',$stateRep).')');
		}
		$toSet = $this->getStateVarsWithAll();
		foreach ($this->getStateVO() as $voIndex)
			if (isset($state[$voIndex])) {
				$vo = $state[$voIndex];
				if ($vo instanceof IExchangeState)
					$state = array_merge($state,$vo->getArrayCopy());
			}

		foreach ($toSet as $var) {
			if (isset($state[$var])) {
				$value = $state[$var];
				if ($this instanceof IValueObject && $value instanceof IFreezable) $value->freeze();
				// @TODO Add fields of ValueObjects to motherObject
				$this->{self::_privatizeField($var)} = $value;
			}
			else
				$this->{self::_privatizeField($var)} = null;
		}
		if ($this instanceof IValueObject)
			$this->markExchanged();
	}

	/**
	 * @param string $offset
	 * @return bool
	 */
	public function offsetExists($offset) {
		return isset($this->{self::_privatizeField($offset)});
	}

	/**
	 * @param string $offset
	 * @return mixed
	 */
	public function offsetGet($offset) {
		return $this->{self::_privatizeField($offset)};
	}

	/**
	 * @param string $offset
	 * @param mixed $value
	 * @throws \Exception
	 */
	public function offsetSet($offset, $value) {
		if ($this instanceof IValueObject && $this->isExchanged())
			throw new Exception("Cannot change ValueObjects. Create new object instead");
		if ($this instanceof IValidatorAware && !is_null($this->getValidator()))
			$this->checkValidChange($offset,$value);
		$this->{self::_privatizeField($offset)} = $value;
	}

	/**
	 * @param string $offset
	 * @throws \Exception
	 */
	public function offsetUnset($offset) {
		if ($this instanceof IValueObject && $this->isExchanged())
			throw new Exception("Cannot change ValueObjects. Create new object instead");
		if ($this instanceof IValidatorAware && !is_null($this->getValidator()))
			$this->checkValidChange($offset,null);
		$this->{self::_privatizeField($offset)} = null;
	}
}
