<?php
/**
 * --------------------------------------------------------------------------
 *  ValueError Exception Class
 * --------------------------------------------------------------------------
 *
 *  This class defines a custom exception that is thrown when a function or method
 *  receives an invalid value. It is part of the ZTeraDB error-handling system 
 *  and is specifically used for value validation errors within the system's protocol.
 *
 *  Key Responsibilities:
 *  ----------------------
 *  - Representing a specific type of error where an invalid value is provided 
 *    to a method or function.
 *  - Allowing developers to distinguish between value-related errors and other 
 *    types of errors in the ZTeraDB ecosystem.
 *  - Providing a clear message and error code (40) to assist in troubleshooting 
 *    protocol-related issues.
 *
 *  Requirements:
 *  -------------
 *  - Extending from the base `ZTeraDBExceptions` class to maintain a consistent 
 *    error-handling structure.
 *  - Accepting an error message and an optional previous exception for detailed 
 *    debugging.
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
 * Class ValueError
 *
 * This exception is thrown when an invalid value is provided to a function 
 * or method within the ZTeraDB system. It is used to signal when the input 
 * value does not meet the expected criteria, such as type mismatches or 
 * out-of-range values.
 *
 * The exception accepts an error message to describe the issue and an optional 
 * previous exception for detailed debugging. The error code `40` is assigned 
 * to indicate that the error pertains to protocol-related value validation.
 *
 * @package ZTeraDB\Exceptions
 * @param string $message  The error message describing the invalid value.
 * @param ValueError|null $previous  The previous exception, if any, for exception chaining.
 */
class ValueError extends ZTeraDBExceptions
{
  public function __construct($message, \Exception $previous = null)
  {
    // Code 40 signifies protocol issues.
    parent::__construct($message, 40, $previous);
  }
}
?>