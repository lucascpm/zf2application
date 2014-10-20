<?php

namespace Cadastro\Model;

use Doctrine\ORM\Mapping as ORM;

use Abstrato\InputFilter\InputFilter;
use Zend\Filter\HtmlEntities;
use Zend\Filter\Int;
use Zend\Filter\StringToUpper;
use Zend\Filter\StripTags;
use Zend\Filter\StringTrim;
use Zend\Validator\Between;
use Zend\Validator\Digits;
use Zend\Validator\StringLength;
use Abstrato\Entity\AbstractEntity;

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
 *
 * @Entity
 * @Table(name="rotas")
 */
class Rota extends AbstractEntity
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	public $id;

    /** @Column(type="integer") **/
	public $codigo;
	
	/** @Column(type="string") **/
	public $nome;

    /**
     *
     * @var Agencia
     * /**
     * @OnetoOne(targetEntity="Agencia")
     * @JoinColumn(name="agencia_id", referencedColumnName="id")
     **/
    public $agencia;

	
	public function getInputFilter()
	{
		if (!isset($this->inputFilter)) {
			
			$inputFilter = new InputFilter();
			$inputFilter->addInput('codigo');
			$inputFilter->addValidator('codigo', new Digits());
			$inputFilter->addValidator('codigo', new Between(array(
					'min'      => 1,
					'max'      => 9999
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
	
			$this->inputFilter = $inputFilter;
			$this->inputFilter->addChains();
		}
	
		return $this->inputFilter;
	}

    public function exchangeArray($array)
    {
//        foreach($array as $attribute => $value)
//        {
//            var_dump($value);
//            $this->$attribute = strtoupper($value);
//        }

        if(is_object($array)) {
            $this->codigo = $array->codigo;
            $this->nome = $array->nome;
        }
        else {
            $this->codigo = $array['codigo'];
            $this->nome = $array['nome'];
            $this->agencia = $GLOBALS['entityManager']->getRepository('Cadastro\Model\Agencia')->find($array['agencia_id']);
        }


    }
}

