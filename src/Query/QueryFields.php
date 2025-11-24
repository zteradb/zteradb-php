<?php
/**
 * --------------------------------------------------------------------------
 *  ZTeraDB Query Fields Module
 * --------------------------------------------------------------------------
 *
 *  This module defines the set of standard query fields used within the ZTeraDB
 *  query operations. These fields represent the metadata and configuration
 *  options that are commonly used when interacting with the ZTeraDB server.
 *
 *  Key Responsibilities:
 *  ----------------------
 *  - Defining a list of fields that are commonly used in ZTeraDB queries.
 *  - Providing a central place to manage and refer to query field names.
 * 
 *  These fields are used in various query types such as SELECT, INSERT, and 
 *  UPDATE to filter, sort, and manage data within the ZTeraDB database.
 *
 *  Requirements:
 *  -------------
 *  - This module is typically used in conjunction with query-building and 
 *    execution classes in the ZTeraDB system.
 *
 * --------------------------------------------------------------------------
 *  @package     ZTeraDB
 *  @version     1.0
 *  @author      ZTeraDB
 *  @license     https://zteradb.com/licence (SPDX-License-Identifier: Proprietary)
 * --------------------------------------------------------------------------
 */
namespace ZTeraDB\Query;

/**
 * Class QueryFields
 * 
 * This class defines the set of standard fields used in ZTeraDB queries. 
 * These fields are part of the query structure and are used to specify various 
 * properties such as schema, database, filters, sorting, and more.
 * 
 * The class is designed to centralize the definitions of common query fields, 
 * ensuring consistency and reducing the chances of errors or duplication in 
 * query definitions across the application.
 */
class QueryFields {
  // Define an array of standard fields commonly used in queries.
  const fields = [
        "__schema_name",        // Schema name for the query
        "__database_id",        // Database identifier for the query
        "__query_type",         // Type of query (SELECT, INSERT, etc.)
        "__fields",             // List of fields to retrieve or manipulate
        "__filters",            // Filters to apply to the query
        "__filter_conditions",  // Conditions for applying filters
        "__limit",              // Limit the number of results
        "__sort",               // Sort order for the results
        "__related_fields",     // Related fields in the query
        "__count",              // Count query results
        "__env",                // Environment information for the query
  ];
}

?>