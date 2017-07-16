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
 * Contains code common to Spectrum and DCT.
 */
class SpectrumParent
{
	/** @var NumArray Frequency sequences */
	var $fs;
	
	/** @var NumArray Amplitudes */
	var $hs;

	/** @var float Samples per second */	
	var $framerate;
	
	
	/**
	 * Initializes a spectrum.
	 *
	 * @param NumArray $hs amplitudes
	 * @param NumArray $fs frequencies
	 * @param int $framerate frames per second
	 */
	public function __construct($fs, $hs, $framerate)
	{
		$this->fs = $fs;
		$this->hs = $hs;
		$this->framerate = $framerate;
	}

	/**
	 * Perform deepcopy of the object.
	 */
	function __clone()
	{
		$this->fs = clone $this->fs;
		$this->hs = clone $this->hs;
		$this->framerate = clone $this->framerate;
	}
	
	/**
	 * Return number of frequencies.
	 *
	 * @return int
	 */
	public function count()
	{
		return($this->fs->getSize());
	}
	
	/**
	 * Returns the Nyquist frequency limit for this spectrum.
	 *
	 * @return float
	 */
	public function max_freq()
	{
		return $this->framerate / 2;
	}
	
	/**
	 * Returns frequency resolution in Hz.
	 *
	 * @return float
	 */
	public function freq_res()
	{
		return $this->framerate / 2 / ($this->fs->getSize() - 1);
	}

	/**
	 * Returns a sequence of amplitudes in absolute value.
	 *
	 * @return NumArray
	 */
	public function amps()
	{
		return $this->hs->abs();
	}

	/**
	 * Returns a sequence of powers.
	 *
	 * @return float
	 */
	public function power()
	{
		$temp = clone $this->amps();
		return $temp->mult($this->amps());
	}
	
	/**
	 * Inverts this spectrum/filter.
	 *
	 * @return $this
	 */
	public function invert()
	{
		$this->hs->reciprocal();
		
		return $this;
	}

	/**
	 * Attenuate frequencies above the cutoff.
	 *
	 * @param float $cutoff_freq frequency in Hz
	 * @param float $factor what to multiply the magnitude by
	 *
	 * @return $this
	 */
	public function low_pass($cutoff_freq, $factor=0)
	{
		return $this->band_stop($cutoff_freq, NULL, $factor);
	}
	
	/**
	 * Attenuate frequencies below the cutoff.
	 *
	 * @param float $cutoff_freq frequency in Hz
	 * @param float $factor what to multiply the magnitude by
	 *
	 * @return $this
	 */
	public function high_pass($cutoff_freq, $factor=0)
	{
		return $this->band_stop(NULL, $cutoff_freq, $factor);
	}
	
	/**
	 * Attenuate frequencies outside the cutoffs.
	 *
	 * @param float $low_freq frequency in Hz
	 * @param float $high_req frequency in Hz
	 * @param float $factor what to multiply the magnitude by
	 *
	 * @return $this
	 */
	public function band_pass($low_freq, $high_req, $factor=0)
	{
		$this->low_pass($high_req, $factor);
		$this->high_pass($low_freq, $factor);
		
		return $this;
	}
	
	/**
	 * Attenuate frequencies between the cutoffs.
	 *
	 * @param float $low_freq frequency in Hz
	 * @param float $high_req frequency in Hz
	 * @param float $factor what to multiply the magnitude by
	 *
	 * @return $this
	 */
	public function band_stop($low_freq, $high_req, $factor=0)
	{
		$low_ind = (is_null($low_freq) ? '' : $this->find_index($low_freq));
		$high_ind = (is_null($high_req) ? '' : $this->find_index($high_req));
		
		$cut = $this->hs->get("{$low_ind}:{$high_ind}");
		$this->hs->set("{$low_ind}:{$high_ind}", $cut->mult($factor));
		
		return $this;
	}
	
	/**
	 * Find the index corresponding to a given frequency.
	 *
	 * @param float $freq frequency in Hz
	 *
	 * @return int
	 */
	public function find_index($freq)
	{
		$n = $this->count();
		$start = $this->fs->get(0)->getData();
		$end = $this->fs->get($this->count() - 1)->getData();
		
		return intval(round(($n - 1) * ($freq - $start) / ($end - $start)));
	}
	
	/**
	 * Provides power vs frequency data in transcomposed json format as required by Google Charts.
	 *
	 * @return string
	 */
	public function google_data_power($x_label='Frequency', $y_label='Power')
	{
		return Chart::google_data($this->fs->getData(), $this->power()->getData(), $x_label, $y_label);
	}
	
	/**
	 * Provides amplitude vs frequency data in transcomposed json format as required by Google Charts.
	 *
	 * @return string
	 */
	public function google_data($x_label='Frequency', $y_label='Amplitude')
	{
		return Chart::google_data($this->fs->getData(), $this->amps()->getData(), $x_label, $y_label);
	}
}