<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB Query Builder Module
 * --------------------------------------------------------------------------
 *
 *  This module provides an interface for building database queries to be 
 *  executed on the ZTeraDB server. It allows the user to define the schema, 
 *  fields, filters, sorting, limits, and other query parameters in a fluid 
 *  manner. It supports various query types such as SELECT, INSERT, UPDATE, and 
 *  DELETE.
 *
 *  Key Responsibilities:
 *  ----------------------
 *  - Defining the schema, database, and query type.
 *  - Setting fields, filters, sorting, limits, and related fields for queries.
 *  - Generating a well-structured query ready for execution on the ZTeraDB server.
 *  - Providing utility methods for checking if certain options are set (e.g., COUNT).
 *
 *  Requirements:
 *  -------------
 *  - `ZTeraDBQueryType`, `ZTeraDBSort`, and `QueryFields` should be properly 
 *    defined and available for use.
 *  - The `ValueError` exception class should be used for validation errors.
 *
 *  The class builds SQL-like queries but is abstracted to communicate with the 
 *  ZTeraDB server. This abstraction allows for flexible query generation with 
 *  validation and error handling mechanisms in place.
 *
 * --------------------------------------------------------------------------
 *  @package     ZTeraDB
 *  @version     1.0
 *  @author      ZTeraDB
 *  @license     https://zteradb.com/licence (SPDX-License-Identifier: Proprietary)
 * --------------------------------------------------------------------------
 */
namespace ZTeraDB\Query;

// require_once __DIR__ . "/../Log/ZTeraDBLogger.php";
// require_once "ZTeraDBQueryType.php";
// require_once "QueryFields.php";
// require_once "ZTeraDBSort.php";
require_once __DIR__ . "/../Exceptions/ZTeraDBExceptions.php";
require_once __DIR__ . "/../Config/ZTeraDBConfig.php";

use ZTeraDB\Exceptions\ValueError;
use ZTeraDB\Log\ZTeraDBLogger as Log;
use ZTeraDB\Query\ZTeraDBQueryType;
use ZTeraDB\Query\ZTeraDBSort;
use ZTeraDB\Query\QueryFields;
use ZTeraDB\Config\ENVS;
use ZTeraDB\Query\ZTeraDBFilterCondition;

// $log = new \ZTeraDB\Log\ZTeraDBLogger();


/**
 * Class ZTeraDBQuery
 * 
 * Provides methods to construct queries for ZTeraDB database operations.
 * Supports various query types (SELECT, INSERT, UPDATE, DELETE) and allows 
 * the setting of _fields, filters, sorting, and limits.
 * 
 * Key Features:
 * - Query type definition (select, insert, update, delete).
 * - Dynamic field selection and filtering.
 * - Sorting, limiting, and related field queries.
 * - Query generation for execution on ZTeraDB server.
 * 
 * @package ZTeraDB\Query
 */
class ZTeraDBQuery {
  /**
   * @var string The name of the schema for the query.
   *             This defines the schema in which the query will operate.
   */
  private $schema_name = "";

  /**
   * @var string The ID of the database to target for the query.
   *             It is an optional field and can be empty if no specific database is set.
   */
  private $database_id = "";

  /**
   * @var string The type of the query (e.g., SELECT, INSERT, UPDATE, DELETE).
   *             It determines what kind of operation this query will perform.
   */
  private $query_type = "";

  /**
   * @var array The fields to be selected or manipulated in the query.
   *             This array holds key-value pairs where keys are field names
   *             and values are the corresponding field values or data.
   */
  private $_fields = [];

  /**
   * @var array The filters for the query (e.g., WHERE conditions).
   *             This array holds field-value pairs for filtering data.
   */
  private $filters = [];

  /**
   * @var array The conditions for the filters (e.g., logical operators, custom conditions).
   *             It allows for more complex conditions beyond simple equality checks.
   */
  private $filter_conditions = [];

  /**
   * @var array The limit for the query results (e.g., pagination).
   *             It is an array containing two values: the start index and the end index.
   */
  private $limit = [];

