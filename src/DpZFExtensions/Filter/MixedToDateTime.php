<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace DpZFExtensions\Filter;

use Zend\Filter\AbstractFilter;
use DateTime;

/**
 * Filters values to either null or int
 */
class MixedToDateTime extends AbstractFilter
{
    /**
     * Defined by Zend\Filter\FilterInterface
     *
     * Returns (int) $value
     *
     * @param  mixed $value
     * @return DateTime
     */
    public function filter($value)
    {
	    if (is_string($value))
	        return new DateTime('@'.strtotime($value));
	    if ($value instanceof DateTime)
		    return $value;
	    if (is_int($value))
		    return new DateTime('@'.$value);
	    return null;
    }
}
