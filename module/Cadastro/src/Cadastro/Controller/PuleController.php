<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Cadastro\Controller;

use Abstrato\Mvc\Controller\AbstractDoctrineCrudController;
use Cadastro\Form\ApostaForm;
use Cadastro\Model\Aposta;
use Cadastro\Model\Fechamento;
use Cadastro\Model\PuleTerminal;
use Doctrine\ORM\EntityManager;
use Zend\Stdlib\DateTime;
use Zend\View\Model\ViewModel;
use Cadastro\Form\PuleForm;
use Cadastro\Model\Pule;

class PuleController extends AbstractDoctrineCrudController
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct()
    {
        $this->formClass = 'Cadastro\Form\PuleForm';
        $this->modelClass = 'Cadastro\Model\Pule';
        $this->route = 'pule';
        $this->title = 'Cadastro de Pules e Apostas';
        $this->label['yes']	= 'Sim';
        $this->label['no']	= 'Não';
        $this->label['add']	= 'Incluir';
        $this->label['edit'] = 'Alterar';

        $this->em = $GLOBALS['entityManager'];
    }

	
    public function indexAction()
    {
        return new ViewModel(array(
        	'pules' => $this->em->getRepository('Cadastro\Model\Pule')->findAll(),
        ));
    }
    
    public function addAction()
    {
    	$form = new PuleForm();
    	$form->get('submit')->setValue('Cadastrarrr');
    	
    	$request = $this->getRequest();
    	
    	if($request->isPost()) {
            //testa se o botão apertado do formulário foi o voltar
            $voltar = $request->getPost('voltar');
            if ($voltar == $this->label['voltar']) {
                return $this->redirect()->toRoute($this->route);
            } else {
                $dadosPost = $request->getPost();
                //Testando se o terminal tem um operador já vinculado
                $terminal = $this->em->getRepository('Cadastro\Model\Terminal')->find($dadosPost['terminal_id']);
                if(is_null($terminal->operador)) {
                    $this->flashMessenger()->addErrorMessage("O terminal $terminal->serial não foi vinculado a um operador. Vincule-o a um operador no menu Operacional > Gerenciar terminal.");
                    return $this->redirect()->toRoute($this->route);
                }

                $pule = new Pule();
                $form->setInputFilter($pule->getInputFilter());
                $form->setData($dadosPost);
                if($form->isValid()) {
                    //Preenche a instância de Pule
                    //Um número de pule do terminal está sendo gerado no método exchangeArray
                    $pule->exchangeArray($form->getData());


                    //Procura se já existe um fechamento para essa extração programada
                    $fechamento = $this->em->getRepository('Cadastro\Model\Fechamento')
                                    ->buscaFechamento($pule->extracaoProgramada->id, $pule->terminal->id);

                    //Caso não tenha encontrado um fechamento, o mesmo é criado
                    if(is_null($fechamento)) {
                        $fechamento = new Fechamento();
                        $fechamento->data_abertura = new DateTime();
                        $fechamento->agencia = $pule->agencia;
                        $fechamento->extracaoProgramada = $pule->extracaoProgramada;
                        $fechamento->ponto = $pule->ponto;
                        $fechamento->operador = $pule->operador;
                        $fechamento->terminal = $pule->terminal;
                        $fechamento->obs = 'Abertura automática.';

                        try {
                            $this->em->persist($fechamento);
                            $this->em->flush();
                        } catch(\Exception $e) {
                            echo 'Problema ao tentar persistir abertura de fechamento.<br><br>';
                            echo $e->getMessage();
                            die();
                        }

                    } else {
                        //No caso de já existir um fechamento, o mesmo não deve ter data de fechamento.
                        //Ou seja, deve estar aberto.
                        if(!is_null($fechamento->data_fechamento)) {
                            $this->flashMessenger()->addErrorMessage('O terminal já tem fechamento com a extração programada na data '.$fechamento->data_fechamento->format('d-m-Y') );
                            return $this->redirect()->toRoute($this->route);
                        }
                    }


                    try {
                        //Primeiramente persiste a Pule
                        $this->em->persist($pule);

                        //Agora, incrementa o prox_numero_pule do Terminal e persiste o mesmo
                        $terminal = $pule->terminal;
                        $terminal->incrementaNumeroPule();
                        $this->em->persist($terminal);

                        $this->em->flush();

                        //Se não gerar nenhuma excessão é porque foi incluído com sucesso
                        $this->flashMessenger()->addSuccessMessage("Registro incluído com sucesso.");

                    } catch(\Exception $e) {
                        $this->flashMessenger()->addErrorMessage('ERRO :'.$e->getMessage());
                    }

                    return $this->redirect()->toRoute($this->route);
                }
            }
    	}
    	return new ViewModel(array('form' => $form));
    }

    public function addApostaAction() {
        $puleId = (int) $this->params()->fromRoute('key');
        if(!$puleId) {
            return $this->redirect()->toRoute('pule', array('action' => 'index'));
        }

        $form = new ApostaForm('apostaForm');
        $form->get('pule_id')->setValue($puleId);

        $urlAction = $this->url()->fromRoute($this->route, array('action' => 'addAposta', 'key' => $puleId));

        if($this->request->isPost()) {

            //testa se o botão apertado do formulário foi o voltar
            $voltar = $this->request->getPost('voltar');
            if ($voltar == $this->label['voltar']) {
                return $this->redirect()->toRoute($this->route);
            } else {
                $aposta = new Aposta();
                $form->setInputFilter($aposta->getInputFilter());
                $form->setData($this->request->getPost());
                if($form->isValid()) {
                    //Preenche a instância de Aposta com os dados do form enviado
                    $aposta->exchangeArray($form->getData());

                    $em = $GLOBALS['entityManager'];

                    try {
                        //Primeiramente persiste a Pule
                        $em->persist($aposta);

                        //Se não gerar nenhuma excessão é porque foi incluído com sucesso
                        $this->flashMessenger()->addSuccessMessage("Registro incluído com sucesso.");

                    } catch(\Exception $e) {
                        $this->flashMessenger()->addErrorMessage('ERRO :'.$e->getMessage());
                    }

                    $em->flush();

                    return $this->redirect()->toRoute($this->route, array('action' => 'addAposta', 'key' => $puleId));
                }
            }
        }

        return new ViewModel(array('form' => $form, 'urlAction' => $urlAction));

    }
    
    public function editAction()
    {
    	 $codigo = (int) $this->params()->fromRoute('codigo');
    	 if(is_null($codigo)) {
    	 	return $this->redirect()->toRoute('pule', array('action' => 'add'));
    	 }
    	 $pule = $this->getPuleTable()->getPule($codigo);
    	 
    	 $form = new PuleForm();
    	 
    	 $form->bind($pule);
    	 $form->get('submit')->setAttribute('value', 'Editar');
    	 
    	 $request = $this->getRequest();
    	 
    	 if($request->isPost()) {
    	 	$form->setInputFilter($pule->getInputFilter());
    	 	$form->setData($request->getPost());
    	 	
    	 	if ($form->isValid()) {
    	 		$this->getPuleTable()->savePule($form->getData());
    	 		
    	 		return $this->redirect()->toRoute('pule');
    	 	}
    	 }
    	 
    	 return array('codigo' => $codigo, 'form' => $form);
    	 
    }

    public function deleteAction() {}

