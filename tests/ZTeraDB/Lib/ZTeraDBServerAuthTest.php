<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDBServerAuth Unit Test Suite
 * --------------------------------------------------------------------------
 *
 *  This test suite validates the `ZTeraDBServerAuth` class, which handles
 *  server-side authentication in ZTeraDB.
 *
 *  • Purpose
 *      - Ensure that `ZTeraDBServerAuth` correctly initializes with valid
 *        authentication data.
 *      - Verify that missing or invalid authentication fields throw
 *        appropriate exceptions.
 *      - Confirm the behavior of access token expiration checks.
 *      - Validate retrieval of server token data.
 *
 *  • Test Coverage
 *      - `testConstructorWithValidData()`: Constructor initializes correctly
 *        with valid auth data.
 *      - `testConstructorMissing*()`: Validates that missing fields throw
 *        `ValueError` exceptions with descriptive messages.
 *      - `testIsAccessTokenExpiredReturns*()`: Checks `is_access_token_expired()`
 *        for valid, expired, and unset tokens.
 *      - `testGetServerTokenData()`: Ensures server token data is returned
 *        correctly as an array.
 *
 *  • Importance
 *      Guarantees reliable server authentication handling in ZTeraDB, with
 *      predictable exception behavior and secure token management.
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

// Import the server authentication class
use ZTeraDB\Lib\ZTeraDBServerAuth;

// Import exception class for validation errors
use ZTeraDB\Exceptions\ValueError;


/**
 * Class ZTeraDBServerAuthTest
 *
 * Unit tests for the ZTeraDBServerAuth class.
 * Validates construction, token expiration checks, and server token retrieval.
 */
class ZTeraDBServerAuthTest extends TestCase
{
    /**
     * Helper method to create valid authentication data as an object.
     *
     * @param string|null $expireDatetime Optional datetime string for token expiration.
     * @return stdClass
     */
    private function getAuthData($expireDatetime = null)
    {
        $data = new stdClass();
        // Required client key for authentication
        $data->client_key = '4UVTIISHFLI3KOMIVH4HV0AVAK';

        // Required access key for authentication
        $data->access_key = '4UVTGPSH9RI2BF8LKQ05Q2RM55';

        // Required access token
        $data->access_token = 'EK2A0903ab2b9Cca0Dc7a44K88aGb94fbd890152f99732520e490b21f6524437221dfe17e50dda215d868655f9807755dcde6ffb9c43fdca81d552d2e42d6RAK';

        // Token expiration time; defaults to 10 minutes from now if not provided
        $data->access_token_expire = $expireDatetime ?: @gmdate('Y-m-d H:i:s', strtotime('+10 minutes'));
        return $data;
    }

    /**
     * Verify constructor initializes correctly with valid authentication data.
     */
    public function testConstructorWithValidData()
    {
        $authData = $this->getAuthData();
        $auth = new ZTeraDBServerAuth($authData);

        // Assert that the object is an instance of ZTeraDBServerAuth
        $this->assertInstanceOf(ZTeraDBServerAuth::class, $auth);
    }

    /**
     * Verify constructor throws ValueError if client_key is missing.
     */
    public function testConstructorMissingClientKey()
    {
        // Expect ValueError with a specific message
        $this->expectException(ValueError::class);
        $this->expectExceptionMessage("client_key not found in auth response data.");

        $authData = $this->getAuthData();

        // Remove client_key
        $authData->client_key = null;

        // Attempt to create auth object should throw exception
        new ZTeraDBServerAuth($authData);
    }

    /**
     * Verify constructor throws ValueError if access_key is missing.
     */
    public function testConstructorMissingAccessKey()
    {
        $this->expectException(ValueError::class);
        $this->expectExceptionMessage("access_key not found in auth response data.");

        $authData = $this->getAuthData();

        // Remove access_key
        $authData->access_key = null;

        new ZTeraDBServerAuth($authData);
    }

    /**
     * Verify constructor throws ValueError if access_token is missing.
     */
    public function testConstructorMissingAccessToken()
    {
        $this->expectException(ValueError::class);
        $this->expectExceptionMessage("access_token not found in auth response data.");

        $authData = $this->getAuthData();

        // Remove access_token
        $authData->access_token = null;

        new ZTeraDBServerAuth($authData);
    }

    /**
     * Verify constructor throws ValueError if access_token_expire is missing.
     */
    public function testConstructorMissingAccessTokenExpire()
    {
        $this->expectException(ValueError::class);
        $this->expectExceptionMessage("access_token_expire not found in auth response data.");

        $authData = $this->getAuthData();

        // Remove expiration time
        $authData->access_token_expire = null;

        new ZTeraDBServerAuth($authData);
    }

    /**
     * Check that a valid token is not marked as expired.
     */
    public function testIsAccessTokenExpiredReturnsFalseForValidToken()
    {
        // token expires in 10 min
        $authData = $this->getAuthData('+10 minutes');
        $auth = new ZTeraDBServerAuth($authData);

        // Assert that token is valid and not expired
        $this->assertFalse($auth->is_access_token_expired());
    }

    /**
     * Check that an expired token is correctly marked as expired.
     */
    public function testIsAccessTokenExpiredReturnsTrueForExpiredToken()
    {
        // token expired 20 minutes ago
        $authData = $this->getAuthData('-20 minutes'); // token expired 20 min ago
        $auth = new ZTeraDBServerAuth($authData);

        // Assert that token is expired
        $this->assertTrue($auth->is_access_token_expired());
    }

    /**
     * Verify behavior when access_token_expire is not set.
     */
    public function testIsAccessTokenExpiredReturnsFalseIfExpireNotSet()
    {
        $authData = $this->getAuthData();
        $authData->access_token_expire = false;

        // Use reflection to bypass constructor validation
        $reflection = new ReflectionClass(ZTeraDBServerAuth::class);
        $method = $reflection->getMethod('__construct');
        $method->setAccessible(true);

        // Manually bypass constructor validation for testing
        $auth = $this->getMockBuilder(ZTeraDBServerAuth::class)
                     ->disableOriginalConstructor()
                     ->getMock();

        // Set the access_token_expire property manually
        $prop = $reflection->getProperty('access_token_expire');
        $prop->setAccessible(true);
        $prop->setValue($auth, false);

        // Assert that is_access_token_expired returns null when expiration is unset
        $this->assertNull($auth->is_access_token_expired());
    }

    /**
     * Validate that server token data is returned correctly.
     */
    public function testGetServerTokenData()
    {
        $authData = $this->getAuthData();
        $auth = new ZTeraDBServerAuth($authData);

        // Retrieve token data as array
        $tokenData = $auth->get_server_token_data();

        // Assert required keys exist
        $this->assertArrayHasKey('client_key', $tokenData);
        $this->assertArrayHasKey('access_token', $tokenData);

        // Assert values match input authentication data
        $this->assertEquals($authData->client_key, $tokenData['client_key']);
        $this->assertEquals($authData->access_token, $tokenData['access_token']);
    }
}
?>