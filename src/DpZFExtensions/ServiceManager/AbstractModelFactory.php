<?php
/**
 * User: dpeuscher
 * Date: 18.03.13
 */

namespace DpZFExtensions\ServiceManager;

// Framework usage
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
// PHP usage
use Exception;

/**
 * Class AbstractModelFactory
 *
 * @package DpZFExtensions\ServiceManager
 */
abstract class AbstractModelFactory implements ServiceLocatorAwareInterface, FactoryInterface {
	use TServiceLocator;
	/**
	 * @var array
	 */
	protected $_buildInModels = array();
	/**
	 * @var string
	 */
	protected $_modelInterface = "";

	/**
	 * @return AbstractModelFactory
	 */
	public static function getInstance() {
		$class = get_called_class();
		if (is_null($class::$_instance)) {
			$class::$_instance = new $class();
		}
		return $class::$_instance;
	}

	/**
	 *
	 */
	private function __construct() {}

	/**
	 * @param      $modelClass
	 * @param null $alias
	 * @throws Exception
	 */
	public function registerClass($modelClass,$alias = null) {
		if (!$this->getServiceLocator()->has($modelClass))
			throw new Exception("Could not find service: ".$modelClass);
		if (!$this->getServiceLocator()->get($modelClass) instanceof $this->_modelInterface)
			throw new Exception($modelClass." is not an instance of ".$this->_modelInterface);
		$this->_buildInModels[!is_null($alias)?$alias:$modelClass] = $modelClass;
	}

	/**
	 * @param string $alias
	 * @param array  $config
	 * @return Object
	 * @throws \Exception
	 */
	public function create($alias = null,array $config = array()) {
		if (is_null($alias))
			$alias = $this->_modelInterface;
		if (!isset($this->_buildInModels[$alias])) {
			if (!$this->getServiceLocator()->has($alias) ||
					!$this->getServiceLocator()->get($alias) instanceof $this->_modelInterface)
				throw new Exception("There is no service with the alias ".$alias." registered");
			else
				$this->registerClass($alias);
		}
		$newModel = clone $this->getServiceLocator()->get($this->_buildInModels[$alias]);
		if ($newModel instanceof ServiceLocatorAwareInterface)
			$newModel->setServiceLocator($this->getServiceLocator());
		foreach ($newModel->getStateVars() as $var)
			if (!isset($config[$var]))
				$config[$var] = null;
		$newModel->exchangeArray($config);
		return $newModel;
	}

	/**
	 * Create service
	 *
	 * @param ServiceLocatorInterface $serviceLocator
	 * @return mixed
	 */
	public function createService(ServiceLocatorInterface $serviceLocator) {
		$this->setServiceLocator($serviceLocator);
		return $this;
	}

}