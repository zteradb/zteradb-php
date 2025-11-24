<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB Query Filter Condition Functions
 * --------------------------------------------------------------------------
 *
 *  This file contains a set of helper functions that construct filter conditions
 *  for querying the ZTeraDB database. These functions support various logical 
 *  and comparison operators to be used in queries, such as AND, OR, EQUAL, IN, 
 *  arithmetic operations, and string matching conditions.
 *
 *  Key Functions:
 *  ---------------
 *  - ZTAND($filters)  
 *      Constructs a filter condition using the "AND" logical operator. Accepts 
 *      an array of conditions, which can be field names, numeric values, or 
 *      instances of `ZTeraDBFilterCondition`.
 *
 *  - ZTOR($filters)  
 *      Constructs a filter condition using the "OR" logical operator. Works 
 *      similarly to `ZTAND()` but applies the "OR" operator between conditions.
 *
 *  - ZTEQUAL($param, $result)  
 *      Constructs a filter condition for equality. Compares `$param` with `$result` 
 *      for equality, where both can be field names, numeric values, or 
 *      instances of `ZTeraDBFilterCondition`.
 *
 *  - ZTIN($key, $value)  
 *      Constructs a filter condition using the "IN" operator. `$key` is a field 
 *      name, and `$value` is an array of values to check if the field is present in.
 *
 *  - ZTADD($values), ZTSUB($values), ZTMUL($values), ZTDIV($dividend, $divisor), 
 *    ZTMOD($numerator, $denominator)  
 *      Constructs filter conditions for various arithmetic operations (addition, 
 *      subtraction, multiplication, division, modulus). The `$values` or operands 
 *      can be strings, numbers, or `ZTeraDBFilterCondition` instances.
 *
 *  - ZTGT($params), ZTGTE($params), ZTLT($params), ZTLTE($params)  
 *      Constructs filter conditions for comparison operations: "GREATER THAN", 
 *      "GREATER THAN OR EQUAL", "LESS THAN", and "LESS THAN OR EQUAL". The `$params`
 *      argument is an array with at least two elements for comparison.
 *
 *  - ZTCONTAINS($field, $value), ZTICONTAINS($field, $value)  
 *      Constructs a filter condition for the "CONTAINS" operation, both case-sensitive 
 *      (`ZTCONTAINS`) and case-insensitive (`ZTICONTAINS`).
 *
 *  - ZTSTARTSWITH($field, $value), ZTISTARTSWITH($field, $value)  
 *      Constructs a filter condition for the "STARTS WITH" operation, both case-sensitive 
 *      (`ZTSTARTSWITH`) and case-insensitive (`ZTISTARTSWITH`).
 *
 *  - ZTENDSWITH($field, $value), ZTIENDSWITH($field, $value)  
 *      Constructs a filter condition for the "ENDS WITH" operation, both case-sensitive 
 *      (`ZTENDSWITH`) and case-insensitive (`ZTIENDSWITH`).
 *
 *  All functions return an instance of `ZTeraDBFilterCondition`, which encapsulates 
 *  the specific filter logic for the database query.
 *
 *  Requirements:
 *  -------------
 *  - `ZTeraDBFilterCondition` must be defined and have methods to handle setting 
 *    different types of filters (e.g., `set_and_filter`, `set_or_filter`, etc.).
 *  - The functions in this file are designed to make constructing complex queries 
 *    more intuitive and flexible.
 *
 * --------------------------------------------------------------------------
 *  @package     ZTeraDB
 *  @subpackage  Query
 *  @version     1.0
 *  @author      ZTeraDB
 *  @license     https://zteradb.com/licence   (SPDX-License-Identifier: Proprietary)
 * --------------------------------------------------------------------------
 */
namespace ZTeraDB\Query;

// Import ZTeraDBFilterCondition class
use ZTeraDB\Query\ZTeraDBFilterCondition;

