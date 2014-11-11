<?php

namespace Cadastro\Form;

use Abstrato\Form\AbstractForm;
use Zend\Form\Element\Checkbox;
/**
 *
 * @author hlm
 *        
 */
class AgenciaForm extends AbstractForm
{
	public function __construct($name = null)
	{
		parent::__construct('agencia');
		$this->setAttribute('method', 'post');
		$this->addElement('codigo', 'text', 'Código :');
		$this->addElement('nome', 'text', 'Nome :');
		$this->addElement('nome_terminal', 'text', 'Nome no terminal:');
		$this->addElement('nome_pule', 'text', 'Nome na PULE:');
		$this->addElement('valor_bancado', 'text', 'Valor bancado :');
		
 		$this->addElement('descarga', 'text', 'Descarga :'); 		
		$this->addElement('cod_cliente_recarga', 'text', 'Código do cliente recarga :');
		
		$this->addElement('submit', 'submit', 'Salvar');
		$this->addElement('voltar', 'submit', 'Voltar');
		
	}
}

