<?php namespace Intellex\Stopwatch;

use Intellex\Stopwatch\Exception\AggregatedMeasurementAlreadyStoppedStopwatchException;
use Intellex\Stopwatch\Exception\AggregatedMeasurementDoubleStartStopwatchException;
use Intellex\Stopwatch\Exception\AggregatedMeasurementNotStartedStopwatchException;
use Intellex\Stopwatch\Exception\StopwatchException;

/**
 * Class AggregateMeasurement measures a single event multiple times and reports the number of
 * occurrences and total time.
 * Useful for measuring total impact of common methods, or total time spent in a part of a loop.
 *
 * @package Intellex\Stopwatch
 */
class AggregateMeasurement {

	/** @var string The name of the measurement. */
	public $name = null;

	/** @var Measurement[] The list of all measurements. */
	private $measurements = [];

	/** @var int The total time elapsed, in milliseconds. */
	private $total = 0;

	/** @var int The total count of passes. */
	private $count = 0;

	/**
	 * AggregateMeasurement constructor.
	 *
	 * @param string $name The name of the measurement.
	 */
	public function __construct($name) {
		$this->name = $name;
	}

	/**
	 * Start a new measurement and add it to the stack.
	 *
	 * @return AggregateMeasurement Itself.
	 */
	public function start() {
		try {

			// Make sure the previous one is stopped
			if (!empty($this->measurements) && !$this->measurements[sizeof($this->measurements) - 1]->isStopped()) {
				throw new AggregatedMeasurementDoubleStartStopwatchException($this);
			}

			$this->measurements[] = new Measurement($this->count);

		} catch (StopwatchException $ex) {
			StopwatchException::handle($ex);
		}

		return $this;
	}

	/**
	 * Pause the current active measurement in the stack.
	 *
	 * @return AggregateMeasurement The looped measurement.
	 */
	public function pause() {
		try {

			// Make sure the previous one is stopped
			if (empty($this->measurements)) {
				throw new AggregatedMeasurementNotStartedStopwatchException($this);
			}

			// Stop the measurements
			$measurement =& $this->measurements[$this->count];
			if ($measurement->isStopped()) {
				throw new AggregatedMeasurementAlreadyStoppedStopwatchException($this);
			}
			$measurement->stop();

			// Update the looped info
			$this->total += $measurement->getTime();
			$this->count++;

		} catch (StopwatchException $ex) {
			StopwatchException::handle($ex);
		}

		return $this;
	}

	/** @return string The name of the measurement. */
	public function getName() {
		return $this->name;
	}

	/** @return Measurement[] The list of all measurements. */
	public function getMeasurements() {
		return $this->measurements;
	}

	/** @return int The total time elapsed, in milliseconds. */
	public function getTotal() {
		return $this->total;
	}

	/** @return int The total count of passes. */
	public function getCount() {
		return $this->count;
	}

}