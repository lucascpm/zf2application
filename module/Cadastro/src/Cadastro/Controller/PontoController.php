<?php

namespace Cadastro\Controller;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\View\Model\ViewModel;
use Cadastro\Form\PontoForm;
use Cadastro\Model\Ponto;
use Zend\Crypt\Key\Derivation\SaltedS2k;
use Abstrato\Mvc\Controller\AbstractDoctrineCrudController;
/**
 *
 * Controlador de Pontos
 * @author hlm
 *        
 */
class PontoController extends AbstractDoctrineCrudController
{

	public function __construct()
	{
		$this->formClass = 'Cadastro\Form\PontoForm';
		$this->modelClass = 'Cadastro\Model\Ponto';
		$this->route = 'ponto';
		$this->title = 'Cadastro de Pontos';
        $this->campoBusca = 'nome';
        $this->camposInputSelect = array('rota'=>'rota_id');
		$this->label['yes']	= 'Sim';
		$this->label['no']	= 'Não';
		$this->label['add']	= 'Incluir';
		$this->label['edit'] = 'Alterar';
	}
	
	public function indexAction()
	{
		$viewModel = parent::indexAction();

		$urlHomepage = $this->url()->fromRoute('application', array('controller' => 'index', 'action'=>'menu'));
	
		$viewModel->setVariable('urlHomepage', $urlHomepage);

        $form = $this->getPesquisaForm();
        $viewModel->setVariable('formPesquisa', $form);

		return $viewModel;
	}


}

?>