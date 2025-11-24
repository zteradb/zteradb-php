<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB Query Test Suite
 * --------------------------------------------------------------------------
 *
 *  This file contains a suite of unit tests for the `ZTeraDBQuery` class,
 *  which handles various operations for constructing and executing database 
 *  queries on the ZTeraDB platform. The tests validate the functionality 
 *  of the class methods, including query creation, CRUD operations, filters, 
 *  sorting, limits, and environment settings.
 *
 *  Key Responsibilities:
 *  ----------------------
 *  - Verifying that query methods (e.g., `select()`, `insert()`, `update()`, 
 *    `delete()`, etc.) behave as expected.
 *  - Ensuring correct handling of dynamic fields and magic methods.
 *  - Testing proper validation and error handling for invalid inputs (e.g., 
 *    invalid filters, sorts, limits, environments).
 *  - Validating that the query can be generated correctly with various conditions.
 *
 *  Requirements:
 *  -------------
 *  - The `ZTeraDB\Query\ZTeraDBQuery` class should be properly defined and 
 *    contain methods for query operations.
 *  - The `ZTeraDB\Exceptions\ValueError` class should be defined for error 
 *    handling in case of invalid values.
 *
 *  This test suite is used to ensure the correctness and robustness of the 
 *  `ZTeraDBQuery` class.
 *
 * --------------------------------------------------------------------------
 *  @package     ZTeraDB
 *  @version     1.0
 *  @author      ZTeraDB
 *  @license     https://zteradb.com/licence (SPDX-License-Identifier: Proprietary)
 * --------------------------------------------------------------------------
 */
use PHPUnit\Framework\TestCase;
use ZTeraDB\Query\ZTeraDBQuery;
use ZTeraDB\Query\ZTeraDBSort;
use ZTeraDB\Query\ZTeraDBQueryType;
use ZTeraDB\Exceptions\ValueError;
use ZTeraDB\Config\ENVS;

/**
 * Class ZTeraDBQueryTest
 *
 * A test class that contains unit tests for the ZTeraDBQuery class. It validates
 * the functionality of methods for building and executing database queries on 
 * the ZTeraDB platform, including operations like select, insert, update, delete, 
 * filter, sort, and limit.
 *
 * This class ensures that the ZTeraDBQuery class behaves as expected under various 
 * conditions, including correct error handling for invalid inputs and edge cases.
 *
 * @package ZTeraDB
 */
class ZTeraDBQueryTest extends TestCase {
  // The $query property will be used to hold the ZTeraDBQuery instance.
  private $query;

  /**
   * Set up the test environment by initializing a ZTeraDBQuery object.
   * This method runs before each test method is executed, ensuring that 
   * each test has a fresh instance of the ZTeraDBQuery object to work with.
   */
  protected function setUp() {
    // Create a new instance of ZTeraDBQuery with a sample schema name 'test_schema'.
    $this->query = new ZTeraDBQuery('test_schema');
  }

  /**
   * Test the constructor of the ZTeraDBQuery class.
   * This test ensures that the ZTeraDBQuery object is created with the correct
   * schema name and database ID.
   */
  public function testConstructor() {
    // Create a new ZTeraDBQuery object with a schema name 'test_schema' 
    // and a database ID '4V0TKM8FHA0PM2OB0KJK6GT0S0'.
    // This simulates the initialization of a query object for a specific schema and database.
    $query = new ZTeraDBQuery('test_schema', '4V0TKM8FHA0PM2OB0KJK6GT0S0');

    // Assert that the $query object is an instance of the ZTeraDBQuery class.
    // This checks that the object was correctly instantiated and is of the expected type.
    $this->assertInstanceOf(ZTeraDBQuery::class, $query);

    // Assert that the schema name provided ('test_schema') is correctly assigned 
    // to the query object. This checks the correct handling of the schema name parameter.
    $this->assertEquals('test_schema', $query->get_schema_name());

    // Assert that the database ID provided ('4V0TKM8FHA0PM2OB0KJK6GT0S0') 
    // is correctly assigned to the query object. This ensures the database ID is 
    // properly initialized and accessible.
    $this->assertEquals('4V0TKM8FHA0PM2OB0KJK6GT0S0', $query->get_database_id());
  }

  /**
   * Test the select method of the ZTeraDBQuery class.
   * This ensures that calling the select() method correctly marks the query
   * as a SELECT type query.
   */
  public function testSelect() {
    // Call the select() method on the query object.
    // This sets the internal query type of the object to SELECT.
    $this->query->select();

    // Assert that the query object is now recognized as a SELECT query.
    // is_select_query() should return true if the query type was set correctly.
    $this->assertTrue($this->query->is_select_query());
  }

