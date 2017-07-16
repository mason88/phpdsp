<?php
/**
 * PhpDsp
 *
 * @copyright  Copyright (c) 2017 Mason Lee
 * @license    http://www.gnu.org/licenses/gpl.html GNU GPLv3
 */

namespace mason88\PhpDsp;

use NumPHP\Core\NumPHP;
use NumPHP\Core\NumArray;

/**
 * Example class for handling data output for visual representation.
 */
class Chart
{

	/**
	 * Example method for outputting data for Google Charts.
	 *
	 * @param array $x_data array of horizontal data
	 * @param array $y_data array of vertical data
	 * @param string $x_label horizontal data label
	 * @param string $y_label vertical data label
	 *
	 * @return string
	 */
	public static function google_data($x_data, $y_data, $x_label='', $y_label='')
	{
		$combined = new NumArray(array(array_merge(array($x_label), $x_data),
			array_merge(array($y_label), $y_data)));
		$result = $combined->getTranspose();
		
		return(json_encode($result->getData()));
	}
}