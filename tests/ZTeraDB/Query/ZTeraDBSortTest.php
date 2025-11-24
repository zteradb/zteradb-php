<?php
use PHPUnit\Framework\TestCase;
use ZTeraDB\Query\ZTeraDBSort;
use ZTeraDB\Exceptions\ValueError;

class ZTeraDBSortTest extends TestCase {
  public function testInvalidSortFieldInteger() {
    $this->expectException(ValueError::class);
    $this->expectExceptionMessage("Sort field must be string");
    $sort = new ZTeraDBSort(1, 1);
  }

  public function testInvalidSortFieldNegativeInteger() {
    $this->expectException(ValueError::class);
    $this->expectExceptionMessage("Sort field must be string");
    $sort = new ZTeraDBSort(-1, 1);
  }

  public function testInvalidSortFieldFloat() {
    $this->expectException(ValueError::class);
    $this->expectExceptionMessage("Sort field must be string");
    $sort = new ZTeraDBSort(1.1, 1);
  }

  public function testInvalidSortFieldBinary() {
    $this->expectException(ValueError::class);
    $this->expectExceptionMessage("Sort field must be string");
    $sort = new ZTeraDBSort(bindec('1101'), 1);
  }

  public function testInvalidSortValueString() {
    $this->expectException(ValueError::class);
    $this->expectExceptionMessage("Sort order must be integer");
    $sort = new ZTeraDBSort("field1", "1");
  }

  public function testInvalidSortValueFloat() {
    $this->expectException(ValueError::class);
    $this->expectExceptionMessage("Sort order must be integer");
    $sort = new ZTeraDBSort("field2", 1.1);
  }

  public function testValidField() {
    $sort = new ZTeraDBSort("field2", 1);
    $this->assertTrue($sort instanceof ZTeraDBSort, "Sort must work with valid field and order");

    $sort = new ZTeraDBSort("field2", -1);
    $this->assertTrue($sort instanceof ZTeraDBSort, "Sort must work with valid field and negative order");

    $sort = new ZTeraDBSort("field2", ZTeraDBSort::ASC);
    $this->assertTrue($sort instanceof ZTeraDBSort, "Sort must work with valid field and order");

    $sort = new ZTeraDBSort("field2", ZTeraDBSort::DESC);
    $this->assertTrue($sort instanceof ZTeraDBSort, "Sort must work with valid field and negative order");
  }
}
?>