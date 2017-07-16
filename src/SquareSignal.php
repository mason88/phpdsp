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
 * Represents a square signal.
 */
class SquareSignal extends Sinusoid
{
	public function evaluate($ts)	
	{
		$cycles = $ts->mult($this->freq)->add($this->offset / (pi() * 2));
		$floored = clone $cycles;
		$floored->apply('floor');
		$cycles->sub($floored);  // frac part only
		
		// unbias data
		$cycles->sub(0.5)->abs();
		$cycles->sub($cycles->mean());
		
		// sign to -1,0,1 then increase to amp
		$cycles->apply(function($val)
			{ if ($val > 0) return 1;
				elseif ($val < 0) return -1;
				else return 0;
			})
			->mult($this->amp);
		
		return $cycles;
	}
}