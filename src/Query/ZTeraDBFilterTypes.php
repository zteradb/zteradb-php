<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB Query Filter Types Class
 * --------------------------------------------------------------------------
 *
 *  This class defines various filter types used for querying data in the 
 *  ZTeraDB database system. These filter types enable the construction of 
 *  complex queries with various operations like logical operators, 
 *  comparison operators, and string-based filters (e.g., LIKE, STARTSWITH).
 *
 *  Key Responsibilities:
 *  ----------------------
 *  - Providing constants for logical operators (AND, OR).
 *  - Defining comparison operators (EQUAL, NOTEQUAL, GT, LT, etc.).
 *  - Including string matching operations like CONTAINS, STARTSWITH, etc.
 *  - Ensuring that these constants are available for use in query-building.
 *
 *  Requirements:
 *  -------------
 *  - This class is part of a larger ZTeraDB system and is intended to be 
 *    used when building and executing queries.
 *
 *  This class simplifies query creation by providing predefined filter types 
 *  to be used in query construction.
 *
 * --------------------------------------------------------------------------
 *  @package     ZTeraDB
 *  @version     1.0
 *  @author      ZTeraDB
 *  @license     https://zteradb.com/licence (SPDX-License-Identifier: Proprietary)
 * --------------------------------------------------------------------------
 */
namespace ZTeraDB\Query;


class ZTeraDBFilterTypes {
  /**
   * --------------------------------------------------------------------------
   *  Logical Operators
   * --------------------------------------------------------------------------
   *
   *  This section defines the logical filter types used in ZTeraDB queries.
   *  Logical operators are essential for combining multiple conditions in 
   *  queries, allowing for more complex logic.
   * 
   *  - The 'OR' operator is used when at least one of the conditions must be true.
   *  - The 'AND' operator is used when all conditions must be true.
   * 
   * --------------------------------------------------------------------------
   */
  // 'OR' filter type representing the logical OR operation in ZTeraDB queries
  // Used to combine multiple conditions where at least one must be true
  const ZTOR = '||';

  // 'AND' filter type representing the logical AND operation in ZTeraDB queries
  // Used to combine multiple conditions where all conditions must be true
  const ZTAND = '&&';

  /**
   * --------------------------------------------------------------------------
   *  Comparison Operators
   * --------------------------------------------------------------------------
   *
   *  This section defines the comparison filter types used in ZTeraDB queries.
   *  Comparison operators are used to compare a field's value against a specified
   *  value to build conditional queries.
   * 
   *  - 'EQUAL' checks for equality.
   *  - 'NOTEQUAL' checks for inequality.
   *  - 'GT' checks if the value is greater than the specified value.
   *  - 'GTE' checks if the value is greater than or equal to the specified value.
   *  - 'LT' checks if the value is less than the specified value.
   *  - 'LTE' checks if the value is less than or equal to the specified value.
   * 
   * --------------------------------------------------------------------------
   */
  // 'EQUAL' filter type for 'equal to' comparison in ZTeraDB queries
  // Used to check if a field's value is exactly equal to the specified value
  const ZTEQUAL = '=';

  // 'NOTEQUAL' filter type for 'not equal to' comparison in ZTeraDB queries
  // Used to check if a field's value is not equal to the specified value
  const ZTNOTEQUAL = '!=';

  // 'GT' filter type for 'greater than' comparison in ZTeraDB queries
  // Used to check if a field's value is greater than the specified value
  const ZTGT = '>';

  // 'GTE' filter type for 'greater than or equal to' comparison in ZTeraDB queries
  // Used to check if a field's value is greater than or equal to the specified value
  const ZTGTE = '>=';

  // 'LT' filter type for 'less than' comparison in ZTeraDB queries
  // Used to check if a field's value is less than the specified value
  const ZTLT = '<';

  // 'LTE' filter type for 'less than or equal to' comparison in ZTeraDB queries
  // Used to evaluate if a field's value is less than or equal to the specified value
  const ZTLTE = '<=';

  /**
   * --------------------------------------------------------------------------
   *  Arithmetic Operators
   * --------------------------------------------------------------------------
   *
   *  This section defines the arithmetic filter types used in ZTeraDB queries.
   *  Arithmetic operators are used to perform mathematical operations within 
   *  query conditions. These operators allow you to sum, subtract, multiply, 
   *  divide, or find the remainder of values or fields in ZTeraDB queries.
   * 
   *  - 'ADD' performs addition between two values or fields.
   *  - 'SUB' performs subtraction between two values or fields.
   *  - 'MUL' performs multiplication between two values or fields.
   *  - 'DIV' performs division between two values or fields.
   *  - 'MOD' performs the modulo (remainder) operation between two values or fields.
   *
   *  These operators are essential for performing mathematical calculations 
   *  as part of more complex query conditions.
   * 
   * --------------------------------------------------------------------------
   */
  // 'ADD' filter type for performing addition in ZTeraDB queries
  // Used to sum values or fields in the filter condition, allowing arithmetic operations in query construction
  const ZTADD = '+';

  // 'SUB' filter type for performing subtraction in ZTeraDB queries
  // Used to subtract values or fields in the filter condition, enabling arithmetic operations for filtering
  const ZTSUB = '-';

  // 'MUL' filter type for performing multiplication in ZTeraDB queries
  // This operator is used to multiply values or fields within the filter conditions, 
  // enabling arithmetic-based filtering in query expressions.
  const ZTMUL = '*';

