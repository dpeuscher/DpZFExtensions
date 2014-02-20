<?php
namespace DpZFExtensions;

use DpZFExtensions\Cache\ICacheAware;
use Redis;
use Zend\Cache\StorageFactory;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Module implements AutoloaderProviderInterface
{
	public function getConfig() {
		return require(__DIR__.'/config/module.config.php');
	}
	public function getServiceConfig()
	{
		return array(
			'aliases' => array(
				'Zend\Cache\Storage\StorageInterface' => 'Zend\Cache\Storage\Adapter\Redis',
				'Zend\Cache\Storage\Adapter' => 'Zend\Cache\Storage\Adapter\Redis',
				'Redis' => 'Zend\Cache\Storage\Adapter\Redis',
				'Memcache' => 'Zend\Cache\Storage\Adapter\Memcache',
				'Memcached' => 'Zend\Cache\Storage\Adapter\Memcached',
				'Apc' => 'Zend\Cache\Storage\Adapter\Apc',
				'LongTermCache' => 'Zend\Cache\Storage\Adapter\Redis',
				'ShortTermCache' => 'Zend\Cache\Storage\Adapter\Apc',
				'IntraCache' => 'Zend\Cache\Storage\Adapter\Memory',
			),
			'invokable' => array(
				'DpZFExtensions\ServiceManager\ServiceLocatorDecorator' =>
				'DpZFExtensions\ServiceManager\ServiceLocatorDecorator'
			),
			'factories' => array(
				'PlainRedisNoSerializer' => function($sm) {
					if (class_exists('Redis')) {
						$redis = new Redis();
						$redis->connect('localhost',6379);
						$redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_NONE);
						return $redis;
					}
					else
						return null;
				},
				'Zend\Cache\Storage\Adapter\Redis' => function($sm) {
					if (class_exists('Redis') && !is_null(StorageFactory::adapterFactory('redis'))) {
						return StorageFactory::adapterFactory('redis',array(
									'resourceId' => 'default',
									'server' => array('127.0.0.1'),
									'libOptions' => array(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_IGBINARY)
								)
							);
					}
					else
						return StorageFactory::adapterFactory('memory');
				},
				'Zend\Cache\Storage\Adapter\Memcached' => function() {
					if (!is_null(StorageFactory::adapterFactory('memcached')))
						return StorageFactory::adapterFactory('memcached',array('namespace' => 'aib',
						                                                        'servers' => array('127.0.0.1',11211)));
					else
						return StorageFactory::adapterFactory('memory');
				},
				'Zend\Cache\Storage\Adapter\Memcache' => function() {
					if (!is_null(StorageFactory::adapterFactory('memcache')))
						return StorageFactory::adapterFactory('memcache');
					else
						return StorageFactory::adapterFactory('memory');
				},
				'Zend\Cache\Storage\Adapter\Memory' => function() {
					return StorageFactory::adapterFactory('memory');
				},
				'Zend\Cache\Storage\Adapter\Apc' => function() {
					if (extension_loaded('apc') && !is_null(StorageFactory::adapterFactory('apc')))
						return StorageFactory::adapterFactory('apc');
					else
						return StorageFactory::adapterFactory('memory');
				}
			),
			'initializers' => array(
				function($instance, $serviceManager) {
					if ($instance instanceof ServiceLocatorAwareInterface) {
						$instance->setServiceLocator($serviceManager);
					}
				},
				function($instance, ServiceLocatorInterface $serviceManager) {
					if ($instance instanceof ICacheAware) {
						if ($serviceManager->has('LongTermCache'))
							$instance->setLongTermCache($serviceManager->get('LongTermCache'));
						elseif ($serviceManager->has('Cache'))
							$instance->setLongTermCache($serviceManager->get('Cache'));
						elseif ($serviceManager->has('Redis'))
							$instance->setLongTermCache($serviceManager->get('Redis'));
						elseif ($serviceManager->has('Memcached'))
							$instance->setLongTermCache($serviceManager->get('Memcached'));
						elseif ($serviceManager->has('Memcache'))
							$instance->setLongTermCache($serviceManager->get('Memcache'));
						elseif ($serviceManager->has('IntraCache'))
							$instance->setLongTermCache($serviceManager->get('IntraCache'));
						if ($serviceManager->has('ShortTermCache'))
							$instance->setShortTermCache($serviceManager->get('ShortTermCache'));
						elseif ($serviceManager->has('Cache'))
							$instance->setShortTermCache($serviceManager->get('Cache'));
						elseif ($serviceManager->has('Memcached'))
							$instance->setShortTermCache($serviceManager->get('Memcached'));
						elseif ($serviceManager->has('Memcache'))
							$instance->setShortTermCache($serviceManager->get('Memcache'));
						elseif ($serviceManager->has('Redis'))
							$instance->setShortTermCache($serviceManager->get('Redis'));
						elseif ($serviceManager->has('IntraCache'))
							$instance->setShortTermCache($serviceManager->get('IntraCache'));
					}
				}
			)
		);
	}
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
