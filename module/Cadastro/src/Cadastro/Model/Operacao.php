<?php

namespace Cadastro\Model;

//use Doctrine\ORM\Mapping as ORM;
//
//use Abstrato\InputFilter\InputFilter;
//use Abstrato\Model\AbstractModel;
//use Zend\Filter\Int;
//use Zend\Filter\StripTags;
//use Zend\Filter\StringTrim;
//use Zend\Validator\Between;
//use Zend\Validator\StringLength;
use Abstrato\Entity\AbstractEntity;

//use Zend\I18n\Filter\NumberFormat;

///**
// *
// *
// * @author Zorro
// *
// * @ORM\Entity
// * @ORM\Table(name="agencias")

class Cadastros extends AbstractEntity
{
//	/** @ORM\Id @ORM\Column(type="integer") **/
	public $codigo;
//
//	/** @ORM\Column(type="string") **/
	public $nome;
//
//	/** @ORM\Column(type="string") **/
//	public $nome_terminal;
//
//	/** @ORM\Column(type="string") **/
//	public $nome_pule;
//
//	/** @ORM\Column(type="decimal") **/
//	public $valor_bancado;
//
//	/** @ORM\Column(type="decimal") **/
//	public $descarga;
//
//	/** @ORM\Column(type="integer") **/
//	public $cod_cliente_recarga;
//
//
//
//	public function getCodigo() {
//		return $this->codigo;
//	}
//	public function setCodigo($c) {
//		$this->codigo = $c;
//	}
//
//	public function getNome() {
//		return $this->nome;
//	}
//	public function setNome($c) {
//		$this->nome = $c;
//	}
//
//	public function getNome_terminal() {
//		return $this->nome_terminal;
//	}
//	public function setNome_terminal($c) {
//		$this->nome_terminal = $c;
//	}
//
//	public function getNome_pule() {
//		return $this->nome_pule;
//	}
//	public function setNome_pule($c) {
//		$this->nome_pule = $c;

    public function getInputFilter()
    {
        // TODO: Implement getInputFilter() method.
    }


//
//
//
//	public function getInputFilter()
//	{
//		if (!isset($this->inputFilter)) {
//			$inputFilter = new InputFilter();
//			$inputFilter->addInput('codigo');
//			$inputFilter->addFilter('codigo', new Int());
//			$inputFilter->addValidator('codigo', new Between(array(
//					'min'      => 1,
//					'max'      => 99
//			)
//			)
//			);
//
//			$inputFilter->addInput('nome');
//			$inputFilter->addFilter('nome', new StripTags());
//			$inputFilter->addFilter('nome', new StringTrim());
//			$inputFilter->addValidator('nome', new StringLength(array(
//					'encoding' => 'UTF-8',
//					'min'      => 2,
//					'max'      => 30,
//			)
//			)
//			);
//
//			$inputFilter->addInput('nome_terminal');
//			$inputFilter->addFilter('nome_terminal', new StripTags());
//			$inputFilter->addFilter('nome_terminal', new StringTrim());
//			$inputFilter->addValidator('nome_terminal', new StringLength(array(
//					'encoding' => 'UTF-8',
//					'min'      => 1,
//					'max'      => 15
//			)
//			)
//			);
//
//			$inputFilter->addInput('nome_pule');
//			$inputFilter->addFilter('nome_pule', new StripTags());
//			$inputFilter->addFilter('nome_pule', new StringTrim());
//			$inputFilter->addValidator('nome_pule', new StringLength(array(
//					'encoding' => 'UTF-8',
//					'min'      => 1,
//					'max'      => 15,
//			)
//			)
//			);
//
//			// Verificar valor do addValidador para Filtro Moeda
//			$inputFilter->addInput('valor_bancado');
//			$inputFilter->addFilter('valor_bancado', new NumberFormat());
//			$inputFilter->addFilter('valor_bancado', new NumberFormat());
//			$inputFilter->addValidator('valor_bancado', new Between(array(
//					'min'      => 1,
//					'max'      => 200000,
//			)
//			)
//			);
//
//			// Verificar valor do addValidador para Filtro Moeda
//			$inputFilter->addInput('descarga');
//			$inputFilter->addFilter('descarga', new NumberFormat());
//			$inputFilter->addFilter('descarga', new NumberFormat());
//			$inputFilter->addValidator('descarga', new Between(array(
//					'min'      => 1,
//					'max'      => 200000,
//			)
//			)
//			);
//
//			$inputFilter->addInput('cod_cliente_recarga');
//			$inputFilter->addFilter('cod_cliente_recarga', new Int());
//			$inputFilter->addFilter('cod_cliente_recarga', new Int());
//			$inputFilter->addValidator('cod_cliente_recarga', new Between(array(
//					'min'      => 0,
//					'max'      => 99999,
//			)
//			)
//			);
//
//			$this->inputFilter = $inputFilter;
//
//			$this->inputFilter->addChains();
//		}
//
//		return $this->inputFilter;
//	}
//
	
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
}