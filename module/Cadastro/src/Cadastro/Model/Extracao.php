<?php

namespace Cadastro\Model;

use Abstrato\InputFilter\InputFilter;
use Doctrine\ORM\Mapping as ORM;

use Abstrato\Entity\AbstractEntity;
use Zend\Filter\HtmlEntities;
use Zend\Filter\Int;
use Zend\Filter\StringToUpper;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Stdlib\DateTime;
use Zend\Validator\Between;
use Zend\Validator\Date;
use Zend\Validator\Digits;
use Zend\Validator\StringLength;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;

/**
 *
 * @Entity(repositoryClass="Cadastro\Model\Repository\ExtracaoRepository")
 * @Table(name="extracoes")
 */
class Extracao extends AbstractEntity
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    public $id;

    /**
     * @Column(type="string")
     */
    public $nome;

    /**
     * @Column(type="time")
     */
    public $horario;

    /**
     * @Column(type="time")
     */
    public $hora_bloqueio;

    /**
     * @Column(type="time")
     */
    public $hora_bloqueio_descarga;

    /**
     * @Column(type="integer")
     */
    public $ordem;

    /**
     * @Column(type="boolean")
     */
    public $corre_descarga;

    /**
     * @Column(type="boolean")
     */
    public $dom;

    /**
     * @Column(type="boolean")
     */
    public $seg;

    /**
     * @Column(type="boolean")
     */
    public $ter;

    /**
     * @Column(type="boolean")
     */
    public $qua;

    /**
     * @Column(type="boolean")
     */
    public $qui;

    /**
     * @Column(type="boolean")
     */
    public $sex;

    /**
     * @Column(type="boolean")
     */
    public $sab;



    public function getInputFilter() {
        if (!$this->inputFilter) {

            $inputFilter = new InputFilter();

            $inputFilter->addInput('nome');
            $inputFilter->addFilter('nome', new StripTags());
            $inputFilter->addFilter('nome', new StringTrim());
            $inputFilter->addFilter('nome', new HtmlEntities());
            $inputFilter->addFilter('nome', new StringToUpper(array('encoding' => 'UTF-8')));
            $inputFilter->addValidator('nome', new StringLength(array(
                        'encoding' => 'UTF-8',
                        'min'      => 2,
                        'max'      => 20,
                    )
                )
            );

            $inputFilter->addInput('ordem');
            $inputFilter->addValidator('ordem', new Digits());
            $inputFilter->addValidator('ordem', new Between(array(
                        'min'      => 0,
                        'max'      => 999,
            )));

            $inputFilter->addInput('horario');
            $inputFilter->addFilter('horario', new StripTags());
            $inputFilter->addFilter('horario', new StringTrim());
            $inputFilter->addValidator('horario', new Date(array('format'=>'H:i')));

            $inputFilter->addInput('hora_bloqueio');
            $inputFilter->addFilter('hora_bloqueio', new StripTags());
            $inputFilter->addFilter('hora_bloqueio', new StringTrim());
            $inputFilter->addValidator('hora_bloqueio', new Date(array('format'=>'H:i')));

            $inputFilter->addInput('corre_descarga');
            $inputFilter->addValidator('corre_descarga', new Digits());
            $inputFilter->addValidator('corre_descarga', new Between(array(
                'min'      => 0,
                'max'      => 1,
            )));

            $inputFilter->addInput('hora_bloqueio_descarga', true);
            $inputFilter->addFilter('hora_bloqueio_descarga', new StripTags());
            $inputFilter->addFilter('hora_bloqueio_descarga', new StringTrim());
            $inputFilter->addValidator('hora_bloqueio_descarga', new Date(array('format'=>'H:i')));

            //Dom
            $inputFilter->addInput('dom');
            $inputFilter->addValidator('dom', new Digits());
            $inputFilter->addValidator('dom', new Between(array(
                'min'      => 0,
                'max'      => 1,
            )));
            //Seg
            $inputFilter->addInput('seg');
            $inputFilter->addValidator('seg', new Digits());
            $inputFilter->addValidator('seg', new Between(array(
                'min'      => 0,
                'max'      => 1,
            )));
            //Ter
            $inputFilter->addInput('ter');
            $inputFilter->addValidator('ter', new Digits());
            $inputFilter->addValidator('ter', new Between(array(
                'min'      => 0,
                'max'      => 1,
            )));
            //Qua
            $inputFilter->addInput('qua');
            $inputFilter->addValidator('qua', new Digits());
            $inputFilter->addValidator('qua', new Between(array(
                'min'      => 0,
                'max'      => 1,
            )));
            //Qui
            $inputFilter->addInput('qui');
            $inputFilter->addValidator('qui', new Digits());
            $inputFilter->addValidator('qui', new Between(array(
                'min'      => 0,
                'max'      => 1,
            )));
            //Sex
            $inputFilter->addInput('sex');
            $inputFilter->addValidator('sex', new Digits());
            $inputFilter->addValidator('sex', new Between(array(
                'min'      => 0,
                'max'      => 1,
            )));
            //Sab
            $inputFilter->addInput('sab');
            $inputFilter->addValidator('sab', new Digits());
            $inputFilter->addValidator('sab', new Between(array(
                'min'      => 0,
                'max'      => 1,
            )));

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


        if(is_array($array)) {
            $h = $array['horario'];
            $this->horario = new DateTime($h);

            $h = $array['hora_bloqueio'];
            $this->hora_bloqueio = new DateTime($h);

            //TODO Resolver problema de validador não obrigatório para hora_bloqueio_descarga
            if($array['hora_bloqueio_descarga'] == '') {
                $this->hora_bloqueio_descarga = null;
            } else {
                $h = $array['hora_bloqueio_descarga'];
                //Testa, a grosso modo, se a string digitada é uma hora válida (contém no mínimo um ':' na posição 2
                if(strpos($array['hora_bloqueio_descarga'], ':') == 2) {
                    $this->hora_bloqueio_descarga = new DateTime($h);
                } else {
                    $this->hora_bloqueio_descarga = null;
                }
            }
        }

    }

    public function getArrayCopy() {
        $array = get_object_vars($this);
        //TODO Resolver problema de validador não obrigatório para hora_bloqueio_descarga
        if($array['hora_bloqueio_descarga'] instanceof \DateTime) {
            $array['hora_bloqueio_descarga'] = $array['hora_bloqueio_descarga']->format('H:i');
        }
        return $array;
    }
}

