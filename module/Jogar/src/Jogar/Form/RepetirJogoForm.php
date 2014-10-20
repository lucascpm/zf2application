<?php

namespace Jogar\Form;

use Abstrato\Form\AbstractForm;
use Zend\Form\Element;
/**
 *
 * @LS
 *        
 */
class RepetirJogoForm extends AbstractForm
{
	public function __construct($name = null)
	{
		parent::__construct('premio');
		$this->setAttribute('method', 'get');

        /* Número da Pule */
        $numPule = new Element('numPule');
        $numPule->setLabel('Número da Pule: ');
        $numPule->setAttributes(array(
            'type'  => 'label'
        ));
        $this->add($numPule);

        /* Terminal */
        $terminal = new Element('terminal');
        $terminal->setLabel('Terminal: ');
        $terminal->setAttributes(array(
            'type'  => 'label'
        ));
        $this->add($terminal);

		/*** Data ***/
		$this->addElement('data', 'text', 'Data: ', array('id' => 'data',
                                                                      'required'=>'true',
                                                                      'autocomplete'=>'off'));

        /** Extração **/
        $extracaoOps = array('value_options' => $this->getExtracaoOptions());
        $this->addElement('extracao', 'select', 'Extração :', array(), $extracaoOps);

		$this->addElement('repetirjogo', 'submit', 'Repetir Jogo');
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