/**
 * This function constructs a filter condition using the "AND" logical operator.
 * The argument $filters is an array, where the values can be database field values, 
 * numeric expressions, or instances of ZTeraDBFilterCondition.
 *
 * @param array $filters - The filter conditions for the "AND" operation.
 * @return ZTeraDBFilterCondition - The filter condition object.
 */
function ZTAND($filters) {
  // Create a new instance of ZTeraDBFilterCondition
  $ZTC = new ZTeraDBFilterCondition();

  // Set the "AND" filter condition with the provided filters array
  $ZTC->set_and_filter($filters);

  // Return the created ZTeraDBFilterCondition object
  return $ZTC;
}

/**
 * This function constructs a filter condition using the "OR" logical operator.
 * The argument $filters is an array, where the values can be database field values, 
 * numeric expressions, or instances of ZTeraDBFilterCondition.
 *
 * @param array $filters - The filter conditions for the "OR" operation.
 * @return ZTeraDBFilterCondition - The filter condition object.
 */
function ZTOR($filters) {
  // Create a new instance of ZTeraDBFilterCondition
  $ZTC = new ZTeraDBFilterCondition();

  // Set the "OR" filter condition with the provided filters array
  $ZTC->set_or_filter($filters);

  // Return the created ZTeraDBFilterCondition object
  return $ZTC;
}

/**
 * This function constructs a filter condition using the "EQUAL" operator.
 * It compares a given parameter with a result value for equality. 
 * The parameter and result can be a field name, a numeric value, or an instance of ZTeraDBFilterCondition.
 *
 * @param mixed $param - The parameter (field, numeric value, or ZTeraDBFilterCondition) to be compared.
 * @param mixed $result - The value (field, numeric value, or ZTeraDBFilterCondition) to compare the parameter against.
 * @return ZTeraDBFilterCondition - The filter condition object with the equality condition.
 */
function ZTEQUAL($param, $result) {
  // Create a new instance of ZTeraDBFilterCondition
  $ZTC = new ZTeraDBFilterCondition();

  // Set the "EQUAL" filter with the provided $param and $result
  $ZTC->set_equal_filter($param, $result);

  // Return the created ZTeraDBFilterCondition object
  return $ZTC;
}

/**
 * This function constructs a filter condition using the "IN" operator.
 * The key must be a string (typically a field name), and the value should be an array of values 
 * to check if the key is present in the array.
 *
 * @param string $key - The field name (as a string) to be checked.
 * @param array $value - The array of values to compare against the field (key).
 * @return ZTeraDBFilterCondition - The filter condition object with the "IN" condition.
 */
function ZTIN($key, $values) {
  // Create a new instance of ZTeraDBFilterCondition
  $ZTC = new ZTeraDBFilterCondition();

  // Set the "IN" filter condition with the provided key (field) and value (array of values)
  $ZTC->set_in_filter($key, $values);

  // Return the created ZTeraDBFilterCondition object
  return $ZTC;
}

/**
 * This function constructs a filter condition for the "ADD" operation.
 * The $values can be a combination of strings, numbers, or instances of ZTeraDBFilterCondition.
 * It adds the specified values to the filter condition.
 *
 * @param array $values - The values to be added. These can be strings, numbers, or ZTeraDBFilterCondition objects.
 * @return ZTeraDBFilterCondition - The filter condition object with the added values.
 */
function ZTADD($values) {
  // Create a new instance of ZTeraDBFilterCondition
  $ZTC = new ZTeraDBFilterCondition();

  // Set the "ADD" filter condition with the provided values
  $ZTC->set_add_filter($values);

  // Return the created ZTeraDBFilterCondition object
  return $ZTC;
}

/**
 * This function constructs a filter condition for the "SUB" (subtract) operation.
 * The $values can be a combination of strings, numbers, or instances of ZTeraDBFilterCondition.
 * It applies the "SUB" operation using the specified values.
 *
 * @param array $values - The values to be subtracted. These can be strings, numbers, or ZTeraDBFilterCondition objects.
 * @return ZTeraDBFilterCondition - The filter condition object with the "SUB" operation applied.
 */
