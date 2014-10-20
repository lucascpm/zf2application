<?php

namespace Cadastro\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Abstrato\InputFilter\InputFilter;
use Zend\Filter\Int;
use Zend\Validator\Between;
use Abstrato\Entity\AbstractEntity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;

/**
 *
 *
 * @author hlm
 *
 * @Entity(repositoryClass="Cadastro\Model\Repository\PuleCanceladaRepository")
 * @Table(name="pulescanceladas")
 */
class PuleCancelada extends AbstractEntity
{
    /** @Id @Column(type="bigint") @GeneratedValue **/
    public $id;

    /**
     * @var Usuario
     * @OnetoOne(targetEntity="Cadastro\Model\Usuario")
     * @JoinColumn(name="usuario_id", referencedColumnName="id")
     **/
    public $usuario;

    /** @Column(type="integer") **/
    public $numero;

    /** @Column(type="boolean") **/
    public $invalidada;

    /**
     * @var Agencia
     * @OnetoOne(targetEntity="Cadastro\Model\Agencia")
     * @JoinColumn(name="agencia_id", referencedColumnName="id")
     **/
    public $agencia;

    /**
     * @var Rota
     * @OnetoOne(targetEntity="Cadastro\Model\Rota")
     * @JoinColumn(name="rota_id", referencedColumnName="id")
     **/
    public $rota;

    /**
     * @var Ponto
     * @OnetoOne(targetEntity="Cadastro\Model\Ponto")
     * @JoinColumn(name="ponto_id", referencedColumnName="id")
     **/
    public $ponto;

    /**
     * @var Operador
     * @OnetoOne(targetEntity="Cadastro\Model\Operador")
     * @JoinColumn(name="operador_id", referencedColumnName="id")
     **/
    public $operador;

    /**
     * @var Terminal
     * @OnetoOne(targetEntity="Cadastro\Model\Terminal")
     * @JoinColumn(name="terminal_id", referencedColumnName="id")
     **/
    public $terminal;

    /**
     * @var extracaoProgramada Cadastro\Model\ExtracaoProgramada
     * @OneToOne(targetEntity="Cadastro\Model\ExtracaoProgramada")
     * @JoinColumn(name="extracao_programada_id", referencedColumnName="id")
     **/
    public $extracaoProgramada;

    /** @Column(type="datetime") **/
    public $data_hora;

    /** @Column(type="datetime") **/
    public $data_hora_confirmacao;

    /**
     * @var ArrayCollection
     * @OneToMany(targetEntity="ApostaCancelada", mappedBy="puleCancelada")
     **/
    public $apostasCanceladas;


    /** @Column(type="decimal") **/
    public $valor_total;


    public function __construct(Pule $pule = null, $usuario = null) {
//        $this->apostas = new ArrayCollection();
        if(!is_null($pule)) {
            $this->usuario = $usuario;
            $this->numero = $pule->numero;
            $this->data_hora = $pule->data_hora;
            $this->extracaoProgramada = $pule->extracaoProgramada;
            $this->agencia = $pule->agencia;
            $this->rota = $pule->rota;
            $this->ponto = $pule->ponto;
            $this->operador = $pule->operador;
            $this->terminal = $pule->terminal;
            $this->valor_total = $pule->totalPule();
        }
    }

    public function getInputFilter()
    {
        if (!isset($this->inputFilter)) {
            $inputFilter = new InputFilter();
            $inputFilter->addInput('codigo');
            $inputFilter->addFilter('codigo', new Int());
            $inputFilter->addValidator('codigo', new Between(array(
                        'min'      => 1,
                        'max'      => 99999999999
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
