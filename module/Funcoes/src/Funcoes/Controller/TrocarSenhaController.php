<?php

namespace Funcoes\Controller;

require_once 'vendor/pjb_etc/funcoes.php';
require_once 'vendor/pjb_etc/debug.php';

use Cadastro\Model\Repository\ExtracaoProgramadaRepository;
use Cadastro\Model\Repository\PuleRepository;
use Doctrine\ORM\EntityManager;
use Zend\Filter\Null;
use Zend\Stdlib\DateTime;
use Zend\Form\Element\Submit;
use Zend\Form\Form;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;
use Abstrato\Mvc\Controller\AbstractDoctrineCrudController;
use Zend\Form\Element\Hidden;

use Relatorio\Form\ApostaLsForm;
use Cadastro\Model\Pule;
use Cadastro\Model\Aposta;

class TrocarSenhaController extends AbstractDoctrineCrudController
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct()
    {
        $this->formClass = 'Funcoes\Form\TrocarSenhaForm';
        $this->route = 'trocarsenha';
        $this->title = 'Trocar Senha';
        $this->label['yes']	= 'Sim';
        $this->label['no']	= 'Não';

        $this->em = $GLOBALS['entityManager'];
    }

	public function indexAction() {
        $viewModel = new ViewModel();

        $auth = new AuthenticationService();
		$viewModel->setVariable('login',$auth->getIdentity()->login);
		
		$form = new $this->formClass();
		$viewModel->setVariable('form', $form);
		$viewModel->setVariable('title', $this->title);

        //TODO Vou precisar da Action??
        $action = $this->url()->fromRoute($this->route, array('action' => 'index'));
        $viewModel->setVariable('urlAction', $action);

        return $viewModel;
	}


}

?>
