<?php namespace Intellex\Stopwatch\Exception;

use Intellex\Stopwatch\Measurement;

/**
 * Class SubMeasurementNotStoppedStopwatchException indicates that the measurement has been tried
 * to be stopped, but one of its child is still active (not stopped).
 *
 * @package Intellex\Stopwatch\Exception
 */
class SubMeasurementNotStoppedStopwatchException extends StopwatchException {

	/**
	 * SubMeasurementNotStoppedStopwatchException constructor.
	 *
	 * @param Measurement $measurement The measurement that caused the error.
	 * @param Measurement $child       The child measurement that was not stopped.
	 */
	public function __construct(Measurement $measurement, Measurement $child) {
		parent::__construct("Unable to stop measurement '{$measurement->getName()}', as its child '{$child->getName()}' is still active (not stopped)");
	}

}