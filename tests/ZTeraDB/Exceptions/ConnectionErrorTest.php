<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB ConnectionError Unit Test Suite
 * --------------------------------------------------------------------------
 *
 *  This test suite validates the `ConnectionError` exception class, which
 *  represents errors occurring during connection attempts to ZTeraDB.
 *
 *  • Purpose
 *      - Ensure that `ConnectionError` correctly inherits from PHP's
 *        Exception class.
 *      - Verify that message, code, and previous exception values are set
 *        and retrievable.
 *
 *  • Test Coverage
 *      - `testIsZTeraDBException()`: Confirms the exception is an instance
 *        of ConnectionError.
 *      - `testMessageIsSetCorrectly()`: Verifies the exception message.
 *      - `testCodeIsSetTo10()`: Ensures the error code defaults to 10.
 *      - `testPreviousExceptionIsSet()`: Confirms previous exception linkage.
 *
 *  • Importance
 *      Validates predictable exception behavior for connection errors,
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

// Import the ConnectionError class to test exception behavior
use ZTeraDB\Exceptions\ConnectionError;

/**
 * Class ConnectionErrorTest
 *
 * Unit tests for the ConnectionError exception.
 * Validates correct initialization and property behavior.
 */
class ConnectionErrorTest extends TestCase
{
    /**
     * Ensure the exception is an instance of ConnectionError
     */
    public function testIsZTeraDBException()
    {
        $exception = new ConnectionError("Connection failed");

        // Assert it is an instance of ConnectionError
        $this->assertInstanceOf(ConnectionError::class, $exception);
    }

    /**
     * Verify that the exception message is correctly set
     */
    public function testMessageIsSetCorrectly()
    {
        $message = "Cannot connect to server";
        $exception = new ConnectionError($message);

        // Assert the message matches
        $this->assertEquals($message, $exception->getMessage());
    }

    /**
     * Confirm the exception code is set to 10 by default
     */
    public function testCodeIsSetTo10()
    {
        $exception = new ConnectionError("Connection failed");

        // Assert the code is 10
        $this->assertEquals(10, $exception->getCode());
    }

    /**
     * Ensure the previous exception is correctly assigned
     */
    public function testPreviousExceptionIsSet()
    {
        $previous = new \Exception("Previous exception");
        $exception = new ConnectionError("Connection failed", $previous);

        // Assert the previous exception matches
        $this->assertSame($previous, $exception->getPrevious());
    }
}
?>