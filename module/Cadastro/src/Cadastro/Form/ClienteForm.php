<?php
/**
 * Created by PhpStorm.
 * User: hlm
 * Date: 09/06/14
 * Time: 21:46
 */

namespace Cadastro\Form;


use Abstrato\Form\AbstractForm;
use Zend\Form\Element\Checkbox;

class ClienteForm extends AbstractForm {

    public function __construct($name = null)
    {
        parent::__construct('cliente');
        $this->setAttribute('method', 'post');
        $this->addElement('razao_social', 'text', 'Razão Social :');
        $this->addElement('situacao', 'text', 'Situação :');

        $chb1 = new Checkbox('ativo');
        $chb1->setLabel('Ativo');
        $chb1->setUseHiddenElement(true);
        $chb1->setCheckedValue("1");
        $chb1->setUncheckedValue("0");
        $this->add($chb1);

        $this->addElement('estado', 'text', 'Estado :');
        $this->addElement('municipio', 'text', 'Município :');
        $this->addElement('responsavel', 'text', 'Responsável :');

        $this->addElement('submit', 'submit', 'Salvar');
        $this->addElement('voltar', 'submit', 'Voltar');

    }
} 