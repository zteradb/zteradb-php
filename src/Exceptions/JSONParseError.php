<?php
/**
 * --------------------------------------------------------------------------
 *  JSONParseError Exception Class
 * --------------------------------------------------------------------------
 *
 *  This class defines a custom exception that is thrown when JSON data cannot be 
 *  parsed correctly within the ZTeraDB system. It is specifically used when a 
 *  response contains malformed JSON or when decoding fails due to invalid structure 
 *  or unexpected formatting.
 *
 *  Key Responsibilities:
 *  ----------------------
 *  - Representing errors that occur during the parsing of JSON responses received 
 *    from the ZTeraDB server.
 *  - Helping developers diagnose malformed or invalid JSON returned by the system 
 *    or external components.
 *  - Providing a clear and specific error code (100) that identifies JSON parsing 
 *    issues within the ZTeraDB protocol.
 *
 *  Requirements:
 *  -------------
 *  - Extending from the base `ZTeraDBExceptions` class to keep error handling 
 *    consistent across the entire ZTeraDB codebase.
 *  - Accepting an error message and an optional previous exception for improved 
 *    debugging and error chaining.
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
 * Class JSONParseError
 *
 * This exception is thrown when there is an error parsing JSON data within 
 * the ZTeraDB system. It is used to handle scenarios where a JSON response 
 * received from the server is malformed or cannot be decoded due to incorrect 
 * structure or unexpected formatting.
 *
 * The exception is initialized with an error message that describes the parsing 
 * issue and an optional previous exception for detailed debugging. The error 
 * code `100` is used to signify that the issue is related to JSON parsing.
 *
 * @package ZTeraDB\Exceptions
 * @param string $message  The error message describing the JSON parsing failure.
 * @param JSONParseError|null $previous  The previous exception, if any, for exception chaining.
 */
class JSONParseError extends ZTeraDBExceptions
{
  public function __construct($message, \Exception $previous = null)
  {
    // Code 100 for JSON parsing issues.
    parent::__construct($message, 100, $previous);
  }
}
?>