<?php

namespace Cadastro\Controller;

use Abstrato\Mvc\Controller\AbstractDoctrineCrudController;
use Zend\Authentication\AuthenticationService;

class MapaExtracaoController extends AbstractDoctrineCrudController
{
	public function __construct()
	{
		$this->formClass = 'Cadastro\Form\MapaExtracaoForm';
		$this->modelClass = 'Cadastro\Model\MapaExtracao';
		$this->route = 'mapaextracao';
		$this->title = 'Cadastro de Mapas de Extração';
		$this->label['yes']	= 'Sim';
		$this->label['no']	= 'Não';
		$this->label['add']	= 'Incluir';
		$this->label['edit'] = 'Alterar';
	}
	
	public function indexAction()
	{
		$viewModel = parent::indexAction();
	
		$auth = new AuthenticationService();
		$viewModel->setVariable('login',$auth->getIdentity()->login);
	
	
		return $viewModel;
	}
}

?>