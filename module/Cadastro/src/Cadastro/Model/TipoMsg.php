<?php

namespace Configuracao\Model;

use Doctrine\ORM\Mapping as ORM;

use Abstrato\InputFilter\InputFilter;
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
 * @author ha
 *
 * @ORM\Entity
 * @ORM\Table(name="agencias")
 */
class TipoMsg extends AbstractEntity
{
    /** @ORM\Id @ORM\Column(type="integer") **/
    public $codigo;

    /** @ORM\Column(type="datetime") **/
    public $vigencia_ini;

    /** @ORM\Column(type="datetime") **/
    public $vigencia_fim;

    /** @ORM\Column(type="smallint") **/
    public $tipo_msg;

    /**
     * @ORM\OnetoOne(targetEntity="Cadastro\Model\Pontos")
     * @ORM\JoinColumn(name="ponto_cod", referencedColumnName="codigo")
     * @ORM\Column(type="integer")
     **/
    public $ponto;

    /**
     * @ORM\OnetoOne(targetEntity="Cadastro\Model\Rotas")
     * @ORM\JoinColumn(name="rota_cod", referencedColumnName="codigo")
     * @ORM\Column(type="smallint")
     **/
    public $rota;

    /** @ORM\Column(type="smallint") **/
    public $tipo_imp;

    /** @ORM\Column(type="string") **/
    public $msg;


    public function getInputFilter()
    {
        if (!isset($this->inputFilter)) {
            $inputFilter = new InputFilter();
            $inputFilter->addInput('codigo');
            $inputFilter->addFilter('codigo', new Int());
            $inputFilter->addValidator('codigo', new Between(array(
                        'min'      => 1,
                        'max'      => 99
                    )
                )
            );

            //Alterar Vigência Inicial e Final para data
            $inputFilter->addInput('vigencia_inicial');
            $inputFilter->addFilter('vigencia_inicial', new StripTags());
            $inputFilter->addFilter('vigencia_inicial', new StringTrim());
            $inputFilter->addValidator('vigencia_inicial', new StringLength(array(
                        'encoding' => 'UTF-8',
                        'min'      => 2,
                        'max'      => 30,
                    )
                )
            );

            $inputFilter->addInput('vigencia_final');
            $inputFilter->addFilter('vigencia_final', new StripTags());
            $inputFilter->addFilter('vigencia_final', new StringTrim());
            $inputFilter->addValidator('vigencia_final', new StringLength(array(
                        'encoding' => 'UTF-8',
                        'min'      => 1,
                        'max'      => 15
                    )
                )
            );

            $inputFilter->addInput('tipo_mensagem');
            $inputFilter->addFilter('tipo_mensagem', new Int());
            $inputFilter->addFilter('tipo_mensagem', new Int());
            $inputFilter->addValidator('tipo_mensagem', new Between(array(
                        'min'      => 0,
                        'max'      => 10, //Alterar para a quantidade de tipos de mensagem
                    )
                )
            );

            $inputFilter->addInput('ponto');
            $inputFilter->addFilter('ponto', new Int());
            $inputFilter->addFilter('ponto', new Int());
            $inputFilter->addValidator('ponto', new Between(array(
                        'min'      => 0,
                        'max'      => 999999, //Alterar para o número máximo
                    )
                )
            );

            $inputFilter->addInput('rota');
            $inputFilter->addFilter('rota', new Int());
            $inputFilter->addFilter('rota', new Int());
            $inputFilter->addValidator('rota', new Between(array(
                        'min'      => 0,
                        'max'      => 999999, //Alterar para o número máximo
                    )
                )
            );

            $inputFilter->addInput('tipo_impressao');
            $inputFilter->addFilter('tipo_impressao', new Int());
            $inputFilter->addFilter('tipo_impressao', new Int());
            $inputFilter->addValidator('tipo_impressao', new Between(array(
                        'min'      => 0,
                        'max'      => 2, //0: Todos, 1: Visor, 2: Papel
                    )
                )
            );

            $this->inputFilter = $inputFilter;

            $this->inputFilter->addChains();
        }

        return $this->inputFilter;
    }


    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}
