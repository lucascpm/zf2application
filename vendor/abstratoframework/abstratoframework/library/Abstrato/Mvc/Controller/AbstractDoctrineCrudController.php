<?php

namespace Abstrato\Mvc\Controller;

use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

class AbstractDoctrineCrudController extends AbstractActionController
{
    protected $formClass;
    protected $modelClass;
    /**
     * @var string Define qual campo deve ser pesquisado na função de busca (buscaRegistros);
     * Também utilizado para ordenação
     */
    protected $campoBusca;
    /**
     * @var array Define os inputs do tipo select que devem manter os dados do modelo ao editar.
     * A sintaxe deve ser: array('nome_do_atributo_no_modelo'=>'name_do_input_do_form')
     */
    protected $camposInputSelect;
    protected $route;
    protected $title;
    protected $label = array(
        'add' 	=>'Add',
        'edit'	=>'Edit',
        'yes'	=>'Yes',
        'no'	=>'No',
        'voltar' => 'Voltar'
    );

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

        $em = $GLOBALS['entityManager'];

        $request = $this->getRequest();

        //Obtem o valor de busca através da URL (Via método GET)
        $valorBusca = $request->getQuery()['busca'];

        //Testa se o usuário fez uma busca
        if (isset($valorBusca)) {
            $result = $this->buscaRegistros($em->getRepository($this->modelClass), $em->createQueryBuilder(), strtoupper($valorBusca), $this->campoBusca);
        } else {
            //Se não foi feita uma busca específica, deve buscar todos

            if(isset($this->campoBusca)) {
                $result = $em->getRepository($this->modelClass)->findBy(array(), array($this->campoBusca => 'ASC'));
            } else {
                $result = $em->getRepository($this->modelClass)->findAll();
            }
        }



        $pageAdapter = new ArrayAdapter($result);
        $paginator = new Paginator($pageAdapter);
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page',1));

