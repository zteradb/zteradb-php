---
sidebar_position: 4
---

# ZTeraDB Query

## Overview
The `ZTeraDBQuery` class provides an intuitive and flexible way to construct queries for a variety of ZTeraDB operations, including **INSERT**, **SELECT**, **UPDATE**, and **DELETE**.

### Key Features:

- **Method Chaining**: Developers can build queries step-by-step, using method chaining for easy configuration of query components.
  
- **Query Customization**: The class allows you to specify key parts of the query, such as:
  - Fields to include or exclude
  - Filters (conditions) for query results
  - Sorting order
  - Limits on the number of results
  - Counting of records
  - Related fields for joins or associations

- **Structured Query Construction**: Query components are organized in a clear, structured way, ensuring that the query is easy to read and modify.

- **Validation**: The class automatically validates required fields and ensures that query parameters are correctly set, reducing the risk of errors.

Once the query is built, you can pass it to the `ZTeraDB Rest API` or `ZTeraDBConnection` object to execute the operation.


### Features:
- **Dynamic Query Creation**: Build queries dynamically using method chaining for easy and flexible configuration.
  
- **Field Selection**: Select specific fields or include all fields in the query results.
  
- **Filter Fields**: Filter fields by specific values, allowing precise query results.

- **Filter Condition**: Use operators like `ZTGT` (greater than), `ZTLT` (less than), `ZTCONTAINS`, and others to apply advanced filtering conditions.

- **Related Field**: Search and retrieve data from related fields (e.g., through joins or associations).

- **Sorting**: Sort results in ascending or descending order.

- **Pagination**: Limit the number of results returned through pagination (using a `limit`).

- **Count Records**: Include a flag to count the total number of records matching the query conditions.

- **Validation**: Automatically ensures that required fields are provided before executing the query, preventing errors.


### Notes
 - The class provides a method chaining syntax for building complex queries in a structured and readable manner.
 - It is essential to call the appropriate query type method (`insert()`, `select()`, `update()`, or `delete()`) before generating the query.
 - Filters are applied to narrow down the data being fetched or modified. Fields and related fields can be specified for SELECT or UPDATE operations.
 - The sorting functionality allows for ordering the results based on specific fields in either ascending or descending order.
 - The `limit()` method is used to specify a range of rows for the query result.
 - The `generate()` method outputs the final ZTeraDB query object that can be passed to the ZTeraDB host execution engine. This method is required only when you are using `ZTeraDB rest API`.


## **Syntax**
## Class
### `ZTeraDBQuery($schema_name: string, $database_name: Optional[string])`

- **Description**: Initializes a new query with the provided `$schema_name` (required) and an optional `$database_name`.
- **Parameters**:
  - `$schema_name`: (string) The name of the schema. This is a required field.
  - `$database_name`: (string, optional) The name of the database. Default is an empty string. For rest API the `$database_name` is required field and if the `$database_name` argument is empty then the query.query() will raise error.


## Methods

### Query methods
  ### `insert()`

  - **Description**: Sets the query type to `INSERT`, enabling the insertion of new records. This method call is necessary for performing insert operations.
  - **Returns**: The `ZTeraDBQuery` instance (supports method chaining).
  - **example**: Insert query example. Before running this query, ensure that you set the fields described in the method below.
    ```php
    use ZTeraDB\Query\ZTeraDBQuery;

    $query = new ZTeraDBQuery("user");
    $query->insert();
    ```

  ### `select()`
  - **Description**: Sets the query type to `SELECT`, indicating that the query will retrieve data. This method call is necessary for performing select operations.
  - **Returns**: The `ZTeraDBQuery` instance (supports method chaining).
  - **example**: Retrieve all records from the `user` table.
    ```php
    use ZTeraDB\Query\ZTeraDBQuery;

    $query = new ZTeraDBQuery("user");
    $query->select();
    ```

  ### `update()`
  - **Description**: Sets the query type to UPDATE, allowing modification of existing records. This method call is necessary for performing update operations.
  - **Returns**: The `ZTeraDBQuery` instance (supports method chaining).
  - **example**: Update query example. Before running this query, ensure that you set the fields described in the method below.
    ```php
    use ZTeraDB\Query\ZTeraDBQuery;

    $query = new ZTeraDBQuery("user");
    $query->update();
    ```
  - **Note**: Before running this query, confirm whether you want to update selected records or all records. Always use filters to avoid accidentally updating every record.

  ### `delete()`
  - **Description**: Sets the query type to `DELETE`, enabling the removal of records. This method call is necessary for performing delete operations.
  - **Returns**: The `ZTeraDBQuery` instance (supports method chaining).
  - **example**: Delete all records from the `user` table.
    ```php
    use ZTeraDB\Query\ZTeraDBQuery;

    $query = new ZTeraDBQuery("user");
    $query->$delete();
    ```
  - **Note**: Before running this query, confirm whether you want to delete selected records or all records. Always use filters to avoid accidentally deleting everything.


