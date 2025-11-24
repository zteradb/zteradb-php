<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB ConnectionPool Unit Test Suite
 * --------------------------------------------------------------------------
 *
 *  This test suite validates the `ConnectionPool` class, which handles
 *  client-side connection pool configuration for the ZTeraDB client.
 *
 *  • Purpose
 *      - Ensure the constructor correctly validates minimum (`min`) and
 *        maximum (`max`) connection limits.
 *      - Verify that invalid inputs (wrong types, negative values, or
 *        inconsistent min/max values) throw `ValueError` exceptions.
 *
 *  • Test Coverage
 *      - `testValidPool()`: Confirms a pool with valid min/max is created.
 *      - `testInvalidMinType()`: Ensures non-integer `min` triggers ValueError.
 *      - `testInvalidMaxType()`: Ensures non-integer `max` triggers ValueError.
 *      - `testNegativeMin()`: Confirms negative `min` throws ValueError.
 *      - `testMinGreaterThanMax()`: Confirms `min > max` throws ValueError.
 *
 *  • Importance
 *      Validates safe and predictable initialization of connection pools,
 *      preventing runtime connection issues in ZTeraDB clients.
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

// Import the ConnectionPool class under test
use ZTeraDB\Config\ConnectionPool;

// Import ValueError for exception assertions
use ZTeraDB\Exceptions\ValueError;

/**
 * Class ConnectionPool_Test
 *
 * Unit tests for the ConnectionPool class.
 * Validates proper constructor behavior and error handling.
 */
class ConnectionPool_Test extends TestCase {
  /**
   * Test that the ConnectionPool can be successfully created with valid
   * minimum and maximum connection limits.
   *
   * This ensures that the constructor correctly initializes the pool
   * and returns an instance of the expected class.
   */
  public function testValidPool() {
    // Create a pool with valid min and max connections
    $connection_pool = new ConnectionPool(1, 5);

    // Assert the object is a ConnectionPool
    $this->assertInstanceOf(ConnectionPool::class, $connection_pool);
  }

  /**
   * Test that creating a ConnectionPool with a non-integer 'min' value
   * throws a ValueError.
   *
   * Ensures the constructor validates input types correctly.
   */
  public function testInvalidMinType() {
    // Expect a ValueError for invalid type
    $this->setExpectedException(ValueError::class);

    // Attempt to create a pool with non-integer min value
    new ConnectionPool("1", 5);
  }

  /**
   * Test that creating a ConnectionPool with a non-integer 'max' value
   * throws a ValueError.
   *
   * Ensures the constructor validates the type of the maximum connection limit.
   */
  public function testInvalidMaxType() {
    // Expect a ValueError for invalid type
    $this->setExpectedException(ValueError::class);

    // Attempt to create a pool with non-integer max value
    new ConnectionPool(1, "5");
  }

  /**
   * Test that creating a ConnectionPool with a negative 'min' value
   * throws a ValueError.
   *
   * Ensures the constructor enforces that the minimum connection count
   * must be non-negative.
   */
  public function testNegativeMin() {
    // Expect a ValueError for negative min value
    $this->setExpectedException(ValueError::class);
    
    // Attempt to create a pool with min = -1
    new ConnectionPool(-1, 5);
  }

  /**
   * Test that creating a ConnectionPool where the minimum connections
   * exceed the maximum connections throws a ValueError.
   *
   * Ensures that the constructor enforces logical consistency between
   * min and max connection limits.
   */
  public function testMinGreaterThanMax() {
    // Expect a ValueError when min > max
    $this->setExpectedException(ValueError::class);

    // Attempt to create a pool with min = 10 and max = 5
    new ConnectionPool(10, 5);
  }
}
?>