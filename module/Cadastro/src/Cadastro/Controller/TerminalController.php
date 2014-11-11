<?php

namespace Cadastro\Controller;

use Zend\Authentication\AuthenticationService;
use Abstrato\Mvc\Controller\AbstractDoctrineCrudController;

class TerminalController extends AbstractDoctrineCrudController
{
	public function __construct()
	{
		$this->formClass = 'Cadastro\Form\TerminalForm';
		$this->modelClass = 'Cadastro\Model\Terminal';
		$this->namespaceTableGateway = 'Cadastro\Model\TerminalTable';
		$this->route = 'terminal';
		$this->title = 'Cadastro de Terminais';
        $this->campoBusca = 'versao';
		$this->label['yes']	= 'Sim';
		$this->label['no']	= 'Não';
		$this->label['add']	= 'Incluir';
		$this->label['edit'] = 'Alterar';
	}
	
	public function indexAction()
	{
		$viewModel = parent::indexAction();
	
// 		$auth = new AuthenticationService();
// 		$login = $auth->getIdentity()->getLogin;
// 		if ($login !== '') {
// 			return $this->redirect()->toRoute('application', array('controller' => 'index', 'action' => 'login'));;
// 		}
// 		$viewModel->setVariable('login',$login);
	
// 		$terminais = $this->getTableGateway()->fetchAll();
// 		$viewModel->setVariable('terminais', $terminais);
		
// 		$em = $GLOBALS['entityManager'];
// 		$viewModel->setVariable('terminais', $em->getRepository('Cadastro\Model\Terminal')->findAll() );
		
		$urlHomepage = $this->url()->fromRoute('application', array('controller' => 'index', 'action'=>'menu'));
		$viewModel->setVariable('urlHomepage', $urlHomepage);

        $form = $this->getPesquisaForm();
        $viewModel->setVariable('formPesquisa', $form);

		return $viewModel;
	}

    protected function save($model, $form, $urlAction, $key)
    {

        $request = $this->getRequest();

        if ($request->isPost())
        {
            //testa se o botão apertado do formulário foi o voltar
            $voltar = $request->getPost('voltar');
            if ($voltar == $this->label['voltar']) {
                return $this->redirect()->toRoute($this->route);
            } else {
                $em = $GLOBALS['entityManager'];
                $repositorio = $em->getRepository($this->modelClass);



                $form->setInputFilter($model->getInputFilter());
                $form->setData($request->getPost());

                if ($form->isValid())
                {

                    //Testa se trata-se de adicionar um novo registro ou editar um existente
                    //Caso não tenha sido passada uma chave primária como parâmetro é porque deve ser criado um novo registro
                    if($key == null) {
                        $chavePrimaria = $request->getPost('serial');

                        if(!empty($chavePrimaria)) {
//              Caso seja feita a inserção de um novo registro, é feito o teste se o objeto a ser registrado já existe
                            if($repositorio->find($chavePrimaria)) {
                                $this->flashMessenger()->addErrorMessage("Não foi possível salvar o registro. Já existe um serial ".$chavePrimaria." cadastrado.");
                                return $this->redirect()->toRoute($this->route);
                            }
                        }
                    }

                    $model->exchangeArray($form->getData());

                    //Testa se o método persist obteve sucesso
                    if(!$em->persist($model)) {
                        $this->flashMessenger()->addSuccessMessage("Registro incluído com sucesso.");
                    } else {
                        $this->flashMessenger()->addErrorMessage("Não foi possível salvar o registro. Verifique o preenchimento dos campos.");
                    }

                    $em->flush();

                    return $this->redirect()->toRoute($this->route);
                }
            }
        }

        return array(
            'key' => $key,
            'form' => $form,
            'urlAction' => $urlAction,
            'title' => $this->setAndGetTitle()
        );
    }
}

?>