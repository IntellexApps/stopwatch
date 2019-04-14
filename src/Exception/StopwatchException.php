<?php namespace Intellex\Stopwatch\Exception;

/**
 * Class StopwatchException base exception for all exceptions thrown by the stopwatch.
 *
 * @package Intellex\Stopwatch\Exception
 */
abstract class StopwatchException extends \Exception {

	/** @var StopwatchExceptionHandling|null The class that will capture any stopwatch exception. If not set, all exceptions will be silently ignored. */
	private static $handler = null;

	/**
	 * Set the handler that will handle stopwatch exceptions. Useful to log errors, without
	 * breaking anything.
	 * By default, all errors are silently ignored.
	 *
	 * @param StopwatchExceptionHandling $handler The class that will capture any to any stopwatch
	 *                                            exception. If set to null, all exceptions will be
	 *                                            silently ignored.
	 */
	public static function setExceptionHandler(StopwatchExceptionHandling $handler) {
		static::$handler = $handler;
	}

	/**
	 * Either handle the exception with the handler, if set. Otherwise silently ignore.
	 *
	 * @param StopwatchException $exception The exception to handle.
	 */
	public static function handle(StopwatchException $exception) {
		if (static::$handler) {
			static::$handler->onStopwatchException($exception);
		}
	}

}
