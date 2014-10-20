<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Element\Text;
use Zend\Form\Element\Password;
use Zend\Form\Element\Submit;

class LoginForm extends Form
{
	public function __construct($name = 'login_form') {
		parent::__construct($name);
		$this->setAttribute('method', 'post');
		
		$element = new Text('login');
		$element->setLabel('Usuário');
		
		$this->add($element);
		
		$element = new Password('credencial');
		$element->setLabel('Senha');
		
		$this->add($element);

        $element = new Password('token');
        $element->setLabel('Token');

        $this->add($element);
		
		$element = new Submit('entrar');
		$element->setValue('Entrar');
		
		$this->add($element);
		
	}
	
}
