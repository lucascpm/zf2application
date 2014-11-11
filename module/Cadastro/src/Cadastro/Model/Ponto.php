<?php

namespace Cadastro\Model;

use Application\Form\Validator\NumericoValidator;
use Doctrine\ORM\Mapping as ORM;

// use Zend\InputFilter\InputFilter;
// use Zend\InputFilter\Factory as InputFactory;
use Abstrato\InputFilter\InputFilter;
use Zend\Filter\HtmlEntities;
use Zend\Filter\Int;
use Zend\Filter\StringToUpper;
use Zend\Validator\Between;
use Zend\Filter\StripTags;
use Zend\Filter\StringTrim;
use Zend\Validator\Digits;
use Zend\Validator\Regex;
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
 * @Entity(repositoryClass="Cadastro\Model\Repository\PontoRepository")
 * @Table(name="pontos")
 */
class Ponto extends AbstractEntity
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	public $id;

    /** @Column(type="integer") **/
	public $codigo;
	
	/** @Column(type="string") **/
	public $nome;
	
	/**
	 *
	 * @var Rota
	 * /**
	 * @OnetoOne(targetEntity="Rota")
	 * @JoinColumn(name="rota_id", referencedColumnName="id")
	 **/
	public $rota;

	/** @Column(type="boolean") **/
	public $ativo;
	
	/** @Column(type="decimal") **/
	public $per_comissao;

    /** @Column(type="decimal") **/
    public $per_comissao_especial;
	
	/** @Column(type="string") **/
	public $tamanho_imp_pule;
	
	
	public function exchangeArray($array) {

		if (is_array($array))
		{

			$this->codigo = $array['codigo'];
			$this->nome = strtoupper($array['nome']);
			$this->per_comissao = $array['per_comissao'];
			$this->per_comissao_especial = $array['per_comissao_especial'] == '' ? null : $array['per_comissao_especial'];
			$this->ativo = $array['ativo'];
			$this->tamanho_imp_pule = $array['tamanho_imp_pule'];

			$em = $GLOBALS['entityManager'];
			$this->rota = $em->getRepository('Cadastro\Model\Rota')->find($array['rota_id']);
		}
		else
		{
            foreach($array as $attribute => $value)
            {
                $this->$attribute = $value == '' ? null : $value;
            }
		}
	}
	
	public function getInputFilter()
	{
		if (!isset($this->inputFilter)) {
			
			$inputFilter = new InputFilter();
			$inputFilter->addInput('codigo');
			$inputFilter->addValidator('codigo', new Digits());
			$inputFilter->addValidator('codigo', new Between(array(
					'min'      => 1,
					'max'      => 999999
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
					'max'      => 50,
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

			$inputFilter->addInput('rota_id');
			$inputFilter->addValidator('rota_id', new Digits());
			$inputFilter->addValidator('rota_id', new Between(array(
					'min'      => 1,
					'max'      => 9999,
			)
			)
			);
			

			$inputFilter->addInput('per_comissao');
			$inputFilter->addFilter('per_comissao', new NumberFormat("pt_BR", \NumberFormatter::PATTERN_DECIMAL));
			$inputFilter->addValidator('per_comissao', new NumericoValidator() );
			$inputFilter->addValidator('per_comissao', new StringLength(array(
					'encoding' => 'UTF-8',
					'min'      => 1,
					'max'      => 10,
			)
			)
			);

            $inputFilter->addInput('per_comissao_especial', true);
            $inputFilter->addFilter('per_comissao_especial', new NumberFormat("pt_BR", \NumberFormatter::PATTERN_DECIMAL));
            $inputFilter->addValidator('per_comissao_especial', new NumericoValidator() );
            $inputFilter->addValidator('per_comissao_especial', new StringLength(array(
                        'encoding' => 'UTF-8',
                        'min'      => 1,
                        'max'      => 10,
                    )
                )
            );
			
			
			// Verificar filtro para BOOLEAN
			$inputFilter->addInput('tamanho_imp_pule');
            $inputFilter->addFilter('tamanho_imp_pule', new StripTags());
            $inputFilter->addFilter('tamanho_imp_pule', new StringTrim());
            $inputFilter->addFilter('tamanho_imp_pule', new StringToUpper(array('encoding' => 'UTF-8')));
            $inputFilter->addValidator('tamanho_imp_pule', new StringLength(array(
                        'encoding' => 'UTF-8',
                        'min'      => 1,
                        'max'      => 1,
                    )
                )
            );
			
			$this->inputFilter = $inputFilter;
				
			$this->inputFilter->addChains();
		}
	
		return $this->inputFilter;
	}
	
}
