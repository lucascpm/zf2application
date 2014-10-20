<?php

namespace Cadastro\Model;

use Doctrine\ORM\Mapping as ORM;

use Abstrato\InputFilter\InputFilter;
use Zend\Filter\Int;
use Zend\Filter\StripTags;
use Zend\Filter\StringTrim;
use Zend\Validator\Between;
use Zend\Validator\StringLength;
use Abstrato\Entity\AbstractEntity;
use Zend\I18n\Filter\NumberFormat;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;

/**
 *
 *
 * @author ls
 *
 * @Entity
 * @Table(name="msgclienteservidor")
 */
class MsgClienteServidor extends AbstractEntity
{
    /** @Id @Column(type="bigint") @GeneratedValue **/
    public $id;

    /**
     * @Column(type="bigint")
     */
    public $codigo;

    /**
     * @var TipoMsgCliente
     * @OnetoOne(targetEntity="Cadastro\Model\TipoMsgCliente")
     * @JoinColumn(name="tipo_msg_id", referencedColumnName="id")
     **/
    public $tipoMsgCliente;

    /**
     * @var Ponto
     * @OnetoOne(targetEntity="Cadastro\Model\Ponto")
     * @JoinColumn(name="ponto_id", referencedColumnName="id")
     **/
    public $ponto;

    /**
     * @var Terminal
     * @OnetoOne(targetEntity="Cadastro\Model\Terminal")
     * @JoinColumn(name="terminal_id", referencedColumnName="id")
     **/
    public $terminal;

    /**
     * @var Operador
     * @OnetoOne(targetEntity="Cadastro\Model\Operador")
     * @JoinColumn(name="operador_id", referencedColumnName="id")
     **/
    public $operador;


    /** @Column(type="datetime") **/
    public $data_hora;

    /** @Column(type="string") **/
    public $obs;



    public function getInputFilter()
    {
        if (!isset($this->inputFilter)) {
            $inputFilter = new InputFilter();

            $inputFilter->addInput('codigo');
            $inputFilter->addFilter('codigo', new Int());
            $inputFilter->addValidator('codigo', new Between(array(
                        'min'      => 1,
                        'max'      => 999
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
            $this->$attribute = is_string($value) ? strtoupper($value) : $value;
        }

    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}
