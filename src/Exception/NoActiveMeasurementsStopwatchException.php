<?php namespace Intellex\Stopwatch\Exception;

/**
 * Class NoActiveMeasurementsStopwatchException indicates that the a measurement has been tried
 * to mark a time, but there were no active measurements.
 *
 * @package Intellex\Stopwatch\Exception
 */
class NoActiveMeasurementsStopwatchException extends StopwatchException {

	/**
	 * NoActiveMeasurementsStopwatchException constructor.
	 *
	 * @param string $measurementName The name of the measurement that was tried to be stopped.
	 */
	public function __construct($measurementName) {
		parent::__construct("Unable to stop measurement '{$measurementName}' as it was not found");
	}

}