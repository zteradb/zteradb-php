# ZTeraDB php 5.6+ Client library
This is a PHP 5.6+ client library for interacting with **ZTeraDB**, a platform that allows you to connect to your 
existing databases and query them using **ZTeraDB Query Language (ZQL)**. The client provides an easy-to-use interface 
to send queries to ZTeraDB and retrieve results in a standardized format.

This package implements a ZQL-first PHP client for ZTeraDB using a raw TCP socket transport.
It assumes the server uses 4-byte big-endian length-prefixed payloads that contain JSON payloads.

## Table of Contents
1. [Features](#features)
2. [Requirements](#requirements)
3. [Installing](#install)
4. [Usage](#usage)
5. [Configuration](https://github.com/zteradb/zteradb-php/blob/main/docs/config.md)
6. [ZTeraDB Connection](https://github.com/zteradb/zteradb-php/blob/main/docs/zteradb-connection.md)
7. [Query](https://github.com/zteradb/zteradb-php/blob/main/docs/query.md)
8. [Filter Conditions](https://github.com/zteradb/zteradb-php/blob/main/docs/filter-condition.md)
9. [License](#license)


## **Features**

- **Connect to Multiple Databases**: Seamlessly interact with your existing databases through ZTeraDB.
- **ZTeraDB Query Language (ZQL)**: Use a unified query language to query data across different database systems.
- **Easy Integration**: Easily integrate ZTeraDB into your Node.js application.
- **Asynchronous Queries**: Support for async/await syntax to handle queries and results asynchronously.
- **Error Handling**: Comprehensive error handling to help debug and manage database queries.

## Requirements
- This is a php5.6+ module available through the Packagist (packagist.org) registry.
- Before installing, download and [install php](https://www.php.net/downloads.php). PHP 5.6.40 or higher is required.

## **Install**
Run following command to install the ZTeraDB client for php.

```sh

# Using composer
composer require vendor/zteradb-php

```

OR

Install from the ZTeraDB github repository.
Create a composor.json file with following content.

```sh
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/zteradb/zteradb-php"
    }
  ],
  "require": {
    "zteradb/zteradb-php": "dev-main"
  }
}

```

Install the ZTeraDB Client for php.
```sh

composer require zteradb/zteradb-php:dev-main

```

## **Usage**

```php

require_once "vendor/autoload.php";

use ZTeraDB\Config\Options;
use ZTeraDB\Config\ConnectionPool;
use ZTeraDB\Config\ENVS;
use ZTeraDB\Config\ResponseDataTypes;
use ZTeraDB\Config\ZTeraDBConfig;
use ZTeraDB\Connection\ZTeraDBConnection;
use ZTeraDB\Query\ZTeraDBQuery;

# ZTeraDB host, port details from environment.
$host = getenv("ZTERADB_HOST");
$port = (int) getenv('PORT');

# ZTeraDB configuration details
$zteradb_config = new ZTeraDBConfig([
        "client_key" => getenv("CLIENT_KEY"),
        "access_key" => getenv("ACCESS_KEY"),
        "secret_key" => getenv("SECRET_KEY"),
        "database_id" => getenv("DATABASE_ID"),
        "env" => ENVS::dev,
        "response_data_type" => ResponseDataTypes::json,
    ]);

# Connect to ZTeraDB server
$conn = new ZTeraDBConnection($host, $port, $zteradb_config);

# Schema name to get the result
$query = new ZTeraDBQuery("<your schema name>");

# Run the query
$rows = $conn->run($query);

# Iterate the query result
foreach($rows as $row) {
  print_r($row);
}

# Close all connections
$conn->close();

```

## **License**

This project is licensed under the **ZTeraDB** License - see [LICENCE](https://github.com/zteradb/zteradb-php/blob/main/LICENCE) file for details.
