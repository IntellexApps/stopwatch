<?php namespace Intellex\Stopwatch\Exception;

use Intellex\Stopwatch\AggregateMeasurement;

/**
 * Class AggregateMeasurementDoubleStartStopwatchException indicates that the aggregate measurement
 * has been started twice, without being stopped.
 *
 * @package Intellex\Stopwatch\Exception
 */
class AggregatedMeasurementDoubleStartStopwatchException extends StopwatchException {

	/**
	 * AggregateMeasurement constructor.
	 *
	 * @param AggregateMeasurement $measurement The measurement that caused the error.
	 */
	public function __construct(AggregateMeasurement $measurement) {
		parent::__construct("Aggregated measurement '{$measurement->name}' started twice, without being stopped");
	}

}