  /**
   * Test the insert method of the ZTeraDBQuery class.
   * This ensures that calling the insert() method correctly marks the query
   * as a INSERT type query.
   */
  public function testInsert() {
    // Call the insert() method on the query object.
    // This sets the internal query type of the object to INSERT.
    $this->query->insert();

    // Assert that the query type is set to INSERT.
    $this->assertEquals(ZTeraDBQueryType::INSERT, $this->query->get_query_type());
  }

  /**
   * Test the update method of the ZTeraDBQuery class.
   * This ensures that calling the update() method correctly marks the query
   * as a UPDATE type query.
   */
  public function testUpdate() {
    // Call the update() method on the query object.
    // This sets the internal query type of the object to UPDATE.
    $this->query->update();

    // Assert that the query type is set to UPDATE.
    $this->assertEquals(ZTeraDBQueryType::UPDATE, $this->query->get_query_type());
  }

  /**
   * Test the delete method of the ZTeraDBQuery class.
   * This ensures that calling the delete() method correctly marks the query
   * as a DELETE type query.
   */
  public function testDelete() {
    // Call the delete() method on the query object.
    // This sets the internal query type of the object to DELETE.
    $this->query->delete();

    // Assert that the query type is set to DELETE.
    $this->assertEquals(ZTeraDBQueryType::DELETE, $this->query->get_query_type());
  }

  /**
   * Test magic methods for setting and getting properties.
   * This ensures that fields can be dynamically set and retrieved.
   */
  public function testMagicSetGet() {
    // Dynamically set the first_name property.
    $this->query->first_name = 'John';

    // Assert that the first_name property is correctly set.
    $this->assertEquals('John', $this->query->first_name);

    // Dynamically set the last_name property.
    $this->query->last_name = "Doe";

    // Assert that the last_name property is correctly set.
    $this->assertEquals('Doe', $this->query->last_name);
  }

  /**
   * Test the fields method of the ZTeraDBQuery class.
   * This ensures that fields can be set using an associative array 
   * and that they are correctly assigned to the query object.
   */
  public function testFields() {
    // Set fields with an associative array.
    $this->query->fields([
        'name' => 'John Doe',
        'age' => 30
    ]);

    // Assert that the name and age properties are set correctly.
    $this->assertEquals('John Doe', $this->query->name);
    $this->assertEquals(30, $this->query->age);
  }

  /**
   * Test the field() method of the ZTeraDBQuery class.
   * This ensures that dynamically assigned fields are correctly stored 
   * and returned as an associative array using the field() method.
   */
  public function testQueryField() {
    // Create a new query object with schema and database ID.
    $query = new ZTeraDBQuery("test_schema", "test_database_id");

    // Set the first_name field dynamically.
    $query->first_name = "john";

    // Assert that the field method returns the correct data.
    $this->assertEquals($query->field(), ["first_name"=>"john"]);

    // Set the last_name field dynamically.
    $query->last_name = "doe";

    // Assert that the field method returns the updated data.
    $this->assertEquals($query->field(), ["first_name"=>"john", "last_name"=>"doe"]);
  }

  /**
   * Test the filter() method of the ZTeraDBQuery class.
   * This ensures that filters can be added using an associative array and 
   * that they are correctly stored and retrievable using the filters() method.
   */
  public function testFilter() {
    // Apply filters to the query using an associative array.
    // The filter() method should validate and store these filters internally.
    $this->query->filter([
        'status' => 'active',
        'age' => 30
    ]);

    // Retrieve the filters and assert their correctness.
    $filters = $this->query->filters();

    // Assert that the 'status' filter key exists in the filters array.
    $this->assertArrayHasKey('status', $filters);

    // Assert that the 'age' filter key exists in the filters array.
    $this->assertArrayHasKey('age', $filters);

    // Assert that the value of the 'status' filter matches the expected string 'active'.
    $this->assertEquals('active', $filters['status']);

    // Assert that the value of the 'age' filter matches the expected integer 30.
    $this->assertEquals(30, $filters['age']);
  }

  /**
   * Test invalid filter values.
   * This ensures that the correct exception is thrown for invalid filters.
   */
  public function testInvalidFilter() {
    // Expect a ValueError to be thrown for invalid filter.
    $this->expectException(ValueError::class);

    // Try applying an invalid filter (object).
    $this->query->filter([
      'status' => new stdClass()  // Invalid filter value (object)
    ]);
  }

