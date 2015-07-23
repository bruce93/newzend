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
            'products' => $this->getProductTable()->fetchAll(),
            'user_login_widget_view_template' => 'zfc-user/user/login.phtml',
        ));
    }
    public function addAction()
    {
        if ($this->zfcUserAuthentication()->hasIdentity() && $this->zfcUserAuthentication()->getIdentity()->getRole() == "admin") {
            $form = new ProductForm();
            $request = $this->getRequest();
            if ($request->isPost()) {   
                $product = new Product();
                $form->setInputFilter($product->getInputFilter());
                $form->setData(array_merge($request->getPost()->toArray(), $request->getFiles()->toArray()));
                if ($form->isValid()) {
                    $fileName = $form->getData()['image']['name'];
                    if (move_uploaded_file($form->getData()['image']['tmp_name'], getcwd() . '/public/img/' . $fileName)) {
                        echo "Файл корректен и был успешно загружен.\n";
                    } else {
                        echo "Возможная атака с помощью файловой загрузки!\n";
                    }
                    $product->exchangeArray($form->getData());
                    $this->getProductTable()->saveProduct($product); 
                    // Redirect to list of products
                    return $this->redirect()->toRoute('product');
                }
            }
            return array('form' => $form);
        } else {
            
            $view = new ViewModel(array(
                'message' => 'GET OUT OF HERE!',
            ));
            $view->setTemplate('product/error/access');
            return $view;
        }
    }
    public function editAction()
    {
        if ($this->zfcUserAuthentication()->hasIdentity() && $this->zfcUserAuthentication()->getIdentity()->getRole() == "admin") {
            $id = (int) $this->params()->fromRoute('id', 0);
            if (!$id) {
                return $this->redirect()->toRoute('product', array(
                    'action' => 'add'
                ));
            }
            $product = $this->getProductTable()->getProduct($id);
            $form  = new ProductForm();
            $form->bind($product);
            $request = $this->getRequest();
            if ($request->isPost()) {
                $form->setInputFilter($product->getInputFilter());
                $form->setData(array_merge($request->getPost()->toArray(), $request->getFiles()->toArray()));
                if ($form->isValid()) {
                	$fileName = $form->getData()['image']['name'];
                    if (move_uploaded_file($form->getData()['image']['tmp_name'], getcwd() . '/public/img/' . $fileName)) {
    				    echo "Файл корректен и был успешно загружен.\n";
    				} else {
    				    echo "Возможная атака с помощью файловой загрузки!\n";
    				}

                    $product->exchangeArray($form->getData());
                    $this->getProductTable()->saveProduct($form->getData());
                    // Redirect to list of products
                    return $this->redirect()->toRoute('product');
                }
            }
            return array(
                'id' => $id,
                'form' => $form,
            );
        } else {
            
            $view = new ViewModel(array(
                'message' => 'GET OUT OF HERE!',
            ));
            $view->setTemplate('product/error/access');
            return $view;
        }
    }
    public function deleteAction()
    {
        if ($this->zfcUserAuthentication()->hasIdentity() && $this->zfcUserAuthentication()->getIdentity()->getRole() == "admin") {
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
            } else {
                
                $view = new ViewModel(array(
                    'message' => 'GET OUT OF HERE',
                ));
                $view->setTemplate('product/error/access');
                return $view;
        }
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
    