<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB Data Manager - Data Packing and Unpacking Utility
 * --------------------------------------------------------------------------
 *
 *  This class is used to handle the packing and unpacking of data for
 *  communication with the ZTeraDB server. It ensures that data sent to 
 *  the server is properly formatted, and that received data is correctly parsed.
 *
 *  Key Responsibilities:
 *  -----------------------
 *  - Pack data into a format suitable for transmission over the network (with data length).
 *  - Unpack received data based on the expected structure.
 *  - Support communication with the ZTeraDB server by properly formatting and reading data.
 *
 *  Usage:
 *  -------
 *  - `pack($data)` is used to prepare the data for transmission by adding its length.
 *  - `unpack($data, $start, $end)` is used to extract a specific part of the data according to 
 *    the defined structure.
 *
 *  Requirements:
 *  -------------
 *  - The packed data format uses the PHP `pack()` and `unpack()` functions with a 
 *    specified format (`N` for unsigned 32-bit integer).
 *
 * --------------------------------------------------------------------------
 *  @package     ZTeraDB
 *  @version     1.0
 *  @author      ZTera
 *  @license     https://zteradb.com/licence   (SPDX-License-Identifier: Proprietary)
 * --------------------------------------------------------------------------
 */

namespace ZTeraDB\Connection;

/**
 * Class ZTeraDBDataManager
 * 
 * Responsible for packing and unpacking data used in communication with the ZTeraDB server.
 * Ensures that data is correctly formatted for transmission and correctly parsed upon reception.
 * 
 * @package ZTeraDB\Connection
 */
class ZTeraDBDataManager
{
  // Format specifier used by unpack/pack methods. 'N' represents a 32-bit unsigned integer.
  private static $struct_fmt = "N";

  // The default size of the buffer (4 bytes).
  public static $buffer_size = 4;

  /**
   * Unpacks the provided binary data into an associative array.
   * 
   * This method extracts a specific part of the data (based on the `$start` and `$end` positions)
   * and unpacks it into a human-readable format using the defined structure (`N`).
   *
   * @param string $data The binary data to unpack.
   * @param int $start The starting position in the data to begin unpacking.
   * @param int $end The number of bytes to unpack.
   * 
   * @return array Returns the unpacked data as an associative array.
   * 
   * @throws Exception If unpacking fails.
   */
  public static function unpack($data, $start, $end)
  {
    // Extract a portion of the data and unpack it using the structure format 'N' (unsigned 32-bit integer).
    return unpack(self::$struct_fmt, substr($data, $start, $end));
  }

  /**
   * Packs the given data into a specific format (length + data).
   * 
   * This method prepends the length of the data (in bytes) to the actual data.
   * The packed format ensures that the data is ready for transmission, 
   * and the receiver knows the data length before processing.
   *
   * @param string $data The data to be packed.
   * 
   * @return string Returns the packed data: [length of data][actual data].
   * 
   * @throws Exception If packing fails.
   */
  public static function pack($data)
  {
    // Pack the data by first adding its length, followed by the actual data.
    return pack(self::$struct_fmt, strlen($data)) . $data;
  }
}
?>