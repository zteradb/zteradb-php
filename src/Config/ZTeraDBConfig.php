<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB Configuration Module
 * --------------------------------------------------------------------------
 *
 *  This module provides the full configuration system for the ZTeraDB client.
 *  It includes the following components:
 *
 *  • **ENVS**  
 *      Handles environment selection and validation. Ensures the client runs
 *      only within the allowed environments: `dev`, `staging`, `qa`, or `prod`.
 *
 *  • **ResponseDataTypes**  
 *      Defines the supported data formats for API responses  
 *      (currently: JSON).
 *
 *  • **ConnectionPool**  
 *      Represents the min/max client-side connection pool configuration.  
 *      Validation Rules:
 *        - `min ≥ 0`  
 *        - `max ≥ 0`  
 *        - `min ≤ max` (unless `max = 0` for unlimited connections)
 *
 *      Notes:
 *        - ConnectionPool **does not manage its own lifecycle**.
 *        - It **does not apply defaults**.
 *        - Its sole responsibility is *validating values on construction*.
 *
 *  • **Options** (Detailed)  
 *      Encapsulates optional settings for the ZTeraDB client instance.
 *      Responsibilities include:
 *
 *        - Managing and validating the connection pool configuration.
 *        - Accepting either:
 *            a) A `ConnectionPool` instance  
 *            b) An associative array: `["min" => int, "max" => int]`
 *        - Automatically constructing a `ConnectionPool` instance when an array
 *          is supplied.
 *        - Validating all nested options using `is_valid()` prior to use.
 *
 *      **Default Behavior:**  
 *        - If no options are provided, a default  
 *          `ConnectionPool(min=1, max=1)` is created, ensuring the client is
 *          always initialized with a safe and valid pool.
 *
 *      **Purpose:**  
 *        The `Options` class is designed for extensibility, allowing additional
 *        optional settings—timeouts, retry policies, caching flags, etc.—to be
 *        added without modifying core configuration logic.
 *
 *  • **ZTeraDBConfig**  
 *      The final configuration container used by the ZTeraDB client.  
 *      Validates:
 *         - `client_key`              (string)
 *         - `access_key`              (string)
 *         - `secret_key`              (string)
 *         - `database_id`             (string)
 *         - `env`                     (string or ENVS instance)
 *         - `use_tls`                 (bool)
 *         - `verify_tls_host`         (bool)
 *         - `response_data_type`      (ResponseDataTypes instance)
 *         - `options`                 (Options instance)
 *
 *      Ensures all configuration parameters are type-safe and complete before
 *      the client is initialized.
 *
 * --------------------------------------------------------------------------
 *  @package     ZTeraDB
 *  @version     1.0
 *  @author      ZTera
 *  @license     https://zteradb.com/licence   (SPDX-License-Identifier: Proprietary)
 * --------------------------------------------------------------------------
 */

namespace ZTeraDB\Config;

use ZTeraDB\Exceptions\ValueError as ValueError;


/**
 * ZTeraDBConfig
 *
 * Represents the configuration for connecting to a ZTeraDB instance.
 *
 * Notes for Developers:
 * ---------------------
 * - Validates all required credentials: `client_key`, `access_key`, `secret_key`, and `database_id`.
 * - Optional flags `use_tls` and `verify_tls_host` default to `false` if not provided.
 * - Environment (`env`) can be provided as a string or an instance of `ENVS`.
 * - Response data type must be an instance of `ResponseDataTypes`.
 * - Options must be an instance of `Options`; if not provided, a default connection pool (1 min, 1 max) is created.
 * - All validations throw `ValueError` for missing keys or invalid types.
 *
 * Example Usage:
 * --------------
 *   $config = new ZTeraDBConfig([
 *       'client_key' => 'my_client_key',
 *       'access_key' => 'my_access_key',
 *       'secret_key' => 'my_secret_key',
 *       'database_id' => 'db_123',
 *       'env' => 'dev',
 *       'response_data_type' => new ResponseDataTypes(ResponseDataTypes::json),
 *       'options' => new Options(['connection_pool' => ['min' => 1, 'max' => 5]])
 *   ]);
 */
class ZTeraDBConfig
{
  // Client key used to authenticate with ZTeraDB
  public $client_key;

  // Access key used to authenticate with ZTeraDB
  public $access_key;

  // Secret key used for secure authentication
  public $secret_key;

  // Database identifier
  public $database_id;

  // Enable TLS for connections (default false)
  public $use_tls;

