<?php
namespace Product\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Product\Model\Product;
use Product\Form\ProductForm;
use Zend\Http\PhpEnvironment\Request;
use Zend\Filter;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Filter\File\RenameUpload;
use Zend\Validator\File\Size;
use Zend\Validator\File\Extension;
use Zend\Validator\File\MimeType;
use Zend\Validator;
class ProductController extends AbstractActionController
{
    protected $productTable;
    public function indexAction()
    {
        return new ViewModel(array(
            //'role' => $this->zfcUserAuthentication()->getIdentity()->getRole(),
            'products' => $this->getProductTable()->fetchAll(),
        ));
    }
    public function addAction()
    {
        $form = new ProductForm();
        $form->get('submit')->setValue('Add');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $product = new Product();
            $form->setInputFilter($product->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $product->exchangeArray($form->getData());
                $this->getProductTable()->saveProduct($product);

// Description text input
$description = new Input('description'); // Standard Input type
$description->getFilterChain()           // Filters are run first w/ Input
            ->attach(new Filter\StringTrim());
$description->getValidatorChain()        // Validators are run second w/ Input
            ->attach(new Validator\StringLength(array('max' => 140)));

// File upload input
$file = new FileInput('image');           // Special File Input type
$file->getValidatorChain()               // Validators are run first w/ FileInput
     ->attach(new Validator\File\UploadFile());
$file->getFilterChain()                  // Filters are run second w/ FileInput
     ->attach(new Filter\File\RenameUpload(array(
         'target'    => './data/tmpuploads/file',
         'randomize' => true,
     )));

// Merge $_POST and $_FILES data together
$request  = new Request();
$postData = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray());

$inputFilter = new InputFilter();
$inputFilter->add($description)
            ->add($file)
            ->setData($postData);

if ($inputFilter->isValid()) {           // FileInput validators are run, but not the filters...
    echo "The form is valid\n";
    $data = $inputFilter->getValues();   // This is when the FileInput filters are run.
} else {
    echo "The form is not valid\n";
    foreach ($inputFilter->getInvalidInput() as $error) {
        print_r ($error->getMessages());
    }
}

                // Redirect to list of products
                return $this->redirect()->toRoute('product');
            }
        }
        return array('form' => $form);
    }
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('product', array(
                'action' => 'add'
            ));
        }
        $product = $this->getProductTable()->getProduct($id);
        $form  = new ProductForm();
        $form->bind($product);
        $form->get('submit')->setAttribute('value', 'Edit');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($product->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->getProductTable()->saveProduct($form->getData());
                // Redirect to list of products
                return $this->redirect()->toRoute('product');
            }
        }
        return array(
            'id' => $id,
            'form' => $form,
        );
    }
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('product');
        }
        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');
            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getProductTable()->deleteProduct($id);
            }
            // Redirect to list of products
            return $this->redirect()->toRoute('product');
        }
        return array(
            'id'    => $id,
            'product' => $this->getProductTable()->getProduct($id)
        );
    }
    public function getProductTable()
    {
        if (!$this->productTable) {
            $sm = $this->getServiceLocator();
            $this->productTable = $sm->get('Product\Model\ProductTable');
        }
        return $this->productTable;
    }
}