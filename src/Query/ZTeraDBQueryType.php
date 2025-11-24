<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB Query Type Module
 * --------------------------------------------------------------------------
 *
 *  This module defines and manages the query types used in the ZTeraDB 
 *  system, such as INSERT, SELECT, UPDATE, DELETE, and NONE (for no query type).
 *
 *  Key Responsibilities:
 *  ----------------------
 *  - Defining constants for different query types (INSERT, SELECT, UPDATE, DELETE).
 *  - Validating that the provided query type is valid when creating an instance.
 *  - Storing and managing the query type for further use in ZTeraDB operations.
 *
 *  Requirements:
 *  -------------
 *  - The query type should be one of the predefined constants or `NONE` to indicate no operation.
 *
 *  This class ensures that only valid query types are used during communication 
 *  with the ZTeraDB server, helping prevent errors in query execution.
 *
 * --------------------------------------------------------------------------
 *  @package     ZTeraDB
 *  @version     1.0
 *  @author      ZTeraDB
 *  @license     https://zteradb.com/licence (SPDX-License-Identifier: Proprietary)
 * --------------------------------------------------------------------------
 */

namespace ZTeraDB\Query;

// Import the ValueError exception for validation failures.
use ZTeraDB\Exceptions\ValueError;


/**
 * Class ZTeraDBQueryType
 * 
 * This class defines the various query types used in ZTeraDB operations, including 
 * `INSERT`, `SELECT`, `UPDATE`, `DELETE`, and a default `NONE` type for when no query 
 * type is specified. It ensures that the provided query type is valid by checking it 
 * against a predefined set of constants.
 * 
 * Key Responsibilities:
 * ----------------------
 * - Defines constants representing different SQL query types (INSERT, SELECT, UPDATE, DELETE).
 * - Validates the provided query type during object construction.
 * 
 * The class helps in managing query types effectively while performing operations with 
 * the ZTeraDB server and ensures that only valid query types are used.
 * 
 * @package ZTeraDB\Query
 */
class ZTeraDBQueryType {
  // Constant for the Insert operation (0x1).
  const INSERT = 0X1;

  // Constant for the Select operation (0x2).
  const SELECT = 0X2;

  // Constant for the Update operation (0x3).
  const UPDATE = 0X3;

  // Constant for the Delete operation (0x4).
  const DELETE = 0X4;

  // Constant to represent no query type, used as the default (0).
  const NONE = 0;

  // Default query type is set to NONE, indicating no operation is assigned.
  public $query_type = self::NONE;
  
  const QUERY_TYPES = [
    self::INSERT,
    self::SELECT,
    self::UPDATE,
    self::DELETE,
    self::NONE,
  ];

  /**
   * Constructor for ZTeraDBQueryType class.
   * 
   * Initializes the query type by validating the provided type. Throws an exception
   * if the provided query type is not valid.
   * 
   * @param int $query_type The query type to be set (must be one of the predefined constants).
   * 
   * @throws ValueError if the provided query type is not valid.
   */
  public function __construct($query_type) {
    // Check if the provided query type is valid by comparing with the predefined query types.
    if(!in_array($query_type, self::QUERY_TYPES)) {
      // Check if the provided query type is valid by comparing with the predefined query types.
      throw new ValueError("Invalid query_type");
    }

    // Set the query type to the provided valid value.
    $this->query_type = $query_type;
  }
}
?>