  /**
   * @var array The sorting order for the query results.
   *             It holds an array of sorting rules, where each rule specifies a field
   *             and the direction of sorting (ascending or descending).
   */
  private $sort = [];

  /**
   * @var array The related fields to include in the query.
   *             This allows for including related data through relationships.
   */
  private $_related_fields = [];

  /**
   * @var bool Flag to indicate whether the query should return a count of results.
   *             When true, the query will return only the count of rows instead of data.
   */
  private $count = false;

  /**
   * @var string The environment for the query (optional).
   *             This can represent the environment in which the query is being executed,
   *             such as 'production', 'development', or 'test'.
   */
  private $env = "";

  /**
   * Constructor for the ZTeraDBQuery class.
   *
   * This method initializes a new instance of the ZTeraDBQuery class by setting the schema name
   * and optionally a database ID. It validates that the provided schema name is a string and 
   * logs the initialization process.
   *
   * @param string $schema_name The name of the schema for the query.
   *                            This parameter is required and must be a valid string.
   * @param string $database_id (Optional) The ID of the database to associate with the query.
   *                            If not provided or is an empty string, no specific database ID is set.
   *
   * @throws ValueError If $schema_name is not a string, an exception will be thrown.
   *                    If the $database_id is provided and is not a string, it will be ignored.
   */
  public function __construct($schema_name, $database_id="") {
    // Ensure the schema name is a valid string
    if(!is_string($schema_name)) {
      throw new ValueError("schema_name must be string");
    }

    // If the database_id is provided and is a valid string, assign it to the instance
    if(is_string($database_id) && $database_id!="") {
      $this->database_id = $database_id;
    }

    // Set the schema name for the query instance
    $this->schema_name = $schema_name;
    
    // Log the initialization of the query
    Log::debug("Initialize ZTeraDBQuery.");
  }
  
  public function get_schema_name(){
    return $this->schema_name;
  }

  public function get_database_id(){
    return $this->database_id;
  }

  /**
   * Magic method to set a value for a dynamic property.
   *
   * This method allows setting dynamic properties on the object. It performs a check to ensure that
   * the property name is not one of the reserved fields defined in the `QueryFields::fields` array.
   * If the property name is reserved, a `ValueError` exception is thrown. Otherwise, the property 
   * is added to the `$fields` array.
   *
   * @param string $name  The name of the property being set.
   *                      This is the dynamic property key.
   * @param mixed  $value The value to assign to the property.
   *
   * @throws ValueError If the $name is a reserved keyword (i.e., exists in `QueryFields::fields`).
   */
  public function __set($name, $value) {
    // Check if the provided property name is reserved (from the QueryFields::fields list)
    if(in_array($name, QueryFields::fields)) {
      throw new ValueError("'$name' is reserved key");
    }

    // If the name is not reserved, store the value in the fields array
    $this->_fields[$name] = $value;
  }

  /**
   * Magic method to get the value of a dynamic property.
   *
   * This method allows getting the value of a dynamic property that has been previously set.
   * If the property exists in the `$fields` array, its value is returned. If it does not exist, 
   * the method returns `null`.
   *
   * @param string $name The name of the dynamic property to retrieve.
   *
   * @return mixed The value of the dynamic property if it exists, or `null` if the property is not set.
   */
  public function __get($name) {
    // Return the value of the property if it exists in the fields array, otherwise return null
    return isset($this->_fields[$name]) ? $this->_fields[$name] : null;
  }

  /**
   * Set the query type to SELECT.
   *
   * This method configures the current query object to perform a SELECT operation.
   * It sets the `query_type` to `ZTeraDBQueryType::SELECT` and returns the current 
   * query object, allowing for method chaining.
   *
   * @return $this The current instance of the ZTeraDBQuery object, allowing method chaining.
   */
  public function select() {
    // Set the query type to SELECT
    $this->query_type = ZTeraDBQueryType::SELECT;

    // Return the current instance for method chaining
    return $this;
  }

  /**
   * Set the query type to INSERT.
   *
   * This method configures the current query object to perform an INSERT operation.
   * It sets the `query_type` to `ZTeraDBQueryType::INSERT` and returns the current 
   * query object, allowing for method chaining.
   *
   * @return $this The current instance of the ZTeraDBQuery object, allowing method chaining.
   */
  public function insert() {
    // Set the query type to INSERT
    $this->query_type = ZTeraDBQueryType::INSERT;

    // Return the current instance for method chaining
    return $this;
  }

