<?php

namespace Cadastro\Model;

use Doctrine\ORM\Mapping as ORM;

use Abstrato\Entity\AbstractEntity;
use Abstrato\InputFilter\InputFilter;
use Zend\Filter\StripTags;
use Zend\Filter\StringTrim;
use Zend\Validator\StringLength;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;

/**
 *
 * @Entity(repositoryClass="Cadastro\Model\Repository\ExtracaoProgramadaRepository")
 * @Table(name="extracoesprogramadas")
 */

class ExtracaoProgramada extends AbstractEntity
{
    /** @Id @Column(type="integer") @GeneratedValue **/
    public $id;

    /** @Column(type="date") **/
    public $data_extracao;

    /**
     * @var extracao Cadastro\Model\Extracao
     * @OneToOne(targetEntity="Cadastro\Model\Extracao")
     * @JoinColumn(name="extracao_id", referencedColumnName="id")
     **/
    public $extracao;

    /** @Column(type="date") **/
    public $data_atualizacao;

    /** @Column(type="time") **/
    public $hora_bloqueio;

    /**
     * @OnetoOne(targetEntity="Cadastro\Model\Usuario")
     * @JoinColumn(name="usuario_id", referencedColumnName="id")
     **/
    public $usuario;


    public function getInputFilter()
    {
        if (!isset($this->inputFilter)) {
            $inputFilter = new InputFilter();

            $inputFilter->addInput('codigo');
            $inputFilter->addFilter('codigo', new Int());
            $inputFilter->addValidator('codigo', new Between(array(
                        'min'      => 1,
                        'max'      => 99999999
                    )
                )
            );
//Corrigir datas
            $inputFilter->addInput('data_extracao');
            $inputFilter->addFilter('data_extracao', new StripTags());
            $inputFilter->addValidator('data_extracao', new Between(array(
                        'min'      => 1,
                        'max'      => 99999999
                    )
                )
            );

            $inputFilter->addInput('data_atualizacao');
            $inputFilter->addFilter('data_atualizacao', new Int());
            $inputFilter->addValidator('data_atualizacao', new Between(array(
                        'min'      => 1,
                        'max'      => 9999
                    )
                )
            );

            $inputFilter->addInput('usuario_id');
            $inputFilter->addFilter('usuario_id', new Int());
            $inputFilter->addValidator('usuario_id', new Between(array(
                        'min'      => 1,
                        'max'      => 9999
                    )
                )
            );

            $inputFilter->addInput('extracao_cod');
            $inputFilter->addFilter('extracao_cod', new Int());
            $inputFilter->addValidator('extracao_cod', new Between(array(
                        'min'      => 1,
                        'max'      => 9999,
                    )
                )
            );
        }
    }

}

?>