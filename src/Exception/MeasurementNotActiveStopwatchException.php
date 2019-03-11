<?php namespace Intellex\Stopwatch\Exception;

use Intellex\Stopwatch\Measurement;

/**
 * Class MeasurementNotActiveStopwatchException indicates that the measurement has been tried
 * to be stopped, but it is not the active one.
 *
 * @package Intellex\Stopwatch\Exception
 */
class MeasurementNotActiveStopwatchException extends StopwatchException {

	/**
	 * MeasurementNotActiveStopwatchException constructor.
	 *
	 * @param string      $measurementName   The name of the measurement that was tried to be
	 *                                       stopped.
	 * @param Measurement $activeMeasurement The currently active measurement.
	 */
	public function __construct($measurementName, $activeMeasurement) {
		parent::__construct("Unable to stop measurement '{$measurementName}' as the active measurement is '{$activeMeasurement->getName()}'");
	}

}