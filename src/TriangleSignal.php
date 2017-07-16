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
 * Represents a triangle signal.
 */
class TriangleSignal extends Sinusoid
{
	public function evaluate($ts)	
	{
		$cycles = $ts->mult($this->freq)->add($this->offset / (2 * pi()));
		$floored = clone $cycles;
		$floored->apply('floor');
		$cycles->sub($floored);  // frac part only
		
		// unbias data
		$cycles->sub(0.5)->abs();
		$cycles->sub($cycles->mean());
		
		// normalize to -amp to +amp
		$cycles->normalize($this->amp);
		
		return $cycles;
	}
}