<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB RequestTypes Unit Test Suite
 * --------------------------------------------------------------------------
 *
 *  This test suite verifies the `RequestTypes` class constants to ensure that
 *  all request type identifiers are correctly defined and accessible.
 *
 *  • Purpose
 *      Ensures that the enumerated request type values used by the ZTeraDB
 *      client for API operations are accurate and consistent.
 *
 *  • Test Coverage
 *      - `testRequestTypeValues()`
 *          Confirms that all request types return the expected numeric or string values.
 *
 *      - `testAllPropertiesExist()`
 *          Verifies that all expected properties are defined in the `RequestTypes` class.
 *
 *  • Importance
 *      These tests prevent misconfigured API requests and ensure that internal
 *      request type constants remain consistent across releases.
 *
 * --------------------------------------------------------------------------
 *  @package     ZTeraDB\Tests
 *  @version     1.0
 *  @author      ZTeraDB
 *  @license     https://zteradb.com/licence   (SPDX-License-Identifier: Proprietary)
 * --------------------------------------------------------------------------
 */

// Import PHPUnit TestCase for writing unit tests.
use PHPUnit\Framework\TestCase;

// Import RequestTypes for defining or validating
use ZTeraDB\Lib\RequestTypes;

/**
 * Class RequestTypesTest
 *
 * Contains unit tests for validating the constants defined in RequestTypes.
 */
class RequestTypesTest extends TestCase
{
    /**
     * Test that each request type constant has the correct predefined value.
     */
    public function testRequestTypeValues()
    {
        // Check that each numeric or string constant is exactly as expected.
        $this->assertSame(0x001, RequestTypes::$CONNECT);
        $this->assertSame(0x003, RequestTypes::$DISCONNECT);
        $this->assertSame(0x005, RequestTypes::$QUERY);
        $this->assertSame(0x007, RequestTypes::$PING);
        $this->assertSame(0x008, RequestTypes::$CREATE_SCHEMA);
        $this->assertSame(0x009, RequestTypes::$PUBLISH_SCHEMA);
        $this->assertSame(0x010, RequestTypes::$DATABASE);
        $this->assertSame(0x011, RequestTypes::$ACTIVE_DATABASE);
        $this->assertSame(0x012, RequestTypes::$SCHEMA);
        $this->assertSame(0x013, RequestTypes::$SCHEMA_FIELDS);
        $this->assertSame(0x014, RequestTypes::$SCHEMA_RELATED);
        $this->assertSame(0x015, RequestTypes::$SCHEMA_ACCESS);
        $this->assertSame(0x016, RequestTypes::$DATABASE_ACCESS);
        $this->assertSame(0x017, RequestTypes::$ENTERPRISE_USER);
        $this->assertSame(0x018, RequestTypes::$ROLE);
        $this->assertSame(0x019, RequestTypes::$ACCESS_CONTROL);
        $this->assertSame(0x020, RequestTypes::$ZTERADB_INSTANCE);
        $this->assertSame(0x021, RequestTypes::$ZTERADB_ENTERPRISE_INSTANCE);
        $this->assertSame(0x022, RequestTypes::$ZTERADB_ENTERPRISE_INSTANCE_GROUP);
        $this->assertSame(0x023, RequestTypes::$ENTERPRISE_INSTANCE);
        $this->assertSame(0x024, RequestTypes::$CREDENTIALS);
        $this->assertSame(0x025, RequestTypes::$USER_PROFILE);
        $this->assertSame("", RequestTypes::$NONE);
    }

    /**
     * Test that all expected properties exist in the RequestTypes class.
     */
    public function testAllPropertiesExist()
    {
        // List of all expected property names.
        $properties = [
            'CONNECT', 'DISCONNECT', 'QUERY', 'PING', 'CREATE_SCHEMA',
            'PUBLISH_SCHEMA', 'DATABASE', 'ACTIVE_DATABASE', 'SCHEMA', 
            'SCHEMA_FIELDS', 'SCHEMA_RELATED', 'SCHEMA_ACCESS', 
            'DATABASE_ACCESS', 'ENTERPRISE_USER', 'ROLE', 'ACCESS_CONTROL',
            'ZTERADB_INSTANCE', 'ZTERADB_ENTERPRISE_INSTANCE', 
            'ZTERADB_ENTERPRISE_INSTANCE_GROUP', 'ENTERPRISE_INSTANCE',
            'CREDENTIALS', 'USER_PROFILE', 'NONE'
        ];

        // Assert that each property is defined in the RequestTypes class.
        foreach ($properties as $property) {
            $this->assertTrue(
                property_exists(RequestTypes::class, $property), 
                "Property $property does not exist");
        }
    }
}
?>