function ZTSUB($values) {
  // Create a new instance of ZTeraDBFilterCondition
  $ZTC = new ZTeraDBFilterCondition();

  // Set the "SUB" filter condition with the provided values
  $ZTC->set_sub_filter($values);

  // Return the created ZTeraDBFilterCondition object
  return $ZTC;
}

/**
 * This function constructs a filter condition for the "MUL" (multiplication) operation.
 * The $values can be a combination of strings, numbers, or instances of ZTeraDBFilterCondition.
 * It applies the "MUL" operation using the specified values.
 *
 * @param array $values - The values to be multiplied. These can be strings, numbers, or ZTeraDBFilterCondition objects.
 * @return ZTeraDBFilterCondition - The filter condition object with the "MUL" operation applied.
 */
function ZTMUL($values) {
  // Create a new instance of ZTeraDBFilterCondition
  $ZTC = new ZTeraDBFilterCondition();

  // Set the "MUL" filter condition with the provided values
  $ZTC->set_mul_filter($values);

  // Return the created ZTeraDBFilterCondition object
  return $ZTC;
}

/**
 * This function constructs a filter condition for the "DIV" (division) operation.
 * The $dividend and $divisor can be a combination of strings, numbers, or instances of ZTeraDBFilterCondition.
 * It applies the "DIV" operation using the provided dividend and divisor values.
 *
 * @param mixed $dividend - The value to be divided. This can be a string, number, or ZTeraDBFilterCondition object.
 * @param mixed $divisor - The value to divide by. This can be a string, number, or ZTeraDBFilterCondition object.
 * @return ZTeraDBFilterCondition - The filter condition object with the "DIV" operation applied.
 */
function ZTDIV($dividend, $divisor) {
  // Create a new instance of ZTeraDBFilterCondition
  $ZTC = new ZTeraDBFilterCondition();

  // Set the "DIV" filter condition with the provided dividend and divisor
  $ZTC->set_div_filter($dividend, $divisor);

  // Return the created ZTeraDBFilterCondition object
  return $ZTC;
}

/**
 * This function constructs a filter condition for the "MOD" (modulus) operation.
 * The $numerator and $denominator can be a combination of strings, numbers, or instances of ZTeraDBFilterCondition.
 * It applies the "MOD" operation using the provided numerator and denominator values.
 *
 * @param mixed $numerator - The value to be divided. This can be a string, number, or ZTeraDBFilterCondition object.
 * @param mixed $denominator - The value to divide by. This can be a string, number, or ZTeraDBFilterCondition object.
 * @return ZTeraDBFilterCondition - The filter condition object with the "MOD" operation applied.
 */
function ZTMOD($numerator, $denominator) {
  // Create a new instance of ZTeraDBFilterCondition
  $ZTC = new ZTeraDBFilterCondition();

  // Set the "MOD" filter condition with the provided numerator and denominator
  $ZTC->set_mod_filter($numerator, $denominator);

  // Return the created ZTeraDBFilterCondition object
  return $ZTC;
}

/**
 * This function constructs a filter condition for the "GREATER THAN" operation.
 * The $params argument must be an array with at least two elements. 
 * Each element can be a string, number, or instance of ZTeraDBFilterCondition.
 * It applies the "GREATER THAN" filter using the provided parameters.
 *
 * @param array $params - An array with at least two elements. 
 *                        Each element can be a string, number, or ZTeraDBFilterCondition.
 * @return ZTeraDBFilterCondition - The filter condition object with the "GREATER THAN" filter applied.
 */
