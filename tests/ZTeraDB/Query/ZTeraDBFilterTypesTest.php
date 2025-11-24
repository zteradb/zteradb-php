<?php

use PHPUnit\Framework\TestCase;
use ZTeraDB\Query\ZTeraDBFilterTypes;

class ZTeraDBFilterTypesTest extends TestCase
{
    /**
     * Test that all logical operators have the correct values
     */
    public function testLogicalOperators()
    {
        $this->assertSame('||', ZTeraDBFilterTypes::ZTOR);
        $this->assertSame('&&', ZTeraDBFilterTypes::ZTAND);
    }

    /**
     * Test that all comparison operators have the correct values
     */
    public function testComparisonOperators()
    {
        $this->assertSame('=', ZTeraDBFilterTypes::ZTEQUAL);
        $this->assertSame('!=', ZTeraDBFilterTypes::ZTNOTEQUAL);
        $this->assertSame('>', ZTeraDBFilterTypes::ZTGT);
        $this->assertSame('>=', ZTeraDBFilterTypes::ZTGTE);
        $this->assertSame('<', ZTeraDBFilterTypes::ZTLT);
        $this->assertSame('<=', ZTeraDBFilterTypes::ZTLTE);
    }

    /**
     * Test that all arithmetic operators have the correct values
     */
    public function testArithmeticOperators()
    {
        $this->assertSame('+', ZTeraDBFilterTypes::ZTADD);
        $this->assertSame('-', ZTeraDBFilterTypes::ZTSUB);
        $this->assertSame('*', ZTeraDBFilterTypes::ZTMUL);
        $this->assertSame('/', ZTeraDBFilterTypes::ZTDIV);
        $this->assertSame('%', ZTeraDBFilterTypes::ZTMOD);
    }

    /**
     * Test that all string matching operators have the correct values
     */
    public function testStringMatchingOperators()
    {
        $this->assertSame('%%', ZTeraDBFilterTypes::ZTCONTAINS);
        $this->assertSame('i%%', ZTeraDBFilterTypes::ZTICONTAINS);
        $this->assertSame('^%%', ZTeraDBFilterTypes::ZTSTARTSWITH);
        $this->assertSame('^i%%', ZTeraDBFilterTypes::ZTISTARTSWITH);
        $this->assertSame('%%$', ZTeraDBFilterTypes::ZTENDSWITH);
        $this->assertSame('i%%$', ZTeraDBFilterTypes::ZTIENDSWITH);
    }

    /**
     * Test that set operator has the correct value
     */
    public function testSetOperators()
    {
        $this->assertSame('IN', ZTeraDBFilterTypes::ZTIN);
    }
}
?>