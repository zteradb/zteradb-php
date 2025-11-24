<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB Server Authentication Module
 * --------------------------------------------------------------------------
 *
 *  This module is responsible for managing the server-side authentication 
 *  and access token handling for the ZTeraDB server.
 *
 *  Key Responsibilities:
 *  ----------------------
 *  - Verifying and storing client authentication data such as client key, 
 *    access key, and access token.
 *  - Checking whether the access token has expired based on a given expiration time.
 *  - Providing the necessary server token data for further authentication and requests.
 *
 *  Requirements:
 *  -------------
 *  - The authentication data provided should contain valid `client_key`, `access_key`, 
 *    `access_token`, and `access_token_expire` fields.
 *
 *  This class works with server-side authentication mechanisms, ensuring 
 *  that the server has a valid access token before processing any requests.
 *
 * --------------------------------------------------------------------------
 *  @package     ZTeraDB\Lib
 *  @version     1.0
 *  @author      ZTeraDB
 *  @license     https://zteradb.com/licence (SPDX-License-Identifier: Proprietary)
 * --------------------------------------------------------------------------
 */
namespace ZTeraDB\Lib;

// Import the ValueError exception for validation failures.
use ZTeraDB\Exceptions\ValueError;

/**
 * ZTeraDBServerAuth: Handles the server-side authentication by managing 
 * the client access credentials and validating the access token expiration.
 * 
 * This class ensures that only valid, authenticated requests are processed 
 * by the server by verifying the presence of necessary keys and tokens.
 * 
 * @package ZTeraDB\Lib
 */
class ZTeraDBServerAuth
{
  // Client key associated with the server.
  private $client_key;

  // Access key required for authenticating the client.
  private $access_key;

  // Access token for authenticating server requests.
  private $access_token;

  // The expiration time of the access token.
  private $access_token_expire;

  /**
   * ZTeraDBServerAuth constructor.
   * 
   * This constructor method initializes the server authentication object with the 
   * provided authentication data. It ensures that the required fields (client_key, 
   * access_key, access_token, and access_token_expire) are present in the input 
   * authentication data. If any of these fields are missing, a `ValueError` 
   * exception is thrown.
   * 
   * The constructor also converts the `access_token_expire` field into a `DateTime` 
   * object using the `get_access_token_expire` method for easier handling of 
   * token expiration checks.
   * 
   * @param object $auth_data The authentication response data that includes 
   *                          client_key, access_key, access_token, and 
   *                          access_token_expire.
   * 
   * @throws ValueError If any of the required fields (client_key, access_key, 
   *                    access_token, or access_token_expire) are missing in the 
   *                    provided authentication data.
   */
  public function __construct($auth_data)
  {
    // Check if the client_key is present in the authentication response
    if (!$auth_data->client_key) {
      throw new ValueError("client_key not found in auth response data.");
    }

    // Check if the access_key is present in the authentication response
    if (!$auth_data->access_key) {
      throw new ValueError("access_key not found in auth response data.");
    }

    // Check if the access_token is present in the authentication response
    if (!$auth_data->access_token) {
      throw new ValueError("access_token not found in auth response data.");
    }

    // Check if the access_token_expire is present in the authentication response
    if (!$auth_data->access_token_expire) {
      throw new ValueError("access_token_expire not found in auth response data.");
    }

    // If all required fields are present, initialize the object's properties
    $this->client_key = $auth_data->client_key;
    $this->access_key = $auth_data->access_key;
    $this->access_token = $auth_data->access_token;

    // Convert the expiration time of the access token to a DateTime object
    $this->access_token_expire = $this->get_access_token_expire($auth_data->access_token_expire);
  }

  /**
   * Checks if the server access token has expired.
   * 
   * This method compares the current UTC time with the stored access token 
   * expiration time. If the token is older than the configured threshold 
   * in minutes, it is considered expired.
   * 
   * Notes:
   * - If `$access_token_expire` is not set or invalid, the method safely returns false.
   * - The 15-minute threshold is hard-coded but could be made configurable.
   * - Any exceptions during the date comparison are caught, and false is returned 
   *   to prevent runtime errors.
   * 
   * @return bool True if the access token is expired, otherwise false.
   */
  public function is_access_token_expired()
  {
    // Return false if the expiration time is not set
    if (empty($this->access_token_expire)) {
      return false;
    }

    try {
      // Get the current UTC time
      $current_time = new \DateTime("now", new \DateTimeZone('UTC'));

      // Calculate the difference between current time and token expiration
      $interval = $current_time->diff($this->access_token_expire);

      // Token is considered expired if more than 15 minutes
      return ($interval->h > 0 || $interval->i > 15) ? true : false;
    } catch (\Exception $e) {
      return false;
    }
  }

  /**
   * Converts the provided access token expiration datetime to a UTC DateTime object.
   *
   * This method takes the access token expiration date-time string and converts it 
   * to a `DateTime` object, set to the UTC timezone. The returned `DateTime` object 
   * can then be used for comparison or further processing.
   *
   * @param string $access_token_expire_datetime The expiration datetime as a string.
   * 
   * @return \DateTime The expiration datetime as a UTC `DateTime` object.
   * 
   * @throws \Exception If the provided datetime string is invalid and cannot be parsed.
   */
  private function get_access_token_expire($access_token_expire_datetime)
  {
    // Convert the provided datetime string to a DateTime object in UTC timezone and return it.
    return new \DateTime($access_token_expire_datetime, new \DateTimeZone('UTC'));
  }

  /**
   * Retrieves the server authentication token data.
   *
   * This method returns an associative array containing the `client_key` and 
   * `access_token` which are used for authenticating the client with the server.
   * The returned data is typically used for making authenticated requests to the server.
   *
   * @return array An associative array containing `client_key` and `access_token`.
   */
  public function get_server_token_data()
  {
    // Return the authentication token data as an associative array
    return [
      "client_key" => $this->client_key,
      "access_token" => $this->access_token
    ];
  }
}
?>