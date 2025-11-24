<?php
/*
 * ZTeraDBFilterCondition Class
 * 
 * This class defines various methods for applying filter conditions on database queries in the ZTeraDB system.
 * It supports various operators like addition, subtraction, multiplication, division, comparison, and logical filters (AND/OR).
 * The methods provided allow for building complex filter conditions in a dynamic and flexible manner.
 *
 * Author: [Your Name]
 * Version: 1.0
 * Date: [Date]
 * 
 * Dependencies:
 * - ZTeraDBFilterTypes.php (for defining the filter types)
 * - ZTeraDB\Exceptions\ValueError (for handling custom error conditions)
 * 
 * Usage:
 * You can create instances of this class to apply filters like equal, greater than, less than, starts with, etc., on a database query.
 * Example:
 *   $filter = new ZTeraDBFilterCondition();
 *   $filter->set_equal_filter('column_name', 'value')
 *          ->set_greater_than_filter([10, 20])
 *          ->set_or_filter([$filter1, $filter2]);
 * 
 * Notes:
 * - Ensure that the values provided are valid types (e.g., string, integer) or fields from your schema.
 * - You can chain multiple filter methods for complex query building.
 */
namespace ZTeraDB\Query;

// Import necessary classes
use ZTeraDB\Exceptions\ValueError;
use ZTeraDB\Query\ZTeraDBFilterTypes;

/**
 * Class ZTeraDBFilterCondition
 * 
 * Provides methods to define and manage filter conditions for database queries.
 * Supports a wide range of operations such as arithmetic, logical, comparison, 
 * and string pattern matching filters to dynamically build complex query conditions.
 * 
 * Key Features:
 * - Arithmetic operations: addition, subtraction, multiplication, division.
 * - Logical operations: AND, OR.
 * - Comparison operations: equal, greater than, less than, greater than or equal, less than or equal.
 * - String pattern matching: contains, starts with, ends with, with both case-sensitive and case-insensitive variants.
 * - Support for filtering fields with dynamic values and other conditions.
 * 
 * @package ZTeraDB\Query
 */
class ZTeraDBFilterCondition {
  /**
   * @var array $filters Stores the list of filter conditions added to the query.
   */
  protected $filters = [];

  /**
   * Validates if a given value is a valid, non-complex type for filter operations.
   * 
   * This method checks whether the provided value is neither an array, an object, 
   * nor a callable function. These types are typically not suitable for direct 
   * inclusion in filter conditions, as they may not be easily represented in 
   * query syntax.
   * 
   * This validation ensures that only scalar values (integers, strings, floats, etc.) 
   * or database fields (non-complex types) are used for filtering operations.
   *
   * @param mixed $value The value to validate. This can be any data type.
   * @return bool Returns true if the value is valid (i.e., not an array, object, or callable), 
   *              false otherwise.
   */
  public function is_valid_value($value) {
    return !is_array($value) && !is_callable($value);
  }

  /**
   * Sets an "ADD" filter operation for the query.
   * 
   * This method allows you to define an addition operation between the provided values 
   * (e.g., adding two or more fields or constants in the query). The values must be 
   * passed as an array, and each value must be valid (non-array, non-object, and non-callable).
   * 
   * If the provided values are valid, they are added to the filter list with the 'ZTADD' operator.
   * This operator is used to instruct the query to perform an addition between the operands.
   *
   * @param array $values The values to be added. This must be an array of scalar values or fields.
   *                      Each item in the array can either be a raw value or an instance of 
   *                      `ZTeraDBFilterCondition` (which can provide additional filter conditions).
   * @throws ValueError If the `$values` is not an array, or if any of the values are invalid.
   * @return $this Returns the current instance to allow method chaining.
   */
  public function set_add_filter($values) {
    // Ensure that the provided values are in an array format
    if (!is_array($values)) {
      throw new ValueError("'values' must be an array for add operation");
    }

    // Prepare an array to store the valid operands for the addition operation
    $operand = [];

    // Validate each value in the array to ensure it's a valid type for the operation
    foreach ($values as $value) {
      // Validate that each value is not an array, object, or callable
      if (!$this->is_valid_value($value)) {
          throw new ValueError("Invalid 'value' for add operation");
      }

      // If the value is an instance of ZTeraDBFilterCondition, get its fields; otherwise, use the value directly
      $operand[] = ($value instanceof self) ? $value->get_fields() : $value;
    }

    // Add the "ADD" operation to the filters array with the prepared operands
    $this->filters[] = ['operator' => ZTeraDBFilterTypes::ZTADD, 'operand' => $operand];

    // Return the current instance to allow method chaining
    return $this;
  }

