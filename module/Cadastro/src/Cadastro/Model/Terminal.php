<?php

namespace Cadastro\Model;

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
 * @Table(name="terminais")
 */
class Terminal extends AbstractEntity
{
	/** @Id
	 * @Column(type="integer")  @GeneratedValue**/
	public $id;

    /** @Column(type="bigint") **/
	public $serial;
	
	/** @Column(type="string") **/
	public $versao;
	
	/** @Column(type="boolean") **/
	public $ativo;
	
	/** @Column(type="string") **/
	public $observacao;
	
	/** @Column(type="datetime") **/
	public $data_criacao;
	
	/** @Column(type="datetime") **/
	public $ultima_autenticacao;

    /** @Column(type="integer") **/
    private $prox_numero_pule;

    /**
     * @var Operador
     * @OnetoOne(targetEntity="Cadastro\Model\Operador")
     * @JoinColumn(name="operador_id", referencedColumnName="id")
     **/
    public $operador;

    public function getProxNumeroPule() {
        return $this->prox_numero_pule;
    }

    /**
     * Função que apenas encapsula o incremento do prox_numero_pule
     */
    public function incrementaNumeroPule() {
        ++$this->prox_numero_pule;
        return $this;
    }
	
	public function getInputFilter()
	{
		if (!isset($this->inputFilter)) {
			$inputFilter = new InputFilter();
			$inputFilter->addInput('serial');
			$inputFilter->addValidator('serial', new Digits());
            $inputFilter->addValidator('serial', new StringLength(array(
                        'encoding' => 'UTF-8',
                        'min'      => 12,
                        'max'      => 12,
                    )
                )
            );
//			$inputFilter->addValidator('serial', new Between(array(
//					'min'      => 1,
//					'max'      => 9999999999999
//			)
//			)
//			);
	

			$inputFilter->addInput('ativo');
			$inputFilter->addValidator('ativo', new Digits());
			$inputFilter->addValidator('ativo', new Between(array(
					'min'      => 0,
					'max'      => 1,
			)
			)
			);

            $inputFilter->addInput('observacao', true);
            $inputFilter->addFilter('observacao', new StripTags());
            $inputFilter->addFilter('observacao', new StringTrim());
            $inputFilter->addFilter('observacao', new HtmlEntities());
            $inputFilter->addValidator('observacao', new StringLength(array(
                        'encoding' => 'UTF-8',
                        'min'      => 2,
                        'max'      => 254,
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
//        if (is_array($array))
//        {
//            $this->codigo = $array['codigo'];
//            $this->descricao = strtoupper($array['descricao']);
//            $this->per_comissao = $array['per_comissao'];
//            $this->ativo = $array['ativo'];
//            $this->tamanho_impresao_pule = $array['tamanho_impresao_pule'];
//
//            $em = $GLOBALS['entityManager'];
//            $this->rota = $em->getRepository('Cadastro\Model\Rota')
//                ->find($array['rota_cod']);
//            $this->agencia = $em->getRepository('Cadastro\Model\Agencia')
//                ->find($array['agencia_cod']);
//        }
//        else
//        {
//
//        }
        foreach($array as $attribute => $value)
        {
            $this->$attribute = $value;
            if($attribute == 'data_criacao') {
                $this->$attribute = new \DateTime('now');
            }
        }
    }
}

