<?php
/**
 * Created by PhpStorm.
 * User: hlm
 * Date: 30/05/14
 * Time: 17:40
 */

namespace Cadastro\Form;


use Abstrato\Form\AbstractForm;

class PesquisaRotaForm extends AbstractForm{

    public function __construct($name = null)
    {
        parent::__construct('pesquisaRota');
        $this->setAttribute('method', 'get');
        $this->addElement('busca', 'text', 'Busca :');

        $this->addElement('submit', 'submit', 'Pesquisar');

    }

} 