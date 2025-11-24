<?php

use PHPUnit\Framework\TestCase;
use function ZTeraDB\Query\ZTAND;
use function ZTeraDB\Query\ZTOR;
use function ZTeraDB\Query\ZTEQUAL;
use function ZTeraDB\Query\ZTIN;
use function ZTeraDB\Query\ZTADD;
use function ZTeraDB\Query\ZTSUB;
use function ZTeraDB\Query\ZTDIV;
use function ZTeraDB\Query\ZTMUL;
use function ZTeraDB\Query\ZTMOD;
use function ZTeraDB\Query\ZTGT;
use function ZTeraDB\Query\ZTGTE;
use function ZTeraDB\Query\ZTLT;
use function ZTeraDB\Query\ZTLTE;
use function ZTeraDB\Query\ZTSTARTSWITH;
use function ZTeraDB\Query\ZTISTARTSWITH;
use function ZTeraDB\Query\ZTENDSWITH;
use function ZTeraDB\Query\ZTIENDSWITH;
use function ZTeraDB\Query\ZTCONTAINS;
use function ZTeraDB\Query\ZTICONTAINS;
use ZTeraDB\Query\ZTeraDBFilterCondition;
// require_once __DIR__ . '/../../../src/Query/ZTeraDBFilterFunctions.php';


class ZTeraDBFunctionsTest extends TestCase
{
    public function testZTAND()
    {
        $filters = ['a', 'b'];
        $result = ZTAND($filters);
        $output = [[
            "operator"=>"&&",
            "operand"=>$filters
        ]];

        $this->assertInstanceOf(ZTeraDBFilterCondition::class, $result);
        $this->assertEquals($output, $result->get_fields());
    }

    public function testZTOR()
    {
        $filters = ['x', 'y'];
        $result = ZTOR($filters);
        $output = [[
            "operator"=>"||",
            "operand"=>$filters
        ]];

        $this->assertInstanceOf(ZTeraDBFilterCondition::class, $result);
        $this->assertEquals($output, $result->get_fields());
    }

    public function testZTEQUAL()
    {
        $param = 'field1';
        $value = 100;
        $result = ZTEQUAL($param, $value);
        $output = [[
            "operator"=>"=",
            "operand"=>$param,
            "result"=>$value
        ]];

        $this->assertInstanceOf(ZTeraDBFilterCondition::class, $result);
        $this->assertEquals($output, $result->get_fields());
    }

    public function testZTIN()
    {
        $key = 'status';
        $values = [1, 2, 3];
        $result = ZTIN($key, $values);
        $output = [[
            "operator"=>"IN",
            "operand"=>$key,
            "result"=>$values
        ]];

        $this->assertInstanceOf(ZTeraDBFilterCondition::class, $result);
        $this->assertEquals($output, $result->get_fields());
    }

    public function testZTADD()
    {
        $values = [1, 2, 3];
        $result = ZTADD($values);
        $output = [[
            "operator"=>"+",
            "operand"=>$values,
        ]];

        $this->assertInstanceOf(ZTeraDBFilterCondition::class, $result);
        $this->assertEquals($output, $result->get_fields());
    }

    public function testZTSUB()
    {
        $values = [5, 2];
        $result = ZTSUB($values);
        $output = [[
            "operator"=>"-",
            "operand"=>$values,
        ]];

        $this->assertInstanceOf(ZTeraDBFilterCondition::class, $result);
        $this->assertEquals($output, $result->get_fields());
    }

    public function testZTMUL()
    {
        $values = [2, 3];
        $result = ZTMUL($values);
        $output = [[
            "operator"=>"*",
            "operand"=>$values,
        ]];

        $this->assertInstanceOf(ZTeraDBFilterCondition::class, $result);
        $this->assertEquals($output, $result->get_fields());
    }

    public function testZTDIV()
    {
        $dividend = 10;
        $divisor = 2;
        $result = ZTDIV($dividend, $divisor);
        $output = [[
            "operator"=>"/",
            "operand"=>[$dividend, $divisor],
        ]];

        $this->assertInstanceOf(ZTeraDBFilterCondition::class, $result);
        $this->assertEquals($output, $result->get_fields());
    }

