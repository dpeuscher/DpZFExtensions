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

/**
 * Filters values to either null or int
 */
class IntNull extends AbstractFilter
{
    /**
     * Defined by Zend\Filter\FilterInterface
     *
     * Returns (int) $value
     *
     * @param  mixed $value
     * @return integer
     */
    public function filter($value)
    {
	    if (is_null($value))
		    return null;
	    else
            return (!is_int($value) && preg_match('#\s*[0-9]#',(string)$value))?((int) ((string) $value)):
	            ($value !== 0 && ((int) $value) === 0?null:((int) ((string) $value)));
    }
}
