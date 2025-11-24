<?php
/**
 * --------------------------------------------------------------------------
 *  QueryComplete Exception Class
 * --------------------------------------------------------------------------
 *
 *  This class defines a custom exception that serves as a signal indicating that
 *  the query processing has been successfully completed. Unlike typical exceptions,
 *  this is used to gracefully exit loops or processes once a query has been processed 
 *  without errors.
 *
 *  Key Responsibilities:
 *  ----------------------
 *  - Signaling the successful completion of a query operation.
 *  - Enabling developers to exit or break loops and processes once a query is 
 *    successfully completed, ensuring clean flow control.
 *  - Unlike other exceptions, it does not carry an error code, as it is not used 
 *    to indicate failure but rather to signal successful completion.
 *
 *  Requirements:
 *  -------------
 *  - Extending from the base `ZTeraDBExceptions` class to keep the exception 
 *    handling consistent across the ZTeraDB system.
 *  - Accepting an error message and an optional previous exception, although 
 *    typically used with a simple message or none at all for signaling.
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
 * Class QueryComplete
 *
 * This exception is used as a signal to indicate that a query has been successfully 
 * completed. Unlike traditional exceptions that are used for error handling, 
 * `QueryComplete` is specifically designed to control the flow of execution, 
 * allowing developers to gracefully exit loops or processes once the query has 
 * finished processing without any errors.
 *
 * This exception does not carry an error code since it is not used to signal 
 * failure, but rather to signal successful completion and stop further processing.
 *
 * @package ZTeraDB\Exceptions
 * @param string $message  The message describing the completion (optional, can be empty).
 * @param QueryComplete|null $previous  The previous exception, if any, for chaining (optional).
 */
class QueryComplete extends ZTeraDBExceptions
{
  public function __construct($message, \Exception $previous = null)
  {
    // No code assigned here because this is more of a signal for query completion.
  }
}
?>