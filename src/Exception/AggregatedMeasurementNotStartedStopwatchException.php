<?php namespace Intellex\Stopwatch\Exception;

use Intellex\Stopwatch\AggregateMeasurement;

/**
 * Class AggregatedMeasurementNotStartedStopwatchException indicates that the aggregate measurement
 * has been tried to be stopped, but was not started or resumed.
 *
 * @package Intellex\Stopwatch\Exception
 */
class AggregatedMeasurementNotStartedStopwatchException extends StopwatchException {

	/**
	 * AggregatedMeasurementNotStartedStopwatchException constructor.
	 *
	 * @param AggregateMeasurement $measurement The measurement that caused the error.
	 */
	public function __construct(AggregateMeasurement $measurement) {
		parent::__construct("Aggregated measurement '{$measurement->name}' was tried to be stopped, but was not previously started or resumed");
	}

}