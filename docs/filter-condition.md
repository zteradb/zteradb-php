---
sidebar_position: 6
---

# ZTeraDB Filter Complex Condition Functions
Filter condition functions enable the creation of complex filter conditions. These functions support various operations, including multiplication, addition, equality checks, logical operations like `AND`, `OR`, `>`, `<`, `>=`, `<=`, `in`, and string operations like `contains`, `starts with`, `ends with`. Errors are triggered when invalid parameters or missing values are encountered.


## Table of Contents
1. [Equal filter](#ztequalparam-any-result-any)
2. [Addition Filter](#ztaddvalues-array)
3. [Substraction Filter](#ztsubvalues-array)
4. [Multiplication Filter](#ztmulvalues-array)
5. [Division Filter](#ztdivdividend-any-divisor-any)
6. [Modulo Filter](#ztmodnumerator-any-denominator-any)
7. [Filed in array Filter](#ztinkey-string-values-array)
8. [String Contains Filter](#ztcontainsfield-string-value-string)
9. [String Case-insensitive contains Filter](#zticontainsfield-string-value-string)
10. [String Starts with Filter](#ztstartswithfield-string-value-string)
11. [String Case-insensitive Starts with Filter](#ztistartswithfield-string-value-string)
12. [String Ends with Filter](#ztendswithfield-string-value-string)
13. [String Case-insensitive Ends with Filter](#ztiendswithfield-string-value-string)
14. [Greater than Filter](#ztgtvalues-array)
15. [Greater than or equal to Filter](#ztgtevalues-array)
16. [Less than Filter](#ztltvalues-array)
17. [Less than or equal to Filter](#ztltevalues-array)
18. [AND Filter](#ztandfilters-array)
19. [OR Filter](#ztorfilters-array)



### `ZTEQUAL($param: any, $result: any)`
- **Description**: The `ZTEQUAL` function applies an equality filter to compare the `param` (left side) and `result` (right side) arguments, then returns a new `FilterCondition` instance. This `FilterCondition` will later be used to build and execute a query. You can combine this filter with other filters to create more complex queries.

- **Parameters**:
  - `$param` (Any): The field (or expression) to be compared on the left side of the equation.
    - **Value**: A constants.
    - **Field**: Schema field.
    - **FilterCondition**: any ZT* function.

    - **Example**: A field name (e.g., `"age"`) or an expression (e.g., `ZTMUL("price", 2)` which multiplies the "price" field by 2).
    - **Usage**: You can use any valid field or expression that you want to compare.
  
  - `$result` (Any): The value (or expression) to be compared to the `key` on the right side of the equation.
    - **Value**: A constants.
    - **Field**: Schema field.
    - **FilterCondition**: Any ZT* function.

    - **Example**: A field value (e.g., `30` or `"active"`) or an expression (e.g., `ZTMUL("quantity", 5)`).
    - **Usage**: This is the value or result of an expression that you want to compare against the `key`.

- **Returns**: 
  - **`FilterCondition`**: A new instance of `FilterCondition` with the equality filter applied. This `FilterCondition` will be passed as an argument to `ZTeraDBQuery.FilterCondition()` or other functions that support filter conditions, allowing you to apply your filter to a query.

- **Throws**: 
  - **`ZTeraDBError`**: If invalid parameters are passed (e.g., the `param` or `result` is missing or of an incorrect type), the function will throw an error.

- **Use Case**:
  - Use `ZTEQUAL` when you need to find records based on a comparison between two fields or between a field and a specific value.
  - **Example Use Case**: To find all users with an "age" equal to 30, you can write: `ZTEQUAL('age', 30)`. Or to check if a field’s value is equal to another field’s calculated value, like `ZTEQUAL(ZTMUL('price', 2), 100)`, where you compare twice the price to 100.

### Example

```php
use ZTeraDB\Query\ZTeraDBQuery;
use ZTeraDB\Query\ZTMUL;
use ZTeraDB\Query\ZTEQUAL;

# Example 1: Get all users whose email ID is 'john.doe@example.com'.
$filter_condition = ZTeraDB\Query\ZTEQUAL("email", "john.doe@example.com");

$query = new ZTeraDBQuery("user");
$query->select()->filterCondition($filter_condition);
# ... pass the query to connection.run() method.

# Example 2: Get all records from the product table where the price multiplied by 2 is equal to 2998.
$filter_condition2 = ZTeraDB\Query\ZTEQUAL(
  ZTeraDB\Query\ZTMUL(["price", 2]),
  3998
);

# Generate the query.
$query2 = new ZTeraDBQuery("product");
$query2->select()->filterCondition($filter_condition2);
# This query fetches all products where "price * 2" equals 100
# ... pass the query2 to connection.run() method.


# Example 3: Get all records from the product table where the product name is 'Tea'.
$filter_condition3 = ZTEQUAL('name', 'Tea');  # Interpreted as name = 'Tea'

# Generate the query.
$query3 = new ZTeraDBQuery("product");
$query3->select()->filterCondition($filter_condition3); # Attach the filter conditions to the query      
# ... pass the query3 to connection.run() method.

# The query will now fetch all products from the "product" table where the "name" is "Tea"

```

---

### `ZTADD($values: Array)`
- **Description**: The `ZTADD` function creates a addition filter for the values provided in the `values` array. It returns a new `FilterCondition` instance that can be used later to build and execute a query. This filter is often used in combination with other filters, such as `ZTEQUAL`, to create more complex queries.
- **Parameters**:
  - `$values` (Array): An array of values, fields, or filter conditions (any ZT* function) to addition. The addition can occur between:
    - **Values**: An array of constants.
    - **Fields**: An array of schema fields.
    - **FilterCondition**: An array of ZT* functions.
    - **Field, Value, FilterCondition**: A combination of array of schema field, constant, and ZT* functions.

    - **Example**: `["price", "discount"]` (price + discount) or `[200, "price"]` (price + 200). here price, discount are schema fields.

- **Returns**:
  - A new `FilterCondition` instance with the addition filter applied. This can later be used in query-building methods like `ZTeraDBQuery.FilterCondition()`.

- **Throws**:
  - **`ZTeraDBError`** if invalid parameters are passed, such as unsupported combinations of fields or data types.

- **Use Case**:
  - Use `ZTADD` when you need to filter records based on the addition of two fields, a field and a constant, or even between ZT* functions. You can combine `ZTADD` with other filters (e.g., `ZTEQUAL`) to match the addition result against another field or constant.

- **Note**:
  - Based on your use case, you pass n number of fields / values / FilterCondition to add with each other.

### Example:

```php
use ZTeraDB\Query\ZTeraDBQuery;
use ZTeraDB\Query\ZTADD;
use ZTeraDB\Query\ZTLTE;
use ZTeraDB\Query\ZTEQUAL;

# Example 1: Get all records from the product table where the price plus quantity is equal to 5050.
$filter_condition = ZTeraDB\Query\ZTLTE([
    ZTeraDB\Query\ZTADD(["price", "quantity"]), 5050
]);

# Generate the query.
$query = new ZTeraDBQuery("product");
$query->select()->filterCondition($filter_condition);
# ... pass the query to connection.run() method.

# Example 2: Get all records from the product table where the price plus 200 is equal to 3699.
$filter_condition2 = ZTeraDB\Query\ZTEQUAL(
    ZTeraDB\Query\ZTADD(["price", 200]),
    3699
);

# Generate the query.
$query2 = new ZTeraDBQuery("product");
$query2->select()->filterCondition($filter_condition2);
# ... pass the query2 to connection.run() method.
```

---


### `ZTSUB($values: Array)`
- **Description**: The `ZTSUB` function creates a subtraction filter for the values provided in the `values` array. It returns a new `FilterCondition` instance that can be used later to build and execute a query. This filter is often used in combination with other filters, such as `ZTEQUAL`, to create more complex queries.
  
- **Parameters**:
  - `$values` (Array): An array of values, fields, or any ZT* functions to subtract. The subtraction can occur between:
    - **Values**: An array of constants.
    - **Fields**: An array of schema fields.
    - **FilterCondition**: An array of ZT* functions.
    - **Field, Value, FilterCondition**: A combination of an array of schema field, constant, and ZT* functions.

    - **Example**: `["price", "discount"]` (price - discount) or `[200, "price"]` (200 - price). here price, discount are schema fields.
  
- **Returns**: 
  - Based on your use case, you pass n number of fields / values / ZT* functions to substract from each other.
  
- **Throws**: 
  - **`ZTeraDBError`** if invalid parameters are passed, such as unsupported combinations of fields or data types.

- **Use Case**:
  - Use `ZTSUB` when you need to filter records based on the difference between two fields, a field and a constant, or even between filter conditions. You can combine `ZTSUB` with other filters (e.g., `ZTEQUAL`) to match the subtraction result against another field or constant.

  - **Example**: Subtract the `discount` from `price` and check if the result equals a specific value using `ZTSUB` and `ZTEQUAL`.

- **Note**:
  - if you pass n number of fields / values / ZT* functions to substract from each other. 


### Example:

```php
use ZTeraDB\Query\ZTeraDBQuery;
use ZTeraDB\Query\ZTSUB;
use ZTeraDB\Query\ZTEQUAL;
use ZTeraDB\Query\ZTLTE;

# Example 1: Get all records from the product table where the price minus quantity is equal to 5050.
$filter_condition = ZTeraDB\Query\ZTEQUAL([
    ZTSUB(["price", "quantity"]),
    5050
]);

# Generate the query.
$query = new ZTeraDBQuery("product");
$query->select()->filterCondition($filter_condition);
# ... pass the query to connection.run() method.

# Example 2: Get all records from the product table where the price minus 200 is equal to 3299.
$filter_condition2 = ZTeraDB\Query\ZTEQUAL(
    ZTeraDB\Query\ZTSUB(["price", 200]),
    3299
);

# Generate the query.
$query2 = new ZTeraDBQuery("product");
$query2->select()->filterCondition($filter_condition2);
# ... pass the query2 to connection.run() method.
```

---


### `ZTMUL(values: Array)`
- **Description**: The `ZTMUL` function creates a multiplication filter for the values provided in the `values` array. It returns a new `FilterCondition` instance that can later be used in query-building, often in combination with other filters like `ZTEQUAL`.
  
- **Parameters**:
  - `$values` (Array): An array of values, fields, or any ZT* functions to multiply together.
    - **Values**: An array of constants.
    - **Fields**: An array of schema fields.
    - **FilterCondition**: An array of ZT* functions.
    - **Field, Value, FilterCondition**: A combination of an array of schema field, constant, and ZT* function.

    - **Example**: `["price", "quantity"]` or `[2, 5]` or `[ZTSUB("price", "discount"), 10]`. The price, quantity and discount are schema fields.
  
- **Returns**:
  - A new `FilterCondition` instance with the multiplication filter applied. This can be used as part of a query to filter records based on the result of the multiplication.
  
- **Throws**: 
  - **`ZTeraDBError`** if invalid parameters are passed, such as unsupported data types.

- **Use Case**:
  - Use `ZTMUL` when you need to filter records based on the product of two or more fields, or a combination of fields and constants.

### Example:

```php
use ZTeraDB\Query\ZTeraDBQuery;
use ZTeraDB\Query\ZTMUL;
use ZTeraDB\Query\ZTEQUAL;
use ZTeraDB\Query\ZTGTE;

# Example 1: Get all records from the product table where the price multiply by 10 is grater than or equal to 10,000.
$filter_condition = ZTeraDB\Query\ZTGTE([
    ZTeraDB\Query\ZTMUL(["price", 10]),
    10000
]);

# Generate the query.
$query = new ZTeraDBQuery("product");
$query->select()->filterCondition($filter_condition);
# ... pass the query to connection.run() method.

# Example 2: Get all records from the product table where the price multiply by 2 is equal to 10,000.
$filter_condition2 = ZTeraDB\Query\ZTEQUAL(
    ZTeraDB\Query\ZTMUL(["price", 2]),
    10000
);

# Generate the query.
$query2 = new ZTeraDBQuery("product");
$query2->select()->filterCondition($filter_condition2);
# ... pass the query2 to connection.run() method.
```

---

### `ZTDIV($dividend: any, $divisor: any)`
- **Description**: Applies a division filter to dividend and divisor and returns a new `FilterCondition` instance which will be later used to prepare the filter conditions query. It will be used with some other combination.

- **Parameters**:
  - `dividend` (any): The divisor can be a constant, schema field, or any filter conditions (ZT* function).
    - **Value**: A constant.
    - **Field**: A schema field.
    - **FilterCondition**: A ZT* function.

  - `divisor` (any): The divisor can be a constant, schema field, or any filter conditions (ZT* function).
    - **Value**: A constant.
    - **Field**: A schema field.
    - **FilterCondition**: A ZT* function.

- **Returns**:
  - A new `FilterCondition` instance with the division filter applied. This can be used as part of a query to filter records based on the result of the division.

- **Throws**:
  - **`ZTeraDBError`** if invalid parameters are passed, such as unsupported data types.

- **Use Case**:
  - If you need to find records based on the division of two schema fields or values, and the result must match a specific field's value or a constant, you can use the ZTDIV filter in conjunction with the ZTEQUAL function.

- **Example**:

```php
use ZTeraDB\Query\ZTeraDBQuery;
use ZTeraDB\Query\ZTDIV;
use ZTeraDB\Query\ZTEQUAL;
use ZTeraDB\Query\ZTLTE;

# Example 1: Get all records from the product table where the price divided by quantity is less than or equal to 10,000.
$filter_condition = ZTeraDB\Query\ZTLTE([
    ZTeraDB\Query\ZTDIV("price", "quantity"),
    10000
]);

# Generate the query.
$query = new ZTeraDBQuery("product");
$query->select()->filterCondition($filter_condition);
# ... pass the query to connection.run() method.

# Example 2: Get all records from the product table where the price divided by 2 is less than or equal to 10,000.
$filter_condition2 = ZTeraDB\Query\ZTLTE([
    ZTeraDB\Query\ZTDIV("price", 2),
    10000
]);

# Generate the query.
$query2 = new ZTeraDBQuery("product");
$query2->select()->filterCondition($filter_condition2);
# ... pass the query2 to connection.run() method.
```

---

### `ZTMOD($numerator: any, $denominator: any)`
- **Description**: Applies a modulo filter to dividend and divisor and returns a new `FilterCondition` instance which will be later used to prepare the filter conditions query. It will be used with some other combination.

- **Parameters**:
  - `$numerator` (any): The numerator can be a constant, schema field, or any filter conditions (ZT* function).
    - **Value**: A constant.
    - **Field**: A schema field.
    - **FilterCondition**: A ZT* function.

  - `$denominator` (any): The divisor can be a constant, schema field, or any filter conditions (ZT* function).
    - **Value**: A constant.
    - **Field**: A schema field.
    - **FilterCondition**: A ZT* function.

- **Returns**:
  - A new `FilterCondition` instance with the modulo filter applied. This can be used as part of a query to filter records based on the result of the modulo.

- **Throws**:
  - **`ZTeraDBError`** if invalid parameters are passed, such as unsupported data types.

- **Use Case**:
  - If you need to find records based on the modulo of two schema fields or values, and the result must match a specific field's value or a constant, you can use the ZTMOD filter in conjunction with the ZTEQUAL, ZTLT, ZTGT, etc functions.

- **Example**:

```php
use ZTeraDB\Query\ZTeraDBQuery;
use ZTeraDB\Query\ZTMOD;
use ZTeraDB\Query\ZTEQUAL;

# Example 1: Get all records from the product table where the price MOD quantity is equal to 1.
$filter_condition = ZTeraDB\Query\ZTEQUAL(
    ZTeraDB\Query\ZTMOD("price", "quantity"),
    1
);

# Generate the query.
$query = new ZTeraDBQuery("products");
$query->select()->filterCondition($filter_condition);
# ... pass the query to connection.run() method.

# Example 2: Get all records from the product table where the price MOD 21 is greater than 18.
$filter_condition2 = ZTeraDB\Query\ZTGT([
    ZTeraDB\Query\ZTMOD("price", 21),
    18
]);

# Generate the query.
$query2 = new ZTeraDBQuery("products");
$query2->select()->filterCondition($filter_condition2);
# ... pass the query2 to connection.run() method.
```

---


### `ZTIN(key: string, values: Array)`
- **Description**: Applies a schema field in filter to the values and returns a new `FilterCondition` instance which will be later used to prepare the filter conditions query.
- **Parameters**:
  - `field` (string): The field is schema field name.
  - `values` (Array): An array of values, fields, or any ZT* functions to multiply together.
    - **Values**: An array of constants.
    - **Fields**: An array of schema fields.
    - **FilterCondition**: An array of ZT* functions.
    - **Field, Value, FilterCondition**: A combination of an array of schema field, constant, and ZT* function.

    - **Example**: `["price", "quantity"]` or `[2, 5]` or `[ZTSUB("price", "discount"), 10]`. The price, quantity and discount are schema fields.

- **Returns**:
  - A new `FilterCondition` instance with the `in` filter applied. This can be used as part of a query to filter records.

- **Throws**:
  - **`ZTeraDBError`** if invalid parameters are passed, such as unsupported data types.

- **Use Case**:
  - To find records based on a combination of schema fields and constants.

- **Example**:

```php
use ZTeraDB\Query\ZTeraDBQuery;
use ZTeraDB\Query\ZTIN;

# Example: Get all records from the product table where the quantity is either 200 or 150.
$filter_condition = ZTeraDB\Query\ZTIN("quantity", [200, 150]);

# Generate the query.
$query = new ZTeraDBQuery("product");
$query->select()->filterCondition($filter_condition);
# ... pass the query to connection.run() method.
```

---

### `ZTCONTAINS(field: string, value: string)`
- **Description**: The `ZTICONTAINS` function is used to find a case-sensitive substring within a schema field's data. It returns a new `FilterCondition` instance that can later be used to build the filter query.
- **Parameters**:
  - `field` (string): The field is schema field name.
  - `value` (string): A substring contained within the value of a schema field.

- **Returns**:
  - A new `FilterCondition` instance with the `like` filter applied to the filter.

- **Throws**:
  - **`ZTeraDBError`** if invalid parameters are passed, such as unsupported data types.

- **Use Case**:
  - To find records where the value appears anywhere within the schema field's data.

- **Example**:

```php
use ZTeraDB\Query\ZTeraDBQuery;
use ZTeraDB\Query\ZTCONTAINS;

# Example 1: Get all records from the product table where the product name contains with 'r'.
$filter_condition = ZTeraDB\Query\ZTCONTAINS("name", "a");

# Generate the query.
$query = new ZTeraDBQuery("product");
$query->select()->filterCondition($filter_condition);
# ... pass the query to connection.run() method.
```

---

### `ZTICONTAINS(field: string, value: string)`
- **Description**: The `ZTICONTAINS` function is used to find a case-insensitive substring within a schema field's data. It returns a new `FilterCondition` instance that can later be used to build the filter query.
- **Parameters**:
  - `field` (string): The field is schema field name.
  - `value` (string): A substring contained within the value of a schema field.

- **Returns**:
  - A new `FilterCondition` instance with the `ilike` filter applied to the filter.

- **Throws**:
  - **`ZTeraDBError`** if invalid parameters are passed, such as unsupported data types.

- **Use Case**:
  - To find records where the value appears anywhere within the schema field's data.

- **Example**:

```php
use ZTeraDB\Query\ZTeraDBQuery;
use ZTeraDB\Query\ZTICONTAINS;

# Example 1: Get all records from the product table where the product name contains with 'r' (case-insensitive).
$filter_condition = ZTICONTAINS("product_name", "a");

# Generate the query.
$query = new ZTeraDBQuery("products");
$query->select()->filterCondition($filter_condition);
# ... pass the query to connection.run() method.
```

---

### `ZTSTARTSWITH(field: string, value: string)`
- **Description**: The `ZTSTARTSWITH` function is used to find records where a schema field's data starts with a specific, case-sensitive substring. It returns a new `FilterCondition` instance that can later be used to construct the filter query.
- **Parameters**:
  - `field` (string): The field is schema field name.
  - `value` (string): A substring contained within the value of a schema field.

- **Returns**:
  - A new `FilterCondition` instance with the `like` filter applied to the filter.

- **Throws**:
  - **`ZTeraDBError`** if invalid parameters are passed, such as unsupported data types.

- **Use Case**:
  - To find records where the value case-sensitively starts with a specific substring in the schema field's data.

### Example:

```php
use ZTeraDB\Query\ZTeraDBQuery;
use ZTeraDB\Query\ZTSTARTSWITH;

# Example 1: Get all records from the product table where the product name starts with 'r'.
$filter_condition = ZTSTARTSWITH("product_name", "a");

# Generate the query.
$query = new ZTeraDBQuery("products");
$query->select()->filterCondition($filter_condition);
# ... pass the query to connection.run() method.
```

---

### `ZTISTARTSWITH(field: string, value: string)`
- **Description**: The `ZTISTARTSWITH` function is used to find records where a schema field's data starts with a specific, case-insensitive substring. It returns a new `FilterCondition` instance that can later be used to construct the filter query.
- **Parameters**:
  - `field` (string): The field is schema field name.
  - `value` (string): A substring contained within the value of a schema field.

- **Returns**:
  - A new `FilterCondition` instance with the `ilike` filter applied to the filter.

- **Throws**:
  - **`ZTeraDBError`** if invalid parameters are passed, such as unsupported data types.

- **Use Case**:
  - To find records where the value case-insensitively starts with a specific substring in the schema field's data.

### Example:

```php
use ZTeraDB\Query\ZTeraDBQuery;
use ZTeraDB\Query\ZTISTARTSWITH;

# Example 1: Get all records from the product table where the product name starts with 'r' (case-insensitive).
$filter_condition = ZTeraDB\Query\ZTISTARTSWITH("name", "s");

# Generate the query.
$query = new ZTeraDBQuery("product");
$query->select()->filterCondition($filter_condition);
# ... pass the query to connection.run() method.
```

---

### `ZTENDSWITH(field: string, value: string)`
- **Description**: The `ZTENDSWITH` function is used to find records where a schema field's data ends with a specific, case-sensitive substring. It returns a new `FilterCondition` instance that can later be used to construct the filter query.
- **Parameters**:
  - `field` (string): The field is schema field name.
  - `value` (string): A substring contained within the value of a schema field.

- **Returns**:
  - A new `FilterCondition` instance with the `like` filter applied to the filter.

- **Throws**:
  - **`ZTeraDBError`** if invalid parameters are passed, such as unsupported data types.

- **Use Case**:
  - To find records where the value case-sensitively ends with a specific substring in the schema field's data.

### Example:

```php
use ZTeraDB\Query\ZTeraDBQuery;
use ZTeraDB\Query\ZTENDSWITH;

# Example 1: Get all records from the product table where the product name ends with 'r'.
$filter_condition = ZTeraDB\Query\ZTENDSWITH("name", "r");

# Generate query
$query = new ZTeraDBQuery("product");
$query->select()->filterCondition($filter_condition);
# ... pass the query to connection.run() method.
```

---

### `ZTIENDSWITH(field: string, value: string)`
- **Description**: The `ZTIENDSWITH` function is used to find records where a schema field's data ends with a specific, case-insensitive substring. It returns a new `FilterCondition` instance that can later be used to construct the filter query.
- **Parameters**:
  - `field` (string): The field is schema field name.
  - `value` (string): A substring contained within the value of a schema field.

- **Returns**:
  - A new `FilterCondition` instance with the `ilike` filter applied to the filter.

- **Throws**:
  - **`ZTeraDBError`** if invalid parameters are passed, such as unsupported data types.

- **Use Case**:
  - To find records where the value case-insensitively ends with a specific substring in the schema field's data.

### Example:

```php
use ZTeraDB\Query\ZTeraDBQuery;
use ZTeraDB\Query\ZTIENDSWITH;

# Example 1: Get all records from the product table where the product name ends with 'r' (case-insensitive).
$filter_condition = ZTeraDB\Query\ZTIENDSWITH("name", "r");

# Generate the query.
$query = new ZTeraDBQuery("product");
$query->select()->filterCondition($filter_condition);
# ... pass the query to connection.run() method.
```

---

### `ZTGT(values: Array)`
- **Description**: The ZTGT function generates a "greater than" filter for the values provided in the values array. It returns a new FilterCondition instance that can be utilized later in query construction. Additionally, this function supports the BETWEEN AND query, enabling you to filter schema fields within a specific range.
  
- **Parameters**:
  - `values` (Array): An array of values, fields, or any ZT* functions to multiply together.
    - **Values**: An array of constants.
    - **Fields**: An array of schema fields.
    - **FilterCondition**: An array of ZT* functions.
    - **Field, Value, FilterCondition**: A combination of an array of schema field, constant, and ZT* function.

    - **Example**: `["price", "quantity"]` or `[2, 5]` or `[ZTSUB("price", "discount"), 10]`. The price, quantity and discount are schema fields.
  
- **Returns**:
  - A new `FilterCondition` instance with the greater than filter applied. This can be used as part of a query to filter records based on the result of the greater than.
  
- **Throws**: 
  - **`ZTeraDBError`** if invalid parameters are passed, such as unsupported data types.

- **Use Case**:
  - Use `ZTGT` when you need to filter records based on the greater than of two or more fields, or a combination of fields and constants.

### Example:

```php
use ZTeraDB\Query\ZTeraDBQuery;
use ZTeraDB\Query\ZTGT;
use ZTeraDB\Query\ZTEQUAL;

# Example 1: Get all records from the product table where the price is grater than or equal to 1000.
$filter_condition = ZTeraDB\Query\ZTGT(["price", 1000]);

# Generate the query.
$query = new ZTeraDBQuery("product");
$query->select()->filterCondition($filter_condition);
# ... pass the query to connection.run() method.

# Example 2: Get all records from the product table where the price is grater than 10000 and less than 200.
$filter_condition2 = ZTeraDB\Query\ZTGT([
    100,
    "price",
    200
]);

# Generate the query.
$query2 = new ZTeraDBQuery("product");
$query2->select()->filterCondition($filter_condition2);
# ... pass the query2 to connection.run() method.

# Example 2: Get all records from the product table where the price is grater than double quantity.
$filter_condition3 = ZTeraDB\Query\ZTGT([
  "price",
  ZTeraDB\Query\ZTMUL(["quantity", 2])
]);

# Generate the query.
$query3 = new ZTeraDBQuery("product");
$query3->select()->filterCondition($filter_condition3);
# ... pass the query3 to connection.run() method.
```

---

### `ZTGTE(values: Array)`
- **Description**: The ZTGTE function generates a "greater than or equal to" filter for the values provided in the values array. It returns a new FilterCondition instance that can be utilized later in query construction. Additionally, this function supports the BETWEEN AND query, enabling you to filter schema fields within a specific range.
  
- **Parameters**:
  - `values` (Array): An array of values, fields, or any ZT* functions to multiply together.
    - **Values**: An array of constants.
    - **Fields**: An array of schema fields.
    - **FilterCondition**: An array of ZT* functions.
    - **Field, Value, FilterCondition**: A combination of an array of schema field, constant, and ZT* function.

    - **Example**: `["price", "discount"]` or `[200, price, 500]` or `[ZTSUB("price", "discount"), 10]`. The price, quantity and discount are schema fields.
  
- **Returns**:
  - A new `FilterCondition` instance with the greater than or equal to filter applied. This can be used as part of a query to filter records based on the result of the greater than.
  
- **Throws**: 
  - **`ZTeraDBError`** if invalid parameters are passed, such as unsupported data types.

- **Use Case**:
  - Use `ZTGTE` when you need to filter records based on the greater than or equal to of two or more fields, or a combination of fields and constants.

### Example:

```php
use ZTeraDB\Query\ZTeraDBQuery;
use ZTeraDB\Query\ZTGTE;
use ZTeraDB\Query\ZTMUL;

# Example 1: Get all records from the product table where the price is grater than or equal to 1000.
$filter_condition = ZTeraDB\Query\ZTGTE(["price", 1000]);

# Generate the query.
$query = new ZTeraDBQuery("product");
$query->select()->filterCondition($filter_condition);
# ... pass the query to connection.run() method.

# Example 2: Get all records from the product table where the price is grater than or equal to 10000 and less than or equal to 200.
$filter_condition2 = ZTeraDB\Query\ZTGTE([
    10000,
    "price",
    200
]);

# Generate the query.
$query2 = new ZTeraDBQuery("product");
$query2->select()->filterCondition($filter_condition2);
# ... pass the query2 to connection.run() method.

# Example 3: Get all records from the product table where the price is grater than or equal to double quantity.
$filter_condition3 = ZTeraDB\Query\ZTGTE([
  "price",
  ZTeraDB\Query\ZTMUL(["quantity", 2])
]);

# Generate the query.
$query3 = new ZTeraDBQuery("product");
$query3->select()->filterCondition($filter_condition3);
# ... pass the query3 to connection.run() method.
```

---


### `ZTLT(values: Array)`
- **Description**: The ZTLT function generates a "less than" filter for the values provided in the values an array. It returns a new FilterCondition instance that can be utilized later in query construction. Additionally, this function supports the BETWEEN AND query, enabling you to filter schema fields within a specific range.
  
- **Parameters**:
  - `values` (Array): An array of values, fields, or any ZT* functions to multiply together.
    - **Values**: An array of constants.
    - **Fields**: An array of schema fields.
    - **FilterCondition**: An array of ZT* functions.
    - **Field, Value, FilterCondition**: A combination of an array of schema field, constant, and ZT* function.

    - **Example**: `["price", "quantity"]` or `[2, 5]` or `[ZTSUB("price", "discount"), 10]`. The price, quantity and discount are schema fields.
  
- **Returns**:
  - A new `FilterCondition` instance with the less than filter applied. This can be used as part of a query to filter records based on the result of the less than.
  
- **Throws**: 
  - **`ZTeraDBError`** if invalid parameters are passed, such as unsupported data types.

- **Use Case**:
  - Use `ZTLT` when you need to filter records based on the less than of two or more fields, or a combination of fields and constants.

### Example:

```php
use ZTeraDB\Query\ZTeraDBQuery;
use ZTeraDB\Query\ZTLT;
use ZTeraDB\Query\ZTMUL;

# Example 1: Get all records from the product table where the quantity is less than 10.
$filter_condition = ZTeraDB\Query\ZTLT(["quantity", 10]);

# Generate the query.
$query = new ZTeraDBQuery("product");
$query->select()->filterCondition($filter_condition);
# ... pass the query to connection.run() method.

# Example 2: Get all records from the product table where the price is greater than 1000 and less than 20000.
$filter_condition2 = ZTeraDB\Query\ZTLT([
    1000,
    "price", 
    20000
]);

# Generate the query.
$query2 = new ZTeraDBQuery("product");
$query2->select()->filterCondition($filter_condition2);
# ... pass the query2 to connection.run() method.

# Example 3: Get all records from the product table where the price is less than double of quantity
$filter_condition3 = ZTeraDB\Query\ZTLT([
  ZTeraDB\Query\ZTMUL(["quantity", 2]),
  "price"
]);
# This checks if (quantity * 2) < price.

# Generate the query.
$query3 = new ZTeraDBQuery("product");
$query3->select()->filterCondition($filter_condition3);
# ... pass the query3 to connection.run() method.
```

---

### `ZTLTE(values: Array)`
- **Description**: The `ZTLTE` function generates a "less than or equal to" filter for the values provided in the values array. It returns a new FilterCondition instance that can be utilized later in query construction. Additionally, this function supports the BETWEEN AND query, enabling you to filter schema fields within a specific range.
  
- **Parameters**:
  - `values` (Array): An array of values, fields, or any ZT* functions to multiply together.
    - **Values**: An array of constants.
    - **Fields**: An array of schema fields.
    - **FilterCondition**: An array of ZT* functions.
    - **Field, Value, FilterCondition**: A combination of an array of schema field, constant, and ZT* function.

    - **Example**: `["price", "discount"]` or `[200, price, 500]` or `[ZTSUB("price", "discount"), 10]`. The price, quantity and discount are schema fields.
  
- **Returns**:
  - A new `FilterCondition` instance with the less than or equal to filter applied. This can be used as part of a query to filter records based on the result of the less than.
  
- **Throws**: 
  - **`ZTeraDBError`** if invalid parameters are passed, such as unsupported data types.

- **Use Case**:
  - Use `ZTLTE` when you need to filter records based on the less than or equal to of two or more fields, or a combination of fields and constants.

### Example:

```php
use ZTeraDB\Query\ZTeraDBQuery;
use ZTeraDB\Query\ZTLTE;

# Example 1: Get all records from the product table where the quantity is less than or equal to 10.
$filter_condition = ZTeraDB\Query\ZTLTE(["quantity", 10]);

# Generate the query.
$query = new ZTeraDBQuery("product");
$query->select()->filterCondition($filter_condition);
# ... pass the query to connection.run() method.

# Example 2: Get all records from the product table where the price is greater than or equal to 100 and less than or equal to 5000.
$filter_condition2 = ZTeraDB\Query\ZTLTE([
    100,
    "price",
    5000
]);

# Generate the query.
$query2 = new ZTeraDBQuery("product");
$query2->select()->filterCondition($filter_condition2);
# ... pass the query2 to connection.run() method.

# Example 3: Get all records from the product table where the price is less than or equal to double of quantity
$filter_condition3 = ZTeraDB\Query\ZTLTE([
  ZTeraDB\Query\ZTMUL(["quantity", 2]),
  "price"
]);

# Generate the query.
$query3 = new ZTeraDBQuery("product");
$query3->select()->filterCondition($filter_condition3);
# ... pass the query3 to connection.run() method.
```

---

### `ZTAND($filters: Array)`
- **Description**: The `ZTAND` function creates an `AND` filter using the filters provided in the `filters` array. It returns a new `FilterCondition` instance, which can be used later in query construction. This function is especially useful when you need to retrieve records that satisfy both conditions.
  
- **Parameters**:
  - `$filters` (Array): An array of filter conditions to be combined with an AND operator.
    - **FilterCondition**: An array of ZT* functions.

    - **Example**: `ZTEQUAL("product_name", "xyz")` or `ZTGT([200, price, 500])` or `ZTEQUAL(ZTSUB("price", "discount"), 10)`. `product_name` and `price` are fields in the schema.
  
- **Returns**:
  - A new `FilterCondition` instance with the `AND` operator applied to the filters.
  
- **Throws**: 
  - **`ZTeraDBError`** if invalid parameters are passed, such as unsupported data types.

- **Use Case**:
  - Use `ZTAND` when you need to filter records based on multiple conditions, where all conditions must be true.

### Example:

```php
use ZTeraDB\Query\ZTeraDBQuery;
use ZTeraDB\Query\ZTAND;
use ZTeraDB\Query\ZTLTE;
use ZTeraDB\Query\ZTGTE;
use ZTeraDB\Query\ZTEQUAL;
use ZTeraDB\Query\ZTISTARTSWITH;

# Example 1: Get all records from the product table where the price is less than 1000 and the quantity is greater than 100.
$filter_condition = ZTeraDB\Query\ZTAND([
  ZTeraDB\Query\ZTLTE(["quantity", 100]),
  ZTeraDB\Query\ZTGTE(["price", 100]),
]);

# Generate the query.
$query = new ZTeraDBQuery("product");
$query->select()->filterCondition($filter_condition);
# ... pass the query to connection.run() method.

# Example 2: Get all records from the product table where the status is A and the product name case-insensitive starts with name.
$filter_condition2 = ZTeraDB\Query\ZTAND([
  ZTeraDB\Query\ZTEQUAL("status", "A"),
  ZTeraDB\Query\ZTISTARTSWITH("name", "S"),
]);

# Generate the query.
$query2 = new ZTeraDBQuery("product");
$query2->select()->filterCondition($filter_condition2);
# ... pass the query2 to connection.run() method.
```

---

### `ZTOR($filters: Array)`
- **Description**: The `ZTOR` function creates an `OR` filter using the filters provided in the `filters` array. It returns a new `FilterCondition` instance, which can be used later in query construction. This function is especially useful when you need to retrieve records that satisfy both conditions.
  
- **Parameters**:
  - `$filters` (Array): An array of filter conditions to be combined with an OR operator.
    - **FilterCondition**: An array of ZT* functions.

    - **Example**: 
    ```python

      ZTEQUAL("product_name", "xyz")
      
      or
      
      ZTGT([200, price, 500])
      
      or
      
      ZTEQUAL(ZTSUB("price", "discount"), 10)

    ```
    `product_name` and `price` are fields in the schema.
  
- **Returns**:
  - A new `FilterCondition` instance with the `OR` operator applied to the filters.
  
- **Throws**: 
  - **`ZTeraDBError`** if invalid parameters are passed, such as unsupported data types.

- **Use Case**:
  - Use `ZTOR` when you need to filter records based on multiple conditions, where at least one condition must be true.

### Example:

```php
use ZTeraDB\Query\ZTeraDBQuery;
use ZTeraDB\Query\ZTOR;
use ZTeraDB\Query\ZTLTE;
use ZTeraDB\Query\ZTGTE;
use ZTeraDB\Query\ZTEQUAL;
use ZTeraDB\Query\ZTISTARTSWITH;

# Example 1: Get all records from the product table where the price is less than 1000 or the quantity is greater than 100.
$filter_condition = ZTeraDB\Query\ZTOR([
  ZTeraDB\Query\ZTLTE(["price", 1000]),
  ZTeraDB\Query\ZTGTE(["quantity", 100]),
]);

# Generate the query.
$query = new ZTeraDBQuery("product");
$query->select()->filterCondition($filter_condition);
# ... pass the query to connection.run() method.

# Example 2: Get all records from the product table where the status is A or the product name case-insensitive starts with name.
$filter_condition2 = ZTeraDB\Query\ZTOR([
  ZTeraDB\Query\ZTEQUAL("status", "A"),
  ZTeraDB\Query\ZTISTARTSWITH("name", "S"),
]);

# Generate the query.
$query2 = new ZTeraDBQuery("product");
$query2->select()->filterCondition($filter_condition2);
# ... pass the query2 to connection.run() method.
```

---
