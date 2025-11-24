<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB Exceptions Module
 * --------------------------------------------------------------------------
 *
 *  This module defines a set of custom exception classes used within the ZTeraDB
 *  system. Each exception corresponds to specific error scenarios such as connection
 *  issues, protocol errors, authentication errors, value errors, query errors, etc.
 *
 *  Key Responsibilities:
 *  ----------------------
 *  - Providing custom exceptions for handling different types of errors that may
 *    occur during interactions with the ZTeraDB system.
 *  - Differentiating between various error types by assigning different exception
 *    codes to each custom exception.
 *  - Ensuring consistency in error handling and reporting within the ZTeraDB library.
 *
 *  Requirements:
 *  -------------
 *  - Extending from the base `ZTeraDBExceptions` exception class to maintain a uniform 
 *    error-handling structure across the ZTeraDB system.
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
 * Base class for all custom ZTeraDB exceptions.
 * 
 * This class extends PHP's built-in `Exception` class to provide a base for 
 * all exceptions related to ZTeraDB operations. It allows for structured error 
 * handling and includes custom exception codes for different types of errors.
 * 
 * @package ZTeraDB\Exceptions
 * @param string $message  The error message describing the authentication failure.
 * @param ZTeraDBAuthError|null $previous  The previous exception, if any, for exception chaining.
 */
class ZTeraDBExceptions extends \Exception {

  /**
   * Constructor for the base exception class.
   * 
   * @param string $message   The error message for the exception.
   * @param int    $code      The error code associated with the exception.
   * @param \Exception|null $previous The previous exception, if any (default: null).
   */
  public function __construct($message, $code, \Exception $previous = null) {
    switch(gettype($message)) {
      case "stdClass":
        if(property_exists($message, "zteradb_error")) {
          parent::__construct($message->zteradb_error, $code, $previous);
        }

        break;

      case "object":
        if(property_exists($message, "zteradb_error")) {
          parent::__construct($message->zteradb_error, $code, $previous);
        }

        break;

      default:
        parent::__construct($message, $code, $previous);
    }
  }
}
?>