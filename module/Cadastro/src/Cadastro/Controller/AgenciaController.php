<?php

namespace Cadastro\Controller;

use Abstrato\Mvc\Controller\AbstractCrudController;
use Cadastro\Model\Agencia;
use Cadastro\Form\AgenciaForm;
use Zend\Authentication\AuthenticationService;
use Abstrato\Mvc\Controller\AbstractDoctrineCrudController;
/**
 *
 * @author hlm
 *        
 */
class AgenciaController extends AbstractDoctrineCrudController
{
	public function __construct()
	{
		$this->formClass = 'Cadastro\Form\AgenciaForm';
		$this->modelClass = 'Cadastro\Model\Agencia';
		$this->route = 'agencia';
        $this->campoBusca = 'nome';
		$this->title = 'Cadastro de Agências';
		$this->label['yes']	= 'Sim';
		$this->label['no']	= 'Não';
		$this->label['add']	= 'Incluir';
		$this->label['edit'] = 'Alterar';
	}


	public function addAction() {
		$modelClass = $this->modelClass;
		$model = new $modelClass();
		
		$formClass = $this->formClass;
		$form = new $formClass();
		$form->get('submit')->setValue($this->label['add']);
		$form->bind($model);
		
		$urlAction = $this->url()->fromRoute($this->route, array('action' => 'add'));
		
		return $this->save($model, $form, $urlAction, null);
	}

	
	public function indexAction()
	{
		$viewModel = parent::indexAction();
	
// 		$auth = new AuthenticationService();
// 		$viewModel->setVariable('login', $auth->getIdentity()->login);
	
		$urlHomepage = $this->url()->fromRoute('application', array('controller' => 'index', 'action'=>'menu'));
	
		$viewModel->setVariable('urlHomepage', $urlHomepage);
		return $viewModel;
	}
}

