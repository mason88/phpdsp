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
* Represents a discrete-time waveform.
*/
class Wave
{
	/** @var NumArray Time sequences */
	var $ts;
	
	/** @var NumArray Amplitudes */
	var $ys;

	/** @var float Samples per second */	
	var $framerate;
	
	/**
	 * Initializes the wave.
	 *
	 * @param NumArray|array $ys sampled values
	 * @param NumArray $ts times
	 * @param float $framerate samples per second
	 */
	function __construct($ys, $ts=NULL, $framerate=NULL)
	{
		$this->framerate = (!is_null($framerate) ? $framerate : 11025);
		
		if (is_array($ys))
			$this->ys = new NumArray(array_map('floatval', $ys));
		else
			$this->ys = clone $ys;

		if (is_null($ts))
			$this->ts = NumPHP::arange(0, $this->ys->getSize() - 1)->div($this->framerate);
		else
			$this->ts = $ts;
	}

	/**
	 * Perform deepcopy of the object.
	 */
	function __clone()
	{
		$this->ts = clone $this->ts;
		$this->ys = clone $this->ys;
		$this->framerate = clone $this->framerate;
	}
	
	/**
	 * Return number of samples.
	 *
	 * @return int
	 */
	public function count()
	{
		return($this->ts->getSize());
	}
	
	/**
	 * Return the first ts.
	 *
	 * @return float
	 */
	public function start()
	{
		return($this->ts->get(0)->getData());
	}

	/**
	 * Return the last ts.
	 *
	 * @return float
	 */
	public function end()
	{
		return($this->ts->get($this->count() - 1)->getData());
	}
	
	/**
	 * Returns duration in seconds
	 *
	 * @return float
	 */
	function duration()
	{
		return($this->count() / $this->framerate);
	}
	
	/**
	 * Concatenates two waves. Framerate of the two waves must be equal.
	 *
	 * @param Wave $other instance of the other wave object
	 *
	 * @return $this
	 */
	public function concat($other)
	{
		if ($this->framerate != $other->framerate)
			trigger_error('Wave->concat: Framerates for the two waves do not match.', E_USER_WARNING);

		$ys = new NumArray(array_merge($this->ys->getData(), $other->ys->getData()));
		//$ts = NumPHP::arange(0, $ys->getSize() - 1)->div($this->framerate);
		
		return new Wave($ys, NULL, $this->framerate);
		
		
		return $this;
	}
	
	/**
	 * Adds two waves element-wise.
	 *
	 * @param Wave $other instance of the other wave object
	 *
	 * @return Wave
	 */
	public function add($other)
	{
		return $this->binary_op($other, 'add');
	}
	
	/**
	 * Subtracts one wave from the other.
	 *
	 * @param Wave $other instance of the other wave object
	 *
	 * @return Wave
	 */
	public function sub($other)
	{
		return $this->binary_op($other, 'sub');
	}
	
	/**
	 * Multiplies two waves elementwise.
	 * This operation ignores the timestamps. Spectrums must have same framerate and duration.
	 *
	 * @param Wave $other instance of the other wave object
	 *
	 * @return Wave
	 */
	public function mult($other)
	{
		assert(($this->count() == $other->count()), 'Lengths for the two waves do not match.');
		
		return $this->binary_op($other, 'mult');
	}
	
	/**
	 * Divides one wave by the other.
	 *
	 * @param Wave $other instance of the other wave object
	 
	 * @return Wave
	 */
	public function div($other)
	{
		return $this->binary_op($other, 'div');
	}
	
	/**
	 * Performs math operation for two waves.
	 * Binary because it operates on 2 operands, and not binary math.
	 *
	 * @param Wave $other instance of the other wave object
	 * @param string $op operation to perform (name of NumArray method)
	 *
	 * @return Wave
	 */
	protected function binary_op($other, $op)
	{
		if (! $other) return($this);

		if ($this->framerate != $other->framerate)
			trigger_error('Framerates for the two waves do not match.', E_USER_WARNING);

		// make an array of times that covers both waves
		$start = min($this->start(), $other->start());
		$end = max($this->end(), $other->end());
		$n = intval(round(($end - $start) * $this->framerate)) + 1;
		$ys = NumPHP::zeros($n);
		$ts = NumPHP::arange(0, $n - 1)->div($this->framerate)->add($start);

		foreach(array($this, $other) as $wave) {
			$i = $this->find_index_ts($ts);

			// make sure the arrays line up reasonably well
			$diff = $ts->get($i)->getData() - $wave->start();
			$dt = 1 / $wave->framerate;
			if (($diff / $dt) > 0.1)
				trigger_error("Can't operate these waveforms; their time arrays don't line up.", E_USER_WARNING);

			$j = $i + $wave->count();
			$segment = $ys->get("$i:$j")->$op($wave->ys);
			$ys->set("$i:$j", $segment);
		}

        return new Wave($ys, $ts, $this->framerate);
	}
	
