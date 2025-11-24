<?php
/**
 * --------------------------------------------------------------------------
 *  ConnectionError Class
 * --------------------------------------------------------------------------
 *
 *  This class defines a custom exception that is thrown when there are issues
 *  with establishing a connection to the ZTeraDB server. It is part of the ZTeraDB
 *  error-handling system and is specifically used for handling connection 
 *  failures, such as network issues or incorrect configuration settings.
 *
 *  Key Responsibilities:
 *  ----------------------
 *  - Representing a specific error scenario when a connection to the ZTeraDB server
 *    cannot be established.
 *  - Allowing developers to identify and handle connection-related errors, such as
 *    network disruptions, configuration errors, or server unavailability.
 *  - Providing a clear error message and error code (10) to help with debugging and
 *    resolving connection problems.
 *
 *  Requirements:
 *  -------------
 *  - Extending from the base `ZTeraDBExceptions` class to maintain consistent 
 *    error-handling practices across the ZTeraDB ecosystem.
 *  - Accepting an error message and an optional previous exception for more 
 *    detailed error reporting.
 *
 * --------------------------------------------------------------------------
 *  @package     ZTeraDB\Exceptions
 *  @version     1.0
 *  @author      ZTeraDB
 *  @license     https://zteradb.com/licence (SPDX-License-Identifier: Proprietary)
 * --------------------------------------------------------------------------
 */
namespace ZTeraDB\Exceptions;


/**
 * Class ConnectionError
 *
 * This exception is thrown when there are issues with establishing a connection 
 * to the ZTeraDB server. It is used to represent scenarios where the client 
 * fails to connect to the server due to network issues, incorrect configuration, 
 * or other connection-related failures.
 *
 * The exception is initialized with an error message describing the connection 
 * problem and an optional previous exception for detailed debugging. The error 
 * code `10` is used to indicate that the issue is related to the connection process.
 *
 * @package ZTeraDB\Exceptions
 * @param string $message  The error message describing the connection failure.
 * @param ConnectionError|null $previous  The previous exception, if any, for exception chaining.
 */
class ConnectionError extends ZTeraDBExceptions
{
  public function __construct($message, \Exception $previous = null)
  {
    // Code 10 signifies connection issues.
    parent::__construct($message, 10, $previous);
  }
}
?>