  /**
   * Test the filter() method of the ZTeraDBQuery class.
   * This test verifies that filters can be applied multiple times and that 
   * the filters are correctly accumulated and returned.
   */
  public function testQueryFilter() {
    // Create a new query object.
    $query = new ZTeraDBQuery("test_schema", "test_database_id");

    // Apply a first filter.
    $query->filter([first_name=>"john", last_name=>"doe"]);

    // Assert that the filters array now contains the first set of filters.
    // The filters array should exactly match the provided filters.
    $this->assertEquals($query->filters(), [first_name=>"john", last_name=>"doe"]);

    // Apply a second filter.
    $query->filter([age=>33]);

    // Assert that the filters are updated correctly.
    $this->assertEquals($query->filters(), [first_name=>"john", last_name=>"doe", age=>33]);
  }

  /**
   * Test the limit() method of the ZTeraDBQuery class.
   * This ensures that the limit for the query is set correctly and that 
   * the limit values can be retrieved as expected.
   */
  public function testLimit() {
    // Set the limit for the query using the limit() method.
    // The limit is set to start from offset 0 and retrieve up to 10 records.
    // The first parameter is the offset (starting point), and the second is the number of records to retrieve.
    $this->query->limit(0, 10);

    // Retrieve the limit values using the get_limit() method.
    // This should return an array with two elements: the offset and the limit.
    $limit = $this->query->get_limit();

    // Assert that the limit values match the expected array [0, 10].
    // This ensures that the limit was set correctly.
    $this->assertEquals([0, 10], $limit);
  }

  /**
   * Test the limit() method of the ZTeraDBQuery class with invalid input.
   * This ensures that a ValueError is thrown when the limit is applied in an invalid order.
   */
  public function testInvalidLimit() {
    // Expect a ValueError to be thrown when invalid parameters are passed to the limit() method.
    // In this case, the offset (10) is larger than the limit (5), which is considered invalid.
    $this->expectException(ValueError::class);

    // Try applying an invalid limit where the offset is greater than the limit.
    // The first parameter should be less than or equal to the second for a valid range.
    $this->query->limit(10, 5);
  }

  /**
   * Test the sort() method of the ZTeraDBQuery class.
   * This ensures that sorting fields can be applied correctly and that
   * they are stored and retrieved with the correct sort directions.
   */
  public function testSort() {
    // Apply sorting rules to the query using an associative array.
    // Each key represents a field name, and the value represents the sort order:
    //  1  = ascending order
    // -1  = descending order
    $this->query->sort([
        'age' => 1,
        'name' => -1
    ]);

    // Retrieve the sorting rules from the query using get_sort().
    // This returns an associative array of all applied sorting instructions.
    $sort = $this->query->get_sort();

    // Assert that the 'age' sorting key exists in the returned sort array.
    $this->assertArrayHasKey('age', $sort);

    // Assert that the 'name' sorting key exists in the returned sort array.
    $this->assertArrayHasKey('name', $sort);

    // Assert that the sort order for 'age' was correctly set to 1 (ascending).
    $this->assertEquals(1, $sort['age']);

    // Assert that the sort order for 'name' was correctly set to -1 (descending).
    $this->assertEquals(-1, $sort['name']);
  }

  /**
   * Test the sort() method of the ZTeraDBQuery class with invalid input.
   * This ensures that the method correctly throws a ValueError when invalid sorting rules are provided.
   */
  public function testInvalidSort() {
    // Try applying an invalid sort where the sort criteria is not a string (field names must be strings).
    try {
      // Passing a number (12) instead of a string as a field name is invalid.
      $this->query->sort([12]);
    } catch (ValueError $e) {
      // Assert that the exception message is as expected: "Sort field must be string".
      $this->assertEquals($e->getMessage(), "Sort field must be string");
    }

        // Try applying a sort where the input is not an array.
    try {
        // Passing an integer (1) instead of an array is invalid.
        $this->query->sort(1);
    } catch (ValueError $e) {
        // Assert that the exception message is as expected: "sort_array should be an array".
        $this->assertEquals($e->getMessage(), "sort_array should be an array");
    }

    // Try applying a sort where an empty array is passed.
    try {
        // An empty array is invalid since there must be at least one field to sort by.
        $this->query->sort(array());
    } catch (ValueError $e) {
        // Assert that the exception message is as expected: "sort_array must not be an array".
        $this->assertEquals($e->getMessage(), "sort_array must not be an array");
    }
  }

