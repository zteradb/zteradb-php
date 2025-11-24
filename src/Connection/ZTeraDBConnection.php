<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB Connection Module
 * --------------------------------------------------------------------------
 *
 *  This module is responsible for managing connections to the ZTeraDB database.
 *  It includes:
 *
 *  • ZTeraDBConnection  
 *      Handles the actual connection to the ZTeraDB database, allowing you to
 *      execute queries and manage connections. It interfaces with the 
 *      ZTeraDBConnectionManager to manage the lifecycle of connections.
 *
 *  • run()  
 *      Executes a query on the ZTeraDB connection. It processes SELECT queries
 *      and non-SELECT queries differently, returning either full result sets or
 *      individual rows, as necessary.
 *
 *  • close()
 *      Closes all active database connections managed by the connection manager.
 *
 *  • ZTeraDBConnectionManager  
 *      A helper class used to manage connections to the ZTeraDB database. It
 *      handles the actual low-level connection tasks such as establishing 
 *      connections, executing queries, and closing connections.
 *
 *  Key Responsibilities:
 *  -----------------------
 *  - Establishing and managing database connections using the connection manager.
 *  - Executing queries through the connection manager.
 *  - Closing connections once the operations are completed.
 *
 *  Usage Notes:
 *  -------------
 *  - This class assumes that the `ZTeraDBConnectionManager` is responsible for
 *    handling all the low-level connection tasks. This class acts as a higher
 *    level controller to facilitate query execution and connection management.
 *
 *  - The `run()` method distinguishes between SELECT and non-SELECT queries and
 *    processes them accordingly.
 *
 *  - The `close_connections()` method ensures proper cleanup by closing any
 *    active connections when the operations are done.
 *
 *  Requirements:
 *  -------------
 *  - `ZTeraDBConnectionManager` must be configured correctly to interact with
 *    the ZTeraDB database.
 *
 * --------------------------------------------------------------------------
 *  @package     ZTeraDB
 *  @version     1.0
 *  @author      ZTeraDB
 *  @license     https://zteradb.com/licence   (SPDX-License-Identifier: Proprietary)
 * --------------------------------------------------------------------------
 */
namespace ZTeraDB\Connection;

// Import necessary classes
use ZTeraDB\Exceptions\ConnectionError;
use ZTeraDB\Connection\ZTeraDBConnectionManager;
use ZTeraDB\Query\ZTeraDBQuery;


/**
 * Constructor
 *
 * Initializes the ZTeraDBConnection object with the connection manager instance.
 *
 * @param string $host The host of the database server.
 * @param int $port The port number to connect to.
 * @param array $zteradb_config Configuration settings for the ZTeraDB connection.
 *
 * Creates a connection manager to handle the actual connections to the database.
 */
class ZTeraDBConnection
{
  // Initialize the connection manager with provided host, port, and config.
  private $connection_manager;

  /**
   * Run a query on the ZTeraDB connection.
   *
   * Executes a query based on whether it's a SELECT query or other types.
   * If the query is a SELECT and doesn't involve counting, it returns the result directly.
   * Otherwise, it returns the first row of the result set.
   *
   * @param ZTeraDBQuery $query The query to be executed.
   * @return mixed The query result, either a full result set or a single row.
   *
   * @throws ConnectionError If connection is not available or there is an error executing the query.
   */
  public function __construct($host, $port, $zteradb_config)
  {
    $this->connection_manager = new ZTeraDBConnectionManager($host, $port, $zteradb_config);
  }

  public function run($query)
  {
    // If the query is a SELECT query and does not require a count, execute and return result directly.
    if ($query->is_select_query() && !$query->has_count()) {
      return $this->connection_manager->execute_query($query);
    } else {
      // Execute the query and return the first result (first row of the result set).
      return $this->connection_manager->execute_query($query)->current();
    }
  }

  /**
   * Close all active database connections.
   *
   * Calls the connection manager to close all open connections.
   */
  public function close()
  {
    // Close all open connections managed by the connection manager.
    $this->connection_manager->close_connections();
  }
}
?>