        return new ViewModel(array(
            //alteração para não utilizar outra página com helpers
            'models' => $result,
            'paginator' => $paginator,
            'title' => $this->setAndGetTitle(),
            'urlAdd' => $urlAdd,
            'urlHomepage' => $urlHomepage
        ));
    }

    /**
     * Função que busca registros a partir de uma string $busca, que será pesquisada como chave primária
     * e como um determinado campo especificado a partir de $campoBusca.
     * @param EntityManager $repositorio
     * @param QueryBuilder $qBuilder
     * @param string $busca
     * @param string $campoBusca
     * @return null
     */
    private function buscaRegistros(EntityRepository $repositorio, QueryBuilder $qBuilder, $busca, $campoBusca) {
        //fazendo cast para testar se pesquisou pelo codigo
        $codigoBusca = intval($busca);
        // testando se o cast foi efetivo
        if(is_int($codigoBusca) && $codigoBusca != 0) {
            $obtidoPeloId = $repositorio->find($codigoBusca);
        }

        if(isset($campoBusca)) {
            //tratar consulta! @TODO
            //Faz uma busca pelo campo de pesquisa
            $qBuilder->select('m')
                ->from($this->modelClass, 'm')
                ->where("m.".$campoBusca." LIKE '%".$busca."%'");
            $obtidoPeloCampo = $qBuilder->getQuery()->execute();
        }

        //Testa se houveram resultados pela busca através do campo
        if(isset($obtidoPeloCampo)) {
            if(isset($obtidoPeloId)) {
                array_push($obtidoPeloCampo, $obtidoPeloId);
                $result = $obtidoPeloCampo;
            } else {
                $result = $obtidoPeloCampo;
            }
        } else {
            $result = array( '0' => $obtidoPeloId);
        }

        return $result;
    }

    public function addAction()
    {
        $modelClass = $this->modelClass;
        $model = new $modelClass();

        $formClass = $this->formClass;
        $form = new $formClass();
        $form->get('submit')->setValue($this->label['add']);
        $form->bind($model);

        $urlAction = $this->url()->fromRoute($this->route, array('action' => 'add'));

        return $this->save($model, $form, $urlAction, null);
    }

    public function editAction()
    {
        $key = (int) $this->params()->fromRoute('key', null);
        if ($key == null)
        {
            return $this->redirect()->toRoute($this->route, array(
                'action' => 'add'
            ));
        }

        $model = $this->getModel($key);

        $formClass = $this->formClass;
        $form  = new $formClass();
        $form->bind($model);

        //Para manter os inputs do tipo select com seus valores selecionados
        if(isset($this->camposInputSelect) && $this->getRequest()->isGet()) {
            
            foreach($this->camposInputSelect as $atributo => $campoForm) {
                $form->get($campoForm)->setValue($model->$atributo->id);
            }
        }

        $form->get('submit')->setAttribute('value', $this->label['edit']);

        $urlAction = $this->url()->fromRoute($this->route, array(
            'action' => 'edit',
            'key' => $key
        ));

        return $this->save($model, $form, $urlAction, $key);
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
                    if(is_null($key)) {

                        $chavePrimaria = $request->getPost('codigo');
                        if(!empty($chavePrimaria)) {
//              Caso seja feita a inserção de um novo registro, é feito o teste se o objeto a ser registrado já existe
                            if($repositorio->findBy(array('codigo'=>$chavePrimaria) )) {
                                $this->flashMessenger()->addErrorMessage("Não foi possível salvar o registro. Já existe um código ".$chavePrimaria." cadastrado.");
                                return $this->redirect()->toRoute($this->route);
                            }

                            //Se é um novo registro transfere o array de dados do form
                            $model->exchangeArray($form->getData());

                        }
                    } else {

                        //Como está sendo feita uma edição, o formulário foi vinculado anteriormente a um objeto modelo
                        //Ou seja, o retorno do getData agora é um objeto.
                        $model = $form->getData();

                    }


                    try
                    {
                        $em->persist($model);
                        $em->flush();
                    }
                    catch(DBALException $dbale)
                    {
                        $this->flashMessenger()->addErrorMessage('ERRO :'.$dbale->getMessage());
                        return $this->redirect()->toRoute($this->route);
                    }



                    if($key == null) {
                        $this->flashMessenger()->addSuccessMessage("Registro incluído com sucesso.");
                    } else {
                        $this->flashMessenger()->addSuccessMessage("Registro alterado com sucesso.");
                    }

                    return $this->redirect()->toRoute($this->route);
                }
            }
        }

        return new ViewModel(array(
            'key' => $key,
            'form' => $form,
            'urlAction' => $urlAction,
            'title' => $this->setAndGetTitle()
        ));
    }



    public function deleteAction()
    {
        $key = (int) $this->params()->fromRoute('key', null);
        if (is_null($key))
        {
            return $this->redirect()->toRoute($this->route);
        }

        $request = $this->getRequest();
        if ($request->isPost())
        {
            $del = $request->getPost('del', $this->label['no']);

            if ($del == $this->label['yes'])
            {
                $em = $GLOBALS['entityManager'];
                try{
                    $em->remove($this->getModel($key));
                    $em->flush();
                } catch(\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                    return $this->redirect()->toRoute($this->route);
                }

            }

            return $this->redirect()->toRoute($this->route);
        }

        $model = $GLOBALS['entityManager']->getRepository($this->modelClass)->find($key);

        $urlAction = $this->url()->fromRoute($this->route, array('action' => 'delete','key'=> $key));

        return new ViewModel(array(
            'key' => $key,
            'form' => $this->getDeleteForm($key),
            'model' => $model,
            'urlAction' => $urlAction,
            'title' => $this->setAndGetTitle()
        ));
    }

    public function getDeleteForm($key)
    {
        $form = new Form();

        $form->add(array(
            'name' => 'del',
            'attributes' => array(
                'type'  => 'submit',
                'value' => $this->label['yes'],
                'id' => 'del',
            ),
        ));

        $form->add(array(
            'name' => 'return',
            'attributes' => array(
                'type'  => 'submit',
                'value' => $this->label['no'],
                'id' => 'return',
            ),
        ));

        return $form;
    }

    public function getPesquisaForm($label = null) {
        $busca = new Element('busca');
        if($label == null) {
            $busca->setLabel('Busca');
        } else {
            $busca->setLabel($label);
        }
        $busca->setAttributes(array(
            'type'  => 'text'
        ));

        $csrf = new Element\Csrf('security');

        $send = new Element('send');
        $send->setValue('Pesquisar');
        $send->setAttributes(array(
            'type'  => 'submit'
        ));

        $form = new Form("Pesquisa");
        $form->add($busca);
        $form->add($csrf);
        $form->add($send);

        $form->setAttribute('method', 'get');
        return $form;
    }

    protected function getModel($key)
    {
        $em = $GLOBALS['entityManager'];
        return $em->getRepository($this->modelClass)->find($key);
    }

    protected function getSm()
    {
        return $this->getEvent()->getApplication()->getServiceManager();
    }

    protected function setAndGetTitle()
    {
        $headTitle = $this->getSm()->get('viewhelpermanager')->get('HeadTitle');
        $headTitle($this->title);
        return $this->title;
    }
}

?>