function ZTGT($params) {
  // Create a new instance of ZTeraDBFilterCondition
  $ZTC = new ZTeraDBFilterCondition();

  // Set the "GREATER THAN" filter condition with the provided parameters
  $ZTC->set_greater_than_filter($params);

  // Return the created ZTeraDBFilterCondition object
  return $ZTC;
}

/**
 * This function constructs a filter condition for the "GREATER THAN OR EQUAL" operation.
 * The $params argument must be an array with at least two elements. 
 * Each element can be a string, number, or instance of ZTeraDBFilterCondition.
 * It applies the "GREATER THAN OR EQUAL" filter using the provided parameters.
 *
 * @param array $params - An array with at least two elements. 
 *                        Each element can be a string, number, or ZTeraDBFilterCondition.
 * @return ZTeraDBFilterCondition - The filter condition object with the "GREATER THAN OR EQUAL" filter applied.
 */
function ZTGTE($params) {
  // Create a new instance of ZTeraDBFilterCondition
  $ZTC = new ZTeraDBFilterCondition();

  // Set the "GREATER THAN OR EQUAL" filter condition with the provided parameters
  $ZTC->set_greater_than_or_equal_filter($params);

  // Return the created ZTeraDBFilterCondition object
  return $ZTC;
}

/**
 * This function constructs a filter condition for the "LESS THAN" operation.
 * The $params argument must be an array with at least two elements. 
 * Each element can be a string, number, or instance of ZTeraDBFilterCondition.
 * It applies the "LESS THAN" filter using the provided parameters.
 *
 * @param array $params - An array with at least two elements. 
 *                        Each element can be a string, number, or ZTeraDBFilterCondition.
 * @return ZTeraDBFilterCondition - The filter condition object with the "LESS THAN" filter applied.
 */
function ZTLT($params) {
  // Create a new instance of ZTeraDBFilterCondition
  $ZTC = new ZTeraDBFilterCondition();

  // Set the "LESS THAN" filter condition with the provided parameters
  $ZTC->set_less_than_filter($params);

  // Return the created ZTeraDBFilterCondition object
  return $ZTC;
}

/**
 * This function constructs a filter condition for the "LESS THAN OR EQUAL" operation.
 * The $params argument must be an array with at least two elements. 
 * Each element can be a string, number, or instance of ZTeraDBFilterCondition.
 * It applies the "LESS THAN OR EQUAL" filter using the provided parameters.
 *
 * @param array $params - An array with at least two elements. 
 *                        Each element can be a string, number, or ZTeraDBFilterCondition.
 * @return ZTeraDBFilterCondition - The filter condition object with the "LESS THAN OR EQUAL" filter applied.
 */
function ZTLTE($params) {
  // Create a new instance of ZTeraDBFilterCondition
  $ZTC = new ZTeraDBFilterCondition();

  // Set the "LESS THAN OR EQUAL" filter condition with the provided parameters
  $ZTC->set_less_than_or_equal_filter($params);

  // Return the created ZTeraDBFilterCondition object
  return $ZTC;
}

/**
 * This function constructs a filter condition for the "CONTAINS" operation.
 * The $field should be a string representing a field name, and $value should be a string (or array of strings)
 * that the field should contain.
 *
 * @param string $field - The field name to check for containment.
 * @param mixed $value - The value(s) that the field should contain. Can be a string or an array of strings.
 * @return ZTeraDBFilterCondition - The filter condition object with the "CONTAINS" filter applied.
 */
function ZTCONTAINS($field, $value) {
  // Create a new instance of ZTeraDBFilterCondition
    $ZTC = new ZTeraDBFilterCondition();

    // Set the "CONTAINS" filter condition with the provided field name and value
    $ZTC->set_contains_filter($field, $value);

    // Return the created ZTeraDBFilterCondition object
    return $ZTC;
}

