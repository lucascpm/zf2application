<?php

namespace Cadastro\Form;

use Abstrato\Form\AbstractForm;
use Zend\Form\Element\Checkbox;

class TerminalForm extends AbstractForm
{
	public function __construct($name = null) {
		parent::__construct('ponto');
	
		$this->setAttribute('method', 'post');
		$this->addElement('serial', 'text', 'Serial ');
		$this->addElement('versao', 'text', 'Versão ');
		
		$chb1 = new Checkbox('ativo');
		$chb1->setLabel('Ativo');
		$chb1->setUseHiddenElement(true);
		$chb1->setCheckedValue("1");
		$chb1->setUncheckedValue("0");
		$this->add($chb1);
				
		$this->addElement('observacao', 'textarea', 'Observação :');
		
		$this->addElement('submit', 'submit', 'Salvar');
		$this->addElement('voltar', 'submit', 'Voltar');
		
	}
}

?>