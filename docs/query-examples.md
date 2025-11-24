---
sidebar_position: 5
---

# ZTeraDB Query examples

Consider we have a database called `test` with generated database ID 7K3WHGOJKJJEJ3PFJM407QO25F. This database has following schemas.

### user schema 
  - id: integer(11) auto increment primary key
  - email: varchar(255) not null unique
  - password varchar(255) not null
  - status boolean default true

### user_profile schema
  - id: integer(11) auto increment primary key
  - user: user schema (related field)
  - address: user address varchar(255)
  - profile_image: varchar(255)

### product schema
  - id: ZTeraDB UID - Varchar field
  - name: varchar(255)
  - description: text
  - quantity: small integer(5)
  - price: small integer(5)
  - create_date: datetime
  - update_date: datetime
  - status: varchar(2) (A: Available, NA: Not available)

### order schema
  - id: ZTeraDBID - Varchar field
  - product: product schema (related field)
  - user: user schema (related field)
  - create_date: datetime
  - update_date: datetime
  - status: varchar(2) (A: Approved, NA: Not Approved, D: Delivered, etc)

## Establish the database connection
  This connection will be used in all below examples

```php
# Import ZTeraDBConnection, ZTeraDBQuery classes 
use ZTeraDB\Config\ZTeraDBConfig;
use ZTeraDB\Config\Options;
use ZTeraDB\Config\ResponseDataTypes;
use ZTeraDB\Config\ConnectionPool;
use ZTeraDB\Connection\ZTeraDBConnection;

$host = "db.zteradb.com";
$port = 7777;

$zteradb_config = new ZTeraDBConfig(
    client_key=>"7DV0AVT0VO81B9KSUJP8Q4PIFS",
    access_key=>"4SVOHVT0VO81B9KSUJP8Q4PIFS",
    secret_key=> "7fbb52c011ecafaa9a1d1b8683dd661cb4143f7f27f86c0303e02880f28fe409c0b4266c012f8edf9ed1b729a6c3d6fa88d8f269d4ad146211708a2cca1a7d9a",
    database_id=>"7K3WHGOJKJJEJ3PFJM407QO25F",
    env=>"dev",
    response_data_type=>new ResponseDataTypes::json,
    options=>new Options(
        connection_pool=>new ConnectionPool(1, 10)
    )
);

# Establish connection with ZTeraDB server
$connection = new ZTeraDBConnection($host, $port, $zteradb_config);
```
## Example 1: Insert queries

### 1: Insert user and profile
```php
# Import ZTeraDBQuery
use ZTeraDB\Exceptions\ValueError;
use ZTeraDB\Query\ZTeraDBQuery;

# Constructing an INSERT query:
$user_query = new ZTeraDBQuery("user");
$user_query->insert(); # Construct query for user schema

# set name, email and status fields
$user_query->fields([
    "email"=>"john.doe@example.com", "password"=>"9b4d99d461723232aff72be0351f114b",
    "status"=>true
]);

$user_id = "";

try{
  # Run the query
  # assuming the connection object exists
  $result = $connection->run($user_query);

  # get the user ID
  $user_id = $result->last_insert_id;

}
catch(ValueError $e) {
    throw new Exception($e);
}

if ($user_id) {
  # Construct query for user_profile schema
  $user_profile_query = new ZTeraDBQuery("user_profile");
  $user_profile_query->insert();

  # set user, address and profile_image fields
  $user_profile_query->fields([
    "user"=>$user_id,
    "address"=>"a-123, xyz lane, my city, IN",
    "profile_image"=>"/user/xyz.jpg"
  ]);

  echo "User ID: $user_id\n";

  try {
    # Run the query
    # assuming the connection object exists
    $user_profile_result = $connection->run($user_profile_query);

    # get the user profile ID
    $user_profile_id = $user_profile_result->last_insert_id;
    echo "user_profile_id: $user_profile_id\n";

  }
  catch(ValueError $e) {
    throw new Exception($e);
  }
}
```