  // 'DIV' filter type for performing division in ZTeraDB queries
  // This operator is used to divide values or fields within the filter conditions,
  // allowing for arithmetic-based filtering where division is required.
  const ZTDIV = '/';

  // 'MOD' filter type for performing modulo (remainder) operation in ZTeraDB queries
  // This operator is used to calculate the remainder of a division between two values or fields,
  // enabling filtering based on the modulus result. Useful for operations where divisibility or remainder checks are required.
  const ZTMOD = '%';

  /**
   * --------------------------------------------------------------------------
   *  String Matching Operators
   * --------------------------------------------------------------------------
   *
   *  This section defines the string matching filter types used in ZTeraDB queries.
   *  String matching operators allow pattern matching, which enables searching for 
   *  partial matches within string fields. These operators use wildcards ('%' symbol) 
   *  for flexible matching criteria.
   *  
   *  - 'CONTAINS' allows partial matching anywhere within the string.
   *  - 'ICONTAINS' allows partial matching without case sensitivity.
   *  - 'STARTSWITH' checks if a string starts with a specified prefix.
   *  - 'ISTARTSWITH' checks if a string starts with a specified prefix, ignoring case.
   *  - 'ENDSWITH' checks if a string ends with a specified suffix.
   *  - 'IENDSWITH' checks if a string ends with a specified suffix, ignoring case.
   *
   *  These operators are useful for filtering records based on patterns, 
   *  where an exact match is not required but rather a part of the string.
   *  
   * --------------------------------------------------------------------------
   */
  // 'LIKE' filter type for performing a pattern matching operation in ZTeraDB queries
  // This operator is used to check if a string field matches a specified pattern, 
  // allowing for partial string matches using wildcards ('%' symbol) at the beginning, 
  // end, or middle of the string. Typically used for 'contains', 'starts with', or 'ends with' scenarios.
  const ZTCONTAINS = '%%';

  // 'ILIKE' filter type for performing a case-insensitive pattern matching operation in ZTeraDB queries
  // This operator is similar to the 'LIKE' operator, but it performs the comparison without regard to case.
  // It allows for partial string matches using wildcards ('%' symbol) and is used when you need to match 
  // a string field against a pattern, regardless of whether the characters are uppercase or lowercase.
  const ZTICONTAINS = 'i%%';

  // 'STARTSWITH' filter type for performing a 'starts with' string operation in ZTeraDB queries
  // This operator checks if a given string field begins with a specified substring.
  // It is typically used to filter records where a string field starts with a certain prefix.
  // The pattern is case-sensitive, meaning 'abc' will not match 'ABC' unless explicitly handled.
  const ZTSTARTSWITH = '^%%';

  // 'ISTARTSWITH' filter type for performing a case-insensitive 'starts with' string operation in ZTeraDB queries
  // This operator checks if a given string field begins with a specified substring, ignoring case differences.
  // It is useful for filtering records where a string field starts with a certain prefix, regardless of letter case.
  // For example, it will match 'abc' and 'ABC', making it ideal for case-insensitive searches.
  const ZTISTARTSWITH = '^i%%';

  // 'ENDSWITH' filter type for performing a string 'ends with' operation in ZTeraDB queries
  // This operator checks if a given string field ends with a specified substring.
  // It is particularly useful for filtering records where a string field finishes with a certain suffix.
  // For example, it can be used to find all records where a field ends with ".com" or any other pattern.
  const ZTENDSWITH = '%%$';

  // 'IENDSWITH' filter type for performing a case-insensitive 'ends with' operation on a string field in ZTeraDB queries
  // This operator checks if a given string field ends with a specified substring, ignoring case differences.
  // It is useful for filtering records where the field ends with a specific suffix, regardless of uppercase or lowercase characters.
  // For example, it can be used to find records where a field ends with "abc" or "ABC", treating both as equivalent.
  const ZTIENDSWITH = 'i%%$';

  /**
   * --------------------------------------------------------------------------
   *  Set Operators
   * --------------------------------------------------------------------------
   *
   *  This section defines the filter types for set-based operations in ZTeraDB queries.
   *  The 'IN' operator is used to match a field against a specified set or list of values.
   *  It is particularly useful when you need to filter records based on multiple possible values for a single field.
   *  The operator simplifies complex queries by eliminating the need for multiple equality checks.
   *
   *  - 'IN' is used to determine if the value of a field is contained within a set of predefined values.
   *
   *  This operator is efficient for querying when there are multiple valid values for a field.
   *  
   *  Example Use Case:
   *  -----------------
   *  You might want to retrieve records where a field's value matches one of a set of possible values.
   *  For example, filtering products that belong to one of several categories.
   *
   *  Syntax Example:
   *  field IN ('apple', 'banana', 'cherry')
   *  
   * --------------------------------------------------------------------------
   */
  // 'IN' filter type for performing the 'IN' operation on a schema field in ZTeraDB queries
  // This operator checks if the value of a field exists within a specified list or set of values.
  // It is commonly used when you want to filter records based on multiple possible values for a field.
  // For example, you can use the 'IN' filter to retrieve records where the field is either 'apple', 'banana', or 'cherry'.
  // Syntax Example: field IN ('apple', 'banana', 'cherry')
  // The 'IN' operator simplifies queries that need to match against a set of values, improving readability and performance.
  const ZTIN = 'IN';
}

?>