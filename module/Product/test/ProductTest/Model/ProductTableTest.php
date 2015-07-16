<?php
// module/Product/test/ProductTest/Model/ProductTableTest.php:
namespace Product\Model;

use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;

class ProductableTest extends PHPUnit_Framework_TestCase
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
    public function testCanRetrieveAnProductByItsId()
    {
        $product = new Product();
        $product->exchangeArray(array('id'     => 123,
                                    'product_name' => 'The Military Wives',
                                    'description'  => 'In My Dreams'
                                    'price' => '22',
                                    'image' => 'bbb',));

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Product());
        $resultSet->initialize(array($product));

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('select'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with(array('id' => 123))
                         ->will($this->returnValue($resultSet));

        $productTable = new ProductTable($mockTableGateway);

        $this->assertSame($product, $productTable->getProduct(123));
    }

    public function testCanDeleteAnProductByItsId()
    {
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('delete'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('delete')
                         ->with(array('id' => 123));

        $productTable = new ProductTable($mockTableGateway);
        $productTable->deleteProduct(123);
    }

    public function testSaveProductWillInsertNewProductsIfTheyDontAlreadyHaveAnId()
    {
        $productData = array('product_name' => 'The Military Wives', 'description' => 'In My Dreams', 'price' => '22', 'image' => 'bbb');
        $product     = new Product();
        $product->exchangeArray($productData);

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('insert'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('insert')
                         ->with($productData);

        $productTable = new ProductTable($mockTableGateway);
        $productTable->saveProduct($product);
    }

    public function testSaveProductWillUpdateExistingProductsIfTheyAlreadyHaveAnId()
    {
        $productData = array('id' => 123, 'product_name' => 'The Military Wives', 'description' => 'In My Dreams', 'price' => '22', 'image' => 'bbb');
        $product     = new Product();
        $product->exchangeArray($productData);

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Product());
        $resultSet->initialize(array($product));

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway',
                                           array('select', 'update'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with(array('id' => 123))
                         ->will($this->returnValue($resultSet));
        $mockTableGateway->expects($this->once())
                         ->method('update')
                         ->with(array('product_name' => 'The Military Wives', 'description' => 'In My Dreams', 'price' => '22', 'image' => 'bbb'),
                                array('id' => 123));

        $productTable = new ProductTable($mockTableGateway);
        $productTable->saveProduct($product);
    }

    public function testExceptionIsThrownWhenGettingNonexistentProduct()
    {
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Product());
        $resultSet->initialize(array());

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('select'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with(array('id' => 123))
                         ->will($this->returnValue($resultSet));

        $productTable = new ProductTable($mockTableGateway);

        try
        {
            $productTable->getProduct(123);
        }
        catch (\Exception $e)
        {
            $this->assertSame('Could not find row 123', $e->getMessage());
            return;
        }

        $this->fail('Expected exception was not thrown');
    }
}