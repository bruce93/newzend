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
use Zend\Validator\File\Size;

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
                $product->exchangeArray($form->getData());
                //$this->getProductTable()->saveProduct($product);
                $adapter = new \Zend\File\Transfer\Adapter\Http();
                $dir = getcwd() . '/public/img/';
                $data = $form->getData();
                $filename = $data['image'];
                $fileTlsName = $dir.$filename;// pashet

                //$adapter->addValidator('Extension', false, 'jpg,png,gif');
                var_dump($adapter); die;
                //var_dump($fileTlsName);die;
                //$adapter->addFilter('Rename', $fileTlsName);// rename file
                
            if ($adapter->receive($filename)) {// upload file
                return $this->redirect()->toRoute('product');
            }
        }
    }
    return array('form' => $form);
}
           // $data = $form->getData();
           // $filename = $data['image'];
           // $dir = 'public/img';
           // $product->setDestination( getcwd() . '/public/img/'); 
          /*  var_dump(getcwd() . '/public/img/');       
            if ($form->isValid()) {  
             $adapter = new \Zend\File\Transfer\Adapter\Http();*/
// }
// }
        /*$adapter->setValidators();
        if (!$adapter->isValid()){

            $dataError = $adapter->getMessages();
            foreach($dataError as $key => $row)
            {
                $errorMessage[] = $row;
            }
            return false;
        } else {
            $fileTlsName = $dir.$fileName;// \gid\uid\fileName
            $adapter->addFilter('Rename', $fileTlsName);// rename file
            if ($adapter->receive($fileName)) {// upload file
                return true;
            }
        }
        return false;*/
    //}

        //$form->get('submit')->setValue('Add');
       // $request = $this->getRequest();
       // if ($request->isPost()) {   
           /* $product = new Product();
            $file = 'public/img';
            $product->setDestination(PUBLIC_PATH . '/img'); 
           var_dump($product); die;
            $form->setInputFilter($product->getInputFilter());*/
           /* $form->setData($request->getPost());
            
            if ($form->isValid()) {
                

                $adapter = new \Zend\File\Transfer\Adapter\Http();
                $adapter->setValidators(array($size),$fileName);

                var_dump($adapter); die;
                //validator can be more than one...
                //var_dump('1'); die;
                if (!$adapter->isValid()){
                	//var_dump(getMessages()); die;
                    $dataError = $adapter->getMessages();
                    foreach($dataError as $key => $row)
                    {
                        $errorMessage[] = $row;
                    }
                    return false;
                } else {
                    $data = $form->getData();
                    var_dump('1'); die;
                    $fileName = $data['image'];
                    $dir = 'puplic/img';
                    $fileTlsName = $dir.$fileName;// \gid\uid\fileName
                    $adapter->addFilter('Rename', $fileTlsName);// rename file
                    if ($adapter->receive($fileName)) {// upload file
                        return true;
                    }
                }
        //return false;

                //var_dump($form->getData()); die; 
                $product->exchangeArray($form->getData());
                $this->getProductTable()->saveProduct($product);
                //$product->setDestination($image);
                //$product->receive();
                // Redirect to list of products
                return $this->redirect()->toRoute('product');
            } else {//взять сообщения и вывести во view 
            }
        }
        return array('form' => $form);*/
    //}
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
    /* protected function getFsConfig()
    {
        if (!$this->_fsConfig) {
            $sm = $this->getServiceLocator();
            //var_dump($sm);die;
            $config = $sm->get('Config');
            //var_dump($config);die;
            $this->_fsConfig = $config['filesystem']['file'];
            var_dump($file);die;
        }
        return $this->_fsConfig;
    }*/
}
