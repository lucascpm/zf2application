<?php
/**
 * Created by PhpStorm.
 * User: hlm
 * Date: 06/07/14
 * Time: 11:16
 */

namespace Cadastro\Form;


use Abstrato\Form\AbstractForm;

class ApostaForm extends AbstractForm {

    public function __construct($name = 'apostaForm') {
        parent::__construct($name);

        $this->setAttribute('method', 'post');

        $ops = array('value_options' => $this->getTipoJogoOptions());
        $this->addElement('tipo_jogo_id', 'select','Tipo Jogo', array(), $ops);

        $ops = array('value_options' => $this->getEscopoOptions() );
        $this->addElement('escopo_id', 'select','Escopo do prêmio :', array(), $ops);

        $this->addElement('pule_id', 'hidden');
        $this->addElement('numero', 'text', 'Número :');
        $this->addElement('valor', 'text', 'Valor :');


        $this->addElement('submit', 'submit', 'Salvar');
        $this->addElement('voltar', 'submit', 'Voltar');

    }


    private function getTipoJogoOptions() {
        $em = $GLOBALS['entityManager'];
        $itens = $em->getRepository('Cadastro\Model\TipoJogo')->findAll();

        $valueOptions = array();
        foreach($itens as $t)
        {
            $valueOptions[$t->id] = $t->codigo.'-'.$t->sigla . ' - ' . $t->nome;
        }
        return $valueOptions;
    }


    private function getEscopoOptions() {
        $em = $GLOBALS['entityManager'];
        $escopos = $em->getRepository('Cadastro\Model\EscopoPremio')->findAll();


        $valueOptions = array();
        foreach($escopos as $e)
        {
            $valueOptions[$e->id] = $e->intervalo;
        }
        return $valueOptions;
    }
} 