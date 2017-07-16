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

class SumSignal extends Signal
{
	var $signals;
	
	/**
	 * Initializes the SumSignal
	 *
	 * @param array $signals array of signal objects
	 */
	public function __construct($signals)
	{
		parent::__construct();
		
		$this->signals = $signals;
	}
	
	/**
	 * Evaluates the combined signals at the given times.
	 *
	 * @param NumArray $ts times of when data should be sampled
	 *
	 * @return NumArray
	 */
	public function evaluate($ts)
	{
		$sum_ys = NumPHP::zeros($ts->getSize());
		foreach($this->signals as $signal)
			$sum_ys->add($signal->evaluate($ts));
		
		return($sum_ys);
	}
}