### 2: Insert products and orders for above inserted user ID
```php
# Import ZTeraDBQuery
use ZTeraDB\Query\ZTeraDBQuery;

# Get the user id from above insert user query or assign the user id
# $user_id = $user_id;

# Prepare products
$products = [
  [
    "name"=>"Wireless Mouse",
    "description"=>"Ergonomic wireless mouse with USB receiver",
    "quantity"=>120,
    "price"=>1999,
    "create_date"=>"2025-02-01 10:00:00",
    "update_date"=>"2025-02-01 10:00:00",
    "status"=>"A"
  ],
  [
    "name"=>"Bluetooth Headphones",
    "description"=>"Noise-canceling over-ear Bluetooth headphones",
    "quantity"=>50,
    "price"=>5999,
    "create_date"=>"2025-02-02 14:30:00",
    "update_date"=>"2025-02-02 14:30:00",
    "status"=>"A"
  ],
  [
    "name"=>"Laptop Stand",
    "description"=>"Adjustable laptop stand for comfortable work setup",
    "quantity"=>200,
    "price"=>1299,
    "create_date"=>"2025-02-03 09:15:00",
    "update_date"=>"2025-02-03 09:15:00",
    "status"=>"A"
  ],
  [
    "name"=>"Smartphone Charger",
    "description"=>"Fast-charging USB-C smartphone charger",
    "quantity"=>150,
    "price"=>799,
    "create_date"=>"2025-02-04 11:00:00",
    "update_date"=>"2025-02-04 11:00:00",
    "status"=>"A"
  ],
  [
    "name"=>"Portable Speaker",
    "description"=>"Water-resistant portable Bluetooth speaker",
    "quantity"=>80,
    "price"=>3499,
    "create_date"=>"2025-02-05 12:45:00",
    "update_date"=>"2025-02-05 12:45:00",
    "status"=>"A"
  ],
  [
    "name"=>"4K Monitor",
    "description"=>"Ultra HD 4K LED monitor with HDMI port",
    "quantity"=>20,
    "price"=>14999,
    "create_date"=>"2025-02-06 16:00:00",
    "update_date"=>"2025-02-06 16:00:00",
    "status"=>"NA"
  ],
  [
    "name"=>"Gaming Keyboard",
    "description"=>"RGB mechanical keyboard with customizable keys",
    "quantity"=>75,
    "price"=>4999,
    "create_date"=>"2025-02-07 13:30:00",
    "update_date"=>"2025-02-07 13:30:00",
    "status"=>"A"
  ],
  [
    "name"=>"Wireless Charger",
    "description"=>"Fast wireless charging pad for smartphones",
    "quantity"=>300,
    "price"=>1499,
    "create_date"=>"2025-02-08 08:30:00",
    "update_date"=>"2025-02-08 08:30:00",
    "status"=>"A"
  ],
  [
    "name"=>"Smartwatch",
    "description"=>"Fitness tracking smartwatch with heart-rate monitor",
    "quantity"=>90,
    "price"=>7999,
    "create_date"=>"2025-02-09 18:15:00",
    "update_date"=>"2025-02-09 18:15:00",
    "status"=>"A"
  ],
  [
    "name"=>"Gaming Mouse",
    "description"=>"Precision gaming mouse with customizable DPI settings",
    "quantity"=>130,
    "price"=>2499,
    "create_date"=>"2025-02-10 17:00:00",
    "update_date"=>"2025-02-10 17:00:00",
    "status"=>"A"
  ]
];

$orders = [
  [
    "user_id"=>$user_id,
    "create_date"=>"2025-02-21 10:00:00",
    "update_date"=>"2025-02-21 10:00:00",
    "status"=>"A"
  ],
  [
    "user_id"=>$user_id,
    "create_date"=>"2025-02-21 12:00:00",
    "update_date"=>"2025-02-21 12:30:00",
    "status"=>"NA"
  ],
  [
    "user_id"=>$user_id,
    "create_date"=>"2025-02-21 14:00:00",
    "update_date"=>"2025-02-21 14:00:00",
    "status"=>"D"
  ],
  [
    "user_id"=>$user_id,
    "create_date"=>"2025-02-21 16:00:00",
    "update_date"=>"2025-02-21 16:15:00",
    "status"=>"A"
  ],
  [
    "user_id"=>$user_id,
    "create_date"=>"2025-02-21 17:30:00",
    "update_date"=>"2025-02-21 17:30:00",
    "status"=>"NA"
  ],
  [
    "user_id"=>$user_id,
    "create_date"=>"2025-02-21 18:00:00",
    "update_date"=>"2025-02-21 18:00:00",
    "status"=>"A"
  ],
  [
    "user_id"=>$user_id,
    "create_date"=>"2025-02-21 19:00:00",
    "update_date"=>"2025-02-21 19:15:00",
    "status"=>"D"
  ],
  [
    "user_id"=>$user_id,
    "create_date"=>"2025-02-21 20:00:00",
    "update_date"=>"2025-02-21 20:10:00",
    "status"=>"A"
  ],
  [
    "user_id"=>$user_id,
    "create_date"=>"2025-02-21 21:00:00",
    "update_date"=>"2025-02-21 21:00:00",
    "status"=>"NA"
  ],
  [
    "user_id"=>$user_id,
    "create_date"=>"2025-02-21 22:30:00",
    "update_date"=>"2025-02-21 22:40:00",
    "status"=>"D"
  ]
];

# A list for storing product ids
$product_ids = [];

foreach($products as $product) {
    # Constructing an INSERT query:
    $product_query = new ZTeraDBQuery("product");
    $product_query->insert();
    $product_query->fields($product);

    # Run the query
    # assuming the connection object exists
    $product_result = $connection->run($product_query);

    # Raise exception if product data is not inserted
    if(!$product_result) {
        throw new Exception("An error occurred while inserting product.");
    }

    # set product id in product ids list
    $product_ids[] = $product_result->last_insert_id;;
}

# A list for storing order ids
$order_ids = [];
foreach($orders as $order_index => $order) {
    # Set the product id
    $order["product_id"] = $product_ids[$order_index];

    # Constructing an INSERT query:
    $order_query = new ZTeraDBQuery("order");
    $order_query->insert()->fields($order);

    # Run the query
    # assuming the connection object exists
    $order_result = $connection->run($order_query);

    # Raise exception if order data is not inserted
    if(!$order_result) {
        throw new Exception("An error occurred while inserting order.");
    }

    # set order id in order ids list
    $order_ids[] = $order_result->last_insert_id;
}

print_r($product_ids);  # ['4UBPSG0O18AGPBE3EFVGD1980I','4UBPSG0LILCFJ6UBSTNNIOSN7A', '4UBPSG0NPTTKHELBLRQLA8OSE6', '4UBPSG0NHB3HKIVFB8OM6QAO3N', '4UBPSG0MEK2BK2MSOJQMI3RDFF', '4UBPSG0LR8EFQIQO0JR4R1C9TK', '4UBPSG0MVONFIQITG390RAK9RG', '4UBPSG0MNOUFKR3T2RA4E1610I', '4UBPSG0M4J66LHS27F4CBU0UHG', '4UBPSG0N6Q24FBP4L1SKSS3P1P']

print_r($order_ids);  # ['4UBPSG14JPJ99HJKTU30NSNP47', '4UBPSG13Q25830O1T6BJDH98KO', '4UBPSG156DKGSOPRB7OAFNSQK2', '4UBPSG151LJ6AK5D7A8SFUGBU2', '4UBPSG165EF6A13B83085ON38D', '4UBPSG149N8G7FPLMUQDRB5TFM', '4UBPSG141SDQTOUM04RHUJKO4G', '4UBPSG15S7AI2MQ6B3ELLGTEPJ', '4UBPSG14RQI8OTP1BRPMICJB9S', '4UBPSG15G0IB7EP9AOSKCA2C7F']
```

