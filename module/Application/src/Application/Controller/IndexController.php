<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Application\Form\LoginForm;
use Application\Model\Usuario;
use Zend\Authentication\AuthenticationService;
use Zend\Form\Element;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
    	$form = new LoginForm();
    	$form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/index/login');
    	
    	$mensagens = '';
    	
    	if ($this->flashMessenger()->getMessages()) {
    		$mensagens = implode(',', $this->flashMessenger()->getMessages());
    	}    	
    	
        return array('form' => $form, 'messages' => $mensagens);
    }
    
    public function loginAction() {
    	$request = $this->getRequest();
    	
    	$login = $request->getPost('login');
    	$credencial = $request->getPost('credencial');
    	$usuario = new Usuario($login, $credencial);
    	if ($usuario->autenticar($this->getServiceLocator())) {
    		return $this->redirect()->toUrl('menu');
    	} else {
    		$this->flashMessenger()->addMessage(implode(',', $usuario->mensagens));
    		return $this->redirect()->toRoute('home');
    	}
    }
    
    public function logoutAction() {
    	$auth = new AuthenticationService();
    	$auth->clearIdentity();
    	return $this->redirect()->toRoute('home');
    }
    
    public function menuAction() {
        $auth = new AuthenticationService();
        return array('usuario' => $auth->getIdentity());
    }
    
    
}
