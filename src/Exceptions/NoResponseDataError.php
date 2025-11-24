<?php
/**
 * --------------------------------------------------------------------------
 *  NoResponseDataError Exception Class
 * --------------------------------------------------------------------------
 *
 *  This class defines a custom exception that is thrown when a query executed
 *  against the ZTeraDB server returns no data. It is used to signal scenarios 
 *  where a response is expected but missing — whether due to network issues, 
 *  protocol failures, or server-side problems.
 *
 *  Key Responsibilities:
 *  ----------------------
 *  - Representing situations where expected response data is absent or empty.
 *  - Helping developers detect and handle cases where the server fails to return 
 *    valid or any data for a given query.
 *  - Providing a consistent error code (100) that identifies missing-response 
 *    conditions within the ZTeraDB protocol.
 *
 *  Requirements:
 *  -------------
 *  - Extending from the base `ZTeraDBExceptions` class to ensure consistent 
 *    error-handling practices across the ZTeraDB system.
 *  - Accepting an error message and an optional previous exception to support 
 *    detailed debugging and exception chaining.
 *
 * --------------------------------------------------------------------------
 *  @package     ZTeraDB\Exceptions
 *  @version     1.0
 *  author       ZTeraDB
 *  @license     https://zteradb.com/licence (SPDX-License-Identifier: Proprietary)
 * --------------------------------------------------------------------------
 */
namespace ZTeraDB\Exceptions;

/**
 * Class NoResponseDataError
 *
 * This exception is thrown when a query to the ZTeraDB server fails to return 
 * any response data. It is used to handle scenarios where the expected data 
 * is missing from the server's response, indicating that the query completed 
 * but no data was returned or the response was empty.
 *
 * The exception is initialized with an error message that describes the missing 
 * data and an optional previous exception for detailed debugging. The error 
 * code `100` is used to signify the absence of response data, making it clear 
 * that the issue is related to the server's response.
 *
 * @package ZTeraDB\Exceptions
 * @param string $message  The error message describing the missing response data.
 * @param NoResponseDataError|null $previous  The previous exception, if any, for exception chaining.
 */
class NoResponseDataError extends ZTeraDBExceptions
{
  public function __construct($message, \Exception $previous = null)
  {
    // Code 100 for missing response data.
    parent::__construct($message, 100, $previous);
  }
}
?>