  // Verify TLS host (default false)
  public $verify_tls_host;

  // Environment (ENVS instance)
  public $env;

  // Response data type (ResponseDataTypes instance)
  public $response_data_type;

  // Additional options, e.g., connection pool
  public $options;

  /**
   * Constructor
   *
   * @param array $zteradb_conf Configuration array for ZTeraDB connection.
   *
   * @throws ValueError If required keys are missing or types are invalid.
   */
  public function __construct($zteradb_conf)
  {
    // Validate required string keys
    $this->client_key = $this->validateStringKey($zteradb_conf, 'client_key');
    $this->access_key = $this->validateStringKey($zteradb_conf, 'access_key');
    $this->secret_key = $this->validateStringKey($zteradb_conf, 'secret_key');
    $this->database_id = $this->validateStringKey($zteradb_conf, 'database_id');

    // Validate optional boolean keys with default values
    $this->use_tls = $this->validateBoolKey($zteradb_conf, 'use_tls', false);
    $this->verify_tls_host = $this->validateBoolKey($zteradb_conf, 'verify_tls_host', false);

    // Validate and assign environment
    $this->env = $this->validateEnv($zteradb_conf);

    // Validate response data type
    if (!array_key_exists('response_data_type', $zteradb_conf)) {
      throw new ValueError("Missing key: 'response_data_type' is required.");
    }

    // If response_data_type is a string, create a new ResponseDataTypes instance
    if (is_string($zteradb_conf['response_data_type'])) {
      $this->response_data_type = new ResponseDataTypes($zteradb_conf['response_data_type']);
    }
    // If it's neither a string nor a ResponseDataTypes instance, throw an error
    else if (!$zteradb_conf['response_data_type'] instanceof ResponseDataTypes) {
      throw new ValueError("Invalid 'response_data_type': Must be an instance of ResponseDataTypes.");
    }
    // If it's already a ResponseDataTypes instance, assign it directly
    else {
      $this->response_data_type = $zteradb_conf['response_data_type'];
    }

    // Check if 'options' key exists in the configuration
    if (array_key_exists('options', $zteradb_conf)) {
      // Throw error if 'options' is not an instance of Options
      if (!$zteradb_conf['options'] instanceof Options) {
        throw new ValueError("Invalid 'options': Must be an instance of Options.");
      }

      // Validate the Options object
      $zteradb_conf['options']->is_valid();

      // Assign the validated Options object
      $this->options = $zteradb_conf['options'];
    }
    // If 'options' is not provided, create a default Options object with a 1-connection pool
    else {
      $this->options = new Options(['connection_pool' => ['min' => 1, 'max' => 1]]);
    }
  }

  /**
   * Validate a required string key in the configuration array.
   *
   * Ensures that the specified key exists and its value is a string.
   * Throws ValueError if the key is missing or the value is not a string.
   *
   * @param array  $config Configuration array to check.
   * @param string $key    Key to validate.
   * @return string        The validated string value.
   *
   * Example:
   *   $this->client_key = $this->validateStringKey($config, 'client_key');
   */
  private function validateStringKey($config, $key)
  {
    // If the required key exists in the configuration array
    if (!array_key_exists($key, $config)) {
      // Throw an error if the key is missing
      throw new ValueError("Missing key: '$key' is required.");
    }

    // If the value for the key is a string
    if (!is_string($config[$key])) {
      // Throw an error if the value is not a string
      throw new ValueError("Invalid '$key': '$key' must be a string.");
    }

    // Return the validated string value for the key
    return $config[$key];
  }

  /**
   * Validate an optional boolean key in the configuration array.
   *
   * Checks if the key exists. If it does, ensures the value is boolean.
   * Returns the default value if the key does not exist.
   * Throws ValueError if the key exists but is not a boolean.
   *
   * @param array  $config  Configuration array to check.
   * @param string $key     Key to validate.
   * @param bool   $default Default value if the key is not present.
   * @return bool           Validated boolean value.
   *
   * Example:
   *   $this->use_tls = $this->validateBoolKey($config, 'use_tls', false);
   */
  private function validateBoolKey($config, $key, $default)
  {
    // If the key exists in the configuration array
    if (array_key_exists($key, $config)) {

      // Ensure the value is a boolean
      if (!is_bool($config[$key])) {

        // Throw an error if the value is not a boolean
        throw new ValueError("Invalid '$key': '$key' must be a boolean.");
      }

      // Return the validated boolean value
      return $config[$key];
    }

    // If the key does not exist, return the default value
    return $default;
  }

