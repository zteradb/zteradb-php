<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB Client Authentication Unit Test Suite
 * --------------------------------------------------------------------------
 *
 *  This test suite validates the `ZTeraDBClientAuth` class, which handles
 *  authentication logic for ZTeraDB API requests.
 *
 *  • Purpose
 *      - Ensure that the authentication class can be constructed with valid
 *        configuration.
 *      - Verify that invalid configuration is properly rejected.
 *      - Test core authentication utilities:
 *          • SHA-256 hashing
 *          • Nonce generation
 *          • Request token generation
 *          • Auth request payload generation
 *
 *  • Test Coverage
 *      - `testConstructorWithValidConfig()`: Confirms class can be instantiated.
 *      - `testConstructorWithInvalidConfig()`: Ensures ValueError is thrown for invalid config.
 *      - `testGetSha()`: Validates SHA-256 hashing helper.
 *      - `testGenerateNonce()`: Confirms nonce is correctly generated.
 *      - `testGenerateRequestToken()`: Verifies request token logic.
 *      - `testGenerateAuthRequest()`: Confirms auth request payload is correct.
 *
 *  • Importance
 *      These tests ensure that ZTeraDB client requests are securely
 *      authenticated and follow a predictable, reproducible workflow.
 *
 * --------------------------------------------------------------------------
 *  @package     ZTeraDB\Tests
 *  @version     1.0
 *  @author      ZTeraDB
 *  @license     https://zteradb.com/licence (SPDX-License-Identifier: Proprietary)
 * --------------------------------------------------------------------------
 */
// Import PHPUnit TestCase for writing unit tests.
use PHPUnit\Framework\TestCase;

// Import the ZTeraDB client authentication class.
use ZTeraDB\Lib\ZTeraDBClientAuth;

// Import RequestTypes for defining or validating
use ZTeraDB\Lib\RequestTypes;

// Import ZTeraDBConfig for providing configuration to the auth class.
use ZTeraDB\Config\ZTeraDBConfig;

// Import ValueError to handle invalid configuration exceptions.
use ZTeraDB\Exceptions\ValueError;

class ZTeraDBClientAuthTest extends TestCase
{
    /**
     * Helper method to create a valid ZTeraDBConfig object.
     *
     * @return ZTeraDBConfig
     */
    private function getConfig()
    {
        $configArray = [
            'database_id'         => '4UVTJ1L0NINNBPK8J4KGKLPA2C',
            'access_key'          => '4UVTGPSH9RI2BF8LKQ05Q2RM55',
            'secret_key'          => 'fe4b4903ab2b9cca0dc7a44f88afb94fbd890152f99732520e490b21f6524437221dfe17e50dda215d868655f9807755dcde6ffb9c43fdca81d552d2e42d6aea',
            'client_key'          => '4UVTIISHFLI3KOMIVH4HV0AVAK',
            'request_token'       => 'token_123',
            'request_type'        => 'CONNECT',
            'env'                 =>  'prod',
            'response_data_type'  =>  'json'
        ];

        return new ZTeraDBConfig($configArray);
    }

    public function testConstructorWithValidConfig()
    {
        $config = $this->getConfig();
        $auth = new ZTeraDBClientAuth($config);

        $this->assertInstanceOf(ZTeraDBClientAuth::class, $auth);
    }

    public function testConstructorWithInvalidConfig()
    {
        $this->expectException(ValueError::class);
        $this->expectExceptionMessage("zteradb_config argument must be an instance of ZTeraDBConfig");

        // Passing a string instead of a ZTeraDBConfig object
        $auth = new ZTeraDBClientAuth("invalid_config");
    }

    public function testGetSha()
    {
        $hash = ZTeraDBClientAuth::get_sha('test_value');
        $this->assertEquals(hash('sha256', 'test_value'), $hash);
    }

    public function testGenerateNonce()
    {
        $config = $this->getConfig();
        $auth = new ZTeraDBClientAuth($config);

        $nonceHash = $auth->generate_nonce();
        $this->assertEquals(64, strlen($nonceHash)); // SHA-256 produces 64 hex chars
    }

    public function testGenerateRequestToken()
    {
        $config = $this->getConfig();
        $auth = new ZTeraDBClientAuth($config);

        // Manually set nonce to match test config
        $reflection = new ReflectionClass($auth);
        $nonceProp = $reflection->getProperty('nonce');
        $nonceProp->setAccessible(true);
        $nonceProp->setValue($auth, 'nonce_123');

        $token = $auth->generate_request_token();
        $expectedToken = hash('sha256', $config->secret_key . 'nonce_123');

        $this->assertEquals($expectedToken, $token);
    }

    public function testGenerateAuthRequest()
    {
        $config = $this->getConfig();
        $auth = new ZTeraDBClientAuth($config);

        $request = $auth->generate_auth_request();

        $this->assertArrayHasKey('access_key', $request);
        $this->assertArrayHasKey('client_key', $request);
        $this->assertArrayHasKey('nonce', $request);
        $this->assertArrayHasKey('request_token', $request);
        $this->assertArrayHasKey('request_type', $request);

        $this->assertEquals($config->access_key, $request['access_key']);
        $this->assertEquals($config->client_key, $request['client_key']);
        $this->assertEquals(RequestTypes::$CONNECT, $request['request_type']);
        $this->assertEquals(64, strlen($request['nonce']));
        $this->assertEquals(64, strlen($request['request_token']));
    }
}
?>