  /**
   * Set the query type to UPDATE.
   *
   * This method configures the current query object to perform an UPDATE operation.
   * It sets the `query_type` to `ZTeraDBQueryType::UPDATE` and returns the current 
   * query object, enabling method chaining.
   *
   * @return $this The current instance of the ZTeraDBQuery object, allowing method chaining.
   */
  public function update() {
    // Set the query type to UPDATE
    $this->query_type = ZTeraDBQueryType::UPDATE;

    // Return the current instance for method chaining
    return $this;
  }

  /**
   * Set the query type to DELETE.
   *
   * This method configures the current query object to perform a DELETE operation.
   * It sets the `query_type` to `ZTeraDBQueryType::DELETE` and returns the current 
   * query object, enabling method chaining.
   *
   * @return $this The current instance of the ZTeraDBQuery object, allowing method chaining.
   */
  public function delete() {
    // Set the query type to DELETE
    $this->query_type = ZTeraDBQueryType::DELETE;

    // Return the current instance for method chaining
    return $this;
  }

  /**
   * Check if the current query type is SELECT.
   *
   * This method checks if the `query_type` is set to `SELECT`, indicating that
   * the query is a SELECT operation. It is useful for conditionally determining
   * if the query is configured to fetch data.
   *
   * @return bool Returns `true` if the query type is SELECT, otherwise `false`.
   */
  public function is_select_query() {
    // Return true if the query type is SELECT, false otherwise
    return $this->query_type === ZTeraDBQueryType::SELECT;
  }

  public function get_query_type() {
    return $this->query_type;
  }

  /**
   * Get the fields of the current query.
   *
   * This method returns the fields that have been set for the current query. 
   * It can be used to retrieve all the fields associated with the query, 
   * which could represent columns in a database table for operations like 
   * SELECT or INSERT.
   *
   * @return array An associative array of fields with their corresponding values.
   */
  public function field() {
    // Return all fields set for the query
    return $this->_fields;
  }

  /**
   * Set multiple fields for the current query.
   *
   * This method allows you to set multiple fields for the query by passing an 
   * associative array where keys are field names and values are the corresponding 
   * field values. It dynamically adds the fields to the query object, 
   * making it flexible for different query operations like SELECT, INSERT, or UPDATE.
   *
   * @param array $fieldArray An associative array where the keys are field names 
   *                           and the values are the values to be set for those fields.
   * 
   * @return $this Returns the current query object to allow method chaining.
   * 
   * @throws ValueError If the argument is not an array.
   */
  public function fields($fieldArray) {
    // Ensure the argument is an array
    if(!is_array($fieldArray)) {
      throw new ValueError("fieldArray argument must be array");
    }

    // Loop through the array and set each field dynamically
    foreach($fieldArray as $key=>$value) {
      $this->$key = $value;
    }

    // Return the current object for method chaining
    return $this;
  }

  /**
   * Apply filters to the query.
   *
   * This method allows you to set filtering conditions for the query by passing 
   * an associative array where keys are field names and values are the values to 
   * filter by. Filters can be used for operations like `SELECT` to narrow down 
   * the results based on specific conditions.
   *
   * @param array $filter_array An associative array where keys are field names 
   *                            and values are the filter values for those fields.
   * 
   * @return $this Returns the current query object to allow method chaining.
   * 
   * @throws ValueError If the argument is not an array, the field name is not a string, 
   *                    or the value is an object.
   */
  public function filter($filter_array) {
    // Ensure the filter argument is an array
    if(!is_array($filter_array)) {
      throw new ValueError("filter should be an array");
    }

    // Loop through the filter array and validate each field-value pair
    foreach($filter_array as $field=>$value) {
      // Field name should be a string
      if(!is_string($field)) {
        throw new ValueError("Filter field name must be string");
      }

      // Value should not be an object
      if(is_object($value)) {
        throw new ValueError("Value should not be any object.");
      }

      // Store the valid filter condition
      $this->filters[$field] = $value;
    }

    // Return the current object for method chaining
    return $this;
  }

