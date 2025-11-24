<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB Response Types Module
 * --------------------------------------------------------------------------
 *
 *  This module defines various constants representing the response types 
 *  that the ZTeraDB server can return in response to client requests.
 *  Each response type is associated with a unique hexadecimal value which 
 *  represents a specific server response for a particular operation.
 *
 *  Key Responsibilities:
 *  ----------------------
 *  - Defining response types that identify the result of server operations 
 *    such as connection, disconnection, query results, and errors.
 *  - Providing standardized response codes that can be used by the client 
 *    to determine the outcome of a request sent to the ZTeraDB server.
 *  - Allowing the client to easily interpret server responses based on predefined constants.
 *
 *  Requirements:
 *  -------------
 *  - These response types are used to determine the server's reply to a request.
 *    The client should handle these responses appropriately to proceed with further actions.
 *
 *  The constants define response categories for success, error, authentication issues, 
 *  schema-related operations, and query results, among others.
 *
 * --------------------------------------------------------------------------
 *  @package     ZTeraDB\Lib
 *  @version     1.0
 *  @author      ZTeraDB
 *  @license     https://zteradb.com/licence (SPDX-License-Identifier: Proprietary)
 * --------------------------------------------------------------------------
 */
namespace ZTeraDB\Lib;

/**
 * ResponseTypes: Defines the response types used by ZTeraDB server to indicate 
 * the result of client requests.
 * 
 * This class holds constants representing different response types that the ZTeraDB
 * server may send in response to client requests. The values correspond to specific 
 * actions or errors encountered during communication with the server.
 * 
 * @package ZTeraDB\Lib
 */
class ResponseTypes
{
  /**
   * @var int $CONNECTED Response type indicating a successful connection to the server.
   *                     This response is sent when the client successfully connects to the ZTeraDB server.
   */
  public static $CONNECTED = 0x002;

  /**
   * @var int $CONNECT_ERROR Response type indicating a connection error.
   *                         This response is sent when there is an error while connecting to the server.
   */
  public static $CONNECT_ERROR = 0x500;

  /**
   * @var int $DISCONNECTED Response type indicating that the client has been disconnected.
   *                        This response is sent when the client is disconnected from the server.
   */
  public static $DISCONNECTED = 0x004;

  /**
   * @var int $DISCONNECT_ERROR Response type indicating a disconnect error.
   *                            This response is sent when there is an error while disconnecting from the server.
   */
  public static $DISCONNECT_ERROR = 0x005;

  /**
   * @var int $CLIENT_AUTH_ERROR Response type indicating an authentication error.
   *                              This response is sent when there is an error with the client's authentication.
   */
  public static $CLIENT_AUTH_ERROR = 0x006;

  /**
   * @var int $QUERY_DATA Response type indicating that query data is being returned.
   *                      This response is sent when the server returns data in response to a query.
   */
  public static $QUERY_DATA = 0x007;

  /**
   * @var int $QUERY_COMPLETE Response type indicating that the query has completed successfully.
   *                           This response is sent when the server has successfully processed a query.
   */
  public static $QUERY_COMPLETE = 0x608;

  /**
   * @var int $QUERY_ERROR Response type indicating a query error.
   *                       This response is sent when there is an error in processing a query.
   */
  public static $QUERY_ERROR = 0x09;

  /**
   * @var int $PONG Response type indicating a successful ping response.
   *                This response is sent when the server responds to a ping request (heartbeat check).
   */
  public static $PONG = 0x010;

  /**
   * @var int $PARSE_QUERY_ERROR Response type indicating an error while parsing the query.
   *                             This response is sent when there is an issue with query parsing.
   */
  public static $PARSE_QUERY_ERROR = 0x100;

  /**
   * @var int $NO_ACCESS Response type indicating no access permission.
   *                     This response is sent when the client does not have sufficient privileges.
   */
  public static $NO_ACCESS = 0x011;

  /**
   * @var int $TOKEN_EXPIRED Response type indicating that the client's authentication token has expired.
   *                          This response is sent when the token used for authentication is no longer valid.
   */
  public static $TOKEN_EXPIRED = 0x400;

  /**
   * @var int $INVALID_SCHEMA Response type indicating an invalid schema.
   *                          This response is sent when an operation involves an invalid or non-existent schema.
   */
  public static $INVALID_SCHEMA = 0x401;

  /**
   * @var int $FIELD_ERROR Response type indicating a field-related error.
   *                       This response is sent when there is an issue with a field in a query or operation.
   */
  public static $FIELD_ERROR = 0x402;

  /**
   * @var int $CREATE_SCHEMA_SUCCESS Response type indicating successful schema creation.
   *                                 This response is sent when a schema is successfully created.
   */
  public static $CREATE_SCHEMA_SUCCESS = 0x201;

  /**
   * @var int $CREATE_SCHEMA_ERROR Response type indicating an error in schema creation.
   *                               This response is sent when there is an error while creating a schema.
   */
  public static $CREATE_SCHEMA_ERROR = 0x501;

  /**
   * @var int $PUBLISH_SCHEMA_SUCCESS Response type indicating successful schema publishing.
   *                                   This response is sent when a schema is successfully published.
   */
  public static $PUBLISH_SCHEMA_SUCCESS = 0x202;

  /**
   * @var int $PUBLISH_SCHEMA_SUCCESS Response type indicating successful schema publishing.
   *                                   This response is sent when a schema is successfully published.
   */
  public static $PUBLISH_SCHEMA_ERROR = 0x502;

  /**
   * @var string $NONE Default response indicating no response or an undefined response.
   *                   This is used when there is no specific response type.
   */
  public static $NONE = '';
}
?>