<?php
/**
 * --------------------------------------------------------------------------
 *  QueryError Class
 * --------------------------------------------------------------------------
 *
 *  This class defines a custom exception that is thrown when a query execution 
 *  fails on the ZTeraDB server. It is used to represent issues like syntax errors, 
 *  execution failures, or any other problems that prevent a query from completing 
 *  successfully.
 *
 *  Key Responsibilities:
 *  ----------------------
 *  - Representing errors that occur during query execution, such as malformed 
 *    queries, incorrect syntax, or runtime failures.
 *  - Helping developers identify and troubleshoot issues with the SQL queries 
 *    being executed on the ZTeraDB server.
 *  - Providing a clear error message and error code (90) to indicate query-related 
 *    failures within the system.
 *
 *  Requirements:
 *  -------------
 *  - Extending from the base `ZTeraDBExceptions` class to ensure consistent 
 *    error-handling across the ZTeraDB codebase.
 *  - Accepting an error message and an optional previous exception to aid in 
 *    detailed error tracking and debugging.
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
 * Class QueryError
 *
 * This exception is thrown when a query execution fails on the ZTeraDB server. 
 * It is used to represent errors such as malformed queries, syntax issues, 
 * or any other problems that prevent a query from executing successfully.
 *
 * The exception is initialized with an error message to describe the specific 
 * issue and an optional previous exception for detailed debugging. The error 
 * code `90` is used to indicate that the failure is related to query execution.
 *
 * @package ZTeraDB\Exceptions
 * @param string $message  The error message describing the query failure.
 * @param QueryError|null $previous  The previous exception, if any, for exception chaining.
 */
class QueryError extends ZTeraDBExceptions
{
  public function __construct($message, \Exception $previous = null)
  {
    // Code 90 for query errors.
    parent::__construct($message, 90, $previous);
  }
}
?>