  /**
   * Get the filters applied to the query.
   *
   * This method returns the current list of filters that have been applied to the 
   * query. Filters are typically set using the `filter()` method, and they specify 
   * the conditions that must be met for the query results.
   * 
   * @return array Returns an associative array of applied filters, where the 
   *               keys are field names and the values are the corresponding filter 
   *               values.
   */
  public function filters() {
    // Return the filters array containing the applied filter conditions
    return $this->filters;
  }

  public function filterCondition($filter_condition) {
    if(!$filter_condition instanceof ZTeraDBFilterCondition) {
      throw new ValueError("'filter_condition' must be an instance of ZTeraDBFilterCondition");
    }

    $this->filter_conditions[] = $filter_condition->get_fields();
    return $this;
  }

  /**
   * Get the filter conditions for the query.
   *
   * This method returns the filter conditions that have been set for the query. 
   * Filter conditions define the logic or criteria used to filter the query results 
   * based on specific fields. This is typically used for more complex queries 
   * involving conditional operators (e.g., `AND`, `OR`, `>=`, etc.).
   * 
   * @return array Returns the filter conditions applied to the query, typically 
   *               including field names and their respective condition logic.
   */
  public function filterConditions() {
    // Return the filter conditions array containing logical conditions
    return $this->filter_conditions;
  }

  /**
   * Set related fields for the query.
   *
   * This method is used to define related fields in the query, allowing you to
   * perform operations that involve relationships between different data sets. 
   * A related field typically represents a link to another query (or entity) 
   * that needs to be included or joined in the current query.
   *
   * @param array $related_fields An associative array where the key is the related 
   *                              field name (string), and the value is a `ZTeraDBQuery`
   *                              instance representing the related query.
   * 
   * @throws ValueError if the `$related_fields` parameter is not an array, if any 
   *                   of the related field names is not a string, or if the query 
   *                   for a related field is not an instance of `ZTeraDBQuery`.
   */
  public function related_field($related_fields) {
    // Validate that the related fields are passed as an array
    if(!is_array($related_fields)) {
      throw new ValueError("Related field must be array");
    }

    // Loop through each related field and validate its name and query
    foreach($related_fields as $field_name=>$query) {
      // Ensure the related field name is a string
      if(!is_string($field_name)) {
        throw new ValueError("Related field name must be string");
      }

      // Ensure the query is an instance of ZTeraDBQuery
      if(!$query instanceof ZTeraDBQuery) {
        throw new ValueError("Related field query must be an instance of ZTeraDBQuery");
      }

      // Ensure the query is an instance of ZTeraDBQuery
      $this->_related_fields[$field_name] = $query;
    }
  }

  /**
   * Get all related fields in the query.
   *
   * This method returns all the related fields that have been set for the query. 
   * Related fields are typically used for including data from related queries, 
   * such as performing joins or fetching data from other tables or entities 
   * linked to the main query.
   *
   * @return array An associative array of related fields, where the key is the 
   *               related field name and the value is the associated `ZTeraDBQuery`.
   */
  public function related_fields() {
    // Return the array of related fields for the query
    return $this->_related_fields;
  }

  /**
   * Set the query to count the results.
   *
   * This method indicates that the query should return a count of the results 
   * rather than the actual data. It is commonly used for queries where you 
   * need to know the number of matching records, but not the full result set.
   *
   * @return $this The current `ZTeraDBQuery` instance, allowing for method chaining.
   */
  public function count() {
    // Set the flag to indicate this is a count query
    $this->count = True;

    // Return the current object for method chaining
    return $this;
  }

  /**
   * Check if the query is set to return a count of the results.
   *
   * This method checks whether the query has been configured to return the 
   * count of matching records, rather than the actual data. It is used to 
   * confirm if the query was marked for a count operation by calling the 
   * `count()` method.
   *
   * @return bool `true` if the query is set to count the results, otherwise `false`.
   */
  public function has_count() {
    // Return the current state of the count flag
    return $this->count;
  }

