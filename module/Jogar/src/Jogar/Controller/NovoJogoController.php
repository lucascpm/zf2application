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

class NovoJogoController extends AbstractDoctrineCrudController
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ExtracaoProgramadaRepository
     */
    protected $extracaoProgramadaRepository;

    /**
     * @var PuleRepository
     */
    protected $puleRepository;

    public function __construct()
    {
        $this->formClass = 'Jogar\Form\NovoJogoForm';
//        $this->modelClass = 'Cadastro\Model\Pule';
//        $this->namespaceTableGateway = 'Cadastro\Model\Aposta';
        $this->route = 'novojogo';
        $this->title = 'Novo Jogo';
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
        $viewModel->setVariable('extracoes', $this->em->getRepository('Cadastro\Model\Extracao')->findAll());

        //TODO Vou precisar da Action??
        $action = $this->url()->fromRoute($this->route, array('action' => 'gerarPdf'));
        $viewModel->setVariable('urlAction', $action);

        return $viewModel;
    }

    public function gerarPdfAction() {
        $viewModel = new ViewModel();

        //Preenche os campos que serão mandados para a view
        $dadosPost = $this->getRequest()->getQuery();
        $qtdApostas = $dadosPost['qtd_jogos_hidden'];
        $numPule = $dadosPost['pule_0_hidden'];
        $jogo = '';
        $linhaAtual = '';
        $maxLinha = 35;
        for($i=0; $i<$qtdApostas; $i++){
            $qtd_jogos = $dadosPost['qtd_'.$i.'_hidden'];
            $tipojogo = $dadosPost['tipojogo_'.$i.'_hidden'];
            $premioini = $dadosPost['premio_'.$i.'_hidden'];
            $valorjogo = $dadosPost['valorjogo_'.$i.'_hidden'];
            $valortotal = $dadosPost['valortotal_'.$i.'_hidden'];
            $extracao = $dadosPost['extracao_'.$i.'_hidden'];
            $viewModel->setVariable('qtd_jogos_'.$i.'_hidden', $qtd_jogos);
            $viewModel->setVariable('tipojogo_'.$i.'_hidden', $tipojogo);
            $viewModel->setVariable('premioini_'.$i.'_hidden', $premioini);
            $viewModel->setVariable('valorjogo_'.$i.'_hidden', $valorjogo);
            $viewModel->setVariable('valortotal_'.$i.'_hidden', $valortotal);
            $viewModel->setVariable('extracao_'.$i.'_hidden', $extracao);
            //TODO continuar
            for($j=0; $j<$qtd_jogos; $j++){
                $linhaAtual .= $dadosPost['jogo_'.$i.'_'.$j] . ' ';
                if(strlen($linhaAtual)>=$maxLinha){
                    $jogo = str_pad($jogo, $maxLinha, '.'); //preenche a linha com .
                    $jogo .= '<br/>';
                    $linhaAtual = $dadosPost['jogo_'.$i.'_'.$j] . ' ';
                    $viewModel->setVariable('jogo_'.$i.'_'.$j, $jogo);
                }
                else{
                    $jogo = $linhaAtual;
                }
            }
            $viewModel->setVariable('jogo_'.$i.'_'.$j-1, $jogo);
        }

        $viewModel->setVariable('empresa', $GLOBALS['pjb_config']['empresa']);
        $viewModel->setVariable('operador', 'TESTE');
        $viewModel->setVariable('terminal', '016529100155');
        $viewModel->setVariable('data', new DateTime());
        $viewModel->setVariable('tempoReclamacoes', 30);
        $viewModel->setVariable('qtd_apostas', $qtdApostas);
        $viewModel->setVariable('numPule', $numPule);

        return $viewModel;
    }

}

?>