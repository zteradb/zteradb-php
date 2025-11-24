<?php

use PHPUnit\Framework\TestCase;
use ZTeraDB\Query\ZTeraDBQueryType;
use ZTeraDB\Exceptions\ValueError;

class ZTeraDBQueryTypeTest extends TestCase {

    // Test that the constructor accepts valid query types.
    public function testConstructorValidQueryTypes() {
        // Test for INSERT query type
        $queryTypeInsert = new ZTeraDBQueryType(ZTeraDBQueryType::INSERT);
        $this->assertEquals(ZTeraDBQueryType::INSERT, $queryTypeInsert->query_type);

        // Test for SELECT query type
        $queryTypeSelect = new ZTeraDBQueryType(ZTeraDBQueryType::SELECT);
        $this->assertEquals(ZTeraDBQueryType::SELECT, $queryTypeSelect->query_type);

        // Test for UPDATE query type
        $queryTypeUpdate = new ZTeraDBQueryType(ZTeraDBQueryType::UPDATE);
        $this->assertEquals(ZTeraDBQueryType::UPDATE, $queryTypeUpdate->query_type);

        // Test for DELETE query type
        $queryTypeDelete = new ZTeraDBQueryType(ZTeraDBQueryType::DELETE);
        $this->assertEquals(ZTeraDBQueryType::DELETE, $queryTypeDelete->query_type);

        // Test for NONE query type
        $queryTypeNone = new ZTeraDBQueryType(ZTeraDBQueryType::NONE);
        $this->assertEquals(ZTeraDBQueryType::NONE, $queryTypeNone->query_type);
    }

    // Test that the constructor throws an exception for invalid query types.
    public function testConstructorInvalidQueryType() {
        $this->expectException(ValueError::class);
        $this->expectExceptionMessage("Invalid query_type");

        // Test with an invalid query type
        new ZTeraDBQueryType(99);
    }

    // Test that the default query type is NONE when the constructor is called with no arguments
    public function testDefaultQueryType() {
        $queryType = new ZTeraDBQueryType(ZTeraDBQueryType::NONE);
        $this->assertEquals(ZTeraDBQueryType::NONE, $queryType->query_type);
    }
}
?>
