<?php

namespace Cadastro\Form;

use Abstrato\Form\AbstractForm;
use Zend\Form\Element\Checkbox;
use Zend\Form\Element\Time;

class ExtracaoForm extends AbstractForm
{
	public function __construct($name = null)
	{
		parent::__construct('extracaoForm');
		$this->setAttribute('method', 'post');
		$this->addElement('nome', 'text', 'Nome :');

        $this->addElement('horario','time','Horário :', array(), array(), true);
		$this->addElement('hora_bloqueio', 'time', 'Hora Bloqueio :', array(), array(), true);
		$this->addElement('hora_bloqueio_descarga', 'text', 'Hora Bloqueio Descarga :');
		$this->addElement('ordem', 'text', 'Ordem :');

        $chb1 = new Checkbox('corre_descarga');
        $chb1->setLabel('Corre Descarga');
        $chb1->setUseHiddenElement(true);
        $chb1->setCheckedValue("1");
        $chb1->setUncheckedValue("0");
        $this->add($chb1);

        $dias = array('dom', 'seg', 'ter', 'qua', 'qui', 'sex','sab');

        foreach($dias as $dia) {
            $chb1 = new Checkbox($dia);
            $chb1->setLabel(ucfirst($dia));
            $chb1->setUseHiddenElement(true);
            $chb1->setCheckedValue("1");
            $chb1->setUncheckedValue("0");
            $this->add($chb1);
        }



		$this->addElement('submit', 'submit', 'Salvar');
		$this->addElement('voltar', 'submit', 'Voltar');
	
	}
}

?>