  /**
   * Validate the environment configuration.
   *
   * Ensures the 'env' key exists. If a string is provided, creates a new ENVS instance.
   * If an ENVS instance is provided, returns it directly.
   * Throws ValueError if the value is neither a string nor an ENVS instance.
   *
   * @param array $config Configuration array to check.
   * @return ENVS         Validated environment instance.
   *
   * Example:
   *   $this->env = $this->validateEnv($config);
   */
  private function validateEnv($config)
  {
    // Throw error if 'env' key is missing
    if (!array_key_exists('env', $config)) {
      throw new ValueError("Missing key: 'env' is required.");
    }

    // Get the value of the 'env' key
    $env = $config['env'];

    // If 'env' is a string, create a new ENVS instance and return it.
    if (is_string($env)) {
      return new ENVS($env);
    }
    // If 'env' is not an ENVS instance, throw an error
    else if (!$env instanceof ENVS) {
      throw new ValueError("Invalid 'env': Must be either a string or an instance of ENVS.");
    }

    // Return the validated ENVS instance
    return $env;
  }
}


class ZTeraDBConfigDep
{
  public function __construct($zteradb_conf)
  {
    if (!array_key_exists("client_key", $zteradb_conf)) {
      throw new ValueError("Missing key client_key");
    } else if (!is_string($zteradb_conf["client_key"])) {
      throw new ValueError("client_key must be string");
    }

    $this->client_key = $zteradb_conf["client_key"];

    if (!array_key_exists("access_key", $zteradb_conf)) {
      throw new ValueError("Missing key access_key");
    } else if (!is_string($zteradb_conf["access_key"])) {
      throw new ValueError("access_key must be string");
    }

    $this->access_key = $zteradb_conf["access_key"];

    if (!array_key_exists("secret_key", $zteradb_conf)) {
      throw new ValueError("Missing key secret_key");
    } else if (!is_string($zteradb_conf["secret_key"])) {
      throw new ValueError("secret_key must be string");
    }

    $this->secret_key = $zteradb_conf["secret_key"];

    if (!array_key_exists("database_id", $zteradb_conf)) {
      throw new ValueError("Missing key database_id");
    } else if (!is_string($zteradb_conf["database_id"])) {
      throw new ValueError("database_id must be string");
    }

    $this->database_id = $zteradb_conf["database_id"];

    if (array_key_exists("use_tls", $zteradb_conf)) {
      if (!is_bool($zteradb_conf["use_tls"])) {
        throw new ValueError("use_tls must be boolean.");
      }

      $this->use_tls = $zteradb_conf["use_tls"];
    } else {
      $this->use_tls = false;
    }

    if (array_key_exists("verify_tls_host", $zteradb_conf)) {
      if (!is_bool($zteradb_conf["verify_tls_host"])) {
        throw new ValueError("verify_tls_host must be boolean.");
      }

      $this->verify_tls_host = $zteradb_conf["verify_tls_host"];
    } else {
      $this->verify_tls_host = false;
    }

    if (!array_key_exists("env", $zteradb_conf)) {
      throw new ValueError("Missing key env");
    } else {
      if (is_string($zteradb_conf["env"])) {
        $this->env = new ENVS($zteradb_conf["env"]);
      } else if (!$zteradb_conf["env"] instanceof ENVS) {
        throw new ValueError("env must be either string or an instance of ENVS.");
      } else {
        $this->env = $zteradb_conf["env"];
      }
    }

    if (!array_key_exists("response_data_type", $zteradb_conf)) {
      throw new ValueError("Missing key response_data_type");
    }

    if (array_key_exists("options", $zteradb_conf)) {
      if (!$zteradb_conf["options"] instanceof Options) {
        throw new ValueError("options must be an instance of Options");
      }

      $zteradb_conf["options"]->is_valid();
      $this->options = $zteradb_conf["options"];
    } else {
      $this->options = array("connection_pool" => new ConnectionPool(1, 1));
    }

    if (!array_key_exists("response_data_type", $zteradb_conf)) {
      throw new ValueError("Missing response_data_type key");
    } else if (!$zteradb_conf["response_data_type"] instanceof ResponseDataTypes) {
      throw new ValueError("response_data_type must be an instance of ResponseDataTypes");
    }

    $this->response_data_type = $zteradb_conf["response_data_type"];
  }
}
?>