  /**
   * Sets a "SUBTRACTION" filter operation for the query.
   * 
   * This method defines a subtraction operation between the provided values (e.g., subtracting 
   * one or more fields or constants in the query). The values must be provided as an array, 
   * and each value must be valid (non-array, non-object, and non-callable).
   * 
   * If the values are valid, they are added to the filter list with the 'ZTSUB' operator.
   * This operator indicates that the query should perform a subtraction operation between 
   * the operands.
   *
   * @param array $values The values to be subtracted. This must be an array of scalar values 
   *                      or fields. Each item in the array can be a direct value or an instance 
   *                      of `ZTeraDBFilterCondition` (which may contain additional filter conditions).
   * @throws ValueError If the `$values` is not an array, or if any of the values are invalid 
   *                    (i.e., they are arrays, objects, or callables).
   * @return $this Returns the current instance to enable method chaining.
   */
  public function set_sub_filter($values) {
    // Ensure that the provided values are in an array format
    if (!is_array($values)) {
        throw new ValueError("'values' must be an array for sub operation");
    }

    // Prepare an array to hold valid operands for the subtraction operation
    $operand = [];

    // Validate each value in the array to ensure it's a valid type for subtraction
    foreach ($values as $value) {
      // Ensure the value is not an array, object, or callable
      if (!$this->is_valid_value($value)) {
          throw new ValueError("Invalid '$value' for sub operation");
      }

      // If the value is an instance of ZTeraDBFilterCondition, get its fields; otherwise, use the value directly
      $operand[] = ($value instanceof self) ? $value->get_fields() : $value;
    }

    // Add the "SUBTRACTION" operation to the filters array with the operands
    $this->filters[] = ['operator' => ZTeraDBFilterTypes::ZTSUB, 'operand' => $operand];

    // Return the current instance to allow for method chaining
    return $this;
  }

  /**
   * Sets a "MULTIPLICATION" filter operation for the query.
   * 
   * This method defines a multiplication operation between the provided values (e.g., multiplying 
   * one or more fields or constants in the query). The values must be provided as an array, and each 
   * value must be valid (non-array, non-object, and non-callable).
   * 
   * If the values are valid, they are added to the filter list with the 'ZTMUL' operator.
   * This operator indicates that the query should perform a multiplication operation between 
   * the operands.
   *
   * @param array $values The values to be multiplied. This must be an array of scalar values 
   *                      or fields. Each item in the array can be a direct value or an instance 
   *                      of `ZTeraDBFilterCondition` (which may contain additional filter conditions).
   * @throws ValueError If the `$values` is not an array, or if any of the values are invalid 
   *                    (i.e., they are arrays, objects, or callables).
   * @return $this Returns the current instance to enable method chaining.
   */
  public function set_mul_filter($values) {
    // Ensure that the provided values are in an array format
    if (!is_array($values)) {
      throw new ValueError("'values' must be an array for mul operation");
    }

    // Prepare an array to hold valid operands for the multiplication operation
    $operand = [];

    // Validate each value in the array to ensure it's a valid type for multiplication
    foreach ($values as $value) {
      // Ensure the value is not an array, object, or callable
      if (!$this->is_valid_value($value)) {
        throw new ValueError("Invalid '$value' for mul operation");
      }

      // If the value is an instance of ZTeraDBFilterCondition, get its fields; otherwise, use the value directly
      $operand[] = ($value instanceof self) ? $value->get_fields() : $value;
    }

    // Add the "MULTIPLICATION" operation to the filters array with the operands
    $this->filters[] = ['operator' => ZTeraDBFilterTypes::ZTMUL, 'operand' => $operand];

    // Return the current instance to allow for method chaining
    return $this;
  }

