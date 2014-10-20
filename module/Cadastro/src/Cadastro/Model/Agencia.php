<?php

namespace Cadastro\Model;

use Application\Form\Validator\NumericoValidator;
use Application\Form\Validator\RealValidator;
use Doctrine\ORM\Mapping as ORM;

use Abstrato\InputFilter\InputFilter;
use Zend\Filter\HtmlEntities;
use Zend\Filter\Int;
use Zend\Filter\StringToUpper;
use Zend\Filter\StripTags;
use Zend\Filter\StringTrim;
use Zend\I18n\Validator\Float;
use Zend\Validator\Between;
use Zend\Validator\Digits;
use Zend\Validator\Regex;
use Zend\Validator\StringLength;
use Abstrato\Entity\AbstractEntity;
use Zend\I18n\Filter\NumberFormat;
use \NumberFormatter;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;

/**
 *
 *
 * @author hlm
 *
 * @Entity
 * @Table(name="agencias")
 */
class Agencia extends AbstractEntity
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	public $id;

    /** @Column(type="integer") **/
	public $codigo;
	
	/** @Column(type="string") **/
	public $nome;
	
	/** @Column(type="string") **/
	public $nome_terminal;
	
	/** @Column(type="string") **/
	public $nome_pule;
	
	/** @Column(type="decimal") **/
	public $valor_bancado;
	
	/** @Column(type="decimal") **/
	public $descarga;
	
	/** @Column(type="integer") **/
	public $cod_cliente_recarga;


    public function preparaValoresParaEdicao() {
        $this->codigo = str_pad($this->codigo, 2, '0', STR_PAD_LEFT);
        $this->valor_bancado = number_format($this->valor_bancado, 2, ',', '.');
        $this->descarga = number_format($this->descarga, 2, ',', '.');
    }

    public function getDescarga() {
        return number_format($this->descarga, 2, ',', '.');
    }
	
	public function getInputFilter()
	{
		if (!isset($this->inputFilter)) {
			$inputFilter = new InputFilter();

			$inputFilter->addInput('codigo');
            $inputFilter->addValidator('codigo', new Digits());
			$inputFilter->addValidator('codigo', new Between(array(
					'min'      => 1,
					'max'      => 99
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
			
			$inputFilter->addInput('nome_terminal');
			$inputFilter->addFilter('nome_terminal', new StripTags());
            $inputFilter->addFilter('nome_terminal', new HtmlEntities());
            $inputFilter->addFilter('nome_terminal', new StringToUpper(array('encoding' => 'UTF-8')));
			$inputFilter->addFilter('nome_terminal', new StringTrim());
			$inputFilter->addValidator('nome_terminal', new StringLength(array(
					'encoding' => 'UTF-8',
					'min'      => 1,
					'max'      => 15
			)
			)
			);
			
			$inputFilter->addInput('nome_pule');
			$inputFilter->addFilter('nome_pule', new StripTags());
			$inputFilter->addFilter('nome_pule', new HtmlEntities());
			$inputFilter->addFilter('nome_pule', new StringToUpper(array('encoding' => 'UTF-8')));
			$inputFilter->addFilter('nome_pule', new StringTrim());
			$inputFilter->addValidator('nome_pule', new StringLength(array(
					'encoding' => 'UTF-8',
					'min'      => 1,
					'max'      => 15,
			)
			)
			);
			
			// Verificar valor do addValidador para Filtro Moeda
			$inputFilter->addInput('valor_bancado');
			$inputFilter->addFilter('valor_bancado', new NumberFormat("pt_BR", NumberFormatter::PATTERN_DECIMAL));
			$inputFilter->addValidator('valor_bancado', new NumericoValidator());
			$inputFilter->addValidator('valor_bancado', new Between(array(
					'min'      => 1,
					'max'      => 200000,
			)
			)
			);
			
			// Verificar valor do addValidador para Filtro Moeda
			$inputFilter->addInput('descarga', true);
            $inputFilter->addFilter('descarga', new NumberFormat("pt_BR", NumberFormatter::PATTERN_DECIMAL));
            $inputFilter->addValidator('descarga', new  NumericoValidator());
            $inputFilter->addValidator('descarga', new Between(array(
					'min'      => 1,
					'max'      => 200000,
			)
			)
			);


				
			$inputFilter->addInput('cod_cliente_recarga', true);
			$inputFilter->addFilter('cod_cliente_recarga', new Int());
            $inputFilter->addValidator('cod_cliente_recarga', new Digits());
			$inputFilter->addValidator('cod_cliente_recarga', new Between(array(
					'min'      => 0,
					'max'      => 99999,
			)
			)
			);
		
			$this->inputFilter = $inputFilter;
			
			$this->inputFilter->addChains();
		}
		
		return $this->inputFilter;
	}

    public function exchangeArray($array)
    {
        foreach($array as $attribute => $value)
        {
//            $this->$attribute = is_string($value) ? strtoupper($value) : $value;
            //Caso o campo não tenha sido preenchido
            $this->$attribute = $value == '' ? null : $value;
        }


        if($this->descarga == '') {
            $this->descarga = null;
        }
    }
	
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
}
