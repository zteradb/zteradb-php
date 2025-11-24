<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB Exceptions Unit Test Suite
 * --------------------------------------------------------------------------
 *
 *  This test suite validates all custom ZTeraDB exception classes, ensuring
 *  proper inheritance, message handling, and error codes.
 *
 *  • Purpose
 *      - Ensure that all ZTeraDB exceptions inherit from the base
 *        `ZTeraDBExceptions` class.
 *      - Verify that exception messages, error codes, and previous exceptions
 *        are correctly initialized.
 *
 *  • Covered Exceptions
 *      - ConnectionError
 *      - JSONParseError
 *      - NoResponseDataError
 *      - ProtocolError
 *      - QueryComplete
 *      - QueryError
 *      - ValueError
 *      - ZTeraDBAuthError
 *      - Base ZTeraDBExceptions
 *
 *  • Importance
 *      Provides a single suite to validate predictable exception behavior
 *      throughout ZTeraDB, supporting robust error handling in applications.
 *
 * --------------------------------------------------------------------------
 *  @package     ZTeraDB\Tests
 *  @version     1.0
 *  @author      ZTeraDB
 *  @license     https://zteradb.com/licence (SPDX-License-Identifier: Proprietary)
 * --------------------------------------------------------------------------
 */

// Import PHPUnit for unit testing
use PHPUnit\Framework\TestCase;

// Import all ZTeraDB exception classes to be tested
use ZTeraDB\Exceptions\ConnectionError;
use ZTeraDB\Exceptions\JSONParseError;
use ZTeraDB\Exceptions\NoResponseDataError;
use ZTeraDB\Exceptions\ProtocolError;
use ZTeraDB\Exceptions\QueryComplete;
use ZTeraDB\Exceptions\QueryError;
use ZTeraDB\Exceptions\ValueError;
use ZTeraDB\Exceptions\ZTeraDBAuthError;
use ZTeraDB\Exceptions\ZTeraDBExceptions;


/**
 * Class ZTeraDBExceptionsTest
 *
 * Comprehensive tests for ZTeraDB exception classes.
 * Verifies inheritance, messages, and error codes.
 */
class ZTeraDBExceptionsTest extends TestCase
{
    /**
     * Class ZTeraDBExceptionsTest
     *
     * Comprehensive tests for ZTeraDB exception classes.
     * Verifies inheritance, messages, and error codes.
     */
    public function testConnectionError()
    {
        $exception = new ConnectionError("Connection failed");

        // Must inherit from ZTeraDBExceptions
        $this->assertInstanceOf(ZTeraDBExceptions::class, $exception);

        // Validate message and code
        $this->assertEquals("Connection failed", $exception->getMessage());
        $this->assertEquals(10, $exception->getCode());
    }

    /**
     * Test JSONParseError exception
     */
    public function testJSONParseError()
    {
        $exception = new JSONParseError("JSON parsing failed");
        $this->assertInstanceOf(ZTeraDBExceptions::class, $exception);

        // Validate message and code
        $this->assertEquals("JSON parsing failed", $exception->getMessage());
        $this->assertEquals(100, $exception->getCode());
    }

    /**
     * Test NoResponseDataError exception
     */
    public function testNoResponseDataError()
    {
        $exception = new NoResponseDataError("No data returned from server");
        $this->assertInstanceOf(ZTeraDBExceptions::class, $exception);
        $this->assertEquals("No data returned from server", $exception->getMessage());
        $this->assertEquals(100, $exception->getCode());
    }

    /**
     * Test ProtocolError exception
     */
    public function testProtocolError()
    {
        $exception = new ProtocolError("Protocol violation");
        $this->assertInstanceOf(ZTeraDBExceptions::class, $exception);

        // Validate message and code
        $this->assertEquals("Protocol violation", $exception->getMessage());
        $this->assertEquals(20, $exception->getCode());
    }

    /**
     * Test QueryComplete exception
     */
    public function testQueryComplete()
    {
        $exception = new QueryComplete("Query completed successfully");
        $this->assertInstanceOf(ZTeraDBExceptions::class, $exception);

        // QueryComplete does not store a message
        $this->assertEquals("", $exception->getMessage());

        // Default code is 0
        $this->assertEquals(0, $exception->getCode());
    }

    /**
     * Test QueryError exception
     */
    public function testQueryError()
    {
        $exception = new QueryError("Query execution failed");
        $this->assertInstanceOf(ZTeraDBExceptions::class, $exception);

        // Validate message and code
        $this->assertEquals("Query execution failed", $exception->getMessage());
        $this->assertEquals(90, $exception->getCode());
    }

    /**
     * Test ValueError exception
     */
    public function testValueError()
    {
        $exception = new ValueError("Invalid value provided");
        $this->assertInstanceOf(ZTeraDBExceptions::class, $exception);

        // Validate message and code
        $this->assertEquals("Invalid value provided", $exception->getMessage());
        $this->assertEquals(40, $exception->getCode());
    }

    /**
     * Test ZTeraDBAuthError exception
     */
    public function testZTeraDBAuthError()
    {
        $exception = new ZTeraDBAuthError("Authentication failed");
        $this->assertInstanceOf(ZTeraDBExceptions::class, $exception);

        // Validate message and code
        $this->assertEquals("Authentication failed", $exception->getMessage());
        $this->assertEquals(30, $exception->getCode());
    }

    /**
     * Test ZTeraDBExceptions exception
     */
    public function testBaseZTeraDBExceptions()
    {
        $exception = new ZTeraDBExceptions("Base error", 999);
        $this->assertInstanceOf(\Exception::class, $exception);

        // Validate message and code
        $this->assertEquals("Base error", $exception->getMessage());
        $this->assertEquals(999, $exception->getCode());
    }
}
?>