//    public function deleteAction()
//    {
//    	$codigo = (int) $this->params()->fromRoute('codigo', null);
//    	if(is_null($codigo)) {
//    		return $this->redirect()->toRoute('pule');
//    	}
//
//    	$request = $this->getRequest();
//
//    	if($request->isPost()) {
//    		$del = $request->getPost('del', 'Nao');
//
//    		if ($del =='Sim') {
//    			$this->getPuleTable()->deletePule($codigo);
//    		}
//
//    		return $this->redirect()->toRoute('pule');
//    	}
//
//    	return array('codigo' => $codigo, 'form' => $this->getDeleteForm($codigo));
//    }
//
//    public function getDeleteForm($codigo){
//    	$form = new PuleForm();
//    	$form->remove('codigo');
//    	$form->remove('numero');
//    	$form->remove('data');
//    	$form->remove('submit');
//    	$form->add(array(
//    			'name' => 'del',
//    			'attributes' => array(
//    					'type' => 'submit',
//    					'value' => 'Sim',
//    					'id' => 'del',
//
//    		),
//    	));
//    	$form->add(array(
//    			'name' => 'return',
//    			'attributes' => array(
//    					'type' => 'submit',
//    					'value' => 'Não',
//    					'id' => 'return',
//
//    			),
//    	));
//
//    	return $form;
//    }
}