  /**
   * Sets a "DIVISION" filter operation for the query.
   * 
   * This method defines a division operation between the provided dividend and divisor. The dividend 
   * and divisor must be valid values (either numeric values or schema fields). Additionally, the divisor 
   * must not be zero to avoid division by zero errors.
   * 
   * Once validated, the method adds the division operation to the filter list with the 'ZTDIV' operator,
   * indicating that the query should perform a division operation between the provided operands.
   *
   * @param mixed $dividend The value (or field) to be divided. This must be either a numeric value 
   *                        or a valid schema field (an instance of `ZTeraDBFilterCondition`).
   * @param mixed $divisor The value (or field) to divide by. This must be either a numeric value 
   *                       or a valid schema field, and it must not be zero.
   * @throws ValueError If the `$dividend` or `$divisor` is invalid (not a numeric value or a valid schema field), 
   *                    or if the `$divisor` is zero (division by zero is not allowed).
   * @return $this Returns the current instance to enable method chaining.
   */
  public function set_div_filter($dividend, $divisor) {
    // Validate the dividend to ensure it's a valid numeric value or schema field
    if (!$this->is_valid_value($dividend)) {
      throw new ValueError("'dividend' must be numeric or schema field.");
    }

    // Validate the divisor to ensure it's a valid numeric value or schema field
    if (!$this->is_valid_value($divisor)) {
        throw new ValueError("'divisor' must be numeric or schema field.");
    }

    // If the dividend or divisor is an instance of ZTeraDBFilterCondition, retrieve their filter fields
    $dividend = ($dividend instanceof self) ? $dividend->get_fields() : $dividend;
    $divisor = ($divisor instanceof self) ? $divisor->get_fields() : $divisor;

    // Add the "DIVISION" operation to the filters array with the dividend and divisor as operands
    $this->filters[] = ['operator' => ZTeraDBFilterTypes::ZTDIV, 'operand' => [$dividend, $divisor]];

    // Return the current instance to allow for method chaining
    return $this;
  }

  /**
   * Retrieves the list of filter conditions set for the query.
   * 
   * This method returns the `$filters` array, which contains all the filter operations 
   * that have been defined for the current query. The filters are stored as an array of 
   * associative arrays, where each entry represents an individual filter with its operator 
   * and operands.
   * 
   * The filters can be used by the database query engine to apply conditions during query 
   * execution, such as filtering records based on various criteria (e.g., equality, range, 
   * pattern matching, mathematical operations).
   *
   * @return array The array of filter conditions currently set for the query. Each entry in 
   *               the array is an associative array with keys 'operator' and 'operand' (and 
   *               possibly 'result' or other keys depending on the filter type).
   */
  public function get_fields() {
    // Return the list of filters applied to the query
    return (sizeof($this->filters) > 1) ? $this->filters: $this->filters[0];
  }

  /**
   * Sets an equality filter condition for the query.
   * 
   * This method adds a filter to the query where the value of a field (or parameter) is 
   * compared for equality with a given result. The filter checks if the field's value 
   * is equal to the provided value (`result`). The equality operation uses the 
   * `ZTeraDBFilterTypes::ZTEQUAL` operator.
   * 
   * It supports using both values directly or other filter objects as the parameters 
   * and result. If the parameters are filter objects themselves, their internal fields 
   * are retrieved and used for the comparison.
   *
   * Throws an error if either the `param` or `result` is invalid.
   * 
   * @param mixed $param The field or value to be compared in the equality filter.
   *                      Can be a schema field name, a value, or another filter object.
   * @param mixed $result The value to compare against. Can be a value or another filter object.
   * 
   * @return $this The current instance of the filter condition, allowing for method chaining.
   * 
   * @throws ValueError If the `param` or `result` are invalid values (non-scalar and non-callable).
   */
  public function set_equal_filter($param, $result) {
    // Validate the param
    if (!$this->is_valid_value($param)) {
        throw new ValueError("Invalid 'param' argument");
    }

    // Validate the result
    if (!$this->is_valid_value($result)) {
        throw new ValueError("Invalid 'result' argument");
    }

    // If the parameters are filter objects, retrieve their fields
    $param = ($param instanceof self) ? $param->get_fields() : $param;
    $result = ($result instanceof self) ? $result->get_fields() : $result;

    // Add the equality filter to the list of filters
    $this->filters[] = [
      'operator'=>ZTeraDBFilterTypes::ZTEQUAL, 
      'operand'=>$param, 
      'result'=>$result
    ];

    // Return the current object to allow method chaining
    return $this;
  }

