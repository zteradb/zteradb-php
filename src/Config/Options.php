<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB Options Class
 * --------------------------------------------------------------------------
 *
 *  The `Options` class encapsulates optional configuration settings used by the
 *  ZTeraDB client. It provides structured handling and validation of optional
 *  parameters, ensuring each configuration element is type-safe before use.
 *
 *  • Purpose  
 *      The Options class is designed for extensibility. It currently manages
 *      the client's `ConnectionPool` configuration but can be expanded to
 *      include additional tuning parameters (timeouts, retry policies, caching
 *      flags, TLS overrides, etc.) without requiring changes to core logic.
 *
 *  • Responsibilities  
 *      - Accept and validate optional configuration entries.  
 *      - Allow `connection_pool` to be supplied as:  
 *          a) A `ConnectionPool` instance  
 *          b) An associative array containing `"min"` and `"max"` keys  
 *      - Construct a `ConnectionPool` automatically when given an array.  
 *      - Enforce strict type-checking using `ValueError` exceptions.  
 *
 *  • Behavior  
 *      - If the user provides no `connection_pool`, the property remains `null`.
 *      - If invalid types are supplied, construction immediately fails to
 *        prevent misconfigured clients from being created.
 *
 *  • Validation  
 *      The `is_valid()` method ensures the `connection_pool` property contains
 *      a valid `ConnectionPool` instance when present.
 *
 * --------------------------------------------------------------------------
 *  @package     ZTeraDB\Config
 *  @version     1.0
 *  @author      ZTeraDB
 *  @license     https://zteradb.com/licence   (SPDX-License-Identifier: Proprietary)
 * --------------------------------------------------------------------------
 */
namespace ZTeraDB\Config;

// Import the ValueError exception for validation failures.
use ZTeraDB\Exceptions\ValueError as ValueError;

/**
 * Class Options
 *
 * Initializes the Options object with optional settings.
 * Currently, it handles the "connection_pool" configuration.
 *
 * @param array $options An array of optional settings.
 *
 * Behavior:
 * - If "connection_pool" is provided in the $options array:
 *     1. If it is an instance of ConnectionPool, it is directly assigned.
 *     2. If it is an array with "min" and "max" keys, a new ConnectionPool is created.
 *     3. If it is neither, a ValueError is thrown to ensure type safety.
 *
 * Example usage:
 *   $options = new Options([
 *       "connection_pool" => new ConnectionPool(1, 5)
 *   ]);
 *
 *   OR
 *
 *   $options = new Options([
 *       "connection_pool" => ["min" => 1, "max" => 5]
 *   ]);
 *
 * @throws ValueError if "connection_pool" is not a ConnectionPool instance or a valid array
 */
class Options
{
  // Stores the connection pool configuration (or null if not provided).
  public $connection_pool;

  /**
   * Constructor
   *
   * Initializes optional settings supplied by the user.
   * 
   * @param array $options  An associative array of optional parameters.
   *
   * Example usage:
   *   $options = new Options([
   *       "connection_pool" => ["min" => 1, "max" => 5]
   *   ]);
   */
  public function __construct($options)
  {
    // Check if "connection_pool" is defined in the options array
    if (array_key_exists("connection_pool", $options)) {

      // If "connection_pool" is already a ConnectionPool instance
      if ($options["connection_pool"] instanceof ConnectionPool) {
        // Directly assign the provided ConnectionPool instance
        $this->connection_pool = $options["connection_pool"];
      }
      // If "connection_pool" is an array with "min" and "max" keys
      else if (is_array($options["connection_pool"])) {
        // Extract min/max values from the user-supplied array.
        $min = $options["connection_pool"]["min"];
        $max = $options["connection_pool"]["max"];

        // Construct a validated ConnectionPool instance.
        $this->connection_pool = new ConnectionPool($min, $max);
      }
      // Throw Invalid "connection_pool" type
      else {
        throw new ValueError("Invalid connection_pool: provide a ConnectionPool object or an array containing integer 'min' and 'max' keys.");
      }
    }
  }

  /**
   * Validate the Options object.
   *
   * Ensures that when a connection_pool exists, it is a valid ConnectionPool.
   *
   * @throws ValueError if validation fails.
   */
  public function is_valid()
  {
    // If connection_pool exists but is not a ConnectionPool instance, reject it.
    if ($this->connection_pool && !$this->connection_pool instanceof ConnectionPool) {
      throw new ValueError("Invalid 'connection_pool': must be an instance of ConnectionPool or null.");
    }
  }
}
?>