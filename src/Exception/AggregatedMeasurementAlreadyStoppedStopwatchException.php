<?php namespace Intellex\Stopwatch\Exception;

use Intellex\Stopwatch\AggregateMeasurement;

/**
 * Class AggregatedMeasurementAlreadyStoppedStopwatchException indicates that the aggregate
 * measurement has been tried to be stopped, but it is already stopped.
 *
 * @package Intellex\Stopwatch\Exception
 */
class AggregatedMeasurementAlreadyStoppedStopwatchException extends StopwatchException {

	/**
	 * AggregatedMeasurementAlreadyStoppedStopwatchException constructor.
	 *
	 * @param AggregateMeasurement $measurement The measurement that caused the error.
	 */
	public function __construct(AggregateMeasurement $measurement) {
		parent::__construct("Aggregated measurement '{$measurement->name}' tried to be stopped, but it is already stopped");
	}

}