  /**
   * Sets a modulus filter condition for the query.
   * 
   * This method adds a filter to the query that checks if the modulus of a field or value 
   * (numerator) divided by another field or value (denominator) meets the condition. 
   * The modulus operation uses the `ZTeraDBFilterTypes::ZTMOD` operator.
   * 
   * It supports using either direct values or other filter objects as the numerator and 
   * denominator. If either parameter is a filter object, its internal fields are retrieved 
   * and used for the modulus operation.
   * 
   * If the denominator is zero, an error is thrown to avoid division by zero.
   * 
   * Throws an error if either the numerator or denominator is invalid.
   * 
   * @param mixed $numerator The field or value to be divided. Can be a schema field name, 
   *                         a value, or another filter object.
   * @param mixed $denominator The value to divide by. Can be a value, field name, or 
   *                           another filter object.
   * 
   * @return $this The current instance of the filter condition, allowing for method chaining.
   * 
   * @throws ValueError If the numerator or denominator is invalid (not numeric or schema field),
   *                    or if the denominator is zero.
   */
  public function set_mod_filter($numerator, $denominator) {
    // Validate that the numerator is a valid value
    if (!$this->is_valid_value($numerator)) {
        throw new ValueError("'numerator' must be numeric or schema field.");
    }

    // Validate that the numerator is a valid value
    if (!$this->is_valid_value($denominator)) {
        throw new ValueError("'denominator' must be numeric or schema field.");
    }

    // Denominator must not be zero
    // if ($denominator == 0) {
    //     throw new ValueError("'denominator' must be greater than 0.");
    // }

    // If numerator or denominator are filter objects, get their fields
    $numerator = ($numerator instanceof self) ? $numerator->get_fields() : $numerator;
    $result = ($denominator instanceof self) ? $denominator->get_fields() : $denominator;

    // Add the modulus filter to the list of filters
    $this->filters[] = [
        'operator'=>ZTeraDBFilterTypes::ZTMOD, 
        'operand'=>[$numerator, $denominator]
    ];

    // Return the current object to allow method chaining
    return $this;
  }

  /**
   * Sets an "IN" filter condition for the query.
   * 
   * This method adds a filter to the query that checks if a given field's value 
   * is within a specified list of values. The "IN" filter uses the `ZTeraDBFilterTypes::ZTIN` 
   * operator to perform the comparison.
   * 
   * The field must be a valid schema field name (a string), and the values should 
   * be provided as an array of acceptable values. If either parameter is invalid, 
   * an error will be thrown.
   * 
   * Supports using other filter objects as values; if the field or values are 
   * filter objects, their internal fields are used.
   * 
   * Throws an error if the field is not a string or is empty, or if the values 
   * parameter is not an array.
   * 
   * @param string $field The field name to apply the "IN" filter on. Must be a 
   *                      valid schema field name (non-empty string).
   * @param array  $values The list of values to check the field against. Must be an array.
   * 
   * @return $this The current instance of the filter condition, enabling method chaining.
   * 
   * @throws ValueError If the field is not a valid string or if the values parameter is not an array.
   */
  public function set_in_filter($field, $values) {
    // Ensure the field is a valid non-empty string (schema field name)
    if (!is_string($field) || empty($field)) {
        throw new ValueError("'IN' filter field must be schema field name");
    }

    // Ensure values is an array
    if (!is_array($values)) {
        throw new ValueError("'IN' filter values must be an array");
    }

    // If the field or values are filter objects, get their fields
    $field = ($field instanceof self) ? $field->get_fields() : $field;
    $values = ($values instanceof self) ? $values->get_fields() : $values;

    // Add the "IN" filter to the list of filters
    $this->filters[] = [
        'operator'=>ZTeraDBFilterTypes::ZTIN, 
        'operand'=>$field, 
        'result'=>$values
    ];

    // Return the current instance for method chaining
    return $this;
  }

  /**
   * Sets an "OR" filter condition for the query.
   * 
   * This method adds a filter to the query that applies an "OR" condition 
   * to a list of filters. The "OR" operator will return true if any of 
   * the conditions in the filter list are true. The filter conditions are 
   * combined using the `ZTeraDBFilterTypes::ZTOR` operator.
   * 
   * The `$filters` parameter must be an array of conditions (filters). 
   * Each condition can either be a raw filter or another filter object.
   * If any filter is an object, its internal fields are used in the operation.
   * 
   * Throws an error if the `$filters` parameter is not an array.
   * 
   * @param array $filters A list of filters that will be combined with an "OR" operator.
   *                       Each element can be a filter object or a raw filter condition.
   * 
   * @return $this The current instance of the filter condition, enabling method chaining.
   * 
   * @throws ValueError If the `$filters` parameter is not an array.
   */
  public function set_or_filter($filters) {
    // Ensure the filters are provided as an array
    if(!is_array($filters)) {
      throw new ValueError("The 'OR' filter must be list");
    }

    // Prepare an array to store the valid operands for the or operation
    $operand = [];

    // Loop through each filter and add to operand, resolving filter objects if necessary
    foreach($filters as $filter) {
      $operand[] = ($filter instanceof self) ? $filter->get_fields() : $filter;
    }

    // Add the "OR" filter to the list of filters
    $this->filters[] = [
        'operator'=>ZTeraDBFilterTypes::ZTOR,
        'operand'=>$operand
    ];

    // Return the current instance for method chaining
    return $this;
  }