  /**
   * Set the limit for the number of records to return in the query.
   *
   * This method is used to apply a limit on the number of records that 
   * should be retrieved by the query. The `$start` parameter defines the 
   * starting position (offset), and the `$end` parameter defines the 
   * ending position (limit). The method validates that both values are 
   * integers and that the start is less than the end.
   *
   * @param int $start The starting index (offset) for the result set.
   * @param int $end The ending index (limit) for the result set.
   * 
   * @return $this The current instance of the query to allow method chaining.
   * 
   * @throws ValueError If any of the parameters are not integers, are negative, 
   *                    or if the start value is greater than or equal to the end value.
   */
  public function limit($start, $end) {
    // Validate that $start is an integer and greater than or equal to 0
    if(!is_int($start)) {
      throw new ValueError("Limit start must be integer");
    }
    else if($start < 0) {
      throw new ValueError("Limit start must be greter than or equal to 0");
    }

    // Validate that $end is an integer and greater than or equal to 0
    if(!is_int($end)) {
      throw new ValueError("Limit end must be integer");
    }
    else if($end < 0) {
      throw new ValueError("Limit start must be greter than or equal to 0");
    }

    // Validate that $end is an integer and greater than or equal to 0
    if($start >= $end) {
      throw new ValueError("Limit end must be greter than start"); 
    }

    // Set the limit range
    $this->limit = [$start, $end];

    // Return the current instance for method chaining
    return $this;
  }

  /**
   * Retrieve the limit range set for the query.
   *
   * This method returns the limit array, which contains the `start` and `end` values 
   * for limiting the number of records retrieved in the query. The limit range is set 
   * using the `limit()` method, and this method simply returns the current limit range.
   *
   * @return array|null The limit range as an array [start, end], or null if no limit has been set.
   */
  public function get_limit() {
    // Return the limit range set for the query
    return $this->limit;
  }

  /**
   * Set sorting criteria for the query.
   *
   * This method allows setting sorting preferences for the query. It accepts an array of
   * field names as keys, with their corresponding sort order ('ASC' or 'DESC') as values.
   * The sorting criteria will be used to order the results of the query when executed.
   *
   * Example: 
   * - ["name" => "ASC"] will sort by the "name" field in ascending order.
   * - ["age" => "DESC"] will sort by the "age" field in descending order.
   *
   * @param array $sort_array An associative array where the key is the field name and the value is the sort order.
   *                           Valid sort orders are "ASC" (ascending) or "DESC" (descending).
   * 
   * @throws ValueError if the argument is not an array.
   * @return $this The current instance of the query object, allowing for method chaining.
   */
  public function sort($sort_array) {
    // Ensure the argument is an array
    if(!is_array($sort_array)) {
      throw new ValueError("sort_array should be an array");
    }
  
    // Ensure the argument is not empty array
    if (empty($sort_array)) {
        throw new ValueError("sort_array must not be an array");
    }

    // Loop through each field and its sorting order
    foreach($sort_array as $key=>$value) {
      // Create a ZTeraDBSort object for each field and its sorting order
      $sort = new ZTeraDBSort($key, $value);

      // Add the ZTeraDBSort object to the sorting criteria
      array_push($this->sort, $sort);
    }

    // Return the current instance for method chaining
    return $this;
  }

  /**
   * Retrieve the sorting criteria for the query.
   *
   * This method returns the sorting configuration set for the query. It iterates over 
   * the stored sorting criteria and returns an associative array where the key is 
   * the field name and the value is the corresponding sort order ('ASC' or 'DESC').
   *
   * Example: 
   * - If the sorting was set with ["name" => "ASC", "age" => "DESC"],
   *   this method will return ['name' => 'ASC', 'age' => 'DESC'].
   *
   * @return array An associative array of the sorting criteria where the key is the field name 
   *               and the value is the sort order ('ASC' or 'DESC').
   */
  public function get_sort() {
    // Initialize an empty array to store sorting data
    $sort_data = array();

    // Iterate through the sort objects stored in the query
    foreach($this->sort as $sort) {
      // Populate the $sort_data array with field names as keys and sort orders as values
      $sort_data[$sort->get_field_name()] = $sort->get_sort_order();
    }

    // Return the array of sorting data
    return $sort_data;
  }

