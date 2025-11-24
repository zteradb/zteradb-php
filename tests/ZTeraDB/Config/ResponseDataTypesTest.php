<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB ResponseDataTypes Unit Test Suite
 * --------------------------------------------------------------------------
 *
 *  This test suite verifies the correctness of the `ResponseDataTypes` class,
 *  ensuring that only supported response formats are accepted and that invalid
 *  formats result in proper exceptions.
 *
 *  • Purpose  
 *      Confirms that the ResponseDataTypes class enforces the allowed formats
 *      list and maintains strict type validation at construction time.
 *
 *  • Test Coverage  
 *      - `testValidType()`  
 *          Ensures that supplying a valid response data type (currently `json`)
 *          results in correct object initialization.
 *
 *      - `testInvalidType()`  
 *          Verifies that unsupported formats (e.g., `"xml"`) trigger a
 *          `ValueError`, reinforcing strict validation rules.
 *
 *  • Importance  
 *      These tests prevent misconfiguration by ensuring the client strictly
 *      rejects invalid data format options, maintaining API integrity and
 *      preventing downstream format handling errors.
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

// Import the ResponseDataTypes class being tested.
use ZTeraDB\Config\ResponseDataTypes;

// Import the ValueError exception that should be thrown on invalid input.
use ZTeraDB\Exceptions\ValueError;

/**
 * Class ResponseDataTypes_Test
 *
 * Contains tests verifying correct behavior of the ResponseDataTypes class.
 */
class ResponseDataTypes_Test extends TestCase {
  /**
   * Ensures that providing a valid response type (e.g., "json")
   * correctly initializes the ResponseDataTypes object.
   */
  public function testValidType() {
    // Create a ResponseDataTypes instance using the valid JSON constant.
    $t = new ResponseDataTypes(ResponseDataTypes::json);

    // Assert that the getter returns the expected value.
    $this->assertEquals("json", $t->get_response_data_type());
  }
  
  /**
   * Ensures that providing an unsupported response type
   * results in a ValueError exception.
   */
  public function testInvalidType() {
    // Inform PHPUnit that we expect a ValueError to be thrown.
    $this->setExpectedException(ValueError::class);

    // Attempt to create an instance with an invalid type (e.g. XML).
    // This should trigger the expected exception.
    new ResponseDataTypes("xml");
  }
}
?>