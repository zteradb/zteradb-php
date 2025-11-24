<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB ConnectionPool Class
 * --------------------------------------------------------------------------
 *
 *  The `ConnectionPool` class defines the minimum and maximum number of
 *  client-side connections allowed for the ZTeraDB client. It provides strict,
 *  low-level validation for pool configuration parameters.
 *
 *  • Purpose  
 *      Ensures safe values for the connection pool before the ZTeraDB client
 *      begins establishing connections. This class *does not* manage pool
 *      behavior—its responsibility is strictly validation.
 *
 *  • Responsibilities  
 *      - Validate that `min` and `max` are integers  
 *      - Ensure values are ≥ 0  
 *      - Enforce the rule `min <= max`, except when `max == 0` meaning unlimited  
 *      - Provide a simple, dependable way for other components (e.g., Options)
 *        to configure connection-pool behavior
 *
 *  • Notes for Developers  
 *      - Adding new rules or constraints should be done here to ensure
 *        centralized validation.  
 *      - A `max` value of `0` indicates “unlimited,” while still following
 *        integer validation rules.  
 *      - This class intentionally contains no defaults — callers must explicitly
 *        supply valid values.
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
 * ConnectionPool
 *
 * Represents a client-side connection pool configuration for ZTeraDB.
 *
 * Notes for Developers:
 * ---------------------
 * - This class does **not manage connections**; it only validates the min/max values.
 * - `min` and `max` must be integers and cannot be negative.
 * - `min` must be less than or equal to `max`, unless `max` is 0 (which can indicate unlimited).
 * - Validation ensures safe initialization of the pool before the client starts using it.
 */
class ConnectionPool
{
  // Holds the minimum number of connections allowed.
  private $min;

  // Holds the maximum number of connections allowed.
  private $max;

  /**
   * Constructor
   *
   * @param int $min Minimum number of connections.
   * @param int $max Maximum number of connections.
   *
   * @throws ValueError If the parameters are not integers, negative, or invalid.
   *
   * Usage:
   * ------
   *   $pool = new ConnectionPool(1, 10);
   */
  public function __construct($min, $max)
  {
    // Validate that 'min' is an integer.
    if (!is_int($min)) {
      throw new ValueError("Invalid connection pool: 'min' must be an integer.");
    }

    // Validate that 'min' is non-negative.
    if ($min < 0) {
      throw new ValueError("Invalid connection pool: 'min' must be an integer greater than or equal to 0.");
    }

    // Ensure max is an integer
    if (!is_int($max)) {
      throw new ValueError("Invalid connection pool: 'max' must be an integer.");
    }

    // Ensure max is >= 0
    if ($min < 0) {
      throw new ValueError("Invalid connection pool: 'max' must be an integer greater than or equal to 0.");
    }

    // Ensure min <= max (unless max is 0 for unlimited)
    if ($max != 0 && $min > $max) {
      throw new ValueError("Invalid connection pool: 'min' must be less than or equal to 'max', unless 'max' is 0 (unlimited).");
    }

    // Store validated values
    $this->min = $min;
    $this->max = $max;
  }

  /**
   * Get the minimum value of the connection pool
   *
   * This method returns the minimum value of the connection pool
   *
   * @returns integer $min The minimum value assigned to the Connection pool
   *
   */
  public function get_min() {
    return $this->min;
  }

  /**
   * Get the maximum value of the connection pool
   *
   * This method returns the maximum value of the connection pool
   *
   * @returns integer $min The maximum value assigned to the Connection pool
   *
   */
  public function get_max() {
    return $this->max;
  }
}
?>