  /**
   * Set the environment for the query.
   *
   * This method is used to assign an environment to the query. It validates that 
   * the provided `$env` is either an instance of the `ENV` class or a valid 
   * environment value from the predefined list (`ENV::ENV_LIST`). If the validation 
   * fails, a `ValueError` is thrown.
   *
   * Example:
   * - Valid usage: `$query->set_env(ENV::PRODUCTION);`
   * - Invalid usage (throws exception): `$query->set_env("invalid_env");`
   *
   * @param mixed $env The environment to be assigned to the query.
   * @throws ValueError If `$env` is not an instance of `ENV` or a valid environment value.
   */
  public function set_env($env) {
    // Ensure that the provided $env is either an instance of ENV or a valid value from ENV::ENV_LIST
    if(!$env instanceof \ZTeraDB\Config\ENVS && !in_array($env, \ZTeraDB\Config\ENVS::ENV_LIST)) {
      // If the validation fails, throw a ValueError with a descriptive message
      throw new ValueError("env must be an instance of environment");
    }

    // If validation passes, assign the environment to the query object
    $this->env = $env;
  }

  /**
   * Get the current environment of the query.
   *
   * This method retrieves the environment that has been set for the query using 
   * the `set_env()` method. It returns the environment value stored in the 
   * `$env` property of the `ZTeraDBQuery` object. If no environment has been 
   * set, it will return `null` or the default value.
   *
   * Example:
   * - After setting an environment: `$env = $query->get_env();`
   * 
   * @return mixed The environment value (`$env`) set for the query. 
   *               Could be an instance of `ENV` or a predefined environment value.
   */
  public function get_env() {
    return $this->env;
  }

  /**
   * Generate the query parameters for the database request.
   *
   * This method constructs the full set of query parameters based on the 
   * properties of the `ZTeraDBQuery` object. It validates that one of the 
   * query types (SELECT, INSERT, UPDATE, DELETE) has been specified. If 
   * no query type is set, it throws an exception.
   *
   * The generated query parameters are returned as an associative array, 
   * which can then be used to perform the database query.
   *
   * Example:
   * - If you call `select()`, `filter()`, and `limit()`, this method will 
   *   return an array of parameters for a SELECT query, which could be sent 
   *   to the database engine for execution.
   * 
   * @throws ValueError if no query type (select, insert, update, or delete) is set.
   * @return array An associative array of query parameters, including:
   *               - `db`: Database ID
   *               - `sh`: Schema Name
   *               - `qt`: Query Type (SELECT, INSERT, UPDATE, DELETE)
   *               - `fl`: Fields to select
   *               - `fi`: Filters applied to the query
   *               - `fc`: Filter conditions
   *               - `rf`: Related fields to include in the query
   *               - `st`: Sort order for the query
   *               - `lt`: Limit on the query result
   *               - `cnt`: Whether count is enabled
   *               - `env`: The environment under which the query is executed
   */
  public function generate() {
    // Ensure a valid query type has been set (SELECT, INSERT, UPDATE, DELETE)
    if(!in_array($this->query_type, ZTeraDBQueryType::QUERY_TYPES)) {
      throw new ValueError("You forgot to call either of select(), insert(), update() or delete() method.");
    }

    // Return the query parameters as an associative array
    return [
      "db"=>$this->database_id,           // Database ID
      "sh"=>$this->schema_name,           // Schema Name
      "qt"=>$this->query_type,            // Query Type (SELECT/INSERT/UPDATE/DELETE)
      "fl"=>$this->field(),               // Fields to select
      "fi"=>$this->filters(),             // Filters applied to the query
      "fc"=>$this->filterConditions(),   // Filter conditions
      "rf"=>$this->related_fields(),      // Related fields
      "st"=>$this->get_sort(),            // Sorting information
      "lt"=>$this->get_limit(),           // Limit for the query
      "cnt"=>$this->count,                // Whether to count the query results
      "env"=>$this->env                   // The environment under which the query is executed
    ];
  }
}
?>