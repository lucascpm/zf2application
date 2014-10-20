<?php

namespace Jogar\Form;

use Abstrato\Form\AbstractForm;
use Zend\Form\Element;
/**
 *
 * @LS
 *        
 */
class LoginForm extends AbstractForm
{
	public function __construct($name = null)
	{
		parent::__construct('premio');
		$this->setAttribute('method', 'get');

        //----------------------
        /* Número do Terminal */
        $numTerminal = new Element('numTerminal');
        $numTerminal->setLabel('Número do Terminal: ');
        $numTerminal->setAttributes(array(
            'type'  => 'text',
            'id'    => 'numTerminal',
        ));
        $this->add($numTerminal);

        /* Código do Operador */
        $operadorCod = new Element('operadorCod');
        $operadorCod->setLabel('Código do Operador: ');
        $operadorCod->setAttributes(array(
            'type'  => 'text',
            'id'    => 'operadorCod',
        ));
        $this->add($operadorCod);

        /* Senha do Operador */
        $operadorSenha = new Element('operadorSenha');
        $operadorSenha->setLabel('Senha do Operador: ');
        $operadorSenha->setAttributes(array(
            'type'  => 'text',
            'id'    => 'operadorSenha',
        ));
        $this->add($operadorSenha);


        $button = new Element\Button('login');
        $button->setLabel('Login')
            ->setValue('Login')
            ->setAttribute('id','login');

        $this->add($button);
	}


}
