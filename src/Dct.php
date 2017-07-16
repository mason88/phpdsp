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
 * Represents the spectrum of a signal using discrete cosine transform.
 */
class Dct extends SpectrumParent
{
	/**
	 * Returns a sequence of amplitudes.
	 * Note: for DCTs, amps are positive or negative real.
	 *
	 * @return NumArray
	 */
	public function amps()
	{
		return $this->hs;
	}
	
	/**
	 * Adds two DCTs elementwise.
	 *
	 * @param DCT $other other wave to add
	 *
	 * @return DCT
	 */
	public function add($other)
	{
		if ($this->framerate != $other->framerate)
			trigger_error('Framerates for the two DCT do not match.', E_USER_WARNING);
		
		$hs = clone $this->hs;
		$hs->add($other->hs);
		
		return new Dct($this->fs, $hs, $this->framerate);
	}

	/**
	 * Transforms to the time domain using Inverse DCT-IV algorithm.
	 * NOTE: whatever the start time was, we lose it when we transform back.
	 *
	 * @return Wave
	 */
	public function make_wave()
	{
		$wave = new Wave($this->hs, $this->fs, $this->framerate);
		$dct = $wave->make_dct();
		$dct->hs->mult(2);
		
		return new Wave($dct->hs, $dct->fs, $dct->framerate);
	}
}