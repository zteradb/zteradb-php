<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB ZTeraDBConfig Unit Test Suite
 * --------------------------------------------------------------------------
 *
 *  This test suite validates the `ZTeraDBConfig` class, ensuring that all
 *  required configuration fields are supplied, type-checked, and validated
 *  according to ZTeraDB client rules.
 *
 *  • Purpose  
 *      Confirms that configuration initialization behaves correctly across
 *      valid, missing, and invalid parameter scenarios. Ensures that ZTeraDB
 *      clients are always constructed with safe, consistent, and valid settings.
 *
 *  • Test Coverage  
 *      - Successful configuration creation  
 *      - Missing required configuration keys  
 *      - Boolean validation (e.g., `use_tls`, `verify_tls_host`)  
 *      - Environment validation (string or ENVS instance)  
 *      - ResponseDataType validation  
 *      - Options instance validation  
 *
 *  • Importance  
 *      This suite provides a strict safety net, preventing invalid or unsafe
 *      configurations from reaching the core ZTeraDB client execution path.
 *      Ensures correctness and consistency across all dependent systems.
 *
 * --------------------------------------------------------------------------
 *  @package     ZTeraDB\Tests
 *  @version     1.0
 *  @autor       ZTeraDB
 *  @license     https://zteradb.com/licence   (SPDX-License-Identifier: Proprietary)
 * --------------------------------------------------------------------------
 */

// Import the PHPUnit TestCase class.
use PHPUnit\Framework\TestCase;

// Import all configuration-related classes under test.
use ZTeraDB\Config\ZTeraDBConfig;
use ZTeraDB\Config\ENVS;
use ZTeraDB\Config\ResponseDataTypes;
use ZTeraDB\Config\Options;

// Import ValueError for expected exception assertions.
use ZTeraDB\Exceptions\ValueError;


/**
 * Class ZTeraDBConfig_Test
 *
 * Contains unit tests verifying proper validation and behavior of the
 * ZTeraDBConfig configuration container.
 */
class ZTeraDBConfig_Test extends TestCase {
  /**
   * Returns a minimal, valid configuration array used as a base for tests.
   *
   * @return array
   */
  private function baseConfig() {
    return [
      "client_key" => "ck",
      "access_key" => "ak",
      "secret_key" => "sk",
      "database_id" => "db1",
      "env" => ENVS::dev,
      "response_data_type" => new ResponseDataTypes(ResponseDataTypes::json)
    ];
  }
  
  /**
   * Ensures that a valid configuration array successfully constructs
   * a ZTeraDBConfig instance without errors.
   */
  public function testCreatesConfigSuccessfully() {
    // Create a config object using valid base settings.
    $conf = new ZTeraDBConfig($this->baseConfig());

    // Assert required fields are stored correctly.
    $this->assertEquals("ck", $conf->client_key);

    // Environment must be converted to an ENVS instance.
    $this->assertInstanceOf(ENVS::class, $conf->env);
  }

  /**
   * Ensures missing required configuration keys trigger a ValueError.
   */
  public function testMissingRequiredKey() {
    // Expect ValueError exception during config construction.
    $this->setExpectedException(ValueError::class);

    // Remove a required field.
    $conf = $this->baseConfig();
    unset($conf["client_key"]);

    // Constructing the config must throw an exception.
    new ZTeraDBConfig($conf);
  }

  /**
   * Ensures that boolean fields (such as use_tls) must be real booleans.
   */
  public function testInvalidBooleanOption() {
    // Expect ValueError for invalid boolean.
    $this->setExpectedException(ValueError::class);
    
    $conf = $this->baseConfig();

    // Supply an invalid (non-boolean) value.
    $conf["use_tls"] = "not_bool";

    // Should fail validation.
    new ZTeraDBConfig($conf);
  }

  /**
   * Ensures that invalid environment values result in a ValueError.
   */
  public function testInvalidEnv() {
    $this->setExpectedException(ValueError::class);
    
    $conf = $this->baseConfig();

    // Provide an invalid type for environment.
    $conf["env"] = 999;
    
    new ZTeraDBConfig($conf);
  }

  /**
   * Ensures that an invalid ResponseDataTypes value results in a ValueError.
   */
  public function testInvalidResponseDataType() {
    $this->setExpectedException(ValueError::class);
    
    $conf = $this->baseConfig();

    // Provide invalid response format string.
    $conf["response_data_type"] = "xml";
    
    new ZTeraDBConfig($conf);
  }

  /**
   * Ensures a valid Options instance is accepted and stored.
   */
  public function testValidOptions() {
    // Create a valid Options object.
    $opt = new Options(["connection_pool" => ["min" => 1, "max" => 2]]);

    // Attach it to the base config.
    $conf = $this->baseConfig();
    $conf["options"] = $opt;

    // Construct ZTeraDBConfig successfully.
    $c = new ZTeraDBConfig($conf);

    // The resulting options must be a valid instance.
    $this->assertInstanceOf(Options::class, $c->options);
  }

  /**
   * Ensures an invalid options type triggers a ValueError.
   */
  public function testInvalidOptions() {
    $this->setExpectedException(ValueError::class);

    $conf = $this->baseConfig();

    // Supply invalid options type (must be Options instance).
    $conf["options"] = "invalid";

    new ZTeraDBConfig($conf);
  }
}
?>