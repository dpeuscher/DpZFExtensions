<?php
/**
 * User: dpeuscher
 * Date: 12.03.13
 */
namespace DpZFExtensions\Validator;

/**
 * Interface to exchange states in models
 */
interface IExchangeState extends \ArrayAccess {
	/**
	 * @return array of all fields that represent the state (only atomic fields and VOs atomic fields - no VO itself)
	 */
	public function getStateVars();
	/**
	 * @return array of all fields that represent the state (only atomic fields and VO)
	 */
	public function getStateVarsVO();
	/**
	 * @return array of all fields that represent the state (with VO and their fields)
	 */
	public function getStateVarsWithAll();
	/**
	 * Returns an array-representation of the state
	 * @return array
	 */
	public function getArrayCopyVO();
	/**
	 * Returns an array-representation of the state
	 * @return array
	 */
	public function getArrayCopy();
	/**
	 * Implements a state into an entity
	 * @param $state array
	 * @throws \Exception if the state is not valid
	 */
	public function exchangeArray(array $state);
}