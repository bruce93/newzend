<?php
namespace Product\Model;
 
use Zend\Db\TableGateway\TableGateway;
 
class ProductTable
{
    protected $tableGateway;
 
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
 
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
 
    public function getProduct($product_id)
    {
        $product_id  = (int) $product_id;
        $rowset = $this->tableGateway->select(array('product_id' => $product_id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $product_id");
        }
        return $row;
    }
 
    public function saveProduct(Product $product)
    {
        $data = array(
            'name' => $product->name,
            'description'  => $product->description,
            'price'  => $product->price,
            'image'  => $product->image,
        );
 
        $product_id = (int)$product->product_id;
        if ($product_id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getProduct($product_id)) {
                $this->tableGateway->update($data, array('product_id' => $product_id));
            } else {
                throw new \Exception('Form product_id does not exist');
            }
        }
    }
 
    public function deleteProduct($product_id)
    {
        $this->tableGateway->delete(array('product_id' => $product_id));
    }
}