  /**
   * Sets an "AND" filter condition for the query.
   * 
   * This method adds a filter to the query that applies an "AND" condition 
   * to a list of filters. The "AND" operator ensures that all conditions 
   * in the filter list must be true for the overall condition to be true. 
   * The filter conditions are combined using the `ZTeraDBFilterTypes::ZTAND` operator.
   * 
   * The `$filters` parameter must be an array of conditions (filters). 
   * Each condition can either be a raw filter or another filter object.
   * If any filter is an object, its internal fields are used in the operation.
   * 
   * Throws an error if the `$filters` parameter is not an array.
   * 
   * @param array $filters A list of filters that will be combined with an "AND" operator.
   *                       Each element can be a filter object or a raw filter condition.
   * 
   * @return $this The current instance of the filter condition, enabling method chaining.
   * 
   * @throws ValueError If the `$filters` parameter is not an array.
   */
  public function set_and_filter($filters) {
    // Ensure the filters are provided as an array
    if(!is_array($filters)) {
      throw new ValueError("The 'AND' filter must be list");
    }

    // Prepare an array to store the valid operands for the and operation
    $operand = [];

    // Loop through each filter and add to operand, resolving filter objects if necessary
    foreach($filters as $filter) {
      $operand[] = ($filter instanceof self) ? $filter->get_fields() : $filter;
    }

    // Add the "AND" filter to the list of filters
    $this->filters[] = [
        'operator'=>ZTeraDBFilterTypes::ZTAND,
        'operand'=>$operand
    ];

    // Return the current instance for method chaining
    return $this;
  }

  /**
   * Sets a "contains" filter condition for the query.
   * 
   * This method adds a filter to the query that checks whether a given field
   * contains a specific value. It uses the `ZTeraDBFilterTypes::ZTCONTAINS` operator 
   * to represent the "contains" condition in the query.
   * 
   * The `$field` parameter should be the name of the field you want to filter by, 
   * and the `$value` parameter should be the value you are checking for in that field.
   * 
   * Both parameters must be strings. If either parameter is null or not a string, 
   * an error will be thrown.
   * 
   * @param string $field The field name in which to check for the presence of the `$value`.
   * @param string $value The value to check for in the specified `$field`.
   * 
   * @return void This method does not return anything; it modifies the internal `filters` array.
   * 
   * @throws ValueError If the `$field` or `$value` parameters are null or not strings.
   */
  public function set_contains_filter($field, $value) {
    // Validate the 'field' parameter
    if(is_null($field) || !is_string($field)) {
      throw new ValueError("The `field` argument must be field name in contains filter.");
    }

    // Validate the 'value' parameter
    if(is_null($value) || !is_string($value)) {
      throw new ValueError("The `value` argument must be field name in contains filter.");
    }

    // Add the "contains" filter condition to the filters array
    $this->filters[] = [
      'operator'=>ZTeraDBFilterTypes::ZTCONTAINS,
      'operand'=>$field,
      'result'=>$value,
    ];

    // Return the current instance for method chaining
    return $this;
  }

  /**
   * Sets an "iContains" (case-insensitive contains) filter condition for the query.
   * 
   * This method adds a filter to the query that checks whether a given field
   * contains a specific value in a case-insensitive manner. It uses the 
   * `ZTeraDBFilterTypes::ZTICONTAINS` operator to represent the "iContains" condition 
   * in the query.
   * 
   * The `$field` parameter should be the name of the field you want to filter by, 
   * and the `$value` parameter should be the value you are checking for in that field.
   * Both parameters must be strings. If either parameter is null or not a string, 
   * an error will be thrown.
   * 
   * @param string $field The field name in which to check for the presence of the `$value`.
   * @param string $value The value to check for in the specified `$field`.
   * 
   * @return self Returns the current instance to allow for method chaining.
   * 
   * @throws ValueError If the `$field` or `$value` parameters are null or not strings.
   */
  public function set_icontains_filter($field, $value) {
    // Validate the 'field' parameter
    if(is_null($field) || !is_string($field)) {
      throw new ValueError("The `field` argument must be field name in icontains filter.");
    }

    // Validate the 'value' parameter
    if(is_null($field) || !is_string($field)) {
      throw new ValueError("The `field` argument must be field name in icontains filter.");
    }

    // Add the "iContains" filter condition to the filters array
    $this->filters[] = [
      'operator'=>ZTeraDBFilterTypes::ZTICONTAINS,
      'operand'=>$field,
      'result'=>$value,
    ];

    // Return the current instance for method chaining
    return $this;
  }