/**
 * This function constructs a case-insensitive filter condition for the "CONTAINS" operation.
 * The $field should be a string representing a field name, and $value should be a string (field name)
 * that the field should contain, applied in a case-insensitive manner.
 *
 * @param string $field - The field name to check for the "CONTAINS" condition.
 * @param string $value - The value that the field should contain, case-insensitive.
 * @return ZTeraDBFilterCondition - The filter condition object with the "ICONTAINS" filter applied.
 */
function ZTICONTAINS($field, $value) {
  // Create a new instance of ZTeraDBFilterCondition
  $ZTC = new ZTeraDBFilterCondition();

  // Set the "CONTAINS" filter condition with the provided field name and value
  $ZTC->set_icontains_filter($field, $value);

  // Return the created ZTeraDBFilterCondition object
  return $ZTC;
}

/**
 * This function constructs a filter condition for the "STARTS WITH" operation.
 * The $field should be a string representing a field name, and $value should be a string (field name)
 * that the field should start with.
 *
 * @param string $field - The field name to check for the "STARTS WITH" condition.
 * @param string $value - The value that the field should start with.
 * @return ZTeraDBFilterCondition - The filter condition object with the "STARTS WITH" filter applied.
 */
function ZTSTARTSWITH($field, $value) {
  // Create a new instance of ZTeraDBFilterCondition
  $ZTC = new ZTeraDBFilterCondition();

  // Set the "STARTS WITH" filter condition with the provided field name and value
  $ZTC->set_starts_with_filter($field, $value);

  // Return the created ZTeraDBFilterCondition object
  return $ZTC;
}

/**
 * This function constructs a case-insensitive filter condition for the "STARTS WITH" operation.
 * The $field should be a string representing a field name, and $value should be a string (field name)
 * that the field should start with, applied in a case-insensitive manner.
 *
 * @param string $field - The field name to check for the "STARTS WITH" condition.
 * @param string $value - The value that the field should start with, case-insensitive.
 * @return ZTeraDBFilterCondition - The filter condition object with the "ISTARTS WITH" filter applied.
 */
function ZTISTARTSWITH($field, $value) {
  // Create a new instance of ZTeraDBFilterCondition
  $ZTC = new ZTeraDBFilterCondition();

  // Set the "ISTARTS WITH" filter condition with the provided field name and value
  $ZTC->set_istarts_with_filter($field, $value);

  // Return the created ZTeraDBFilterCondition object
  return $ZTC;
}

/**
 * This function constructs a filter condition for the "ENDS WITH" operation.
 * The $field should be a string representing a field name, and $value should be a string (field name)
 * that the field should start with.
 *
 * @param string $field - The field name to check for the "ENDS WITH" condition.
 * @param string $value - The value that the field should start with.
 * @return ZTeraDBFilterCondition - The filter condition object with the "ENDS WITH" filter applied.
 */
function ZTENDSWITH($field, $value) {
  // Create a new instance of ZTeraDBFilterCondition
  $ZTC = new ZTeraDBFilterCondition();

  // Set the "ENDS WITH" filter condition with the provided field name and value
  $ZTC->set_ends_with_filter($field, $value);

  // Return the created ZTeraDBFilterCondition object
  return $ZTC;
}

/**
 * This function constructs a case-insensitive filter condition for the "ENDS WITH" operation.
 * The $field should be a string representing a field name, and $value should be a string (field name)
 * that the field value should **end with**, applied in a case-insensitive manner.
 *
 * @param string $field - The field name whose value is being checked.
 * @param string $value - The string value that the field value should end with, case-insensitive.
 * @return ZTeraDBFilterCondition - The filter condition object with the case-insensitive "ENDS WITH" filter applied.
 */
function ZTIENDSWITH($field, $value) {
  // Create a new instance of ZTeraDBFilterCondition
  $ZTC = new ZTeraDBFilterCondition();

  // Set the "IENDS WITH" filter condition with the provided field name and value
  $ZTC->set_iends_with_filter($field, $value);

  // Return the created ZTeraDBFilterCondition object
  return $ZTC;
}
?>