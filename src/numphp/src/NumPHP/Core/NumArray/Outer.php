<?php
/**
 * NumPHP - Mathematical PHP library for scientific computing
 *
 */

namespace NumPHP\Core\NumArray;

use NumPHP\Core\Exception\InvalidArgumentException;

/**
 * Class Outer
 *
 * @package   NumPHP\Core\NumArray
 * @author    Gordon Lesti <info@gordonlesti.com>
 * @copyright 2014-2015 Gordon Lesti (https://gordonlesti.com/)
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link      http://numphp.org/
 * @since     1.0.0
 */
abstract class Outer
{
	/**
	* Returns results of outer product on two vectors
	*
	* @param array $data1 first vector
	* @param array $data2 second vector
	*
	* @return mixed
	*
	* @throws InvalidArgumentException will be thrown if the vector sizes differ
	*
	* @since 1.0.0
	*/
	public static function outer(array $data1, array $data2)
	{
		if (! count($data1) || ! count($data2)) {
			throw new InvalidArgumentException('Vectors cannot be empty');
		}

		$outer = array();
		foreach ($data1 as $key1 => $value1) {
			foreach ($data2 as $key2 => $value2) {
				$outer[$key1][$key2] = $value1 * $value2;
			}
		}

		return $outer;
	}
}
