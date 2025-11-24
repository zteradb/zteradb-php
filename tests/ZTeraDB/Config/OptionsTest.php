<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB Options Unit Test Suite
 * --------------------------------------------------------------------------
 *
 *  This test suite validates the behavior of the `Options` configuration class,
 *  specifically its handling and validation of the `connection_pool` optional
 *  setting.
 *
 *  • Purpose  
 *      Ensures the Options class correctly accepts:
 *        - A valid ConnectionPool instance
 *        - A valid associative array defining "min" and "max"
 *      And correctly rejects invalid types.
 *
 *  • Test Coverage  
 *      - `testValidConnectionPoolInstance()`  
 *          Verifies that directly passing a ConnectionPool instance is valid.
 *
 *      - `testValidConnectionPoolArray()`  
 *          Confirms that the Options class correctly constructs a ConnectionPool
 *          from an associative array.
 *
 *      - `testInvalidConnectionPoolType()`  
 *          Ensures that a non-object, non-array, invalid type triggers a
 *          `ValueError`, enforcing strict configuration rules.
 *
 *  • Importance  
 *      These tests ensure reliable configuration initialization and prevent
 *      misconfigured client setups, preserving system stability and type safety.
 *
 * --------------------------------------------------------------------------
 *  @package     ZTeraDB\Tests
 *  @version     1.0
 *  @autor       ZTeraDB
 *  @license     https://zteradb.com/licence   (SPDX-License-Identifier: Proprietary)
 * --------------------------------------------------------------------------
 */
// Import PHPUnit's base TestCase class for writing unit tests.
use PHPUnit\Framework\TestCase;

// Import the Options class we are testing.
use ZTeraDB\Config\Options;

// Import the ConnectionPool class, which Options depends on.
use ZTeraDB\Config\ConnectionPool;

// Import the ValueError exception thrown on invalid configuration.
use ZTeraDB\Exceptions\ValueError;

/**
 * Class Options_Test
 *
 * Contains unit tests to validate correct behavior of the Options class.
 */
class Options_Test extends TestCase {
  /**
   * Ensures that providing a valid ConnectionPool instance
   * is accepted and assigned correctly.
   */
  public function testValidConnectionPoolInstance() {
    // Create a valid ConnectionPool instance.
    $pool = new ConnectionPool(1, 5);

    // Initialize Options using the instance directly.
    $opt  = new Options(["connection_pool" => $pool]);

    // Assert that the connection_pool property contains a ConnectionPool object.
    $this->assertInstanceOf(ConnectionPool::class, $opt->connection_pool);
  }

  /**
   * Ensures that providing an array with "min" and "max" values
   * results in a correctly constructed ConnectionPool instance.
   */
  public function testValidConnectionPoolArray() {
    // Create Options using an array definition for min/max.
    $opt = new Options(["connection_pool" => ["min" => 1, "max" => 5]]);
    
    // Assert that Options converted the array into a ConnectionPool object.
    $this->assertInstanceOf(ConnectionPool::class, $opt->connection_pool);
  }
  
  /**
   * Ensures that an invalid connection_pool type triggers a ValueError.
   */
  public function testInvalidConnectionPoolType() {
    // Inform PHPUnit that a ValueError exception is expected.
    $this->setExpectedException(ValueError::class);
    
    // Attempt to initialize Options with an invalid type.
    new Options(["connection_pool" => "invalid"]);
  }
}
?>