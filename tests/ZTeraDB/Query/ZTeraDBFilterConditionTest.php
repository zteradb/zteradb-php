<?php

use PHPUnit\Framework\TestCase;
use ZTeraDB\Query\ZTeraDBFilterCondition;
use ZTeraDB\Query\ZTeraDBFilterTypes;
use ZTeraDB\Exceptions\ValueError;

class ZTeraDBFilterConditionTest extends TestCase
{
    public function testIsValidValue()
    {
        $filter = new ZTeraDBFilterCondition();

        $this->assertTrue($filter->is_valid_value(123));
        $this->assertTrue($filter->is_valid_value('abc'));
        $this->assertFalse($filter->is_valid_value([]));
        $this->assertFalse($filter->is_valid_value(function(){}));
    }

    public function testSetAddFilterWithValidValues()
    {
        $filter = new ZTeraDBFilterCondition();
        $filter->set_add_filter([1, 2, 3]);
        $filters = $filter->get_fields();

        $this->assertCount(1, $filters);
        $this->assertEquals(ZTeraDBFilterTypes::ZTADD, $filters[0]['operator']);
        $this->assertEquals([1, 2, 3], $filters[0]['operand']);
    }

    public function testSetAddFilterWithInvalidValueThrows()
    {
        $this->expectException(ValueError::class);
        $filter = new ZTeraDBFilterCondition();
        $filter->set_add_filter([[]]); // array inside is invalid
    }

    public function testSetSubFilterWithValidValues()
    {
        $filter = new ZTeraDBFilterCondition();
        $filter->set_sub_filter([5, 2]);
        $filters = $filter->get_fields();

        $this->assertEquals(ZTeraDBFilterTypes::ZTSUB, $filters[0]['operator']);
        $this->assertEquals([5, 2], $filters[0]['operand']);
    }

    public function testSetDivFilterWithZeroDivisorThrows()
    {
        $this->expectException(ValueError::class);
        $filter = new ZTeraDBFilterCondition();
        $filter->set_div_filter(10, 0);
    }

    public function testSetDivFilterWithValidValues()
    {
        $filter = new ZTeraDBFilterCondition();
        $filter->set_div_filter(10, 2);
        $filters = $filter->get_fields();

        $this->assertEquals(ZTeraDBFilterTypes::ZTDIV, $filters[0]['operator']);
        $this->assertEquals([10, 2], $filters[0]['operand']);
    }

    public function testSetEqualFilterWorks()
    {
        $filter = new ZTeraDBFilterCondition();
        $filter->set_equal_filter('field', 10);
        $filters = $filter->get_fields();

        $this->assertEquals(ZTeraDBFilterTypes::ZTEQUAL, $filters[0]['operator']);
        $this->assertEquals('field', $filters[0]['operand']);
        $this->assertEquals(10, $filters[0]['result']);
    }

    public function testSetInFilterThrowsOnInvalidField()
    {
        $this->expectException(ValueError::class);
        $filter = new ZTeraDBFilterCondition();
        $filter->set_in_filter('', [1,2]);
    }

    public function testSetInFilterWorks()
    {
        $filter = new ZTeraDBFilterCondition();
        $filter->set_in_filter('field', [1,2,3]);
        $filters = $filter->get_fields();

        $this->assertEquals(ZTeraDBFilterTypes::ZTIN, $filters[0]['operator']);
        $this->assertEquals('field', $filters[0]['operand']);
        $this->assertEquals([1,2,3], $filters[0]['result']);
    }

    public function testSetOrFilterWithNestedFilters()
    {
        $child1 = (new ZTeraDBFilterCondition())->set_add_filter([1]);
        $child2 = (new ZTeraDBFilterCondition())->set_sub_filter([2]);

        $filter = new ZTeraDBFilterCondition();
        $filter->set_or_filter([$child1, $child2]);

        $filters = $filter->get_fields();
        $this->assertEquals(ZTeraDBFilterTypes::ZTOR, $filters[0]['operator']);
        $this->assertCount(2, $filters[0]['operand']);
    }

    public function testSetGreaterThanFilterThrowsOnInvalidParams()
    {
        $this->expectException(ValueError::class);
        $filter = new ZTeraDBFilterCondition();
        $filter->set_greater_than_filter([5]); // less than 2 elements
    }

    public function testChainingFilters()
    {
        $filter = new ZTeraDBFilterCondition();
        $filter->set_add_filter([1])
               ->set_sub_filter([2])
               ->set_mul_filter([3])
               ->set_div_filter(4, 2);

        $filters = $filter->get_fields();
        $this->assertCount(4, $filters);
    }
}
?>