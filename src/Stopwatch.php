<?php namespace Intellex\Stopwatch;

use Intellex\Stopwatch\Exception\MeasurementNotActiveStopwatchException;
use Intellex\Stopwatch\Exception\MeasurementNotFoundStopwatchException;
use Intellex\Stopwatch\Exception\NoActiveMeasurementsStopwatchException;
use Intellex\Stopwatch\Exception\StopwatchException;

/**
 * Class Stopwatch measures elapsed time between calls.
 * It also handles measurement nesting and aggregated measurements that are called more than once.
 *
 * @package Intellex\Stopwatch
 */
class Stopwatch {

	/** @var Measurement[] The list of all stopwatches, active and inactive. */
	private static $measurements = [];

	/** @var AggregateMeasurement[] The index of all aggregated measurements, where key is the name. */
	private static $aggregatedMeasurements = [];

	/**
	 * Get the current timestamp, in milliseconds.
	 *
	 * @return float The current timestamp, in milliseconds.
	 */
	public static function microseconds() {
		return microtime(true) * 1000;
	}

	/**
	 * Get the total elapsed time, since the PHP started.
	 *
	 * @return float The total elapsed time, in seconds.
	 */
	public static function getTotalElapsedTime() {
		return (microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) * 1000;
	}

	/**
	 * Start a named measurement.
	 *
	 * @param string $name The name of the measurement.
	 *
	 * @return Measurement The started measurement.
	 */
	public static function start($name) {

		// New measurement od active one
		if (sizeof(static::$measurements)) {
			$measurement =& static::$measurements[sizeof(static::$measurements) - 1];
			if (!$measurement->isStopped()) {
				return $measurement->start($name);
			}
		}

		// Add as nea measurement
		static::$measurements[] = new Measurement($name);
		return end(static::$measurements);
	}

	/**
	 * Stop the measurement and store the result.
	 *
	 * @param string $name The name of the measurement.
	 *
	 * @return Measurement|null The measurement that was stopped, or null on error.
	 */
	public static function stop($name) {
		try {

			// Validate that we have an active measurement
			foreach (static::$measurements as $measurement) {
				if ($active = $measurement->getActive()) {
					if ($active->getName() !== $name) {
						throw new MeasurementNotActiveStopwatchException($name, $active);
					}
					return $active->stop();
				}
			}

			// Fail if reached
			throw new MeasurementNotFoundStopwatchException($name);

		} catch (StopwatchException $ex) {
			StopwatchException::handle($ex);
		}

		return null;
	}

	/**
	 * Get all of the measurements.
	 *
	 * @return Measurement[] The list of all measurement.
	 */
	public static function getMeasurements() {
		return static::$measurements;
	}

	/**
	 * Mark the current time with a name, without stopping the measurement.
	 *
	 * @param string $name The name of the mark.
	 */
	public static function markTime($name) {
		try {

			// Validate that we have an active measurement
			foreach (static::$measurements as $measurement) {
				if ($active = $measurement->getActive()) {
					$active->mark($name);
					return;
				}
			}

			// Fail if reached
			throw new NoActiveMeasurementsStopwatchException($name);

		} catch (StopwatchException $ex) {
			StopwatchException::handle($ex);
		}
	}

	/**
	 * Start or continue an aggregated measurement.
	 *
	 * @param string $name The name of the loop measurement.
	 *
	 * @return AggregateMeasurement The looped measurement.
	 */
	public static function startAggregate($name) {
		if (!isset(static::$aggregatedMeasurements[$name])) {
			static::$aggregatedMeasurements[$name] = new AggregateMeasurement($name);
		}

		return static::$aggregatedMeasurements[$name]->start();
	}

	/**
	 * Pause an aggregated measurement.
	 *
	 * @param string $name The name of the loop measurement.
	 *
	 * @return AggregateMeasurement The looped measurement.
	 */
	public static function pauseAggregate($name) {
		return static::$aggregatedMeasurements[$name]->pause();
	}

	/**
	 * Get the list of all looped measurements.
	 *
	 * @return AggregateMeasurement[] The list of looped measurements.
	 */
	public static function getAggregatedMeasurements() {
		return static::$aggregatedMeasurements;
	}

}
