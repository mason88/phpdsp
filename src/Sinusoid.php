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

class Sinusoid extends Signal
{
	/**
	 * Constructor for Sinusoid.
	 *
	 * @param float $freq frequency in Hz
	 * @param float $amp amplitude, 1.0 is nominal max
	 * @param float $offset phase offset in radians
	 * @param float $func function that maps phase to amplitude
	 */
	public function __construct($freq=440, $amp=1.0, $offset=0, $func='sin')
	{
		parent::__construct();
		
		$this->freq = $freq;
		$this->amp = $amp;
		$this->offset = $offset;
		$this->func = $func;
	}
	
	/**
	 * Evaluates the signal at the given times.
	 *
	 * @param NumArray $ts times of when data should be sampled
	 *
	 * @return NumArray
	 */
	public function evaluate($ts)
	{
		$phases = $ts->mult(2 * pi() * $this->freq)->add($this->offset);
		$ys = $phases->apply($this->func)->mult($this->amp);

		return($ys);
	}
}