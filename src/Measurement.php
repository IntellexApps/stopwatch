<?php namespace Intellex\Stopwatch;

use Intellex\Stopwatch\Exception\MeasurementAlreadyStoppedStopwatchException;
use Intellex\Stopwatch\Exception\StopwatchException;
use Intellex\Stopwatch\Exception\SubMeasurementNotStoppedStopwatchException;

/**
 * Class Measurement contains a information about a single measurement.
 *
 * @package Intellex\Stopwatch
 */
class Measurement {

	/** @var string The name of the measurement. */
	private $name = null;

	/** @var int The total time elapsed, in milliseconds. */
	private $time = null;

	/** @var int The start time of the measurement, in milliseconds. */
	private $start;

	/** @var int The end time of the measurement in milliseconds, or null if still measuring. */
	private $end = null;

	/** @var Measurement[] The list of all the measurements that have occurred during this one. */
	private $children = [];

	/**
	 * Measurement constructor.
	 *
	 * @param string $name The name of the measurement, be sure to use unique names to avoid
	 *                     overwriting.
	 */
	public function __construct($name) {
		$this->name = $name;
		$this->setStartTime();
	}

	/**
	 * Start another measurement as its child.
	 *
	 * @param string $name The name of the measurement.
	 *
	 * @return Measurement The started measurement.
	 */
	public function start($name) {

		// Make sure the last child is stopped
		if ($child = $this->getActiveChild()) {
			return $child->start($name);
		}

		$this->children[] = new Measurement($name);
		return end($this->children);
	}

	/**
	 * Stop the measurement and calculated the elapsed time.
	 *
	 * @return Measurement The stopped measurement with results ready.
	 */
	public function stop() {
		try {

			// Make sure that the measurement was not already stopped
			if ($this->isStopped()) {
				throw new MeasurementAlreadyStoppedStopwatchException($this);
			}

			// Stop
			$this->end = Stopwatch::microseconds();
			$this->time = $this->end - $this->start;

		} catch (StopwatchException $ex) {
			StopwatchException::handle($ex);
		}

		return $this;
	}

	/**
	 * Get the currently active child that is still measuring.
	 *
	 * @return Measurement|null The active child, or null if no child is active.
	 */
	public function & getActiveChild() {
		if (!empty($this->children)) {
			$child =& $this->children[sizeof($this->children) - 1];

			// Return child if was not yet stopped
			if (!$child->isStopped()) {
				return $child;
			}
		}

		$null = null;
		return $null;
	}

	/**
	 * Get the lowest active measurement.
	 *
	 * @return Measurement|null Lowest active child, or null if nothing is active.
	 */
	public function & getActive() {
		if (!$this->isStopped()) {
			foreach ($this->children as $i => $child) {
				$activeChild = &$this->children[$i]->getActive();
				if ($activeChild) {
					return $activeChild;
				}
			}

			return $this;
		}

		$null = null;
		return $null;
	}

	/**
	 * Mark a current time.
	 *
	 * @param string $name The name of the mark./p: _[=
	 *                     /
	 */
	public function mark($name) {
		try {

			// Make sure the last child is stopped
			if ($child = $this->getActiveChild()) {
				throw new SubMeasurementNotStoppedStopwatchException($this, $child);
			}

			// Add mark as new child
			$mark = new Measurement($name);
			$mark->setStartTime();
			$mark->stop();
			$this->children[] = $mark;

		} catch (StopwatchException $ex) {
			StopwatchException::handle($ex);
		}
	}

	/**
	 * Set the start time of the measurement.
	 *
	 * @param int $time The time when the measurement started in milliseconds, or null to take now.
	 */
	private function setStartTime($time = null) {
		$this->start = $time ? $time : Stopwatch::microseconds();
	}

	/** @return string The name of the measurement. */
	public function getName() {
		return $this->name;
	}

	/** @return int|null The total time elapsed in milliseconds, or null if still measuring. */
	public function getTime() {
		return $this->time;
	}

	/** @return int The start time of the measurement, in milliseconds. */
	public function getStart() {
		return $this->start;
	}

	/** @return int|null The end time of the measurement in milliseconds, or null if still measuring. */
	public function getEnd() {
		return $this->end;
	}

	/** @return Measurement[] The list of all the measurements that have occurred during this one. */
	public function getChildren() {
		return $this->children;
	}

	/** @return bool True if the measurement has stopped. */
	public function isStopped() {
		return $this->getEnd() !== null;
	}

	/**
	 * Get the report.
	 *
	 * @param int|null $round The number of decimals to show, or null not to round.
	 * @param int      $level Used internally.
	 * @param bool     $last  Used internally.
	 *
	 * @return string The generated report, as plain text.
	 */
	function report($round = 0, $level = 0, $last = false) {

		// Prepare the characters
		$start = $level ? ($last ? '└───' : '├───') : null;

		$dashes = str_repeat('│   ', max(0, $level - 1)) . $start;
		$lines = $dashes . $this->getName() . ': ' . round($this->getTime(), $round) . PHP_EOL;

		// Print children
		$childrenLength = sizeof($this->children);
		foreach ($this->children as $i => $child) {
			$lines .= $child->report($round, $level + 1, $childrenLength === $i + 1);
		}

		// Wrap in unordered list
		return $lines;
	}

}
