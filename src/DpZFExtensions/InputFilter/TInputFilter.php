<?php
/**
 * User: dpeuscher
 * Date: 11.03.13
 */
namespace DpZFExtensions\InputFilter;

use DpZFExtensions\ServiceManager\TServiceLocator;
use Zend\InputFilter\InputFilterInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\InputFilter\InputFilterAwareTrait;

/**
 * Trait to generate methods for InputFilters to the specific model. Needs a
 * field named $_inputFilter
 */
trait TInputFilter {
	/**
	 * @var InputFilterInterface
	 */
	protected $inputFilter = null;

	/**
	 * Set input filter
	 *
	 * @param InputFilterInterface $inputFilter
	 * @return mixed
	 */
	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		$this->inputFilter = $inputFilter;

		return $this;
	}
	/**
	 * @return array|object
	 */
	public function getInputFilter()
	{
		if (is_null($this->inputFilter) && $this instanceof ServiceLocatorAwareInterface &&
			is_null($this->getServiceLocator()) && isset($this->_inputFilterClassName))
			$this->inputFilter = clone $this->getServiceLocator()->get($this->_inputFilterClassName);
		return $this->inputFilter;
	}
}