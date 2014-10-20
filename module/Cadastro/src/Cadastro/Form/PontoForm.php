<?php

namespace Cadastro\Form;

use Abstrato\Form\AbstractForm;
use Zend\Form\Element\Checkbox;
/**
 * Classe base do formulário de Ponto.
 * @author hlm
 *        
 */
class PontoForm extends AbstractForm
{

	public function __construct($name = null) {
		parent::__construct('ponto');
		
		$this->setAttribute('method', 'post');
		$this->addElement('codigo', 'text', 'Código :');
		$this->addElement('nome', 'text', 'Nome :');
		
		$chb1 = new Checkbox('ativo');
		$chb1->setLabel('Ativo');
		$chb1->setUseHiddenElement(true);
		$chb1->setCheckedValue("1");
		$chb1->setUncheckedValue("0");
		$this->add($chb1);
		
		$this->addElement('per_comissao', 'text', 'Percentual Comissão :');
		
		$chb2 = new Checkbox('tamanho_impresao_pule');
		$chb2->setLabel('Impressão grande ');
		$chb2->setUseHiddenElement(true);
		$chb2->setCheckedValue("G");
		$chb2->setUncheckedValue("N");
		$this->add($chb2);
		
		$rotaOps = array('value_options' => $this->getRotaOptions());
		$this->addElement('rota_id', 'select','Rota', array(), $rotaOps);

		$this->addElement('submit', 'submit', 'Salvar');
		$this->addElement('voltar', 'submit', 'Voltar');
		
	}
	
	private function getRotaOptions() {
		$valueOptions = array();
		
		$dql = "select r from Cadastro\Model\Rota r";
		$em = $GLOBALS['entityManager'];
		$query = $em->createQuery($dql);
		$rotas = $query->getResult();
		
		foreach($rotas as $rota)
		{
			$valueOptions[$rota->id] = $rota->codigo.'-'.$rota->nome;
		}
		return $valueOptions;
	}
}

?>