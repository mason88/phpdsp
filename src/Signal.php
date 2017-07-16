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
 * Abstract class for representing a time-varying signal.
 */
abstract class Signal
{
	/** @var float frequency */
	var $freq;
	
	/** @var float amplitude */
	var $amp;
	
	/** @var float phase offset */
	var $offset;
	
	/** @var string wave function */
	var $func;
	
	public function __construct()
	{
	}
	
	/**
	 * Add two signals to created a SumSignal object.
	 *
	 * @param Wave $other instance of the other wave object
	 *
	 * @return SumSignal
	 */
	public function add($other)
	{
		if (! $other)
			return($this);
		
		return(new SumSignal(array($this, $other)));
	}
	
	/**
	 * Makes a Wave object.
	 *
	 * @param float $duration duration of wave in seconds
	 * @param float $start when the sampling should start in seconds
	 * @param int $framerate frames per second
	 *
	 * @return Wave
	 */
	public function make_wave($duration=1, $start=0, $framerate=11025)
	{
		$n = round($duration * $framerate);
		$ts = NumPHP::arange(0, $n - 1)->div($framerate)->add($start);
		$ys = $this->evaluate(clone $ts);

		return new Wave($ys, $ts, $framerate);
	}
	
	/**
	 * Evaluates the signal at the given times.
	 *
	 * @param NumArray $ts times of when data should be sampled
	 *
	 * @return NumArray
	 */
	abstract public function evaluate($ts);
}
