<?php
/**
 * User: Dominik
 * Date: 24.06.13
 */

namespace DpZFExtensions\Cache;

use Zend\Cache\Storage\StorageInterface;

/**
 * Class ICacheAware
 *
 * @package DpZFExtensions\Cache
 */
interface ICacheAware {
	/**
	 * @param \Zend\Cache\Storage\StorageInterface $cache
	 */
	public function setLongTermCache(StorageInterface $cache);
	/**
	 * @param \Zend\Cache\Storage\StorageInterface $cache
	 */
	public function setShortTermCache(StorageInterface $cache);
	/**
	 * @return StorageInterface
	 */
	public function getLongTermCache();
	/**
	 * @return StorageInterface
	 */
	public function getShortTermCache();
	/**
	 * @param \Zend\Cache\Storage\StorageInterface $cache
	 */
	public static function setStaticLongTermCache(StorageInterface $cache);
	/**
	 * @param \Zend\Cache\Storage\StorageInterface $cache
	 */
	public static function setStaticShortTermCache(StorageInterface $cache);
	/**
	 * @return StorageInterface
	 */
	public static function getStaticLongTermCache();
	/**
	 * @return StorageInterface
	 */
	public static function getStaticShortTermCache();
}