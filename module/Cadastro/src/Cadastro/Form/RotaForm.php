<?php

namespace Cadastro\Form;

use Abstrato\Form\AbstractForm;
/**
 *
 * @author hlm
 *        
 */
class RotaForm extends AbstractForm
{
	public function __construct($name = null)
	{
		parent::__construct('agencia');
		$this->setAttribute('method', 'post');
		$this->addElement('codigo', 'text', 'Código :');
		$this->addElement('nome', 'text', 'Nome :');

        $agenciaOps = array('value_options' => $this->getAgenciaOptions());
        $this->addElement('agencia_id', 'select','Agencia', array(), $agenciaOps);
		
		$this->addElement('submit', 'submit', 'Salvar');
		$this->addElement('voltar', 'submit', 'Voltar');
		
	}

    private function getAgenciaOptions()
    {
        $valueOptions = array();

        $em = $GLOBALS['entityManager'];
        //Obtém todas as agencias ordenando pelo nome
        $agencias = $em->getRepository('Cadastro\Model\Agencia')->findBy(array(), array('nome'=>'ASC'));

        foreach($agencias as $a)
        {
            $valueOptions[$a->id] = $a->codigo.'-'.$a->nome;
        }
        return $valueOptions;
    }


}

