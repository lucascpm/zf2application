<?php

namespace Cadastro\Form;

use Abstrato\Form\AbstractForm;
use Zend\Form\Element\Checkbox;
/**
 *
 * @author hlm
 *        
 */
class OperadorForm extends AbstractForm
{
	public function __construct($name = null) {
		parent::__construct('operador');
		
		$this->setAttribute('method', 'post');
		$this->addElement('codigo', 'text', 'Código :');
		$this->addElement('nome', 'text', 'Nome :');
		$this->addElement('senha', 'text', 'Senha:');
		$this->addElement('limite_p_datacao', 'text', 'Limite p/ datacao :');
		
		$chb1 = new Checkbox('ativo');
		$chb1->setLabel('Ativo');
		$chb1->setUseHiddenElement(true);
		$chb1->setCheckedValue("1");
		$chb1->setUncheckedValue("0");
		$this->add($chb1);
		
		$chb2 = new Checkbox('permite_cancelar');
		$chb2->setLabel('Permite cancelar ');
		$chb2->setUseHiddenElement(true);
		$chb2->setCheckedValue("1");
		$chb2->setUncheckedValue("0");
		$this->add($chb2);

		$chb3 = new Checkbox('limita_jb');
		$chb3->setLabel('Limita Jogo');
		$chb3->setUseHiddenElement(true);
		$chb3->setCheckedValue("1");
		$chb3->setUncheckedValue("0");
		$this->add($chb3);
				
// 		
		
 		$this->addElement('limite_venda_jb', 'text', 'Limite Venda :');
 		
 		$options = array('value_options' => $this->getPontoOptions());
 		$this->addElement('ponto_id', 'select','Ponto', array(), $options);

		
		$this->addElement('submit', 'submit', 'Salvar');
		$this->addElement('voltar', 'submit', 'Voltar');
		
	}
	
	private function getPontoOptions()
	{
		$valueOptions = array();
	

		$em = $GLOBALS['entityManager'];
		$pontos = $em->getRepository('Cadastro\Model\Ponto')->findBy(array(), array('nome'=> 'ASC'));
	
		foreach($pontos as $p)
		{
			$valueOptions[$p->id] = $p->codigo.'-'.$p->nome;
		}
		return $valueOptions;
	}
}

?>