## Example 2: Get / Select queries

### 1: Get / Select all users
```php
# Import ZTeraDBQuery
use ZTeraDB\Query\ZTeraDBQuery;
use ZTeraDB\Query\QueryError;

# Constructing a SELECT query for getting all users and all fields from the user schema
$user_query = new ZTeraDBQuery("user");
$user_query->select();

# Run the query
# assuming the connection object exists
$user_result = $connection->run($user_query);

# Iterate the result
foreach($user_result as $row) {
  print_r($row);  # {'email': 'john.doe@example.com', 'password': '9b4d99d461723232aff72be0351f114b', 'status': true, 'id': 1}
}
```

### 2. Get user where user email = "john.doe@example.com"
```php

# Import ZTeraDBQuery
use ZTeraDB\Query\ZTeraDBQuery;

# Constructing a SELECT query for getting all users with email="john.doe@example.com" and all fields from the user schema
$user_query = new ZTeraDBQuery("user");
$user_query->select()->filter(["email"=>"john.doe@example.com"]);

# Run the query
# assuming the connection object exists
$user_result = $connection->run($user_query);

# Iterate the result
foreach($user_result as $row) {
  print_r($row);  # {'email': 'john.doe@example.com', 'password': '9b4d99d461723232aff72be0351f114b', 'status': true, 'id': 1}
}
```

