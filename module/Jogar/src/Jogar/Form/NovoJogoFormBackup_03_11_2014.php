<?php

namespace Jogar\Form;

use Abstrato\Form\AbstractForm;
use Zend\Form\Element;
use Zend\Stdlib\DateTime;

/**
 *
 * @LS
 *        
 */
class NovoJogoForm extends AbstractForm
{
	public function __construct($name = null)
	{
		parent::__construct('premio');
		$this->setAttribute('method', 'get');

        /* Número da Pule */
        $numPule = new Element('numPule');
        $numPule->setLabel('Número da Pule: ');
        $numPule->setAttributes(array(
            'type'  => 'text',
            'id'    => 'numPule',
        ));
        $this->add($numPule);
//        $this->addElement('numPule', 'text', 'Número da Pule: ', array('id' => 'numPule', 'autocomplete'=>'off', 'required'=>'false'));

        /** Extração **/
        $extracaoOps = array('value_options' => $this->getExtracaoOptions());
        //$this->addElement('extracao', 'select', 'Extração :', array('id' => 'extracao'), array());
        $this->addElement('extracao', 'select', 'Extração :', array('id' => 'extracao'), $extracaoOps);

        /*** Tipo de Jogo ***/
        $tipoJogoOps = array('value_options' => $this->getTipoJogoOptions());
        //$this->addElement('tipojogo', 'select','Tipo de Jogo', array(), array());
        $this->addElement('tipojogo', 'select','Tipo de Jogo', array(), $tipoJogoOps);

        /*** Jogos ***/
        $jogos = new Element('jogos');
        $jogos->setLabel('M');
        $jogos->setAttributes(array(
            'type'  => 'label',
            'id'    => 'jogo_0',
            'maxlength' => 4,
        ));
        $jogos->setLabelAttributes(array(
            'type'  => 'label',
            'id'    => 'siglajogos',
        ));
        $this->add($jogos);

        /*** Quantidade de Jogos ***/
        //TODO criar label sem input
        $qtdjogos = new Element('qtd');
        $qtdjogos->setLabel('Quantidade de jogos: ');
        $qtdjogos->setAttributes(array(
            'type'  => 'label',
            'id'    => 'qtd',
            'disabled' => true,
            'value' => '1',
        ));
        $qtdjogos->setLabelAttributes(array(
            'type'  => 'label',
            'id'    => 'qtdjogos',
        ));
        $this->add($qtdjogos);

        $this->addElement('premioini', 'text', 'Prêmio: ', array('maxlength' => 5));
        $this->addElement('premiofim', 'text', ' / ', array('value' => '10'));
        $this->addElement('valorjogo', 'text', 'Valor do Jogo: ', array('value' => '20,00'));


        $this->addElement('submit', 'submit', 'Imprimir', array(), "imprimir");

        $imprimir = new Element\Submit('imprimir');
        $imprimir->setLabel('imprimir')
            ->setValue('Imprimir')
            ->setAttribute('id','imprimir')
            ->setAttribute('required',false);

        $this->add($imprimir);

        $button = new Element\Button('jogar');
        $button->setLabel('Jogar')
            ->setValue('Jogar')
            ->setAttribute('id','jogar')
            ->setAttribute('required',false);

        $this->add($button);

	}

    private function getTipoJogoOptions() {
        $valueOptions = array();

        //adiciona a opção vazia
        //$valueOptions["todos"] = "--Selecione--";

        $em = $GLOBALS['entityManager'];
        $result = $em->getRepository('Cadastro\Model\TipoJogo')->findAll();

        foreach($result as $o)
        {
            $valueOptions[$o->id] = $o->codigo.'-'.$o->nome;
        }

        return $valueOptions;
    }


    private function getExtracaoOptions() {
        $valueOptions = array();

        $em = $GLOBALS['entityManager'];
        $result = $em->getRepository('Cadastro\Model\ExtracaoProgramada')->extracaoPNaData(new DateTime());

        foreach($result as $o)
        {
            $valueOptions[$o->id] = $o->extracao->nome;//." - ".$o->data_extracao->format("d/m/Y");
        }

        return $valueOptions;
    }

}
