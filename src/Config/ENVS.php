<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB ENVS Class
 * --------------------------------------------------------------------------
 *
 *  The `ENVS` class represents the allowed environments in which the ZTeraDB
 *  client may operate. It serves as a strict environment selector and validator.
 *
 *  • Purpose  
 *      Provides a controlled set of immutable environment identifiers
 *      (`dev`, `staging`, `qa`, `prod`) to ensure consistent deployment behavior
 *      across all ZTeraDB clients.
 *
 *  • Responsibilities  
 *      - Defines environment constants used throughout the client system.  
 *      - Validates any environment assigned during construction.  
 *      - Prevents misuse by restricting environments to a predefined list.  
 *
 *  • Validation Rules  
 *      - Only values explicitly listed in `ENV_LIST` are allowed.  
 *      - Attempting to initialize with an unsupported environment triggers  
 *        a `ValueError`.  
 *
 *  • Extensibility  
 *      To add a new environment:
 *        1. Add a new constant (e.g., `const sandbox = "sandbox";`).  
 *        2. Add the new constant to `ENV_LIST`.  
 *      Validation logic will automatically include it.  
 *
 * --------------------------------------------------------------------------
 *  @package     ZTeraDB\Config
 *  @version     1.0
 *  @author      ZTeraDB
 *  @license     https://zteradb.com/licence   (SPDX-License-Identifier: Proprietary)
 * --------------------------------------------------------------------------
 */
namespace ZTeraDB\Config;

// Import the ValueError exception to handle invalid environment values.
use ZTeraDB\Exceptions\ValueError as ValueError;

/**
 * ENVS
 *
 * Represents the various environments in which ZTeraDB can operate.
 *
 * Notes for Developers:
 * ---------------------
 * - Use these constants to define the environment when initializing the client.
 * - All environment values are immutable constants.
 * - Validation is enforced in the constructor to prevent invalid environment assignment.
 * - Adding a new environment requires updating both the constant and ENV_LIST.
 */
class ENVS
{
  // Development environment used for local or in-progress development.
  const dev = "dev";

  // Staging environment used for pre-production testing in a production-like setting.
  const staging = "staging";

  // Quality assurance environment for testing, bug-fixing, and validation before going live.
  const qa = "qa";

  // Production environment, where the system is deployed for use by end-users.
  const prod = "prod";

  // List of all valid environments
  const ENV_LIST = [
    self::dev,
    self::staging,
    self::qa,
    self::prod
  ];

  // Stores the current environment.
  private $env;

  /**
   * Constructor
   *
   * Initializes the ENVS object with a specific environment.
   *
   * @param string $env  One of the predefined environment constants.
   *
   * @throws ValueError  Thrown when an unsupported environment string is provided.
   *
   * Example:
   *   $env = new ENVS(ENVS::dev);
   */
  public function __construct($env)
  {
    // Validate the provided environment against the allowed list.
    // If not found, immediately throw a ValueError exception.
    if (!in_array($env, self::ENV_LIST)) {
      throw new ValueError("Invalid Env -> $env");
    }

    // Store the validated environment.
    $this->env = $env;
  }

  /**
   * Returns the environment assigned to this instance.
   *
   * @return string  The environment string (e.g., "dev", "qa").
   */
  public function get_env()
  {
    return $this->env;
  }
}
?>