<?php
namespace Cadastro\Form;

use Abstrato\Form\AbstractForm;
use Zend\Stdlib\DateTime;

class PuleForm extends AbstractForm
{
    public function __construct($name = null) {
        parent::__construct('puleForm');

        $this->setAttribute('method', 'post');

        $ops = array('value_options' => $this->getTerminalOptions());
        $this->addElement('terminal_id', 'select','Terminal', array(), $ops);

        $agenciaOps = array('value_options' => $this->getAgenciaOptions());
        $this->addElement('agencia_id', 'select','Agencia', array(), $agenciaOps);

        $ops = array('value_options' => $this->getExtracaoProgramadaOptions());
        $this->addElement('extracao_programada_id', 'select','ExtracaoProgramada :', array(), $ops);

        $this->addElement('submit', 'submit', 'Salvar');
        $this->addElement('voltar', 'submit', 'Voltar');

    }


    private function getAgenciaOptions() {
        $valueOptions = array();

        $em = $GLOBALS['entityManager'];
        $agencias = $em->getRepository('Cadastro\Model\Agencia')->findBy(array(), array('codigo'=>'ASC'));

        foreach($agencias as $a)
        {
            $valueOptions[$a->id] = $a->codigo.'-'.$a->nome;
        }
        return $valueOptions;
    }

    private function getTerminalOptions() {
        $em = $GLOBALS['entityManager'];
        $itens = $em->getRepository('Cadastro\Model\Terminal')->findBy(array(), array('serial'=>'ASC'));

        $valueOptions = array();
        foreach($itens as $t)
        {
            $valueOptions[$t->id] = $t->serial . ' - ' . $t->versao;
        }
        return $valueOptions;
    }

    private function getExtracaoProgramadaOptions() {
        $em = $GLOBALS['entityManager'];                               //Busca as extrações programadas a partir da data de hoje
        $exP = $em->getRepository('Cadastro\Model\ExtracaoProgramada')->extracoesAPartir(new DateTime('now'));


        $valueOptions = array();
        foreach($exP as $o)
        {
            $valueOptions[$o->id] = $o->data_extracao->format('d-m-Y') . ' - ' . $o->extracao->nome;
        }
        return $valueOptions;
    }


}