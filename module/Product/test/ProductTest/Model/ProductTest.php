<?php
namespace Product\TestModel;
 
use Product\Model\Product;
use PHPUnit_Framework_TestCase;
 
class ProductTest extends PHPUnit_Framework_TestCase
{
    public function testProductInitialState()
    {
        $product = new Product();
 
        $this->assertNull($product->name, '"name" should initially be null');
        $this->assertNull($product->product_id, '"product_id" should initially be null');
        $this->assertNull($product->description, '"description" should initially be null');
        $this->assertNull($product->price, '"price" should initially be null');
        $this->assertNull($product->image, '"image" should initially be null');
    }
 
    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $product = new Product();
        $data  = array('name' => 'some name',
                       'product_id'     => 123,
                       'description'  => 'some description'
                       'price'  => 'some price'
                       'image'  => 'some image');
 
        $product->exchangeArray($data);
 
        $this->assertSame($data['name'], $product->name, '"name" was not set correctly');
        $this->assertSame($data['product_id'], $product->product_id, '"product_id" was not set correctly');
        $this->assertSame($data['description'], $product->description, '"description" was not set correctly');
        $this->assertSame($data['price'], $product->price, '"price" was not set correctly');
        $this->assertSame($data['image'], $product->image, '"image" was not set correctly');
    }
 
    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $product = new Product();
 
        $product->exchangeArray(array('name' => 'some name',
                       'product_id'     => 123,
                       'description'  => 'some description'
                       'price'  => 'some price'
                       'image'  => 'some image'));
        $product->exchangeArray(array());
 
        $this->assertNull($product->name, '"name" should have defaulted to null');
        $this->assertNull($product->product_id, '"product_id" should have defaulted to null');
        $this->assertNull($product->description, '"description" should have defaulted to null');
        $this->assertNull($product->price, '"price" should have defaulted to null');
        $this->assertNull($product->image, '"image" should have defaulted to null');
    }
}