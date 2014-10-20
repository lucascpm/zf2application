<?php

namespace Cadastro\Controller;

use Cadastro\Form\PesquisaRotaForm;
use Abstrato\Mvc\Controller\AbstractDoctrineCrudController;
use Cadastro\Form\RotaForm;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

/**
 *
 * @author hlm
 *        
 */
class RotaController extends AbstractDoctrineCrudController
{
	public function __construct()
	{
		$this->formClass = 'Cadastro\Form\RotaForm';
		$this->modelClass = 'Cadastro\Model\Rota';
		$this->route = 'rota';
		$this->title = 'Cadastro de Rotas';
        $this->campoBusca = 'nome';
        $this->camposInputSelect = array('agencia'=>'agencia_id');
		$this->label['yes']	= 'Sim';
		$this->label['no']	= 'Não';
		$this->label['add']	= 'Incluir';
		$this->label['edit'] = 'Alterar';
	}
	
	public function indexAction()
	{
        $partialLoop = $this->getSm()->get('viewhelpermanager')->get('PartialLoop');
        $partialLoop->setObjectKey('model');

        $urlAdd = $this->url()->fromRoute($this->route, array('action'=>'add'));
        $urlEdit = $this->url()->fromRoute($this->route, array('action'=>'edit'));
        $urlDelete = $this->url()->fromRoute($this->route, array('action'=>'delete'));
        $urlHomepage = $this->url()->fromRoute('home');

        $placeHolder = $this->getSm()->get('viewhelpermanager')->get('Placeholder');
        $placeHolder('url')->edit = $urlEdit;
        $placeHolder('url')->delete = $urlDelete;


//      Diff
        $request = $this->getRequest();

        //Obtem o valor de busca através da URL
        $valorBusca = $request->getQuery()['busca'];

        $em = $GLOBALS['entityManager'];

        //Se o usuário não fez busca
        if (!isset($valorBusca)) {
            $result = $em->getRepository($this->modelClass)->findBy(array(), array('nome'=>'ASC'));
        } else {
            //fazendo cast para testar se pesquisou pelo codigo
            $codigoBusca = intval($valorBusca);
            // testando se o cast foi efetivo
            if(is_int($codigoBusca) && $codigoBusca != 0) {
                $rotaPeloCodigo = $em->getRepository($this->modelClass)->find($codigoBusca);
            }

            $qBuilder = $em->createQueryBuilder();
            //tratar consulta! @TODO
            $qBuilder->select('r')
                    ->from($this->modelClass, 'r')
                    ->where("r.nome LIKE '%".strtoupper($valorBusca)."%'");
            $rotasPeloNome = $qBuilder->getQuery()->execute();
            //Testa se houveram resultados pela busca através do nome
            if(isset($rotasPeloNome)) {
                if(isset($rotaPeloCodigo)) {
                    array_push($rotasPeloNome, $rotaPeloCodigo);
                    $result = $rotasPeloNome;
                } else {
                    $result = $rotasPeloNome;
                }
            } else {
                $result = array( '0' => $rotaPeloCodigo);
            }
        }

        $pageAdapter = new ArrayAdapter($result);
        $paginator = new Paginator($pageAdapter);
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page',1));

        $viewModel = new ViewModel(array(
            //alteração para não utilizar outra página com helpers
            'models' => $result,
            'paginator' => $paginator,
            'title' => $this->setAndGetTitle(),
            'urlAdd' => $urlAdd,
            'urlHomepage' => $urlHomepage
        ));

        //Código acima = do parent

		$urlPesquisa = $this->url()->fromRoute('rota', array('controller' => 'rota', 'action'=>'index'));
	
		$viewModel->setVariable('urlPesquisa', $urlPesquisa);

        $formPesquisa = new PesquisaRotaForm();
        $viewModel->setVariable('formPesquisa', $formPesquisa);

		return $viewModel;
	}

//    public function editAction()
//    {
//        $key = (int) $this->params()->fromRoute('key', null);
//        if ($key == null)
//        {
//            return $this->redirect()->toRoute($this->route, array(
//                'action' => 'add'
//            ));
//        }
//
//        $model = $this->getModel($key);
//
////        $formClass = RotaFo;
//        $form  = new RotaForm();
//        $form->bind($model);
//
//        foreach($this->camposInputSelect as $atributo => $campoForm) {
//            $form->get($campoForm)->setValue($model->$atributo->id);
//        }
//
//        $form->get('submit')->setAttribute('value', $this->label['edit']);
//
//        $urlAction = $this->url()->fromRoute($this->route, array(
//            'action' => 'edit',
//            'key' => $key
//        ));
//
//        return $this->save($model, $form, $urlAction, $key);
//    }

}