  /**
   * Sets a "starts with" filter condition for the query.
   * 
   * This method adds a filter to the query that checks whether a given field
   * starts with a specific value. It uses the `ZTeraDBFilterTypes::ZTSTARTSWITH`
   * operator to represent the "starts with" condition in the query.
   * 
   * The `$field` parameter should be the name of the field you want to filter by, 
   * and the `$value` parameter should be the value that the field should start with.
   * Both parameters must be strings. If either parameter is null or not a string, 
   * an error will be thrown.
   * 
   * @param string $field The field name to check for the prefix match.
   * @param string $value The value that the `$field` should start with.
   * 
   * @return self Returns the current instance to allow for method chaining.
   * 
   * @throws ValueError If the `$field` or `$value` parameters are null or not strings.
   */
  public function set_starts_with_filter($field, $value) {
    // Validate the 'field' parameter
    if(is_null($field) || !is_string($field)) {
      throw new ValueError("The `field` argument must be field name in starts with filter.");
    }

    // Validate the 'value' parameter
    if(is_null($value) || !is_string($value)) {
      throw new ValueError("The `value` argument must be string in starts with filter.");
    }

    // Add the "starts with" filter condition to the filters array
    $this->filters[] = [
      'operator'=>ZTeraDBFilterTypes::ZTSTARTSWITH,
      'operand'=>$field,
      'result'=>$value,
    ];

    // Return the current instance for method chaining
    return $this;
  }

  /**
   * Sets a "case-insensitive starts with" filter condition for the query.
   * 
   * This method adds a filter to the query that checks whether a given field
   * case-insensitively starts with a specific value. It uses the `ZTeraDBFilterTypes::ZTISTARTSWITH`
   * operator to represent the "case-insensitive starts with" condition in the query.
   * 
   * The `$field` parameter should be the name of the field you want to filter by, 
   * and the `$value` parameter should be the value that the field should start with.
   * Both parameters must be strings. If either parameter is null or not a string, 
   * an error will be thrown.
   * 
   * @param string $field The field name to check for the prefix match.
   * @param string $value The value that the `$field` should case-insensitively start with.
   * 
   * @return self Returns the current instance to allow for method chaining.
   * 
   * @throws ValueError If the `$field` or `$value` parameters are null or not strings.
   */
  public function set_istarts_with_filter($field, $value) {
    // Validate the 'field' parameter
    if(is_null($field) || !is_string($field)) {
      throw new ValueError("The `field` argument must be field name in istarts with filter.");
    }

    // Validate the 'value' parameter
    if(is_null($value) || !is_string($value)) {
      throw new ValueError("The `value` argument must be string in istarts with filter.");
    }

    // Add the "case-insensitive starts with" filter condition to the filters array
    $this->filters[] = [
      'operator'=>ZTeraDBFilterTypes::ZTISTARTSWITH,
      'operand'=>$field,
      'result'=>$value,
    ];

    // Return the current instance for method chaining
    return $this;
  }

  /**
   * Sets an "ends with" filter condition for the query.
   * 
   * This method adds a filter to the query that checks whether a given field
   * ends with a specific value. It uses the `ZTeraDBFilterTypes::ZTENDSWITH`
   * operator to represent the "ends with" condition in the query.
   * 
   * The `$field` parameter should be the name of the field you want to filter by, 
   * and the `$value` parameter should be the value that the field should end with.
   * Both parameters must be strings. If either parameter is null or not a string, 
   * an error will be thrown.
   * 
   * @param string $field The field name to check for the suffix match.
   * @param string $value The value that the `$field` should end with.
   * 
   * @return self Returns the current instance to allow for method chaining.
   * 
   * @throws ValueError If the `$field` or `$value` parameters are null or not strings.
   */
  public function set_ends_with_filter($field, $value) {
    // Validate the 'field' parameter
    if(is_null($field) || !is_string($field)) {
      throw new ValueError("The `field` argument must be field name in ends with filter.");
    }

    // Validate the 'value' parameter
    if(is_null($value) || !is_string($value)) {
      throw new ValueError("The `value` argument must be string in ends with filter.");
    }

    // Add the "ends with" filter condition to the filters array
    $this->filters[] = [
      'operator'=>ZTeraDBFilterTypes::ZTENDSWITH,
      'operand'=>$field,
      'result'=>$value,
    ];

    // Return the current instance for method chaining
    return $this;
  }

