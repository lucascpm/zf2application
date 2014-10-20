<?php

namespace Jogar\Form;

use Abstrato\Form\AbstractForm;
use Zend\Form\Element;
/**
 *
 * @LS
 *        
 */
class PreDatarJogoForm extends AbstractForm
{
    public function __construct($name = null)
    {
        parent::__construct('premio');
        $this->setAttribute('method', 'get');

        /*** Data ***/
        $this->addElement('data', 'text', 'Data:', array('id' => 'data',
            'required'=>'true',
            'autocomplete'=>'off'));

        /* Saldo */
        $saldo = new Element('saldo');
        $saldo->setLabel('Saldo: ');
        $saldo->setAttributes(array(
            'type'  => 'label'
        ));
        $this->add($saldo);

        /* Número da Pule */
        $numPule = new Element('numPule');
        $numPule->setLabel('Número da Pule: ');
        $numPule->setAttributes(array(
            'type'  => 'label'
        ));
        $this->add($numPule);

        /** Extração **/
        $this->addElement('extracao', 'select', 'Extração :', array(), array());

        /*** Tipo de Jogo ***/
        $this->addElement('tipojogo', 'select','Tipo Jogo', array(), array());

		$this->addElement('predatar', 'submit', 'Pré -Datar Jogo');
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
        $this->addElement('valorjogo', 'text', 'Valor do Jogo: ', array('value' => '2000'));



        $button = new Element\Button('jogar');
        $button->setLabel('Jogar')
            ->setValue('Jogar')
            ->setAttribute('id','jogar')
            ->setAttribute('required',false);

        $this->add($button);

    }

}
