<?php

namespace Application\Controller;

use Abstrato\Mvc\Controller\AbstractDoctrineCrudController;
use Doctrine\ORM\EntityManager;
use Application\Form\BuscaForm;
use Zend\Form\Element\Select;
use Zend\Form\Form;
use Zend\View\Model\ViewModel;

/**
 *
 * @author hlm
 *
 */
class BuscaController extends AbstractDoctrineCrudController
{
    /**
     * @var EntityManager
     */
    protected $em;
    protected $tipos;

    public function __construct()
    {
        $this->modelClass = 'Cadastro\Model\TipoJogo';
        $this->route = 'busca';

        $this->em = $GLOBALS['entityManager'];

        //Estrutura dos jogos
        //          sigla   descrição           dígitos fator
        $tps = 'M,Milhar,4,4000;C,Centena,3,500;D,Dezeeeeena,2,100;MC,Milhar Centena,4,4500;CI,Centena Invertida,12,500;MI,Milhar Invertida,12,4500;CD,Centena Dezena,3,150;DI,Dezena Invertida,2,50;MCI,MC Invertida,12,4500;TG,Terno de Grupo,6,100;DG,Duque de Grupo,2,100;MCD,Milhar Cen Dez,4,100;G,Grupo,2,100;CG,Combinado Grupo,4,100;DD,Duque de Dezena,2,100;TD,Terno Dezena,6,100;MD,Milhar Dezena,4,100';

        $tps = explode(';', $tps);
        foreach($tps as $tp){
            $tips = explode(',', $tp);
            $this->tipos[] = array(
                'sigla' => $tips[0],
                'descricao' => $tips[1],
                'digitos' => $tips[2],
                'fator' => $tips[3]);
        }
    }

    public function indexAction()
    {
        $request = $this->getRequest();

        //Obtem o valor de busca através da URL
        $valorBusca = $request->getQuery()['busca'];
        $valorJogo = $request->getQuery()['valorjogo'];
        $em = $GLOBALS['entityManager'];

        //Se o usuário não fez busca
        if (!isset($valorBusca)) {
            $result = '--Selecione um tipo de jogo--';
        } else {
            $codigoBusca = intval($valorBusca);
            $result = $this->getTipoJogo($codigoBusca);
        }

        $valortotal = $em->getRepository('Cadastro\Model\TipoJogo')->findBy(array('aposta_valor_mult' => $valorJogo))[0];
        //procura o tipo, de acordo com o result(alteração do select)
        $tamanhoInput = $this->getTamanhoInputPelaSigla($result);

        /*if($result == "MI"){
            $tamanhoInput = 12;
        }
        else if($result == 'JGTN'){
            $tamanhoInput = 1;
        }
        else if($result == 'M' || $result == 'MC'){
            $tamanhoInput = 4;
        }
        else if($result == 'C'){
            $tamanhoInput = 3;
        }
        else if($result == 'D' || $result == 'G'){
            $tamanhoInput = 2;
        }*/

        $viewModel = new ViewModel(array(
            //alteração para não utilizar outra página com helpers
            'sigla' => $result,
            'tamanhoInput' => $tamanhoInput,
            'valorTotal' => $valortotal,
        ));

        return $viewModel;
    }

    private function getTamanhoInputPelaSigla($result){
        for($i=0; $i < count($this->tipos); $i++){
            if($this->tipos[$i]['sigla'] == $result){
                return $this->tipos[$i]['digitos'];
            }
        }
        //Não encontrou o tipo
        return 0;
    }

    private function getTipoJogo($id) {
        $em = $GLOBALS['entityManager'];
        $result = $em->getRepository('Cadastro\Model\TipoJogo')->findBy(array('id' => $id))[0]->sigla;
        return $result;
    }

}