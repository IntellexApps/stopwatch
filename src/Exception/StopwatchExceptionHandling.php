<?php namespace Intellex\Stopwatch;

use Intellex\Stopwatch\Exception\StopwatchException;

/**
 * Interface StopwatchExceptionHandling defines the protocol for handling stopwatch exceptions and
 * errors. Useful to log errors, without breaking anything.
 *
 * @package Intellex\Stopwatch
 */
interface StopwatchExceptionHandling {

	/**
	 * Handle the stopwatch related exception.
	 *
	 * @param StopwatchException $exception The thrown exception.
	 */
	public function onStopwatchException(StopwatchException $exception);

}