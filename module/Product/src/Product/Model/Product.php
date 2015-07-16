<?php
namespace Product\Model;
 
class Product
{
    public $product_id;
    public $name;
    public $description;
    public $price;
    public $image;

 
    public function exchangeArray($data)
    {
        $this->product_id     = (isset($data['product_id'])) ? $data['product_id'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->description  = (isset($data['description'])) ? $data['description'] : null;
        $this->price  = (isset($data['price'])) ? $data['price'] : null;
        $this->image  = (isset($data['image'])) ? $data['image'] : null;
    }