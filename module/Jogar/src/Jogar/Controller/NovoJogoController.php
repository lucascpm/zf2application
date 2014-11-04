<?php

namespace Jogar\Controller;

require_once 'vendor/pjb_etc/funcoes.php';
require_once 'vendor/pjb_etc/debug.php';

use Cadastro\Model\Repository\ExtracaoProgramadaRepository;
use Cadastro\Model\Repository\PuleRepository;
use Cadastro\Model\Repository\TipoJogoRepository;
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

    /**
     * @var TipoJogoRepository
     */
    protected $tipoJogoRepository;

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
        $this->tipoJogoRepository = $this->em->getRepository('Cadastro\Model\TipoJogo');
//        $this->extracaoProgramadaRepository = $this->em->getRepository('Cadastro\Model\ExtracaoProgramada');
//        $this->puleRepository = $this->em->getRepository('Cadastro\Model\Pule');
    }

    public function indexAction() {
        $dadosPost = $this->getRequest()->getQuery();
        $imprimir = $dadosPost['imprimir'];
        $viewModel = new ViewModel();

        $auth = new AuthenticationService();
        $viewModel->setVariable('login',$auth->getIdentity()->login);

        $form = new $this->formClass();
        $viewModel->setVariable('form', $form);
        $viewModel->setVariable('title', $this->title);
        $viewModel->setVariable('extracoes', $this->em->getRepository('Cadastro\Model\Extracao')->findAll());

        //TODO Vou precisar da Action??
        if(isset($imprimir)){
            $action = $this->url()->fromRoute($this->route, array('action' => 'gerarPdf'));
            $viewModel->setVariable('urlAction', $action);
            return $viewModel;
        }
        if($this->request->isGet()){
            return $viewModel;
        }


    }

    public function gerarPdfAction() {
        $viewModel = new ViewModel();

        //Preenche os campos que serão mandados para a view
        $pule = new Pule();
        $dadosPost = $this->getRequest()->getQuery();
        $qtdApostas = $dadosPost['qtd_jogos_hidden'];
        $numPule = $dadosPost['pule_0_hidden'];
        $jogos = '';
        $linhaAtual = '';
        $jogosAtual = '';
        $jogosAnt = '';
        $preenchimento = '';
        $caracPreenc = '.';
        $maxLinha = 33;
        $tamanhoLinha = 0;
        $valorTotalApostas = 0;
        $separadorJogos = '<br/><br/>';
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

            $linhaAtual = $tipojogo . '<br/>' . $premioini . ' a ' . $valorjogo .  ' = ' . number_format($valortotal, 2, ',', '.') . '<br/>';
            for($j=0; $j<$qtd_jogos; $j++){
                $jogosAnt = $linhaAtual;
                $linhaAtual .= $dadosPost['jogo_'.$i.'_'.$j] . ' ';
                $tamanhoLinha = strlen($linhaAtual);
                if($tamanhoLinha >= $maxLinha){
                    $preenchimento = str_pad('', $maxLinha - strlen($jogosAnt), $caracPreenc);
                    $jogosAtual .=  $jogosAnt . $preenchimento.'<br/>';
                    $linhaAtual = $dadosPost['jogo_'.$i.'_'.$j] . ' ';
                    $tamanhoLinha = strlen($linhaAtual);
                }
            }
            $preenchimento = str_pad('', $maxLinha - $tamanhoLinha, $caracPreenc);
            $jogosAtual .= $linhaAtual . $preenchimento; //preenche a última linha do jogo atual
            $jogos .= $jogosAtual . $separadorJogos;
            $jogosAtual = '';
            $valorTotalApostas += $valortotal;
        }

        $viewModel->setVariable('jogos', $jogos);

        $viewModel->setVariable('empresa', $GLOBALS['pjb_config']['empresa']);
        $viewModel->setVariable('operador', 'TESTE');
        $viewModel->setVariable('terminal', '016529100155');
        $viewModel->setVariable('data', new DateTime());
        $viewModel->setVariable('tempoReclamacoes', 30);
        $viewModel->setVariable('qtd_apostas', $qtdApostas);
        $viewModel->setVariable('numPule', $numPule);
        $viewModel->setVariable('maxLinha', $maxLinha);
        $viewModel->setVariable('valorTotalApostas', $valorTotalApostas);

        return $viewModel;
    }
}

?>