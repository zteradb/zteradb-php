<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB ValueError Unit Test Suite
 * --------------------------------------------------------------------------
 *
 *  This test suite validates the `ValueError` exception class, which
 *  represents errors that occur due to invalid or out-of-range values
 *  in ZTeraDB configuration or operations.
 *
 *  • Purpose
 *      - Ensure that `ValueError` correctly inherits from
 *        `ZTeraDBExceptions`.
 *      - Verify that the exception message, code, and previous exception
 *        values are correctly initialized and retrievable.
 *
 *  • Test Coverage
 *      - `testIsZTeraDBException()`: Confirms inheritance from ZTeraDBExceptions.
 *      - `testMessageIsSetCorrectly()`: Verifies the exception message.
 *      - `testCodeIsSetTo40()`: Ensures the error code defaults to 40.
 *      - `testPreviousExceptionIsSet()`: Confirms previous exception linkage.
 *
 *  • Importance
 *      Validates predictable exception behavior for value validation errors,
 *      allowing safe try-catch handling in ZTeraDB clients.
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

// Import ValueError exception class
use ZTeraDB\Exceptions\ValueError;

// Import the base ZTeraDB exception class
use ZTeraDB\Exceptions\ZTeraDBExceptions;


/**
 * Class ValueErrorTest
 *
 * Unit tests for the ValueError exception.
 * Validates behavior for invalid values in ZTeraDB.
 */
class ValueErrorTest extends TestCase
{
    /**
     * Ensure the exception is an instance of ZTeraDBExceptions
     */
    public function testIsZTeraDBException()
    {
        $exception = new ValueError("Invalid value provided");

        // Assert it is an instance of ZTeraDBExceptions
        $this->assertInstanceOf(ZTeraDBExceptions::class, $exception);
    }

    /**
     * Verify that the exception message is correctly set
     */
    public function testMessageIsSetCorrectly()
    {
        $message = "Value must be a positive integer";
        $exception = new ValueError($message);

        // Assert the message matches
        $this->assertEquals($message, $exception->getMessage());
    }

    /**
     * Confirm that the default exception code is 40
     */
    public function testCodeIsSetTo40()
    {
        $exception = new ValueError("Invalid value provided");

        // Assert the code is 40
        $this->assertEquals(40, $exception->getCode());
    }

    /**
     * Ensure the previous exception is correctly assigned
     */
    public function testPreviousExceptionIsSet()
    {
        $previous = new Exception("Underlying validation error");
        $exception = new ValueError("Invalid value provided", $previous);

        // Assert the previous exception matches
        $this->assertSame($previous, $exception->getPrevious());
    }
}
?>