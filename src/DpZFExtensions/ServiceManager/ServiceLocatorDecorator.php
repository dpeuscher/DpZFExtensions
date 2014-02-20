<?php
/**
 * User: Dominik
 * Date: 25.06.13
 */

namespace DpZFExtensions\ServiceManager;


use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

class ServiceLocatorDecorator extends ServiceManager implements ServiceLocatorInterface {
	/**
	 * @var ServiceLocatorInterface
	 */
	protected $_decoree;
	public function setDecoree(ServiceLocatorInterface $serviceLocator) {
		$this->_decoree = $serviceLocator;
	}
	public function getDecoree() {
		return $this->_decoree;
	}
	public function has($key,$checkAbstractFactories = true,$usePeeringServiceManagers = true) {
		return parent::has($key,$checkAbstractFactories,$usePeeringServiceManagers)
			|| isset($this->_decoree) && $this->_decoree->has($key,$checkAbstractFactories,$usePeeringServiceManagers);
	}

	/**
	 * Retrieve a registered instance
	 *
	 * @param  string $name
	 * @param  bool   $usePeeringServiceManagers
	 * @throws \Exception|\Zend\ServiceManager\Exception\ServiceNotFoundException
	 * @return object|array
	 */
	public function get($name, $usePeeringServiceManagers = true) {
		try {
			return parent::get($name,$usePeeringServiceManagers);
		} catch (ServiceNotFoundException $e) {
			if (isset($this->_decoree))
				return $this->getDecoree()->get($name,$usePeeringServiceManagers);
			else
				throw $e;
		}
	}
}
