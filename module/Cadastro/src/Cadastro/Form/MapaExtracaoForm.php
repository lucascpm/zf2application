<?php

namespace Cadastro\Form;

use Abstrato\Form\AbstractForm;
use Zend\Form\Element\Checkbox;

class MapaExtracaoForm extends AbstractForm
{
    public function __construct($name = null)
    {
        parent::__construct('mapaextracao');
        $this->setAttribute('method', 'post');
        $this->addElement('descricao', 'text', 'Descricao :');
        $this->addElement('horario', 'text', 'Horario :');
        $this->addElement('hora_bloqueio', 'text', 'Hora Bloqueio :');
        $this->addElement('hora_bloqueio_descarga', 'text', 'Hora Bloqueio Descarga:');
        $this->addElement('ordem', 'text', 'Ordem :');

        $chb1 = new Checkbox('corre_descarga');
        $chb1->setLabel('Corre Descarga :');
        $chb1->setUseHiddenElement(true);
        $chb1->setCheckedValue("1");
        $chb1->setUncheckedValue("0");
        $this->add($chb1);

        $chb2 = new Checkbox('dom');
        $chb2->setLabel('Domingo :');
        $chb2->setUseHiddenElement(true);
        $chb2->setCheckedValue("1");
        $chb2->setUncheckedValue("0");
        $this->add($chb2);

        $chb3 = new Checkbox('seg');
        $chb3->setLabel('Segunda :');
        $chb3->setUseHiddenElement(true);
        $chb3->setCheckedValue("1");
        $chb3->setUncheckedValue("0");
        $this->add($chb3);

        $this->addElement('submit', 'submit', 'Salvar');
        $this->addElement('voltar', 'submit', 'Voltar');

    }
}

?>