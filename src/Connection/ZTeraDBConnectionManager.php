<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB Connection Manager Module
 * --------------------------------------------------------------------------
 *
 *  This module is responsible for managing the connections to the ZTeraDB server.
 *  It handles:
 *  
 *  • Establishing and managing connections with ZTeraDB server.
 *  • Authenticating the client and server for secure communication.
 *  • Executing queries and managing responses.
 *  • Releasing and closing connections when no longer needed.
 *
 *  Key Responsibilities:
 *  -----------------------
 *  - Establishing a connection with the server using client authentication.
 *  - Handling server authentication using the `ZTeraDBServerAuth` class.
 *  - Managing a pool of active and running connections.
 *  - Reconnecting or closing connections based on the server's state or errors.
 *  - Ensuring secure communication with the ZTeraDB server.
 *
 *
 * --------------------------------------------------------------------------
 *  @package     ZTeraDB
 *  @version     1.0
 *  @author      ZTera
 *  @license     https://zteradb.com/licence   (SPDX-License-Identifier: Proprietary)
 * --------------------------------------------------------------------------
 */


namespace ZTeraDB\Connection;

// Import necessary classes
// Import necessary classes for exception handling, config, and query handling
use ZTeraDB\Exceptions\ValueError;
use ZTeraDB\Exceptions\ConnectionError;
use ZTeraDB\Exceptions\ZTeraDBAuthError;
use ZTeraDB\Exceptions\QueryError;

// Import ZTeraDBConfig for providing configuration to the auth class.
use ZTeraDB\Config\ZTeraDBConfig;
use ZTeraDB\Connection\ZTeraDBClientProtocol;

use ZTeraDB\Lib\ZTeraDBClientAuth;
use ZTeraDB\Lib\ZTeraDBServerAuth;

// Import RequestTypes for defining or validating
use ZTeraDB\Lib\RequestTypes;
use ZTeraDB\Query\ZTeraDBQuery;
use ZTeraDB\Lib\ResponseTypes;


/**
 * ZTeraDBConnectionManager
 *
 * Manages database connections, authentication, and query execution for ZTeraDB client.
 * Handles connection pooling, authentication, query processing, and error management.
 */
class ZTeraDBConnectionManager
{
  // The hostname or IP address of the ZTeraDB server.
  private $host;

  // The port number used to connect to the ZTeraDB server.
  private $port;

  // ZTeraDBClientAuth instance used to manage the client's authentication process.
  private $client_auth;

  // Holds the ZTeraDB configuration object, including settings like database ID, environment, and authentication keys.
  private $zteradb_config;

  // Stores all currently active but idle connections to the ZTeraDB server, ready to be reused.
  private $active_connections = [];

  // Stores all connections that are currently in use and actively processing queries.
  private $running_connections = [];

  /**
   * Constructor for ZTeraDBConnectionManager.
   *
   * Initializes the connection manager, validates host and port, and sets up
   * client authentication using the provided configuration.
   *
   * @param string $host Database server host.
   * @param int $port Database server port.
   * @param ZTeraDBConfig $zteradb_config Configuration object for the client.
   *
   * @throws ValueError If the host is invalid or port is not an integer.
   */
  public function __construct($host, $port, $zteradb_config)
  {
    // Ensure the server host is provided; it's mandatory for establishing connections
    if (!$host) {
      throw new ValueError("Invalid ZTeraDB server host: Host must be a valid address.");
    }

    // Ensure the server port is an integer; non-integer ports are invalid
    if (!is_int($port)) {
      throw new ValueError("Invalid ZTeraDB server port: Port must be an integer.");
    }

    // Initialize the client authentication handler with the provided configuration
    // This object manages credentials and prepares auth requests for the server
    $this->client_auth = new ZTeraDBClientAuth($zteradb_config);

    // Store server host and port for subsequent connection attempts
    $this->host = $host;
    $this->port = $port;

    // Keep a reference to the full ZTeraDB configuration object for easy access
    $this->zteradb_config = $zteradb_config;

    // Create the minimum number of connections defined in configuration
    // This ensures that the client has ready-to-use connections immediately after initialization
    $this->create_min_connections();
  }