  /**
   * Sets a case-insensitive "ends with" filter condition for the query.
   * 
   * This method adds a filter to the query that checks whether a given field
   * ends with a specific value, but in a case-insensitive manner. It uses the 
   * `ZTeraDBFilterTypes::ZTIENDSWITH` operator to represent the "case-insensitive 
   * ends with" condition in the query.
   * 
   * The `$field` parameter should be the name of the field you want to filter by, 
   * and the `$value` parameter should be the value that the field should end with. 
   * Both parameters must be strings. If either parameter is null or not a string, 
   * an error will be thrown.
   * 
   * @param string $field The field name to check for the case-insensitive suffix match.
   * @param string $value The value that the `$field` should end with (case-insensitive).
   * 
   * @return self Returns the current instance to allow for method chaining.
   * 
   * @throws ValueError If the `$field` or `$value` parameters are null or not strings.
   */
  public function set_iends_with_filter($field, $value) {
    // Validate the 'field' parameter
    if(is_null($field) || !is_string($field)) {
      throw new ValueError("The `field` argument must be field name in iends with filter.");
    }

    // Validate the 'value' parameter
    if(is_null($value) || !is_string($value)) {
      throw new ValueError("The `value` argument must be string in iends with filter.");
    }

    // Add the case-insensitive "ends with" filter condition to the filters array
    $this->filters[] = [
      'operator'=>ZTeraDBFilterTypes::ZTIENDSWITH,
      'operand'=>$field,
      'result'=>$value,
    ];

    // Return the current instance for method chaining
    return $this;
  }

  /**
   * Sets a "greater than" filter condition for the query.
   * 
   * This method adds a filter that checks whether the specified field(s) are 
   * greater than the given value(s). The filter expects an array of parameters 
   * (`$params`) where the first element is the field to compare, and the second 
   * element is the value to compare against. It uses the `ZTeraDBFilterTypes::ZTGT` 
   * operator to represent the "greater than" condition in the query.
   * 
   * The `$params` array should contain at least two elements:
   * - The first element is the field name to compare.
   * - The second element is the value that the field should be greater than.
   * 
   * @param array $params An array with at least two elements: 
   *                      - the field to compare, and 
   *                      - the value to compare it against.
   * 
   * @return self Returns the current instance to allow for method chaining.
   * 
   * @throws ValueError If the `$params` is not an array or if it contains fewer than two elements.
   */
  public function set_greater_than_filter($params) {
    // Ensure that $params is an array
    if(!is_array($params)) {
      throw new ValueError("The 'Greater than' filter params must be list");
    }

    // Ensure that $params contains at least two elements
    if(count($params) < 2) {
      throw new ValueError("The 'Greater than' filter params must contains at-least two element in the list");
    }

    // Prepare an array to store the valid operands for the greater than operation
    $operand = [];

    // Loop through $params and ensure each value is valid
    foreach($params as $param) {
      $operand[] = ($param instanceof self) ? $param->get_fields() : $param;
    }

    // Add the "greater than" filter condition to the filters array
    $this->filters[] = [
        'operator'=>ZTeraDBFilterTypes::ZTGT, 
        'operand'=>$operand
    ];

    // Return the current instance for method chaining
    return $this;
  }

