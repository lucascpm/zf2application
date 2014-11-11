<?php

namespace Cadastro\Model;

use Application\Form\Validator\NumericoValidator;
use Doctrine\ORM\Mapping as ORM;

use Abstrato\InputFilter\InputFilter;
use Zend\Filter\HtmlEntities;
use Zend\Filter\Int;
use Zend\Filter\StringToUpper;
use Zend\Validator\Between;
use Zend\Filter\StripTags;
use Zend\Filter\StringTrim;
use Zend\Validator\Digits;
use Zend\Validator\StringLength;
use Abstrato\Entity\AbstractEntity;
use Zend\I18n\Filter\NumberFormat;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;

/**
 * 
 * @author hlm
 * @Entity
 * @Table(name="operadores")
 */
class Operador extends AbstractEntity
{
	/** @Id
	 * @Column(type="integer") @GeneratedValue **/
	public $id;

    /** @Column(type="integer") **/
	public $codigo;
	
	/** @Column(type="string") **/
	public $nome;	
	
	/** @Column(type="string") **/
	public $senha;
	
	/** @Column(type="decimal") **/
	public $limite_p_datacao;
	
	/** @Column(type="boolean") **/
	public $ativo;
	
	/** @Column(type="boolean") **/
	public $permite_cancelar;
	
	/** @Column(type="boolean") **/
	public $limita_jb;
	
	/** @Column(type="decimal") **/
	public $limite_venda_jb;
	
	/**
	 * 
	 * @var Ponto
	 * /**
	 * @OnetoOne(targetEntity="Ponto")
	 * @JoinColumn(name="ponto_id", referencedColumnName="id")
	 **/
	public $ponto;

	
	
	public function getInputFilter()
	{
		if (!isset($this->inputFilter)) {
			$inputFilter = new InputFilter();
			
			$inputFilter->addInput('codigo');
			$inputFilter->addValidator('codigo', new Digits());
			$inputFilter->addValidator('codigo', new Between(array(
					'min'      => 1,
					'max'      => 99999999
			)
			)
			);
			
			$inputFilter->addInput('nome');
			$inputFilter->addFilter('nome', new StripTags());
			$inputFilter->addFilter('nome', new StringTrim());
            $inputFilter->addFilter('nome', new HtmlEntities());
            $inputFilter->addFilter('nome', new StringToUpper(array('encoding' => 'UTF-8')));
			$inputFilter->addValidator('nome', new StringLength(array(
					'encoding' => 'UTF-8',
					'min'      => 2,
					'max'      => 30,
			)
			)
			);
			
			$inputFilter->addInput('senha');
			$inputFilter->addValidator('senha', new Digits());
			$inputFilter->addValidator('senha', new Between(array(
					'min'      => 1,
					'max'      => 9999
			)
			)
			);
		
						
			// Verificar valor do addValidador para Filtro Moeda
			$inputFilter->addInput('limite_p_datacao');
			$inputFilter->addFilter('limite_p_datacao',new NumberFormat("pt_BR", \NumberFormatter::PATTERN_DECIMAL));
			$inputFilter->addValidator('limite_p_datacao',new NumericoValidator());
			$inputFilter->addValidator('limite_p_datacao', new StringLength(array(
					'encoding' => 'UTF-8',
					'min'      => 1,
					'max'      => 15,
			)
			)
			);
			
			// Verificar filtro para BOOLEAN
			$inputFilter->addInput('ativo');
			$inputFilter->addValidator('ativo', new Digits());
			$inputFilter->addValidator('ativo', new Between(array(
					'min'      => 0,
					'max'      => 1,
			)
			)
			);
			
			// Verificar filtro para BOOLEAN
			$inputFilter->addInput('permite_cancelar');
			$inputFilter->addValidator('permite_cancelar', new Digits());
			$inputFilter->addValidator('permite_cancelar', new Between(array(
					'min'      => 0,
					'max'      => 1,
			)
			)
			);
			
			$inputFilter->addInput('limita_jb');
			$inputFilter->addValidator('limita_jb', new Digits());
			$inputFilter->addValidator('limita_jb', new Between(array(
					'min'      => 0,
					'max'      => 1,
			)
			)
			);
			
			$inputFilter->addInput('limite_venda_jb', true);
			$inputFilter->addFilter('limite_venda_jb', new NumberFormat("pt_BR", \NumberFormatter::PATTERN_DECIMAL));
			$inputFilter->addValidator('limite_venda_jb', new NumericoValidator());
			$inputFilter->addValidator('limite_venda_jb', new StringLength(array(
					'encoding' => 'UTF-8',
					'min'      => 1,
					'max'      => 15,
			)
			)
			);

			/**
             * TODO
			 * @task Implementar validação com verificação de pontos existentes no banco
			 */
 			$inputFilter->addInput('ponto_id');
 			$inputFilter->addValidator('ponto_id', new Digits());
 			$inputFilter->addValidator('ponto_id', new Between(array(
 					'min'      => 1,
 					'max'      => 99999999,
 			)));
			
			$this->inputFilter = $inputFilter;
			
			$this->inputFilter->addChains();
		}
		
		
		
		return $this->inputFilter;
	}
	
	public function exchangeArray($array) {
		if (is_array($array))
		{
			$this->codigo = $array['codigo'];
			$this->nome = strtoupper($array['nome']);
			$this->senha = $array['senha'];
			$this->ativo = $array['ativo'];
			$this->permite_cancelar = $array['permite_cancelar'];
			$this->limite_p_datacao = $array['limite_p_datacao'];
			$this->limita_jb = $array['limita_jb'];
			$this->limite_venda_jb = $array['limite_venda_jb'] == '' ? null : $array['limite_venda_jb'];
			
			$em = $GLOBALS['entityManager'];
			$this->ponto = $em->getRepository('Cadastro\Model\Ponto')
			->find($array['ponto_id']);
		}
		else
		{
            foreach($array as $chave => $valor) {
			    $this->$chave = $valor == '' ? null : $valor;
            }
		}
	}
	

}

