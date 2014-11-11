<?php
/**
 * Created by PhpStorm.
 * User: hlm
 * Date: 03/06/14
 * Time: 20:20
 */

namespace Cadastro\Model;

use Application\Form\Validator\NumericoValidator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Abstrato\Entity\AbstractEntity;
use Zend\Filter\HtmlEntities;
use Zend\Filter\Int;
use Zend\Filter\StringToUpper;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\I18n\Filter\NumberFormat;
use Abstrato\InputFilter\InputFilter;
use Zend\Validator\Between;
use Zend\Validator\Digits;
use Zend\Validator\StringLength;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\OneToMany;


/**
 *
 * @Entity
 * @Table(name="tiposjogo")
 */
class TipoJogo extends AbstractEntity {

    /**
     * @Id @Column(type="integer") @GeneratedValue
     */
    public $id;

    /**
     * @Column(type="integer")
     */
    public $codigo;

    /**
     * @Column(type="string")
     */
    public $nome;

    /**
     * @Column(type="string")
     */
    public $sigla;

    /**
     * @Column(type="boolean")
     */
    public $ativo;

    /**
     * @Column(type="boolean")
     */
    public $descarga;


    /**
     * @Column(type="decimal")
     */
    public $premio_valor_mult;

    /**
     * @Column(type="decimal")
     */
    public $divisor_valor_aposta;


    public function getInputFilter()
    {
        if (!$this->inputFilter) {

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
                        'max'      => 41,
                    )
                )
            );

            $inputFilter->addInput('sigla');
            $inputFilter->addFilter('sigla', new StripTags());
            $inputFilter->addFilter('sigla', new StringTrim());
            $inputFilter->addFilter('sigla', new HtmlEntities());
            $inputFilter->addFilter('sigla', new StringToUpper(array('encoding' => 'UTF-8')));
            $inputFilter->addValidator('sigla', new StringLength(array(
                        'encoding' => 'UTF-8',
                        'min'      => 1,
                        'max'      => 4,
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
            $inputFilter->addInput('descarga');
            $inputFilter->addValidator('descarga', new Digits());
            $inputFilter->addValidator('descarga', new Between(array(
                        'min'      => 0,
                        'max'      => 1,
                    )
                )
            );


            $inputFilter->addInput('premio_valor_mult');
            $inputFilter->addFilter('premio_valor_mult', new NumberFormat("pt_BR", \NumberFormatter::PATTERN_DECIMAL));
            $inputFilter->addValidator('premio_valor_mult', new Between(array(
                        'min'      => 1,
                        'max'      => 15,
                    )
                )
            );
            $inputFilter->addValidator('premio_valor_mult', new NumericoValidator());

            $inputFilter->addInput('divisor_valor_aposta');
            $inputFilter->addFilter('divisor_valor_aposta', new NumberFormat("pt_BR", \NumberFormatter::PATTERN_DECIMAL));
            $inputFilter->addValidator('divisor_valor_aposta', new Between(array(
                        'min'      => 1,
                        'max'      => 15,
                    )
                )
            );
            $inputFilter->addValidator('divisor_valor_aposta', new NumericoValidator());

            $this->inputFilter = $inputFilter;
            $this->inputFilter->addChains();
        }

        return $this->inputFilter;
    }

    public function exchangeArray($array)
    {
        foreach($array as $attribute => $value)
        {
            $this->$attribute = !is_object($value) ? strtoupper($value) : $value;
        }

    }
}