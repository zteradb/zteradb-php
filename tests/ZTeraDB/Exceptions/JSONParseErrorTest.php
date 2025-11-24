<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB JSONParseError Unit Test Suite
 * --------------------------------------------------------------------------
 *
 *  This test suite validates the `JSONParseError` exception class, which
 *  represents errors occurring when parsing JSON responses from ZTeraDB.
 *
 *  • Purpose
 *      - Ensure that `JSONParseError` correctly inherits from
 *        `ZTeraDBExceptions`.
 *      - Verify that message, code, and previous exception values are set
 *        and retrievable.
 *
 *  • Test Coverage
 *      - `testIsZTeraDBException()`: Confirms inheritance from ZTeraDBExceptions.
 *      - `testMessageIsSetCorrectly()`: Verifies the exception message.
 *      - `testCodeIsSetTo100()`: Ensures the error code defaults to 100.
 *      - `testPreviousExceptionIsSet()`: Confirms previous exception linkage.
 *
 *  • Importance
 *      Validates predictable exception behavior for JSON parsing errors,
 *      enabling safe try-catch handling in ZTeraDB clients.
 *
 * --------------------------------------------------------------------------
 *  @package     ZTeraDB\Tests
 *  @version     1.0
 *  @author      ZTeraDB
 *  @license     https://zteradb.com/licence (SPDX-License-Identifier: Proprietary)
 * --------------------------------------------------------------------------
 */

// Import PHPUnit framework for unit testing
use PHPUnit\Framework\TestCase;

// Import JSONParseError exception class
use ZTeraDB\Exceptions\JSONParseError;

// Import the base ZTeraDB exception class
use ZTeraDB\Exceptions\ZTeraDBExceptions;


/**
 * Class JSONParseErrorTest
 *
 * Unit tests for the JSONParseError exception.
 * Validates correct initialization and property behavior.
 */
class JSONParseErrorTest extends TestCase
{
    /**
     * Ensure the exception is an instance of ZTeraDBExceptions
     */
    public function testIsZTeraDBException()
    {
        $exception = new JSONParseError("JSON parsing failed");

        // Assert it is an instance of ZTeraDBExceptions
        $this->assertInstanceOf(ZTeraDBExceptions::class, $exception);
    }

    /**
     * Verify that the exception message is correctly set
     */
    public function testMessageIsSetCorrectly()
    {
        $message = "Invalid JSON format";
        $exception = new JSONParseError($message);

        // Assert the message matches
        $this->assertEquals($message, $exception->getMessage());
    }

    /**
     * Confirm the exception code is set to 100 by default
     */
    public function testCodeIsSetTo100()
    {
        $exception = new JSONParseError("JSON parsing failed");

        // Assert the code is 100
        $this->assertEquals(100, $exception->getCode());
    }

    /**
     * Ensure the previous exception is correctly assigned
     */
    public function testPreviousExceptionIsSet()
    {
        $previous = new Exception("Underlying JSON error");
        $exception = new JSONParseError("JSON parsing failed", $previous);

        // Assert the previous exception matches
        $this->assertSame($previous, $exception->getPrevious());
    }
}
