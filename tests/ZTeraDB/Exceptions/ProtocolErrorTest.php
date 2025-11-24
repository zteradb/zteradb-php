<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB ProtocolError Unit Test Suite
 * --------------------------------------------------------------------------
 *
 *  This test suite validates the `ProtocolError` exception class, which
 *  represents errors occurring due to protocol violations or invalid
 *  request formats in ZTeraDB communication.
 *
 *  • Purpose
 *      - Ensure that `ProtocolError` correctly inherits from
 *        `ZTeraDBExceptions`.
 *      - Verify that message, code, and previous exception values are set
 *        and retrievable.
 *
 *  • Test Coverage
 *      - `testIsZTeraDBException()`: Confirms inheritance from ZTeraDBExceptions.
 *      - `testMessageIsSetCorrectly()`: Verifies the exception message.
 *      - `testCodeIsSetTo20()`: Ensures the error code defaults to 20.
 *      - `testPreviousExceptionIsSet()`: Confirms previous exception linkage.
 *
 *  • Importance
 *      Validates predictable exception behavior for protocol violations,
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

// Import ProtocolError exception class
use ZTeraDB\Exceptions\ProtocolError;

// Import the base ZTeraDB exception class
use ZTeraDB\Exceptions\ZTeraDBExceptions;


/**
 * Class ProtocolErrorTest
 *
 * Unit tests for the ProtocolError exception.
 * Validates correct initialization and property behavior.
 */
class ProtocolErrorTest extends TestCase
{
    /**
     * Ensure the exception is an instance of ZTeraDBExceptions
     */
    public function testIsZTeraDBException()
    {
        $exception = new ProtocolError("Protocol violation detected");

        // Assert it is an instance of ZTeraDBExceptions
        $this->assertInstanceOf(ZTeraDBExceptions::class, $exception);
    }

    /**
     * Verify that the exception message is correctly set
     */
    public function testMessageIsSetCorrectly()
    {
        $message = "Invalid request format";
        $exception = new ProtocolError($message);

        // Assert the message matches
        $this->assertEquals($message, $exception->getMessage());
    }

    /**
     * Confirm the exception code is set to 20 by default
     */
    public function testCodeIsSetTo20()
    {
        $exception = new ProtocolError("Protocol error occurred");

        // Assert the code is 20
        $this->assertEquals(20, $exception->getCode());
    }

    /**
     * Ensure the previous exception is correctly assigned
     */
    public function testPreviousExceptionIsSet()
    {
        $previous = new Exception("Underlying protocol issue");
        $exception = new ProtocolError("Protocol error occurred", $previous);

        // Assert the previous exception matches
        $this->assertSame($previous, $exception->getPrevious());
    }
}
