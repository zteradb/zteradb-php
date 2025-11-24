<?php
/**
 * --------------------------------------------------------------------------
 *  ProtocolError Class
 * --------------------------------------------------------------------------
 *
 *  This class defines a custom exception that is thrown when there are protocol
 *  errors during communication between the ZTeraDB client and server. It is used
 *  to indicate violations of the expected protocol, such as malformed messages, 
 *  incorrect formatting, or failures in the communication process.
 *
 *  Key Responsibilities:
 *  ----------------------
 *  - Representing errors that occur when the client and server communication 
 *    protocol is violated.
 *  - Helping developers identify and handle issues like message corruption, 
 *    incorrect request formats, or communication failures.
 *  - Providing a clear error code (20) to signify protocol-related issues in 
 *    the ZTeraDB system.
 *
 *  Requirements:
 *  -------------
 *  - Extending from the base `ZTeraDBExceptions` class to maintain uniform 
 *    error-handling across the entire ZTeraDB system.
 *  - Accepting an error message and an optional previous exception for detailed 
 *    error tracking and debugging.
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
 * Class ProtocolError
 *
 * This exception is thrown when there are protocol-related errors during 
 * communication between the ZTeraDB client and server. It is used to signal 
 * violations of the expected communication protocol, such as malformed messages, 
 * invalid request formats, or failures in the communication process.
 *
 * The exception is initialized with an error message that describes the protocol 
 * issue and an optional previous exception for detailed debugging. The error 
 * code `20` is used to indicate protocol-related failures within the ZTeraDB system.
 *
 * @package ZTeraDB\Exceptions
 * @param string $message  The error message describing the protocol failure.
 * @param ProtocolError|null $previous  The previous exception, if any, for exception chaining.
 */
class ProtocolError extends ZTeraDBExceptions
{
  public function __construct($message, \Exception $previous = null)
  {
    // Code 20 signifies protocol issues.
    parent::__construct($message, 20, $previous);
  }
}
?>