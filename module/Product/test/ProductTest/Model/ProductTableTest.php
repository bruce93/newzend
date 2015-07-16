<?php
namespace ProductTestModel;
 
use Product\Model\ProductTable;
use Product\Model\Product;
use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;
 
class ProductTableTest extends PHPUnit_Framework_TestCase
{
    public function testFetchAllReturnsAllProducts()
    {
        $resultSet        = new ResultSet();
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway',
                                           array('select'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with()
                         ->will($this->returnValue($resultSet));
 
        $productTable = new ProductTable($mockTableGateway);
 
        $this->assertSame($resultSet, $productTable->fetchAll());
    }
}