<?php

namespace Relatorio\Model;

use Doctrine\ORM\Mapping as ORM;

use Abstrato\InputFilter\InputFilter;
use Abstrato\Model\AbstractModel;
use Zend\Filter\Int;
use Zend\Filter\StripTags;
use Zend\Filter\StringTrim;
use Zend\Validator\Between;
use Zend\Validator\StringLength;
use Abstrato\Entity\AbstractEntity;
use Zend\I18n\Filter\NumberFormat;

/**
 *
 *
 * @author zorro
 *
 * @ORM\Entity
 * @ORM\Table(name="pules")
 */
class Guia extends AbstractEntity
{
    /**
     * @var Ponto
     * @ORM\OnetoOne(targetEntity="Cadastro\Model\Ponto")
     * @ORM\JoinColumn(name="ponto_cod", referencedColumnName="codigo")
     **/
    public $ponto;

    /**
     * @var extracao Cadastro\Model\Extracao
     * @ORM\OneToOne(targetEntity="Cadastro\Model\Extracao")
     * @ORM\JoinColumn(name="extracao_cod", referencedColumnName="codigo")
     **/
    public $extracao;

	/** @ORM\Column(type="money") **/
	public $total;
	
	/** @ORM\Column(type="integer") **/
	public $per_comissao;


	public function getInputFilter()
	{
//		if (!isset($this->inputFilter)) {
//			$inputFilter = new InputFilter();
//			$inputFilter->addInput('codigo');
//			$inputFilter->addFilter('codigo', new Int());
//			$inputFilter->addValidator('codigo', new Between(array(
//					'min'      => 1,
//					'max'      => 99999999999
//			)
//			)
//			);
//
//			$this->inputFilter = $inputFilter;
//
//			$this->inputFilter->addChains();
//		}

		return $this->inputFilter;
	}


	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
}
