<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB QueryComplete Unit Test Suite
 * --------------------------------------------------------------------------
 *
 *  This test suite validates the `QueryComplete` exception class, which
 *  represents a non-error scenario indicating that a query has finished
 *  successfully in ZTeraDB.
 *
 *  • Purpose
 *      - Ensure that `QueryComplete` correctly inherits from
 *        `ZTeraDBExceptions`.
 *      - Verify that message, code, and previous exception values are
 *        handled as expected for a "successful completion" exception.
 *
 *  • Test Coverage
 *      - `testIsZTeraDBException()`: Confirms inheritance from ZTeraDBExceptions.
 *      - `testMessageIsSetCorrectly()`: Ensures the exception message is empty.
 *      - `testCodeIsDefaultZero()`: Validates default code is 0.
 *      - `testPreviousExceptionIsSet()`: Confirms that previous exceptions are ignored.
 *
 *  • Importance
 *      Provides a controlled way to signal query completion without
 *      raising actual errors, enabling safe exception handling flows.
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

// Import QueryComplete exception class
use ZTeraDB\Exceptions\QueryComplete;

// Import the base ZTeraDB exception class
use ZTeraDB\Exceptions\ZTeraDBExceptions;


/**
 * Class QueryCompleteTest
 *
 * Unit tests for the QueryComplete exception.
 * Validates behavior for a "successful query completion" scenario.
 */
class QueryCompleteTest extends TestCase
{
    /**
     * Ensure the exception is an instance of ZTeraDBExceptions
     */
    public function testIsZTeraDBException()
    {
        $exception = new QueryComplete("Query finished successfully");

        // Assert it is an instance of ZTeraDBExceptions
        $this->assertInstanceOf(ZTeraDBExceptions::class, $exception);
    }

    /**
     * Verify that the exception message is empty by design
     */
    public function testMessageIsSetCorrectly()
    {
        $message = "Query finished successfully";
        $exception = new QueryComplete($message);

        // Assert message is empty string (QueryComplete intentionally has no message)
        $this->assertEquals("", $exception->getMessage());
    }

    /**
     * Confirm that previous exceptions are ignored in QueryComplete
     */
    public function testPreviousExceptionIsSet()
    {
        $previous = new Exception("Underlying exception");
        $exception = new QueryComplete("Query finished", $previous);

        // Assert previous exception is null
        $this->assertSame(null, $exception->getPrevious());
    }

    /**
     * Ensure the default exception code is 0
     */
    public function testCodeIsDefaultZero()
    {
        $exception = new QueryComplete("Query finished successfully");

        // Assert code is 0
        $this->assertEquals(0, $exception->getCode());
    }
}
?>