  /**
   * Sets a "greater than or equal to" filter condition for the query.
   * 
   * This method adds a filter that checks whether the specified field(s) are 
   * greater than or equal to the given value(s). The filter expects an array of 
   * parameters (`$params`) where the first element is the field to compare, 
   * and the second element is the value to compare against. It uses the 
   * `ZTeraDBFilterTypes::ZTGTE` operator to represent the "greater than or equal to" 
   * condition in the query.
   * 
   * The `$params` array should contain at least two elements:
   * - The first element is the field name to compare.
   * - The second element is the value that the field should be greater than or equal to.
   * 
   * @param array $params An array with at least two elements: 
   *                      - the field to compare, and 
   *                      - the value to compare it against.
   * 
   * @return self Returns the current instance to allow for method chaining.
   * 
   * @throws ValueError If the `$params` is not an array or if it contains fewer than two elements.
   */
  public function set_greater_than_or_equal_filter($params) {
    // Ensure that $params is an array
    if(!is_array($params)) {
      throw new ValueError("The 'Greater than or equal' filter params must be list");
    }

    // Ensure that $params contains at least two elements
    if(count($params) < 2) {
      throw new ValueError("The 'Greater than or equal' filter params must contains at-least two element in the list");
    }

    // Prepare an array to store the valid operands for the greater than or equal operation
    $operand = [];

    // Loop through $params and ensure each value is valid
    foreach($params as $param) {
      $operand[] = ($param instanceof self) ? $param->get_fields() : $param;
    }

    // Add the "greater than or equal to" filter condition to the filters array
    $this->filters[] = [
        'operator'=>ZTeraDBFilterTypes::ZTGTE, 
        'operand'=>$operand
    ];

    // Return the current instance for method chaining
    return $this;
  }

  /**
   * Sets a "less than" filter condition for the query.
   * 
   * This method adds a filter that checks whether the specified field(s) are 
   * less than the given value(s). The filter expects an array of parameters (`$params`) 
   * where the first element is the field to compare, and the second element is 
   * the value to compare against. It uses the `ZTeraDBFilterTypes::ZTLT` operator 
   * to represent the "less than" condition in the query.
   * 
   * The `$params` array should contain at least two elements:
   * - The first element is the field name to compare.
   * - The second element is the value that the field should be less than.
   * 
   * @param array $params An array with at least two elements: 
   *                      - the field to compare, and 
   *                      - the value to compare it against.
   * 
   * @return self Returns the current instance to allow for method chaining.
   * 
   * @throws ValueError If the `$params` is not an array or if it contains fewer than two elements.
   */
  public function set_less_than_filter($params) {
    // Ensure that $params is an array
    if(!is_array($params)) {
      throw new ValueError("The 'Less than' filter params must be list");
    }

    // Ensure that $params contains at least two elements
    if(count($params) < 2) {
      throw new ValueError("The 'Less than' filter params must contains at-least two element in the list");
    }

    // Prepare an array to store the valid operands for the less than operation
    $operand = [];

    // Loop through $params and ensure each value is valid
    foreach($params as $param) {
      $operand[] = ($param instanceof self) ? $param->get_fields() : $param;
    }

    // Add the "less than" filter condition to the filters array
    $this->filters[] = [
        'operator'=>ZTeraDBFilterTypes::ZTLT, 
        'operand'=>$operand
    ];

    // Return the current instance for method chaining
    return $this;
  }

  /**
   * Sets a "less than or equal" filter condition for the query.
   * 
   * This method adds a filter that checks whether the specified field(s) are 
   * less than or equal to the given value(s). The filter expects an array of parameters (`$params`) 
   * where the first element is the field to compare, and the second element is 
   * the value to compare against. It uses the `ZTeraDBFilterTypes::ZTLTE` operator 
   * to represent the "less than or equal" condition in the query.
   * 
   * The `$params` array should contain at least two elements:
   * - The first element is the field name to compare.
   * - The second element is the value that the field should be less than or equal to.
   * 
   * @param array $params An array with at least two elements:
   *                      - the field to compare, and 
   *                      - the value to compare it against.
   * 
   * @return self Returns the current instance to allow for method chaining.
   * 
   * @throws ValueError If the `$params` is not an array or if it contains fewer than two elements.
   */
  public function set_less_than_or_equal_filter($params) {
    // Ensure that $params is an array
    if(!is_array($params)) {
      throw new ValueError("The 'Less than or equal' filter params must be list");
    }

    // Ensure that $params contains at least two elements
    if(count($params) < 2) {
      throw new ValueError("The 'Less than or equal' filter params must contains at-least two element in the list");
    }

    // Prepare an array to store the valid operands for the less than or equal operation
    $operand = [];

    // Loop through $params and ensure each value is valid
    foreach($params as $param) {
      $operand[] = ($param instanceof self) ? $param->get_fields() : $param;
    }

    // Add the "less than or equal" filter condition to the filters array
    $this->filters[] = [
      'operator'=>ZTeraDBFilterTypes::ZTLTE, 
      'operand'=>$operand
    ];

    // Return the current instance for method chaining
    return $this;
  }
}

?>