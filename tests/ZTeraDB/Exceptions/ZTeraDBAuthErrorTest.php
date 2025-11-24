<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB ZTeraDBAuthError Unit Test Suite
 * --------------------------------------------------------------------------
 *
 *  This test suite validates the `ZTeraDBAuthError` exception class, which
 *  represents authentication-related errors in ZTeraDB.
 *
 *  • Purpose
 *      - Ensure that `ZTeraDBAuthError` correctly inherits from
 *        `ZTeraDBExceptions`.
 *      - Verify that the exception message, code, and previous exception
 *        are correctly initialized and retrievable.
 *
 *  • Test Coverage
 *      - `testIsZTeraDBException()`: Confirms inheritance from ZTeraDBExceptions.
 *      - `testMessageIsSetCorrectly()`: Verifies the exception message.
 *      - `testCodeIsSetTo30()`: Ensures the error code defaults to 30.
 *      - `testPreviousExceptionIsSet()`: Confirms previous exception linkage.
 *
 *  • Importance
 *      Validates predictable exception behavior for authentication failures,
 *      enabling secure handling in client applications.
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

// Import PHPUnit framework for unit testing
use ZTeraDB\Exceptions\ZTeraDBAuthError;

// Import the base ZTeraDB exception class
use ZTeraDB\Exceptions\ZTeraDBExceptions;


/**
 * Class ZTeraDBAuthErrorTest
 *
 * Unit tests for the ZTeraDBAuthError exception.
 * Validates behavior for authentication-related failures in ZTeraDB.
 */
class ZTeraDBAuthErrorTest extends TestCase
{
    /**
     * Ensure the exception is an instance of ZTeraDBExceptions
     */
    public function testIsZTeraDBException()
    {
        $exception = new ZTeraDBAuthError("Authentication failed");

        // Assert it is an instance of the base ZTeraDB exception
        $this->assertInstanceOf(ZTeraDBExceptions::class, $exception);
    }

    /**
     * Verify that the exception message is correctly set
     */
    public function testMessageIsSetCorrectly()
    {
        $message = "Invalid credentials provided";
        $exception = new ZTeraDBAuthError($message);

        // Assert the message matches
        $this->assertEquals($message, $exception->getMessage());
    }

    /**
     * Confirm that the default exception code is 30
     */
    public function testCodeIsSetTo30()
    {
        $exception = new ZTeraDBAuthError("Authentication failed");

        // Assert the code is 30
        $this->assertEquals(30, $exception->getCode());
    }

    /**
     * Ensure the previous exception is correctly assigned
     */
    public function testPreviousExceptionIsSet()
    {
        $previous = new Exception("Underlying auth system error");
        $exception = new ZTeraDBAuthError("Authentication failed", $previous);

        // Assert the previous exception matches
        $this->assertSame($previous, $exception->getPrevious());
    }
}
?>