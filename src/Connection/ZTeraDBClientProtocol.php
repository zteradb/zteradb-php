<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB Client Protocol
 * --------------------------------------------------------------------------
 *
 *  This class manages the client-side protocol for communicating with a ZTeraDB server.
 *  It uses a TCP socket connection to send and receive data, ensuring structured 
 *  communication and handling server responses.
 *
 *  Main Responsibilities:
 *  ----------------------
 *  - Establish a TCP socket connection to the ZTeraDB server.
 *  - Send data using ZTeraDBDataManager for proper packing.
 *  - Receive data in chunks and handle parsing of JSON responses.
 *  - Detect query completion and throw exceptions when needed.
 *  - Close connections safely to prevent resource leaks.
 *
 *  Usage Notes:
 *  ------------
 *  - Always call `close_connection()` when finished to free socket resources.
 *  - Use `send()` to transmit requests and `receive()` to iterate over responses.
 *  - Be ready to handle ProtocolError, QueryComplete, and JSONParseError.
 *
 *  Dependencies:
 *  -------------
 *  - ZTeraDBDataManager
 *  - ResponseTypes
 *  - ZTeraDBExceptions (ProtocolError, QueryComplete, JSONParseError)
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
use ZTeraDB\Exceptions\ProtocolError;
use ZTeraDB\Exceptions\QueryComplete;
use ZTeraDB\Exceptions\JSONParseError;
use ZTeraDB\Connection\ZTeraDBDataManager;
use ZTeraDB\Lib\ResponseTypes;


/**
 * Class ZTeraDBClientProtocol
 * 
 * Handles low-level socket communication with the ZTeraDB server, including:
 * - Sending data
 * - Receiving data and parsing responses
 * - Handling query completion
 * - Managing socket connections
 */
class ZTeraDBClientProtocol
{
  // Socket client resource
  private $client;

  /**
   * Constructor: Establishes a TCP socket connection to the ZTeraDB server.
   *
   * This method:
   *  - Creates a TCP socket using IPv4.
   *  - Connects the socket to the specified host and port.
   *  - Throws a ProtocolError if socket creation or connection fails.
   *
   * @param string $host The IP address or hostname of the ZTeraDB server.
   * @param int    $port The port number to connect to on the ZTeraDB server.
   *
   * @throws ProtocolError if socket creation or connection fails.
   */
  public function __construct($host, $port)
  {
    // Create a TCP/IP socket (IPv4, stream socket, TCP)
    $this->client = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

    // Check if socket creation was successful
    if ($this->client === false) {
      // Throw an exception if socket creation failed
      throw new ProtocolError("Failed to create socket for ZTeraDB server connection.\n" .
        "Socket Error: " . socket_strerror(socket_last_error()) . "\n" .
        "Possible causes: insufficient system resources, unsupported socket type, or network configuration issues.");
    }

    // Attempt to connect the socket to the given host and port
    if (socket_connect($this->client, $host, $port) === false) {
      // Throw an exception if the connection attempt fails
      throw new ProtocolError("Failed to establish a connection to ZTeraDB server.\n" .
        "Host: $host, Port: $port\n" .
        "Socket Error: " . socket_strerror(socket_last_error($this->client)) . "\n" .
        "Possible causes: server unavailable, network issues, or firewall blocking the connection.");
    }
  }

  /**
   * Send data to the ZTeraDB server over the established socket connection.
   *
   * This method:
   *  - Packs the given data using ZTeraDBDataManager to ensure proper protocol format.
   *  - Writes the packed data to the TCP socket for transmission.
   *
   * @param mixed $data The data (array, object, or string) to send to the server.
   */
  public function send($data)
  {
    // Pack the data using ZTeraDBDataManager to convert it into the proper protocol format
    $pack_data = ZTeraDBDataManager::pack($data);

    // Send the packed data to the server via the TCP socket
    socket_write($this->client, $pack_data);
  }

