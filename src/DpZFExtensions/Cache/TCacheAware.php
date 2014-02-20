<?php
/**
 * User: Dominik
 * Date: 24.06.13
 */

namespace DpZFExtensions\Cache;

use Zend\Cache\Storage\Adapter\Memory;
use Zend\Cache\Storage\StorageInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

trait TCacheAware {
	protected $_longTermCache;
	protected $_shortTermCache;
	protected static $_staticLongTermCache;
	protected static $_staticShortTermCache;
	/**
	 * @param \Zend\Cache\Storage\StorageInterface $cache
	 */
	public function setLongTermCache(StorageInterface $cache) {
		$this->_longTermCache = $cache;
	}
	/**
	 * @param \Zend\Cache\Storage\StorageInterface $cache
	 */
	public function setShortTermCache(StorageInterface $cache) {
		$this->_shortTermCache = $cache;
	}

	/**
	 * @param \Zend\Cache\Storage\StorageInterface $cache
	 */
	public static function setStaticLongTermCache(StorageInterface $cache) {
		self::$_staticLongTermCache = $cache;
	}
	/**
	 * @param \Zend\Cache\Storage\StorageInterface $cache
	 */
	public static function setStaticShortTermCache(StorageInterface $cache) {
		self::$_staticShortTermCache = $cache;
	}

	/**
	 * @return StorageInterface
	 */
	public function getLongTermCache() {
		if (!isset($this->_longTermCache)) {
			if ($this instanceof ServiceLocatorAwareInterface && $this->getServiceLocator()->has('LongTermCache'))
				$this->_longTermCache = $this->getServiceLocator()->get('LongTermCache');
			else
				$this->_longTermCache = new Memory();
		}
		return $this->_longTermCache;
	}
	/**
	 * @return StorageInterface
	 */
	public function getShortTermCache() {
		if (!isset($this->_shortTermCache)) {
			if ($this instanceof ServiceLocatorAwareInterface && $this->getServiceLocator()->has('ShortTermCache'))
				$this->_shortTermCache = $this->getServiceLocator()->get('ShortTermCache');
			else
				$this->_shortTermCache = new Memory();
		}
		return $this->_shortTermCache;
	}
	/**
	 * @return StorageInterface
	 */
	public static function getStaticLongTermCache() {
		if (!isset(self::$_staticLongTermCache))
			self::$_staticLongTermCache = new Memory();
		return self::$_staticLongTermCache;
	}
	/**
	 * @return StorageInterface
	 */
	public static function getStaticShortTermCache() {
		if (!isset(self::$_staticShortTermCache))
			self::$_staticShortTermCache = new Memory();
		return self::$_staticShortTermCache;
	}
}
