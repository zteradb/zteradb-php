<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB ResponseTypes Unit Test Suite
 * --------------------------------------------------------------------------
 *
 *  This test suite validates the `ResponseTypes` class, which defines
 *  standardized response codes used across ZTeraDB.
 *
 *  • Purpose
 *      - Ensure that all response type constants have the correct values.
 *      - Confirm that all expected properties exist in the class.
 *
 *  • Test Coverage
 *      - `testResponseTypeValues()`: Verifies that each response type constant
 *        matches its predefined numeric or string value.
 *      - `testAllPropertiesExist()`: Confirms that all expected class properties
 *        are defined.
 *
 *  • Importance
 *      Guarantees predictable response codes for ZTeraDB operations, enabling
 *      consistent client handling of database events, errors, and authentication
 *      states.
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

// Import the ResponseTypes class for validation
use ZTeraDB\Lib\ResponseTypes;


/**
 * Class ResponseTypesTest
 *
 * Unit tests for the ResponseTypes class.
 * Validates that all response type constants are correctly defined and exist.
 */
class ResponseTypesTest extends TestCase
{
    /**
     * Verify that each response type has the correct value
     */
    public function testResponseTypeValues()
    {
        $this->assertSame(0x002, ResponseTypes::$CONNECTED);
        $this->assertSame(0x500, ResponseTypes::$CONNECT_ERROR);
        $this->assertSame(0x004, ResponseTypes::$DISCONNECTED);
        $this->assertSame(0x005, ResponseTypes::$DISCONNECT_ERROR);
        $this->assertSame(0x006, ResponseTypes::$CLIENT_AUTH_ERROR);
        $this->assertSame(0x007, ResponseTypes::$QUERY_DATA);
        $this->assertSame(0x608, ResponseTypes::$QUERY_COMPLETE);
        $this->assertSame(0x09, ResponseTypes::$QUERY_ERROR);
        $this->assertSame(0x010, ResponseTypes::$PONG);
        $this->assertSame(0x100, ResponseTypes::$PARSE_QUERY_ERROR);
        $this->assertSame(0x011, ResponseTypes::$NO_ACCESS);
        $this->assertSame(0x400, ResponseTypes::$TOKEN_EXPIRED);
        $this->assertSame(0x401, ResponseTypes::$INVALID_SCHEMA);
        $this->assertSame(0x402, ResponseTypes::$FIELD_ERROR);
        $this->assertSame(0x201, ResponseTypes::$CREATE_SCHEMA_SUCCESS);
        $this->assertSame(0x501, ResponseTypes::$CREATE_SCHEMA_ERROR);
        $this->assertSame(0x202, ResponseTypes::$PUBLISH_SCHEMA_SUCCESS);
        $this->assertSame(0x502, ResponseTypes::$PUBLISH_SCHEMA_ERROR);
        $this->assertSame('', ResponseTypes::$NONE);
    }

    /**
     * Ensure all expected properties exist in the ResponseTypes class
     */
    public function testAllPropertiesExist()
    {
        $properties = [
            'CONNECTED', 'CONNECT_ERROR', 'DISCONNECTED', 'DISCONNECT_ERROR',
            'CLIENT_AUTH_ERROR', 'QUERY_DATA', 'QUERY_COMPLETE', 'QUERY_ERROR',
            'PONG', 'PARSE_QUERY_ERROR', 'NO_ACCESS', 'TOKEN_EXPIRED',
            'INVALID_SCHEMA', 'FIELD_ERROR', 'CREATE_SCHEMA_SUCCESS',
            'CREATE_SCHEMA_ERROR', 'PUBLISH_SCHEMA_SUCCESS', 'PUBLISH_SCHEMA_ERROR',
            'NONE'
        ];

        foreach ($properties as $property) {
            $this->assertTrue(property_exists(ResponseTypes::class, $property), "Property $property does not exist");
        }
    }
}
?>