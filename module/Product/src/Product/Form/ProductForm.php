<?php
// module/Product/src/Product/Form/ProductForm.php:
namespace Product\Form;
use Zend\Form\Form;
use Zend\InputFilter;
use Zend\Form\Element;
class ProductForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('product');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'product_name',
            'attributes' => array(
                'type'  => 'text',
                'style' => 'margin-left:2px; margin-top: 2px; margin-bottom: 2px',
                'size' => '19px',
            ),
            'options' => array(
                'label' => 'Название ',
            ),
        ));
        $textarea = new Element\Textarea('description');
        $textarea->setLabel('Описание ');
        $this->add($textarea);
        $this->add(array(
            'name' => 'price',
            'attributes' => array(
                'type'  => 'text',
                'style' => 'margin-left:35px; margin-top: 2px; margin-bottom: 2px',
                'size' => '19px',
            ),
            'options' => array(
                'label' => 'Цена ',
            ),
        ));
        $this->add(array(
            'name' => 'image',
            'attributes' => array(
                'type'  => 'file',
            ),
            'options' => array(
                'label' => 'Изображение ',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
                'style' => 'margin-top: 2px'
            ),
        ));
    }
}

