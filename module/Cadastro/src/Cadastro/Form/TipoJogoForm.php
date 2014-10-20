<?php
/**
 * Created by PhpStorm.
 * User: hlm
 * Date: 03/06/14
 * Time: 21:05
 */

namespace Cadastro\Form;


use Abstrato\Form\AbstractForm;
use Zend\Form\Element\Checkbox;

class TipoJogoForm extends AbstractForm
{

    public function __construct($name = null) {
        parent::__construct('tipo_jogo');

        $this->setAttribute('method', 'post');
        $this->addElement('codigo', 'text', 'Código :');
        $this->addElement('nome', 'text', 'Nome :');
        $this->addElement('sigla', 'text', 'Sigla :');

        $chb1 = new Checkbox('ativo');
        $chb1->setLabel('Ativo ');
        $chb1->setUseHiddenElement(true);
        $chb1->setCheckedValue("1");
        $chb1->setUncheckedValue("0");
        $this->add($chb1);

        $chb2 = new Checkbox('descarga');
        $chb2->setLabel('Descarga ');
        $chb2->setUseHiddenElement(true);
        $chb2->setCheckedValue("1");
        $chb2->setUncheckedValue("0");
        $this->add($chb2);

        $this->addElement('premio_valor_mult', 'text', 'Multiplicador do Valor do Prêmio :');
        $this->addElement('aposta_valor_mult', 'text', 'Multiplicador do Valor de Aposta :');

        $this->addElement('submit', 'submit', 'Salvar');
        $this->addElement('voltar', 'submit', 'Voltar');

    }

} 