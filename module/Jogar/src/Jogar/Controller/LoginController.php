<?php

namespace Jogar\Controller;

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

class LoginController extends AbstractDoctrineCrudController
{

    public function __construct()
    {
        $this->formClass = 'Jogar\Form\LoginForm';
        $this->route = 'login';
        $this->title = 'Login';
        $this->label['yes']	= 'Sim';
        $this->label['no']	= 'Não';

        $this->em = $GLOBALS['entityManager'];
//        $this->extracaoProgramadaRepository = $this->em->getRepository('Cadastro\Model\ExtracaoProgramada');
//        $this->puleRepository = $this->em->getRepository('Cadastro\Model\Pule');
    }

	public function indexAction() {
        $viewModel = new ViewModel();

        $auth = new AuthenticationService();
		$viewModel->setVariable('login',$auth->getIdentity()->login);
		
		$form = new $this->formClass();
		$viewModel->setVariable('form', $form);
		$viewModel->setVariable('title', $this->title);

        return $viewModel;
	}


}

?>