### 3. Get user where user id = 1

```php
# Import ZTeraDBQuery
use ZTeraDB\Query\ZTeraDBQuery;

# Constructing a SELECT query for getting all users with id=1 and all fields from the user schema
user_query = new ZTeraDBQuery("user");
$user_query->select()->filter(["id"=>1]);

# Run the query
# assuming the connection object exists
$user_result = $connection->run($user_query);

# Iterate the result
foreach($user_result as $row) {
  print_r($row)  # Output: dict -> {'email': 'john.doe@example.com', 'password': '9b4d99d461723232aff72be0351f114b', 'status': true, 'id': 1}
}
```

### 4. Get email id from user where user status = true.
```php
# Import ZTeraDBQuery
use ZTeraDB\Query\ZTeraDBQuery;

# Constructing a SELECT query for getting all active users email
$user_query = new ZTeraDBQuery("user");
$user_query->select()->fields(["email"=>1])->filter(["status"=>true]);

# Run the query
# assuming the connection object exists
$user_result = $connection->run($user_query);

# Iterate the result
foreach($user_result as $row) {
  echo $row->email;  # Output: john.doe@example.com
}
```

### 5. Get one product where name = "Gaming Keyboard" and create_date = "2025-02-23T01:05:48.563929"
```php
# Import ZTeraDBQuery
use ZTeraDB\Query\ZTeraDBQuery;

# Import ValueError exception
use ZTeraDB\Exceptions\ValueError;

# Constructing a SELECT query
$product_query = new ZTeraDBQuery("product");
$product_query->select()->filter([
    "name"=>"Gaming Keyboard",
    "create_date"=>"2025-02-23T01:05:48.563929"
])->limit(0, 1);

try {
  # Run the query
  # assuming the connection object exists
  $product_result = $connection->run($product_query);

  # Iterate the result
  foreach($product_result as $row) {
    print_r($row)  # Output: dict -> {'id': '4U8RGUC9SPS4I0S5FLP9VH93IN', 'name': 'Gaming Keyboard', 'description': 'RGB mechanical keyboard with customizable keys', 'quantity': 75, 'price': 4999, 'create_date': '2025-02-23T01:05:48.563929+00:00', 'update_date': '2025-02-23T01:05:48.563934+00:00', 'status': 'A'}
  }
}
catch(ValueError as $e){
    throw new Exception($e);
}
```

## Example 3: Update queries

### 1: Update product schema for all product name = "Gaming Keyboard" to name = "Wireless Gaming Keyboard"
```php
# Import ZTeraDBQuery
use ZTeraDB\Query\ZTeraDBQuery;

# Import ValueError exception
use ZTeraDB\Exceptions\ValueError;

# Constructing an UPDATE query
$product_query = new ZTeraDBQuery("product");
$product_query->update()->fields([
    "name"=>"Wireless Gaming Keyboard"
])->filter(["name"=>"Gaming Keyboard"]);

try {
  # Run the query
  # assuming the connection object exists
  $product_result = $connection->run($product_query);

  # Check the product status
  if($product_result->is_updated) {
      echo "Product has been updated successfully.";
  }
  else {
      echo "Product update failed.";
  }
}
catch(ValueError $e) {
    throw new Exception($e);
}
```

## Example 4: Delete queries

### 1: Delete from order schema for order id = "Wireless Gaming Keyboard"
```php
# Import ZTeraDBQuery
use ZTeraDB\Query\ZTeraDBQuery;

# Import ValueError exception
use ZTeraDB\Exceptions\ValueError;

# Constructing a DELETE query
$delete_order_query = new ZTeraDBQuery("order");
$delete_order_query->delete();
$delete_order_query->filter([
    "product"=>"4UARJ2B0KOABVQVRSUDMLR4M89"
]);

# Note: product is foreign key field

try {
    # Run the query
    # assuming the connection object exists
    $delete_order_result = $connection->run($delete_order_query);

    # Check the delete order status
    if delete_order_result["is_deleted"] {
        echo "Order has been deleted successfully.";
    }
    else {
        echo "Order deleted failed.";
    }
}
catch(ValueError as $e) {
    throw new Exception($e);
}
```