### Query fields
### `fields($fieldArray=array(field_name: string => value: any))`
- **Description**: This method is required when you need to select, insert, or update specific fields in a query. It allows you to define the field (schema field) and its corresponding value for select, insert, or update operations:
  - For select, set the field value to 1 to include it in the query.
  - For insert, specify the actual value to modify the field.
  - For update, specify the actual value to modify the field.

- **Parameters**:
  - `$fieldArray`: An associative array where the keys are field names (string) and and the values are the values to be set for those fields. These values should not be objects.
    - **example**:
      - **Select**: Retrieve the `name` and `age` of all users from the `users` schema.
      ```php
      
      use ZTeraDB\Query\ZTeraDBQuery;

      $query = new ZTeraDBQuery("user");
      $query->select()->fields([
        "name"=1,
        "age"=1
      ]);

      ```
      - **Insert**: Insert a new record into the `user` schema.
      ```php

      use ZTeraDB\Query\ZTeraDBQuery;

      $query = new ZTeraDBQuery("user");
      $query->insert()->fields([
        "name"="John",
        "email"="john@example.com",
        "age"=33
      ]);

      ```
      - **Update**: Update the `name` from 'John' to 'John Doe' where the `email` is 'john@example.com' in the `user` schema.
      ```php

      use ZTeraDB\Query\ZTeraDBQuery;

      $query = new ZTeraDBQuery("user");
      $query->update();
      $query->fields(name="John Doe");
      $query->filter(email="john@example.com");

      ```

- **Returns**: The `ZTeraDBQuery` instance (supports method chaining).

- **Note**: The filter value is case-sensitive and will not work in a case-insensitive manner.

### Related Query fields
### `related_fields($related_fields: array(field_name: string => $query: ZTeraDBQuery))`
  - **Description**: Add one or more related fields to the current query. A related field is a field that references
        another query. This method accepts keyword arguments where the key is the related field name
        (string) and the value is the associated ZTeraDBQuery instance.This method is necessary only when the schema contains related fields (related schema fields), and you need to select, insert, or update those related fields in the query.

    - **Parameters**:
      - `$related_fields`: An associative array where each key is a related field name (string) and each value is an instance of ZTeraDBQuery.
        - **example**: Retrieve all orders from the `order` schema where the `order.user.email` (with `order.user` being from the `user` schema) is 'john.doe@example.com'.
        ```php

        use ZTeraDB\Query\ZTeraDBQuery;

        // Create related field query
        $related_field_query = new ZTeraDBQuery("user");
        $related_field_query->select()->filter(email="john.doe@example.com");

        $query = new ZTeraDBQuery("profile");
        $query->select();
        $query->related_field(
          user=$related_field_query
        );

        ```
  
  - **Returns**: The `ZTeraDBQuery` instance (supports method chaining).

### Query Field Filters
### `filter($filter_array: array(field_name: string => value: any))`

- **Description**: This method allows you to specify filter criteria for the query by providing
        field-value pairs (e.g., `field = value`). Each field represents a schema field in the query, and the
        corresponding value is the filter value associated with that field.