	/**
	 * Find the index corresponding to a given value in NumArray.
	 *
	 * @param NumArray $ts
	 *
	 * @return int
	 */
	public function find_index_ts($ts)
	{
		$n = $ts->getSize();
		$start = $ts->get(0)->getData();
		$end = $ts->get($ts->getSize() - 1)->getData();
		
		return intval(round(($n - 1) * ($this->start() - $start) / ($end - $start)));
	}

	/**
	 * Find the index corresponding to a time.
	 *
	 * @param float $t
	 *
	 * @return int
	 */
	public function find_index($t)
	{
		$n = $this->count();
		$start = $this->start();
		$end = $this->end();
		
		return intval(round(($n - 1) * ($t - $start) / ($end - $start)));
	}
		
	/**
	 * Tapers the amplitude at the beginning and end of the signal.
	 * Tapers either the given duration of time or the given fraction of the total duration, whichever is less.
	 *
	 * @param float $denom fraction of the segment to taper
	 * @param float $duration duration of the taper in seconds
	 *
	 * @return $this
	 */
	public function apodize($denom=20, $duration=0.1)
	{
		// a fixed fraction of the segment
		$n = $this->count();
		$k1 = intval(floor($n / $denom));

		// a fixed duration of time
		$k2 = intval($duration * $this->framerate);

		$k = min($k1, $k2);

		$w1 = NumPHP::linspace(0, 1, $k);
		$w2 = NumPHP::ones($n - (2 * $k));
		$w3 = NumPHP::linspace(1, 0, $k);

		$window = new NumArray(array_merge($w1->getData(), $w2->getData(), $w3->getData()));
		$this->ys->mult($window);
		
		return $this;
	}
	
	/**
	 * Multplies the wave by a factor.
	 *
	 * @param float $factor scale factor
	 *
	 * @return $this
	 */
	public function scale($factor)
	{
		$this->ys->mult($factor);
		
		return $this;
	}
	
	/**
	 * Shifts the wave left or right in time.
	 *
	 * @param float $shift how much of time to shift
	 *
	 * @return $this
	 */
	public function shift($shift)
	{
		$this->ts->add($shift);
		
		return $this;
	}
	
	/**
	 * Normalizes the signal to the given amplitude.
	 *
	 * @param float $amp amplitude
	 *
	 * @return $this
	 */
	public function normalize($amp=1.0)
	{
		$this->ys->normalize($amp);
		
		return $this;
	}
	
	/**
	 * Computes the DCT of this wave using DCT-IV algorithm.
	 *
	 * @return DCT
	 */
	public function make_dct()
	{
		$N = $this->count();
		
		$ts = NumPHP::arange(0, $N - 1)->add(0.5)->div($N);
		$fs = NumPHP::arange(0, $N - 1)->add(0.5)->div(2);
		$ts->outer($fs);  // $ts is now args
		
		$ts->mult(2 * pi())->apply('cos');  // $ts is now M
		$ts->dot($this->ys)->div(2);  // $ts is now amps
		
		return new Dct($fs, $ts, $this->framerate);
	}
	
	/**
	 * Extracts a segment.
	 *
	 * @param float $start start time in seconds
	 * @param float $duration duration in seconds
	 *
	 * @return Wave
	 */
	public function segment($start=NULL, $duration=NULL)
	{
		if (is_null($start)) {
			$start = $this->ts->get(0)->getData();
			$i = 0;
		}
		else {
			$i = $this->find_index($start);
		}

		$j = (is_null($duration) ? '' : $this->find_index($start + $duration));
		
		return $this->slice($i, $j);
	}
	
	/**
	 * Makes a slice from a Wave.
	 *
	 * @param int $i first slice index
	 * @param int $j second slice index
	 *
	 * @return Wave
	 */
	public function slice($i, $j)
	{
		$ys = $this->ys->get("{$i}:{$j}");
		$ts = $this->ts->get("{$i}:{$j}");
		
		return new Wave($ys, $ts, $this->framerate);
	}

	/**
	 * Covariance of two unbiased waves.
	 *
	 * @param Wave $other
	 *
	 * @return float
	 */
	public function cov($other)
	{
		$ys = clone $this->ys;
		$sum = array_sum($ys->mult($other->ys)->getData());

		return $sum / $this->count();
	}
	
	/**
	 * Provides data in transcomposed json format as required by Google Charts.
	 *
	 * @return string
	 */
	public function google_data($x_label='ts', $y_label='ys')
	{
		return Chart::google_data($this->ts->getData(), $this->ys->getData(), $x_label, $y_label);
	}
}