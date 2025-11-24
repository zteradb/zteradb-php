<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB NoResponseDataError Unit Test Suite
 * --------------------------------------------------------------------------
 *
 *  This test suite validates the `NoResponseDataError` exception class,
 *  which represents errors occurring when a ZTeraDB server returns no
 *  response data.
 *
 *  • Purpose
 *      - Ensure that `NoResponseDataError` correctly inherits from
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
 *      Validates predictable exception behavior for empty server responses,
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

// Import NoResponseDataError exception class
use ZTeraDB\Exceptions\NoResponseDataError;

// Import the base ZTeraDB exception class
use ZTeraDB\Exceptions\ZTeraDBExceptions;


/**
 * Class NoResponseDataErrorTest
 *
 * Unit tests for the NoResponseDataError exception.
 * Validates correct initialization and property behavior.
 */
class NoResponseDataErrorTest extends TestCase
{
    /**
     * Ensure the exception is an instance of ZTeraDBExceptions
     */
    public function testIsZTeraDBException()
    {
        $exception = new NoResponseDataError("No data returned from server");

        // Assert it is an instance of ZTeraDBExceptions
        $this->assertInstanceOf(ZTeraDBExceptions::class, $exception);
    }

    /**
     * Verify that the exception message is correctly set
     */
    public function testMessageIsSetCorrectly()
    {
        $message = "Server returned empty response";
        $exception = new NoResponseDataError($message);

        // Assert the message matches
        $this->assertEquals($message, $exception->getMessage());
    }

    /**
     * Confirm the exception code is set to 100 by default
     */
    public function testCodeIsSetTo100()
    {
        $exception = new NoResponseDataError("No data returned");

        // Assert the code is 100
        $this->assertEquals(100, $exception->getCode());
    }

    /**
     * Ensure the previous exception is correctly assigned
     */
    public function testPreviousExceptionIsSet()
    {
        $previous = new Exception("Underlying server issue");
        $exception = new NoResponseDataError("No data returned", $previous);

        // Assert the previous exception matches
        $this->assertSame($previous, $exception->getPrevious());
    }
}
?>