- **Parameters**:
  - `params`: (object) A dictionary of filter conditions.
    - **example**: Retrieve the user from the `user` schema where the `name` is 'john' and the `age` is 30.

      ```php

      use ZTeraDB\Query\ZTeraDBQuery;

      $query = new ZTeraDBQuery("user");
      $query->$filter(name="john", age=30);

      ```

- **Returns**: The `ZTeraDBQuery` instance (supports method chaining).


### Advance Query Filters
### `filterCondition($filter_condition: ZTeraDBFilterCondition)`
  - **Description**: Adds an advanced filter condition to the query.
        This method allows the addition of a custom filter condition to the query. The
        provided `filter_condition` should be an instance of `ZTeraDBFilterCondition`.
        The method appends the filter condition's fields to the internal list of filter conditions.

  - **Parameters**:
    - `$filter_condition`: An instance of ZTeraDBFilterCondition generated by the ZTeraDB filter function, which defines the custom filtering logic to be included in the query.
      - **example**: Retrieve all products from the product schema where the quantity of any product is greater than 3.
        ```php

        use ZTeraDB\Query\ZTeraDBQuery;
        use ZTeraDB\Query\ZTGT;

        $filter_condition = ZTeraDB\Query\ZTGT(["quantity", 3]);

        $query = new ZTeraDBQuery("product");
        $query->filterCondition($filter_condition);

        ```
  - **raises**: ValueError: If the `filter_condition` is not an instance of `ZTeraDBFilterCondition`.
  - **Returns**: The `ZTeraDBQuery` instance (supports method chaining).

### Sort Query Result
### `sort($sort_array: array(field_name:string => sort_order: int))`

- **Description**: Applies sorting to the query results based on the specified fields and order (ascending/descending).
- **Parameters**:
  - `$sort_array`: An associative array where the key is the field name (string) to sort by, and each value is the sort order (either 1 or Sort::ASC for ascending and -1 or Sort::DESC for descending).
    - **example**: Retrieve all users from the `user` schema, sorted by `name` in ascending order and `age` in descending order.
      ```php
      use ZTeraDB\Query\ZTeraDBQuery;
      use ZTeraDB\Query\Sort;

      $query = new ZTeraDBQuery("user");
      $query->sort(["name"=>Sort::ASC, "age"=>Sort::DESC])
      ```
      OR
      ```php

      use ZTeraDB\Query\ZTeraDBQuery;

      $query = new ZTeraDBQuery("user");
      $query->sort(name= 1, age=-1);

      ```
- **Returns**: The `ZTeraDBQuery` instance (supports method chaining).

### Limit Query Records
### `limit($start: number, $end: number)`

- **Description**: Limits the query results to a specific range (for pagination).

- **Parameters**:
  - `$start`: (integer) The starting index of the results.
  - `$end`: (integer) The ending index of the results.

- **Returns**: The `ZTeraDBQuery` instance (supports method chaining).

- **example**: The following query will return the first 10 users.
    ```php

    use ZTeraDB\Query\ZTeraDBQuery;

    $query = new ZTeraDBQuery("user");
    $query->limit(0, 10);

    ```

### Count Query Records
### `count()`

- **Description**: Switches the query to count mode, returning the number of records rather than the data itself.

- **Returns**: The `ZTeraDBQuery` instance (supports method chaining).

- **example**: The following query will return the total number of users.
    ```php

    use ZTeraDB\Query\ZTeraDBQuery;

    $query = new ZTeraDBQuery("user");
    $query->count();

    ```

### Generate Query
### `generate()`

- **Description**: Finalizes the query construction and returns the generated query object. It validates all required fields before returning the final structure.

- **Returns**: (Array) The final query array, ready for execution.

- **Throws**:
  - Throws an error if any required fields (like `schema_name` or `queryType`) are missing or invalid.

- **Note**: This method call is essential for retrieving the query and sending it to the ZTeraDB server when using the REST API.