  /**
   * Receive and process responses from the ZTeraDB server.
   *
   * This method:
   *  - Continuously reads data from the socket in chunks.
   *  - Unpacks the buffer size and reads the full response.
   *  - Parses the JSON response and yields it to the caller.
   *  - Detects query completion using the QueryComplete exception.
   *
   * Usage:
   *  - Use this method as a generator to iterate over server responses.
   *  - Handle QueryComplete exception to know when a query is fully processed.
   *
   * @return Generator Yields parsed server responses.
   * @throws \Exception For socket read errors or JSON parsing errors.
   */
  public function receive()
  {
    // Flag to track if the query has completed
    $query_complete = false;

    // Loop until the server indicates the query is complete
    while (!$query_complete) {
      // Read the initial buffer containing the size of the incoming data
      $buffer_data = $this->receive_all(ZTeraDBDataManager::$buffer_size);

      // Unpack the buffer to get the exact size of the incoming full data
      $buffer_data_size = ZTeraDBDataManager::unpack($buffer_data, 0, ZTeraDBDataManager::$buffer_size);

      // Read the full response based on the unpacked size
      $data = $this->receive_all($buffer_data_size[1]);

      try {
        // Parse the response and yield it to the caller
        yield $this->parseResponseData($data);
      } catch (QueryComplete $e) {
        // Set flag to true when the query is fully completed
        $query_complete = true;
        break;
      } catch (\Exception $e) {
        // rethrow exception
        throw new \Exception($e);
      }
    }
  }

  /**
   * Read the specified number of bytes from the socket.
   *
   * This method:
   *  - Continuously reads from the TCP socket until the requested buffer size is received.
   *  - Handles partial reads by looping until the entire data chunk is obtained.
   *  - Throws a ProtocolError if the socket read fails.
   *
   * @param int $buffer_size The total number of bytes to read from the socket.
   * @return string The full response data read from the socket.
   * @throws ProtocolError If socket read fails.
   */
  public function receive_all($buffer_size)
  {
    // Initialize the response buffer
    $response = '';

    // Keep track of remaining bytes to read
    $temp_buffer_size = $buffer_size;

    // Loop until we have read the full buffer
    while ($temp_buffer_size > 0) {
      // Read a chunk of data from the socket
      $data = socket_read($this->client, $temp_buffer_size, PHP_BINARY_READ);

      // Check if the read failed or returned empty data
      if ($data === false || $data === '') {
        throw new ProtocolError("Failed to read data from ZTeraDB server socket.\n" .
          "Socket Error: " . socket_strerror(socket_last_error($this->client)) . "\n" .
          "This usually occurs if the connection was closed or interrupted. " .
          "Check server availability and network connectivity.");
      }

      // Append the received chunk to the response
      $response .= $data;

      // Update remaining bytes to read
      $temp_buffer_size = $buffer_size - strlen($response);
    }

    // Return the complete response once fully read
    return $response;
  }

  /**
   * Parse the JSON response received from the ZTeraDB server.
   *
   * This method:
   *  - Decodes the JSON string into a PHP object.
   *  - Validates the JSON and throws an exception if it's invalid.
   *  - Checks if the response indicates query completion and throws QueryComplete.
   *
   * @param string $data The raw JSON string received from the server.
   * @return object The decoded PHP object representing the server response.
   * @throws JSONParseError If the JSON is invalid.
   * @throws QueryComplete If the response indicates that the query is complete.
   */
  public function parseResponseData($data)
  {
    // Decode the JSON response into a PHP object
    $response_data = json_decode($data);

    // Check for JSON decoding errors
    if (json_last_error() !== JSON_ERROR_NONE) {
      throw new JSONParseError("Failed to parse JSON response from ZTeraDB server.\n" .
        "JSON Error: " . json_last_error_msg() . "\n" .
        "Raw Response (truncated to 500 chars): " . substr($data, 0, 500));
    }

    // Check if the response indicates the query is complete
    if ($response_data->response_code == ResponseTypes::$QUERY_COMPLETE) {
      throw new QueryComplete("Query has completed successfully.");
    }

    // Return the parsed response object
    return $response_data;
  }

  /**
   * Close the active socket connection to the ZTeraDB server.
   *
   * This method:
   *  - Properly closes the TCP socket to release system resources.
   *  - Should be called when the connection is no longer needed.
   *
   * @return void
   */
  public function close_connection()
  {
    // Close the TCP socket associated with this client
    socket_close($this->client);
  }
}
?>