  /**
   * Initialize the minimum number of connections defined in the configuration.
   *
   * This method ensures that when the client starts, there are already
   * idle connections available in the pool according to the configured minimum.
   */
  private function create_min_connections()
  {
    // Check if the minimum number of connections is greater than zero
    if ($this->zteradb_config->options->connection_pool->get_min() > 0) {

      // Loop to create each connection up to the minimum required
      for ($i = 0; $i < $this->zteradb_config->options->connection_pool->get_min(); $i++) {

        // Establish a new connection and add it to the active pool
        $conn = $this->connect();
        if($conn) {
          $this->add_active_connection($conn);
        }
      }
    }
  }

  /**
   * Establish a new connection to the ZTeraDB server and perform client authentication.
   *
   * Steps:
   * 1. Create a new low-level protocol connection.
   * 2. Generate a client authentication request.
   * 3. Send the auth request and wait for the server response.
   * 4. Validate the response and initialize server authentication.
   * 5. Return the fully authenticated connection.
   *
   * @return ZTeraDBClientProtocol The authenticated connection instance.
   * @throws ZTeraDBAuthError If authentication fails.
   */
  private function connect()
  {
    // Create a new protocol-level connection to the server using host and port
    $conn = new ZTeraDBClientProtocol($this->host, $this->port);

    // Generate the authentication request payload using client credentials
    $client_auth_request = $this->client_auth->generate_auth_request();

    // Send the authentication request to the server
    $conn->send(json_encode($client_auth_request));

    // Receive the server's response to the authentication request
    $auth_response = $conn->receive();

    // Loop through all responses (in case multiple auth messages are returned)
    foreach ($auth_response as $response) {
      // If the server returned an error, throw an authentication exception
      if ($response->error) {
        throw new ZTeraDBAuthError($response->data);
      }

      // Store server authentication information for later use
      $server_auth = new ZTeraDBServerAuth($response->data);
      $conn->set_server_auth($server_auth);
    }

    // Return the authenticated connection ready for query execution
    return $conn;
  }

  /**
   * Re-establish a connection by closing the existing one and creating a new authenticated connection.
   *
   * Steps:
   * 1. Close the provided connection to ensure no dangling resources remain.
   * 2. Create a fresh connection using `connect()`.
   * 3. Add the new connection to the pool of active connections.
   * 4. Return the new connection instance for immediate use.
   *
   * @param ZTeraDBClientProtocol $connection The connection to be replaced.
   * @return ZTeraDBClientProtocol The new, fully authenticated connection.
   */
  private function re_connect($connection)
  {
    // Close the old connection to free resources
    $this->close_connection($connection);

    // Create a new connection and perform client-server authentication
    $conn = $this->connect();

    // Return the new connection for immediate usage
    return $conn;
  }

  /**
   * Execute a ZTeraDB query and yield results.
   *
   * Steps:
   * 1. Validate that the provided query is an instance of ZTeraDBQuery.
   * 2. Acquire an available connection from the connection pool.
   * 3. Check if the server access token has expired and re-connect if necessary.
   * 4. Prepare the request payload including query, request type, database ID, and environment.
   * 5. Send the request to the server and receive the response.
   * 6. Yield the query data if the response code indicates successful query data.
   * 7. Release the connection back to the active pool after execution.
   *
   * @param ZTeraDBQuery $query The query object to be executed.
   * @param int $timeout Optional timeout for the query (currently unused).
   * @return Generator Yields each row of query results.
   * @throws ValueError If the provided query is not a ZTeraDBQuery instance.
   * @throws ConnectionError If a connection cannot be obtained.
   * @throws QueryError If the server returns an error response.
   */
  public function execute_query($query, $timeout = 0)
  {
    // Ensure the provided object is a valid ZTeraDBQuery
    if (!$query instanceof ZTeraDBQuery) {
      throw new ValueError("Invalid ZTeraDB server port: Port must be an integer.");
    }

    // Get an available connection from the pool
    $conn = $this->get_connection();

    // If no connection could be acquired, throw an exception
    if (!$conn) {
      throw new ConnectionError("Invalid connection: ZTeraDB server connection does not exist.");
    }

    // Prepare the request payload for the server
    $request_data = [
      "query" => $query->generate(),  // Generate query string to send
      "request_type" => RequestTypes::$QUERY,  // Specify request type as QUERY
      "database_id" => $this->zteradb_config->database_id,   // Include database ID
      "env" => $this->zteradb_config->env->get_env(),   // Include environment
    ];

    // Send the JSON-encoded query request to the server
    $conn->send(json_encode($request_data));

    // Receive the response from the server
    $query_response = $conn->receive();

    // Iterate over the response and yield data if successful
    foreach ($query_response as $response) {
      if ($response->response_code === ResponseTypes::$QUERY_DATA) {
        // Yield each row of data
        yield $response->data;
      } else {
        // Release connection back to pool before throwing an error
        $this->release_connection($conn);

        // Server returned an error
        throw new QueryError($response->data);
      }
    }

    // Release the connection back to the active pool after finishing the query
    $this->release_connection($conn);
  }

