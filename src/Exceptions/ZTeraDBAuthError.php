<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDBAuthError Exception Class
 * --------------------------------------------------------------------------
 *
 *  This class defines a custom exception that is thrown when an authentication 
 *  error occurs in the ZTeraDB system. It is used when there are issues with 
 *  the authentication process, such as invalid credentials, failed login attempts, 
 *  or other authorization-related errors.
 *
 *  Key Responsibilities:
 *  ----------------------
 *  - Representing errors that occur during the authentication process, such as 
 *    invalid or missing credentials.
 *  - Helping developers identify and resolve issues with authentication 
 *    procedures, ensuring secure and correct access to the ZTeraDB system.
 *  - Providing a clear error message and error code (30) that indicates authentication 
 *    failures within the ZTeraDB protocol.
 *
 *  Requirements:
 *  -------------
 *  - Extending from the base `ZTeraDBExceptions` class to maintain consistent 
 *    error handling across the ZTeraDB system.
 *  - Accepting an error message and an optional previous exception to support 
 *    detailed debugging and error tracing.
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
 * Class ZTeraDBAuthError
 *
 * This exception is thrown when there is an authentication error during the 
 * interaction with the ZTeraDB system. It is specifically used to signal issues 
 * like invalid credentials or failed authentication attempts, ensuring that 
 * authentication-related errors are handled separately from other types of errors.
 *
 * The exception is initialized with an error message and optionally, a previous 
 * exception for detailed debugging. The error code `30` is used to indicate 
 * authentication-related failures in the protocol.
 *
 * @package ZTeraDB\Exceptions
 * @param string $message  The error message describing the authentication failure.
 * @param ZTeraDBAuthError|null $previous  The previous exception, if any, for exception chaining.
 */
class ZTeraDBAuthError extends ZTeraDBExceptions
{
  public function __construct($message, \Exception $previous = null)
  {
    // Code 30 signifies protocol issues.
    parent::__construct($message, 30, $previous);
  }
}
?>