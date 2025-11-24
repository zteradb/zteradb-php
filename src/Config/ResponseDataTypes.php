<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB ResponseDataTypes Class
 * --------------------------------------------------------------------------
 *
 *  The `ResponseDataTypes` class defines the allowable formats for data returned
 *  by the ZTeraDB client. It ensures strict type safety by validating that only
 *  supported response formats are used.
 *
 *  • Purpose  
 *      Provides a controlled set of immutable response type identifiers
 *      (currently only `json`). Prevents invalid formats from being passed into
 *      the client or configuration layers.
 *
 *  • Responsibilities  
 *      - Define supported response formats.  
 *      - Validate the user-supplied response format at construction time.  
 *      - Reject unsupported formats using a `ValueError` exception.  
 *
 *  • Extensibility  
 *      To support new response formats in the future (e.g., `xml`, `binary`):  
 *        1. Add a new constant (e.g., `const xml = "xml";`)  
 *        2. Add it to the `RESPONSE_DATA_TYPES` array  
 *      Validation logic will automatically include the new type.
 *
 * --------------------------------------------------------------------------
 *  @package     ZTeraDB\Config
 *  @version     1.0
 *  @author      ZTeraDB
 *  @license     https://zteradb.com/licence   (SPDX-License-Identifier: Proprietary)
 * --------------------------------------------------------------------------
 */
namespace ZTeraDB\Config;

// Import the ValueError exception to use for validation failures.
use ZTeraDB\Exceptions\ValueError as ValueError;

/**
 * ResponseDataTypes
 *
 * Represents the supported response formats for ZTeraDB.
 * 
 * Notes for Developers:
 * ---------------------
 * - ZTeraDB currently supports only JSON responses.
 * - This class ensures type safety by validating the provided response type.
 * - Add new response formats here in the future (e.g., "xml", "binary").
 * - Always update `RESPONSE_DATA_TYPES` when adding new constants.
 */
class ResponseDataTypes
{
  // JSON response format (currently the only supported data format).
  const json = "json";

  // Assign default response_data_type to null
  private $response_data_type = null;

  // List of all allowed response data types — used for validation.
  const RESPONSE_DATA_TYPES = [
    self::json,
  ];

  /**
   * Constructor
   *
   * Creates a new ResponseDataTypes object and validates the provided response
   * type to ensure it is supported.
   * 
   * @param string $response_data_type
   *        The response format selected by the user.
   *
   * @throws ValueError
   *         If the provided response type is not in RESPONSE_DATA_TYPES.
   *
   * Usage Example:
   * --------------
   *   $type = new ResponseDataTypes(ResponseDataTypes::json);
   */
  public function __construct($response_data_type)
  {
    // Validate that the provided type exists in the list of allowed types.
    if (!in_array($response_data_type, self::RESPONSE_DATA_TYPES)) {
      throw new ValueError("Invalid ResponseDataTypes");
    }

    // Store the validated response type for later retrieval.
    $this->response_data_type = $response_data_type;
  }

  /**
   * Returns the selected response data type.
   *
   * @return string The response format (e.g., "json").
   */
  public function get_response_data_type()
  {
    // Return the stored type value.
    return $this->response_data_type;
  }
}
?>