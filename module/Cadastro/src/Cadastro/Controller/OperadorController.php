<?php

namespace Cadastro\Controller;

use Zend\View\Model\ViewModel;
use Cadastro\Form\OperadorForm;
use Cadastro\Model\Operador;
use Abstrato\Mvc\Controller\AbstractDoctrineCrudController;
/**
 * Controlador de Operadores
 * @author hlm
 *        
 */
class OperadorController extends AbstractDoctrineCrudController
{
	
	public function __construct()
	{
		$this->formClass = 'Cadastro\Form\OperadorForm';
		$this->modelClass = 'Cadastro\Model\Operador';
		$this->route = 'operador';
		$this->title = 'Cadastro de Operadores';
        $this->campoBusca = 'nome';
        $this->camposInputSelect = array('ponto'=>'ponto_id');
		$this->label['yes']	= 'Sim';
		$this->label['no']	= 'Não';
		$this->label['add']	= 'Incluir';
		$this->label['edit'] = 'Alterar';
	}
	
	public function indexAction()
	{
		$viewModel = parent::indexAction();

	
		$urlHomepage = $this->url()->fromRoute('application', array('controller' => 'index', 'action'=>'menu'));

        $form = $this->getPesquisaForm();
        $viewModel->setVariable('formPesquisa', $form);
		
		$viewModel->setVariable('urlHomepage', $urlHomepage);
		return $viewModel;
	}
}

?>