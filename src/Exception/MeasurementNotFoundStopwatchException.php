<?php namespace Intellex\Stopwatch\Exception;

/**
 * Class MeasurementNotFoundStopwatchException indicates that the measurement has been tried
 * to be stopped, but it was not found.
 *
 * @package Intellex\Stopwatch\Exception
 */
class MeasurementNotFoundStopwatchException extends StopwatchException {

	/**
	 * MeasurementNotFoundStopwatchException constructor.
	 *
	 * @param string $measurementName The name of the measurement that was tried to be stopped.
	 */
	public function __construct($measurementName) {
		parent::__construct("Unable to stop measurement '{$measurementName}' as it was not found");
	}

}