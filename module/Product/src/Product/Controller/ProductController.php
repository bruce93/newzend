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
use Zend\InputFilter\FileInput;
use Zend\Validator;

class ProductController extends AbstractActionController
{
    protected $productTable;
    public function indexAction()
    {
        return new ViewModel(array(
            //'role' => $this->zfcUserAuthentication()->getIdentity()->getRole(),
            'products' => $this->getProductTable()->fetchAll(),
            'user_login_widget_view_template' => 'zfc-user/user/login.phtml',
        ));
    }
    public function addAction()
    {
        $form = new ProductForm();
        $request = $this->getRequest();
        if ($request->isPost()) {   
            $product = new Product();
            $form->setInputFilter($product->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $adapter = new \Zend\File\Transfer\Adapter\Http();
                if (!$adapter->isValid()){
                    $dataError = $adapter->getMessages();
                    foreach($dataError as $key => $row)
                    {
                        $errorMessage[] = $row;
                    } 
                    //print_r($row);
                   // return false;
                } else { 
                    $data = $form->getData();
                    $fileName = $data['image'];
                    //$dir = getcwd() . '/public/img/';
                    $dir = 'var/www/newzend/public/img/';
                    $fileTlsName = $dir.$fileName;// \gid\uid\fileName
                    $adapter->addFilter('Rename', $fileTlsName);// rename file
                       if ($adapter->receive($fileName)) {// upload file
                            return true;
                        }
                }
                //return false;
                $product->exchangeArray($form->getData());
                //var_dump($product); die;
                //$product->receive();
                $this->getProductTable()->saveProduct($product); 
                // Redirect to list of products
                return $this->redirect()->toRoute('product');
            } else {//написать сообщения и вывести во view
                echo 'error';
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
    public function uploadFormAction()
    {
        $form = new UploadForm('upload-form');

        $request = $this->getRequest();
        if ($request->isPost()) {
            // Make certain to merge the files info!
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);
            if ($form->isValid()) {
                $data = $form->getData();
                // Form is valid, save the form!
                return $this->redirect()->toRoute('product');
            }
        }

        return array('form' => $form);
    }
}
