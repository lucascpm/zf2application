<?php

namespace Relatorio\Form;

use Abstrato\Form\AbstractForm;
use Zend\Form\Element;
/**
 *
 * @LS
 *        
 */
class ResultadoForm extends AbstractForm
{
	public function __construct($name = null)
	{
		parent::__construct('premio');
		$this->setAttribute('method', 'get');

        /*** Data ***/
        $this->addElement('data', 'text', 'Data:', array('id' => 'data',
            'required'=>'true',
            'autocomplete'=>'off'));

        /** Extração **/
        $extracaoOps = array('value_options' => $this->getExtracaoOptions());
        $this->addElement('extracao', 'select', 'Extração :', array(), $extracaoOps);

        /* Quantidade de cópias */
        $qtdeCopias = new Element('qtdeCopia');
        $qtdeCopias->setLabel('Quantidade de Cópias: ');
        $qtdeCopias->setAttributes(array(
            'type'  => 'text'
        ));
        $this->add($qtdeCopias);

        //TODO Pedir confirmação do cancelamento
		$this->addElement('pesquisar', 'submit', 'Pesquisar');
	}

    private function getExtracaoOptions() {
        $valueOptions = array();


        $em = $GLOBALS['entityManager'];
        $result = $em->getRepository('Cadastro\Model\Extracao')->findAll();

        foreach($result as $o)
        {
            $valueOptions[$o->id] = $o->nome;
        }

        return $valueOptions;
    }

}
