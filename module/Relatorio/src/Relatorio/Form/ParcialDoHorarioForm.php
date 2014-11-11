<?php

namespace Relatorio\Form;

use Abstrato\Form\AbstractForm;
use Zend\Form\Element;
/**
 *
 * @LS
 *        
 */
class ParcialDoHorarioForm extends AbstractForm
{
	public function __construct($name = null)
	{
		parent::__construct('premio');
		$this->setAttribute('method', 'get');

        /** Extração **/
        $extracaoOps = array('value_options' => $this->getExtracaoOptions());
        $this->addElement('extracao', 'select', 'Extração :', array(), $extracaoOps);

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
