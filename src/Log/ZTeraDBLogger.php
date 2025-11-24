<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB Logging Module
 * --------------------------------------------------------------------------
 *
 *  This module is responsible for handling logging functionality within the 
 *  ZTeraDB system. It provides a way to log messages at different log levels
 *  (DEBUG, INFO, WARN, ERROR, CRITICAL) to a specified log file or directly 
 *  to the console.
 *
 *  Key Responsibilities:
 *  ----------------------
 *  - Initializing and managing log levels (DEBUG, INFO, WARN, ERROR, CRITICAL).
 *  - Writing log messages to a file or console based on the configured log level.
 *  - Providing a simple interface for logging messages at various levels.
 * 
 *  Requirements:
 *  -------------
 *  - `file_put_contents` should be available for writing logs to files (if enabled).
 *  - A valid file path for log output is required if logging to a file.
 *
 *  The module provides a simple, extensible logging mechanism, which helps 
 *  track events, errors, and important system information for debugging and 
 *  auditing purposes.
 *
 * --------------------------------------------------------------------------
 *  @package     ZTeraDB
 *  @version     1.0
 *  @author      ZTeraDB
 *  @license     https://zteradb.com/licence (SPDX-License-Identifier: Proprietary)
 * --------------------------------------------------------------------------
 */

namespace ZTeraDB\Log;

// Import the ValueError exception for validation failures.
use ZTeraDB\Exceptions\ValueError;


function throwExceptionOnWarning($errno, $errstr, $errfile, $errline) {
    // Only convert Warnings to Exceptions (E_WARNING is often 2)
    // You can adjust the error levels you want to catch here.
    if (!(error_reporting() & $errno)) {
        // Error code not included in the current error_reporting level, so ignore.
        return false;
    }
    
    // Throw an exception for a warning or notice
    throw new ValueError($errstr);
}

/**
 * Class ZTeraDBLogger
 * 
 * This class provides logging capabilities for ZTeraDB. It allows logging 
 * messages at different levels (DEBUG, INFO, WARN, ERROR, CRITICAL) to either 
 * a file or the console. The log messages are formatted with timestamps, 
 * log level indicators, and the message itself.
 * 
 * The log level can be configured to control which log messages are actually 
 * written to the log file or displayed in the console. Only messages that 
 * meet the minimum severity level will be logged.
 * 
 * @package ZTeraDB\Log
 */
class ZTeraDBLogger {
  // Stores handlers for each log level
  private static $handlers = [];

  // Set initialized flag to false to check logger is initialized or not
  private static $initialized = false;

  // Predefined log levels with their corresponding numeric severity values
  const LOG_LEVELS = [
    'DEBUG' => 10,
    'INFO'  => 20,
    'WARN'  => 30,
    'ERROR' => 40,
    'CRITICAL' => 50,
  ];

  /**
   * Initializes the logging system by setting the minimum log level and configuring 
   * the output destination (either a log file or the console). The log messages will
   * be written based on the provided log level. Only messages that meet or exceed
   * the configured log level will be logged.
   *
   * If a log file path is provided and the file does not exist, it attempts to create
   * the file. If it fails to create the file, an exception will be thrown.
   * 
   * The log level can be one of the following, with increasing severity:
   * - DEBUG: 10
   * - INFO: 20
   * - WARN: 30
   * - ERROR: 40
   * - CRITICAL: 50
   *
   * The method sets up handlers for each log level. For active levels (those greater
   * than or equal to the provided log level), a function is assigned to write log
   * messages to the log file (or console if no path is provided). For inactive levels,
   * a no-op (empty) function is used to avoid unnecessary operations.
   * 
   * @param int $level The minimum log level (default: 10, which is DEBUG).
   *                   Log levels lower than this will be ignored.
   * @param string|null $log_path The path to the log file. If not provided, logs
   *                              will be written to the console.
   * @throws Exception If the log file path does not exist and cannot be created.
   */
  public static function init($level=10, $log_path=null) {
    // Check if the provided log path exists. If not, try to create it.
    if($log_path && !file_exists($log_path)) {
      // Try to create the log file
      try {
        set_error_handler("ZTeraDB\Log\\throwExceptionOnWarning");
        touch($log_path);
      }
      catch(ValueError $e) {
        throw new ValueError("Log path does not exists");
      }
      finally {
        restore_error_handler();
      }
    }

    // Loop through the predefined log levels (DEBUG, INFO, etc.)
    foreach (self::LOG_LEVELS as $name => $value) {
      // If the current log level value is greater than or equal to the minimum level,
      // set an active handler function to log messages at this level
      if ($value >= $level) {
        // active log function
        self::$handlers[$name] = function ($msg) use ($name, $log_path) {
          // Get the current date and time for the log message timestamp
          $date = @date('Y-m-d H:i:s');

          // Format the log message with timestamp and log level
          $log_message = "[$date] [$name] $msg" . PHP_EOL;

          // If a log path is provided, write the message to the file
          // Otherwise, print the message to the console (STDOUT)
          if($log_path) {
            // Append the log message to the log file
            file_put_contents($log_path, $log_message, FILE_APPEND);
          }
          else {
            // Output log message to console (STDOUT)
            fwrite(STDOUT, $log_message . PHP_EOL);
          }
        };
      } else {
        // If the current log level is below the configured level, set a no-op function
        // to avoid unnecessary processing or logging for this level
        self::$handlers[$name] = function ($msg) {};
      }
    }

    // Set logger initialized flag to true
    self::$initialized = true;
  }

  /**
   * Retrieves the currently configured log handlers based on the log levels.
   * 
   * This method returns the `self::$handlers` array, which contains 
   * the active logging functions for each log level (e.g., DEBUG, INFO, WARN, etc.).
   * Each handler corresponds to a log level and is responsible for processing 
   * and outputting log messages that meet or exceed the specified log level.
   * 
   * @return array The associative array of log handlers indexed by log level name.
   */
  public static function getHandlers()
  {
    // Return the array of log handlers based on the configured log levels.
    // Each handler corresponds to a specific log level (e.g., DEBUG, INFO, etc.), 
    // and is responsible for processing log messages for that level.
    return self::$handlers;
  }

  /**
   * Magic method to handle dynamic static calls for logging.
   * 
   * This method is triggered when a static method is called that doesn't exist 
   * in the `ZTeraDBLogger` class. It checks the log level (e.g., DEBUG, INFO, etc.) 
   * and calls the corresponding log handler function that was initialized via `init()`.
   * 
   * The log handler is invoked with the arguments passed to the dynamic method call.
   * 
   * @param string $name The name of the method being called (log level, e.g., 'debug', 'info').
   * @param array  $args The arguments passed to the log method (e.g., log message).
   */
  public static function __callStatic($name, $args)
  {
    // If logger is not initialized then return
    if(!self::$initialized) {
      return;
    }

    // Get the corresponding log handler for the log level.
    $fn = self::$handlers[strtoupper($name)];

    // If the handler exists for the given log level, invoke it with the provided arguments.
    if(isset($fn)) {
      $fn(...$args);
    }
  }
}
?>