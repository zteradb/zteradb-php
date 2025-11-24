<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB ENVS Unit Test Suite
 * --------------------------------------------------------------------------
 *
 *  This test suite validates the behavior of the `ENVS` configuration class.
 *  It ensures correct handling of environment selection and strict validation.
 *
 *  • Purpose  
 *      The ENVS class restricts the client to one of the allowed environments:
 *      `dev`, `staging`, `qa`, or `prod`.  
 *      These tests verify that:
 *        - A valid environment initializes successfully.
 *        - An invalid environment triggers a `ValueError`.
 *
 *  • Test Coverage  
 *      - `testValidEnvironment()`  
 *          Confirms that initializing ENVS with a valid constant (e.g. `dev`)
 *          returns the expected environment name.
 *
 *      - `testInvalidEnvironment()`  
 *          Ensures passing an unsupported environment string raises the
 *          appropriate exception, enforcing input correctness.
 *
 *  • Importance  
 *      These tests guarantee that the ENVS component maintains strict input
 *      validation—critical for preventing misconfiguration of ZTeraDB clients
 *      across deployment environments.
 *
 * --------------------------------------------------------------------------
 *  @package     ZTeraDB\Tests
 *  @version     1.0
 *  @author      ZTeraDB
 *  @license     https://zteradb.com/licence   (SPDX-License-Identifier: Proprietary)
 * --------------------------------------------------------------------------
 */

// Import the base TestCase class from PHPUnit for creating unit tests.
use PHPUnit\Framework\TestCase;

// Import the ENVS class we are testing.
use ZTeraDB\Config\ENVS;

// Import the ValueError exception used when invalid environments are passed.
use ZTeraDB\Exceptions\ValueError;

/**
 * Class ENVS_Test
 *
 * This test suite verifies that the ENVS configuration class correctly handles
 * valid and invalid environment inputs.
 */
class ENVS_Test extends TestCase {
  /**
   * Test that providing a valid environment (e.g., ENVS::dev) is accepted.
   */
  public function testValidEnvironment() {
    // Create an ENVS object using a valid environment constant.
    $env = new ENVS(ENVS::dev);

    // Assert that the environment getter returns the correct value.
    $this->assertEquals("dev", $env->get_env());
  }

  /**
   * Test that providing an invalid environment name triggers a ValueError.
   */
  public function testInvalidEnvironment() {
    // Inform PHPUnit that we expect a ValueError exception.
    $this->setExpectedException(ValueError::class);

    // Attempt to create an ENVS instance with an unsupported environment.
    // This should trigger the expected exception.
    new ENVS("invalid");
  }
}
?>