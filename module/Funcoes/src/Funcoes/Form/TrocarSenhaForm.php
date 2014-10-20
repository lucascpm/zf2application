<?php

namespace Funcoes\Form;

use Abstrato\Form\AbstractForm;
use Zend\Form\Element;
/**
 *
 * @LS
 *        
 */
class TrocarSenhaForm extends AbstractForm
{
	public function __construct($name = null)
	{
		parent::__construct('premio');
		$this->setAttribute('method', 'get');

        /* Login */
        $login = new Element('login');
        $login->setLabel('Login: ');
        $login->setAttributes(array(
            'type'  => 'text'
        ));
        $this->add($login);

        /* Senha Antiga */
        $senhaAntiga = new Element('senhaAntiga');
        $senhaAntiga->setLabel('Senha Antiga: ');
        $senhaAntiga->setAttributes(array(
            'type'  => 'text'
        ));
        $this->add($senhaAntiga);

        /* Nova Senha */
        $novaSenha = new Element('novaSenha');
        $novaSenha->setLabel('Nova Senha: ');
        $novaSenha->setAttributes(array(
            'type'  => 'text'
        ));
        $this->add($novaSenha);

        /* Confirmar Senha */
        $confirmarSenha = new Element('confirmarSenha');
        $confirmarSenha->setLabel('Confirmar Nova Senha: ');
        $confirmarSenha->setAttributes(array(
            'type'  => 'text'
        ));
        $this->add($confirmarSenha);

        //TODO Pedir confirmação do cancelamento
		$this->addElement('trocarSenha', 'submit', 'Trocar Senha');
	}


}