  /**
   * Adds an active connection to the connection pool.
   *
   * @param ZTeraDBClientProtocol $connection The connection to add.
   */
  public function add_active_connection($connection)
  {
    // Push the provided connection object into the active_connections array
    $this->active_connections[\spl_object_hash($connection)] = $connection;
  }

  /**
   * Releases a connection that is currently running and moves it back to the pool of active connections
   *
   * @param ZTeraDBClientProtocol $connection The connection to release.
   */
  public function release_connection($connection)
  {
    // Check if the connection exists in the running_connections array
    // Use spl_object_hash to uniquely identify the object
    if (isset($this->running_connections[\spl_object_hash($connection)])) {
      // Remove the connection from the running_connections array
      unset($this->running_connections[\spl_object_hash($connection)]);
    }

    // Add the connection back to the active_connections pool for reuse
    $this->add_active_connection($connection);
  }

  /**
   * Adds a connection to the running connections pool.
   *
   * @param ZTeraDBClientProtocol $connection The connection to add to the running pool.
   */
  public function add_running_connection($connection)
  {
    // Adds the connection to the active_connections array
    $this->running_connections[\spl_object_hash($connection)] = $connection;
  }

  /**
   * Retrieves a connection from the pool, either reusing an active one or creating a new one
   *
   * @return ZTeraDBClientProtocol The available connection.
   *
   * @throws ConnectionError If no connection is available and one cannot be created.
   */
  public function get_connection()
  {
    // Check if there are any available connections in the active pool
    if (!empty($this->active_connections)) {
      // Reuse the last available connection
      $conn = array_pop($this->active_connections);

      // If the server access token has expired, reconnect to refresh it
      if ($conn->is_access_token_expired()) {
        $conn = $this->re_connect($conn);

        // Add the newly created connection to the pool of running connections
        $this->add_running_connection($conn);
      }
    } else {
      // No active connections available, create a new one
      $conn = $this->connect();

      // If connection creation fails, throw an exception
      if (!$conn) {
        throw new ConnectionError("Invalid connection: ZTeraDB server connection does not exist.");
      }
    }

    // Mark the connection as currently running using its unique object hash
    $this->add_running_connection($conn);

    // Return the connection to the caller
    return $conn;
  }

  /**
   * Returns all currently available (inactive) connections in the pool
   *
   * @return array List of active connections.
   */
  public function get_all_active_connections()
  {
    // Simply return the array of active connections
    return $this->active_connections;
  }

  /**
   * Returns all currently running connections
   *
   * @return array List of running connections.
   */
  public function get_all_running_connections()
  {
    // Return the array of connections currently in use
    return $this->running_connections;
  }

  /**
   * Closes a given connection and removes it from both active and running pools
   *
   * @param ZTeraDBClientProtocol $conn The connection to close.
   */
  public function close_connection($conn)
  {
    try {
      // Attempt to close the connection (assuming the connection object has this method)
      $conn->close_connection();
    } catch (\Exception $e) {
      // error_log($e->getMessage());
    } finally {
      // Remove the connection from running_connections if present
      $connHash = spl_object_hash($conn);

      // Remove the connection from active_connections if present
      if (isset($this->active_connections[$connHash])) {
        unset($this->active_connections[$connHash]);
      }

      // Remove the connection from running_connections if present
      if (isset($this->running_connections[$connHash])) {
        unset($this->running_connections[$connHash]);
      }
    }
  }

  /**
   * Closes all connections in both active and running pools
   */
  public function close_connections()
  {
    // Close all active (unused) connections
    foreach ($this->active_connections as $conn) {
      $conn->close_connection();
    }

    // Close all running (in-use) connections
    foreach ($this->running_connections as $conn) {
      try {
        $conn->close_connection();
      } catch (\Exception $e) {
        // error_log("Failed to close running connection: " . $e->getMessage());
      }
    }
  }
}
?>