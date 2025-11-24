<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB QueryError Unit Test Suite
 * --------------------------------------------------------------------------
 *
 *  This test suite validates the `QueryError` exception class, which
 *  represents errors that occur during query execution in ZTeraDB.
 *
 *  • Purpose
 *      - Ensure that `QueryError` correctly inherits from
 *        `ZTeraDBExceptions`.
 *      - Verify that the exception message, code, and previous exception
 *        values are correctly initialized and retrievable.
 *
 *  • Test Coverage
 *      - `testIsZTeraDBException()`: Confirms inheritance from ZTeraDBExceptions.
 *      - `testMessageIsSetCorrectly()`: Verifies the exception message.
 *      - `testCodeIsSetTo90()`: Ensures the error code defaults to 90.
 *      - `testPreviousExceptionIsSet()`: Confirms previous exception linkage.
 *
 *  • Importance
 *      Validates predictable exception behavior for query errors, allowing
 *      safe try-catch handling in ZTeraDB clients.
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

// Import QueryError exception class
use ZTeraDB\Exceptions\QueryError;

// Import the base ZTeraDB exception class
use ZTeraDB\Exceptions\ZTeraDBExceptions;


/**
 * Class QueryErrorTest
 *
 * Unit tests for the QueryError exception.
 * Validates behavior for query execution errors.
 */
class QueryErrorTest extends TestCase
{
    /**
     * Ensure the exception is an instance of ZTeraDBExceptions
     */
    public function testIsZTeraDBException()
    {
        $exception = new QueryError("Query execution failed");

        // Assert it is an instance of ZTeraDBExceptions
        $this->assertInstanceOf(ZTeraDBExceptions::class, $exception);
    }

    /**
     * Verify that the exception message is correctly set
     */
    public function testMessageIsSetCorrectly()
    {
        $message = "Malformed query syntax";
        $exception = new QueryError($message);

        // Assert the message matches
        $this->assertEquals($message, $exception->getMessage());
    }

    /**
     * Confirm that the default exception code is 90
     */
    public function testCodeIsSetTo90()
    {
        $exception = new QueryError("Query execution failed");

        // Assert it is an instance of ZTeraDBExceptions
        $this->assertEquals(90, $exception->getCode());
    }

    /**
     * Ensure the previous exception is correctly assigned
     */
    public function testPreviousExceptionIsSet()
    {
        $previous = new Exception("Underlying DB error");
        $exception = new QueryError("Query execution failed", $previous);

        // Assert the previous exception matches
        $this->assertSame($previous, $exception->getPrevious());
    }
}
?>