    public function testZTMOD()
    {
        $numerator = 10;
        $denominator = 3;
        $result = ZTMOD($numerator, $denominator);
        $output = [[
            "operator"=>"%",
            "operand"=>[$numerator, $denominator],
        ]];

        $this->assertInstanceOf(ZTeraDBFilterCondition::class, $result);
        $this->assertEquals($output, $result->get_fields());
    }

    public function testZTGT()
    {
        $params = [5, 3];
        $result = ZTGT($params);
        $output = [[
            "operator"=>">",
            "operand"=>$params,
        ]];

        $this->assertInstanceOf(ZTeraDBFilterCondition::class, $result);
        $this->assertEquals($output, $result->get_fields());
    }

    public function testZTGTE()
    {
        $params = [5, 3];
        $result = ZTGTE($params);
        $output = [[
            "operator"=>">=",
            "operand"=>$params,
        ]];

        $this->assertInstanceOf(ZTeraDBFilterCondition::class, $result);
        $this->assertEquals($output, $result->get_fields());
    }

    public function testZTLT()
    {
        $params = [2, 5];
        $result = ZTLT($params);
        $output = [[
            "operator"=>"<",
            "operand"=>$params,
        ]];

        $this->assertInstanceOf(ZTeraDBFilterCondition::class, $result);
        $this->assertEquals($output, $result->get_fields());
    }

    public function testZTLTE()
    {
        $params = [2, 5];
        $result = ZTLTE($params);
        $output = [[
            "operator"=>"<=",
            "operand"=>$params,
        ]];

        $this->assertInstanceOf(ZTeraDBFilterCondition::class, $result);
        $this->assertEquals($output, $result->get_fields());
    }

    public function testZTCONTAINS()
    {
        $field = 'name';
        $value = 'John';
        $result = ZTCONTAINS($field, $value);
        $output = [[
            "operator"=>"%%",
            "operand"=>$field,
            "result"=>$value
        ]];

        $this->assertInstanceOf(ZTeraDBFilterCondition::class, $result);
        $this->assertEquals($output, $result->get_fields());
    }

    public function testZTICONTAINS()
    {
        $field = 'name';
        $value = 'john';
        $result = ZTICONTAINS($field, $value);
        $output = [[
            "operator"=>"i%%",
            "operand"=>$field,
            "result"=>$value
        ]];

        $this->assertInstanceOf(ZTeraDBFilterCondition::class, $result);
        $this->assertEquals($output, $result->get_fields());
    }

    public function testZTSTARTSWITH()
    {
        $field = 'name';
        $value = 'J';
        $result = ZTSTARTSWITH($field, $value);
        $output = [[
            "operator"=>"^%%",
            "operand"=>$field,
            "result"=>$value
        ]];

        $this->assertInstanceOf(ZTeraDBFilterCondition::class, $result);
        $this->assertEquals($output, $result->get_fields());
    }

    public function testZTISTARTSWITH()
    {
        $field = 'name';
        $value = 'j';
        $result = ZTISTARTSWITH($field, $value);
        $output = [[
            "operator"=>"^i%%",
            "operand"=>$field,
            "result"=>$value
        ]];

        $this->assertInstanceOf(ZTeraDBFilterCondition::class, $result);
        $this->assertEquals($output, $result->get_fields());
    }

    public function testZTENDSWITH()
    {
        $field = 'name';
        $value = 'n';
        $result = ZTENDSWITH($field, $value);
        $output = [[
            "operator"=>"%%$",
            "operand"=>$field,
            "result"=>$value
        ]];

        $this->assertInstanceOf(ZTeraDBFilterCondition::class, $result);
        $this->assertEquals($output, $result->get_fields());
    }

    public function testZTIENDSWITH()
    {
        $field = 'name';
        $value = 'n';
        $result = ZTIENDSWITH($field, $value);
        $output = [[
            "operator"=>"i%%$",
            "operand"=>$field,
            "result"=>$value
        ]];

        $this->assertInstanceOf(ZTeraDBFilterCondition::class, $result);
        $this->assertEquals($output, $result->get_fields());
    }
}
?>