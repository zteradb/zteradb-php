<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB Query Sort Class
 * --------------------------------------------------------------------------
 *
 *  This class defines a sorting mechanism for queries executed on the 
 *  ZTeraDB server. It allows for sorting by a specified field in either 
 *  ascending or descending order. The class ensures that the sort order 
 *  and field are properly validated before they are used.
 *
 *  Key Responsibilities:
 *  ----------------------
 *  - Storing the field name and sort order.
 *  - Validating that the field is a string and the sort order is an integer.
 *  - Ensuring that the sort order is either ascending (1) or descending (-1).
 *  - Providing methods to retrieve the field and sort order.
 *
 *  Requirements:
 *  -------------
 *  - The `ZTeraDB\Exceptions\ValueError` class should be defined for error handling.
 *
 *  This class is used in query-building processes to define how results 
 *  should be sorted (ascending or descending).
 *
 * --------------------------------------------------------------------------
 *  @package     ZTeraDB
 *  @version     1.0
 *  @author      ZTeraDB
 *  @license     https://zteradb.com/licence (SPDX-License-Identifier: Proprietary)
 * --------------------------------------------------------------------------
 */
namespace ZTeraDB\Query;

// Import the custom exception class for validation errors
use ZTeraDB\Exceptions\ValueError as ValueError;


/**
 * Class ZTeraDBSort
 * 
 * A class that defines a sorting mechanism for database queries, supporting 
 * both ascending and descending orders.
 * @package ZTeraDB\Query
 */
class ZTeraDBSort {
  // Constants defining the sort orders
  const ASC = 1;                // Ascending order (1)
  const DESC = -1;              // Descending order (-1)

  // Private properties to store the field and sort order
  private $field = "";          // The field to be sorted
  private $sort_order = -1;     // The sort order (ascending/descending)

  /**
   * Constructor to initialize the sorting field and order.
   * 
   * This method ensures that the field and sort order provided for sorting are valid
   * before they are assigned to the object's properties. If any validation fails, 
   * a custom `ValueError` exception is thrown.
   *
   * @param string $field The field name to sort by.
   * @param int $sort_order The order of sorting (1 for ascending, -1 for descending).
   * 
   * @throws ValueError if the field is not a string, the sort order is not an integer,
   *         or the sort order is invalid.
   */
  public function __construct($field, $sort_order) {
    // Validate that the $field is a string
    if(!is_string($field)) {
      // Throw an exception if the field is not a string
      throw new ValueError("Sort field must be string");
    }

    // Validate that the $sort_order is an integer
    if(!is_int($sort_order)) {
      // Throw an exception if the sort order is not an integer
      throw new ValueError("Sort order must be integer");
    }

    // Ensure that the sort order is either 1 (ascending) or -1 (descending)
    if(!in_array($sort_order, $this->getList())) {
      // Throw an exception if the sort order is neither ascending nor descending
      throw new ValueError("Invalid Sort order");
    }

    // Set the field property
    $this->filed = $field;

    // Set the sort order property
    $this->sort_order = $sort_order;
  }

  /**
   * Get the field name used for sorting.
   * 
   * This method returns the name of the field that is being used to sort 
   * the query results. It retrieves the value of the `$filed` property 
   * that was set during the object initialization (constructor).
   * 
   * @return string The field name used for sorting.
   */
  public function get_field_name() {
    // Return the field name property
    return $this->filed;
  }

  /**
   * Get the sort order used for sorting.
   * 
   * This method returns the sorting order (ascending or descending) that 
   * was set during the initialization of the object. The value of the 
   * `$sort_order` property is either 1 for ascending or -1 for descending.
   * 
   * @return int The sort order (1 for ascending, -1 for descending).
   */
  public function get_sort_order() {
    // Return the sort order property
    return $this->sort_order;
  }

  /**
   * Get the list of valid sort orders.
   * 
   * This method returns an array containing the two valid sort orders that can 
   * be used for sorting: ascending (1) and descending (-1). These values are 
   * defined as constants in the class (self::ASC and self::DESC).
   * 
   * @return array An array containing the valid sort orders [1, -1].
   */
  public function getList() {
    return [
      self::ASC,      // Ascending order constant (1)
      self::DESC      // Descending order constant (-1)
    ];
  }
}
?>