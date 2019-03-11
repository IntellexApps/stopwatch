<?php namespace Intellex\Stopwatch\Exception;

use Intellex\Stopwatch\Measurement;

/**
 * Class MeasurementAlreadyStoppedStopwatchException indicates that the measurement has been tried
 * to be stopped, but it is already stopped.
 *
 * @package Intellex\Stopwatch\Exception
 */
class MeasurementAlreadyStoppedStopwatchException extends StopwatchException {

	/**
	 * MeasurementAlreadyStoppedStopwatchException constructor.
	 *
	 * @param Measurement $measurement The measurement that caused the error.
	 */
	public function __construct(Measurement $measurement) {
		parent::__construct("Measurement '{$measurement->getName()}' tried to be stopped, but it is already stopped");
	}

}