  /**
   * Test the count() method of the ZTeraDBQuery class.
   * This ensures that calling count() correctly marks the query as a COUNT query.
   */
  public function testCountQuery() {
    // Call the count() method on the query object.
    // This should internally set a flag indicating that the query
    // is intended to return only the count of matching records.
    $this->query->count();

    // Assert that the query is marked for counting.
    // The has_count() method should return true if count() was called.
    $this->assertTrue($this->query->has_count());
  }

  /**
   * Test the generate() method of the ZTeraDBQuery class.
   * This ensures that the query generation process properly compiles all parts of the query 
   * (such as fields, filters, limits, and sorting) into a complete query array.
   */
  public function testGenerate() {
    // Build the query step by step, using various methods to set query options:
    // - select() initializes the query type to SELECT.
    // - fields() sets the fields to be selected (name and age).
    // - filter() applies a filter to only include 'active' statuses.
    // - limit() restricts the query to retrieve records starting at 0 with a maximum of 10 records.
    // - sort() applies sorting rules (age in ascending order).
    $this->query->select()
        ->fields(['name' => 'John Doe', 'age' => 30])
        ->filter(['status' => 'active'])
        ->limit(0, 10)
        ->sort(['age' => 1]);

    // Generate the query by calling generate().
    // This should return an associative array representing the complete query.
    $generated = $this->query->generate();

    // Assert that the generated query contains a key 'qt' for the query type (SELECT).
    $this->assertArrayHasKey('qt', $generated);

    // Assert that the generated query contains a key 'fl' for the fields (name and age).
    $this->assertArrayHasKey('fl', $generated);

    // Assert that the generated query contains a key 'fi' for the filters (status = 'active').
    $this->assertArrayHasKey('fi', $generated);

    // Assert that the generated query contains a key 'lt' for the limit (offset = 0, limit = 10).
    $this->assertArrayHasKey('lt', $generated);

    // Assert that the generated query contains a key 'st' for the sort (age in ascending order).
    $this->assertArrayHasKey('st', $generated);
  }

  /**
   * Test the set_env() and get_env() methods of the ZTeraDBQuery class.
   * This ensures that the environment can be correctly set and retrieved.
   */
  public function testEnv() {
    // Set the environment to production using the set_env() method.
    // The ENVS::prod constant represents the production environment.
    $this->query->set_env(ENVS::prod);

    // Retrieve the environment using the get_env() method.
    // Assert that the environment is correctly set to 'prod'.
    $this->assertEquals(ENVS::prod, $this->query->get_env());
  }

  /**
   * Test the set_env() method with invalid input.
   * This ensures that the method throws a ValueError when an invalid environment is set.
   */
  public function testInvalidEnv() {
    // Expect a ValueError to be thrown when an invalid environment is passed.
    $this->expectException(ValueError::class);

    // Try setting an invalid environment (a string that is not part of the defined ENVS constants).
    // This should trigger a ValueError since only valid environment constants are allowed.
    $this->query->set_env('invalid_env');
  }

  /**
   * Test the related_field() and related_fields() methods of the ZTeraDBQuery class.
   * This ensures that related fields can be correctly set and retrieved.
   */
  public function testRelatedFields() {
    // Create a related query object for a related schema ('related_schema').
    // This represents a sub-query that will be related to the main query.
    $relatedQuery = new ZTeraDBQuery('related_schema');

    // Set a related field in the main query using the related_field() method.
    // 'related_data' will store the related query object ($relatedQuery).
    $this->query->related_field([
        'related_data' => $relatedQuery
    ]);

    // Retrieve the related fields using the related_fields() method.
    // This should return an associative array with 'related_data' as a key.
    $relatedFields = $this->query->related_fields();

    // Assert that the 'related_data' key exists in the related fields array.
    $this->assertArrayHasKey('related_data', $relatedFields);

    // Assert that the value of 'related_data' is an instance of ZTeraDBQuery.
    // This confirms that the related field is correctly associated with a query.
    $this->assertInstanceOf(ZTeraDBQuery::class, $relatedFields['related_data']);
  }

  /**
   * Test that setting an invalid related field throws a ValueError.
   * This ensures that only ZTeraDBQuery objects can be assigned as related fields.
   */
  public function testInvalidRelatedField() {
    // Expect a ValueError to be thrown when an invalid related field is passed.
    $this->expectException(ValueError::class);

    // Attempt to set a related field with an invalid value (stdClass object instead of ZTeraDBQuery).
    // The related_field() method should validate the type and throw a ValueError.
    $this->query->related_field([
        'related_data' => new stdClass()
    ]);
  }
}
?>