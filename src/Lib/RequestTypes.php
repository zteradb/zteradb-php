<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB Request Types Module
 * --------------------------------------------------------------------------
 *
 *  This module defines various constants representing the request types 
 *  used in communication between the ZTeraDB client and server. Each request
 *  type corresponds to a specific action or operation that can be requested 
 *  from the ZTeraDB system.
 *
 *  Key Responsibilities:
 *  ----------------------
 *  - Defining request types that identify different operations like connect, 
 *    disconnect, query, schema creation, user access, etc.
 *  - Providing unique hexadecimal values for each request type that can be 
 *    used to specify operations when interacting with the ZTeraDB server.
 *  - Ensuring clear and structured communication by using well-defined request types.
 *
 *  Requirements:
 *  -------------
 *  - These request types should be used when sending requests to the ZTeraDB server 
 *    to specify which operation or action is being requested.
 *
 *  The constants are used throughout the ZTeraDB client to define the type of 
 *  request being made and to communicate the intended action to the server.
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
 * RequestTypes: Defines the available request types for communication with ZTeraDB server.
 * 
 * This class contains constants representing various types of requests that the ZTeraDB
 * client can make to the ZTeraDB server. Each constant corresponds to a specific operation 
 * and is identified by a unique hexadecimal value. These request types are essential for 
 * correctly identifying the nature of the request when interacting with the server.
 * 
 * @package ZTeraDB\Lib
 */
class RequestTypes
{
  /**
   * @var int $CONNECT Request type for establishing a connection with the server.
   *                   This request is used when the client needs to connect to the ZTeraDB server.
   */
  public static $CONNECT = 0x001;

  /**
   * @var int $DISCONNECT Request type for disconnecting from the server.
   *                      This request is used when the client needs to disconnect from the server.
   */
  public static $DISCONNECT = 0x003;

  /**
   * @var int $QUERY Request type for executing a query on the server.
   *                  This request is used when the client needs to run a database query.
   */
  public static $QUERY = 0x005;

  /**
   * @var int $PING Request type for pinging the server to check its status.
   *                This request is used to verify the server is alive and responsive.
   */
  public static $PING = 0x007;

  /**
   * @var int $CREATE_SCHEMA Request type for creating a new schema in the database.
   *                          This request is used when the client needs to define a new database schema.
   */
  public static $CREATE_SCHEMA = 0x008;

  /**
   * @var int $PUBLISH_SCHEMA Request type for publishing a schema.
   *                           This request is used to make a schema available for use in the ZTeraDB system.
   */
  public static $PUBLISH_SCHEMA = 0x009;

  /**
   * @var int $DATABASE Request type for database-related operations.
   *                    This request is used when interacting with databases in the ZTeraDB system.
   */
  public static $DATABASE = 0x010;

  /**
   * @var int $ACTIVE_DATABASE Request type for fetching or modifying the active database.
   *                           This request is used to retrieve or set the currently active database in the system.
   */
  public static $ACTIVE_DATABASE = 0x011;

  /**
   * @var int $SCHEMA Request type for operations related to database schemas.
   *                  This request is used when working with schemas in the database.
   */
  public static $SCHEMA = 0x012;

  /**
   * @var int $SCHEMA_FIELDS Request type for operations involving schema fields.
   *                          This request is used to manipulate fields within a schema.
   */
  public static $SCHEMA_FIELDS = 0x013;

  /**
   * @var int $SCHEMA_RELATED Request type for operations involving related schema entities.
   *                           This request is used when working with entities related to schemas.
   */
  public static $SCHEMA_RELATED = 0x014;

  /**
   * @var int $SCHEMA_ACCESS Request type for managing access control on schemas.
   *                          This request is used to control who has access to specific schemas.
   */
  public static $SCHEMA_ACCESS = 0x015;

  /**
   * @var int $DATABASE_ACCESS Request type for managing access control on databases.
   *                            This request is used to manage database-level access permissions.
   */
  public static $DATABASE_ACCESS = 0x016;

  /**
   * @var int $ENTERPRISE_USER Request type for enterprise-level user operations.
   *                            This request is used to manage users at the enterprise level.
   */
  public static $ENTERPRISE_USER = 0x017;

  /**
   * @var int $ROLE Request type for managing user roles in the system.
   *                This request is used to assign or modify roles within the ZTeraDB system.
   */
  public static $ROLE = 0x018;

  /**
   * @var int $ACCESS_CONTROL Request type for managing access control across the system.
   *                          This request is used to handle permissions and access policies.
   */
  public static $ACCESS_CONTROL = 0x019;

  /**
   * @var int $ZTERADB_INSTANCE Request type for operations on ZTeraDB instances.
   *                            This request is used for operations related to instances of the ZTeraDB system.
   */
  public static $ZTERADB_INSTANCE = 0x020;

  /**
   * @var int $ZTERADB_ENTERPRISE_INSTANCE Request type for enterprise-level ZTeraDB instance operations.
   *                                         This request is used for operations on enterprise-level ZTeraDB instances.
   */
  public static $ZTERADB_ENTERPRISE_INSTANCE = 0x021;

  /**
   * @var int $ZTERADB_ENTERPRISE_INSTANCE_GROUP Request type for managing enterprise instance groups.
   *                                              This request is used to group enterprise instances for easier management.
   */
  public static $ZTERADB_ENTERPRISE_INSTANCE_GROUP = 0x022;

  /**
   * @var int $ENTERPRISE_INSTANCE Request type for managing enterprise instances.
   *                               This request is used to perform actions on enterprise instances.
   */
  public static $ENTERPRISE_INSTANCE = 0x023;

  /**
   * @var int $CREDENTIALS Request type for managing user credentials.
   *                       This request is used when modifying or retrieving user credentials.
   */
  public static $CREDENTIALS = 0x024;

  /**
   * @var int $USER_PROFILE Request type for managing user profile details.
   *                         This request is used to modify or fetch information related to a user's profile.
   */
  public static $USER_PROFILE = 0X025;

  /**
   * @var string $NONE Represents no request type or an undefined request.
   *                   This is used when there is no specific request type.
   */
  public static $NONE = "";
}
?>