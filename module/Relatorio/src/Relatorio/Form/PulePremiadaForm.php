<?php

namespace Relatorio\Form;

use Abstrato\Form\AbstractForm;
use Zend\Form\Element;
/**
 *
 * @LS
 *        
 */
class PulePremiadaForm extends AbstractForm
{
	public function __construct($name = null)
	{
		parent::__construct('premio');
		$this->setAttribute('method', 'get');

        /* Número da Pule */
        $numPule = new Element('numPule');
        $numPule->setLabel('Número da Pule: ');
        $numPule->setAttributes(array(
            'type'  => 'label'
        ));
        $this->add($numPule);

        /* Número do Terminal */
        $numTerminal = new Element('numTerminal');
        $numTerminal->setLabel('Número do Terminal: ');
        $numTerminal->setAttributes(array(
            'type'  => 'label'
        ));
        $this->add($numTerminal);

        //TODO Pedir confirmação do cancelamento
		$this->addElement('pesquisar', 'submit', 'Pesquisar');
	}


}
