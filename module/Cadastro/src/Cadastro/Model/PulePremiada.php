<?php
/**
 * Created by PhpStorm.
 * User: hlm
 * Date: 14/07/14
 * Time: 19:41
 */

namespace Cadastro\Model;

use Cadastro\Model\Pule;
use Cadastro\Model\ApostaPremiada;
use Cadastro\Model\Resultado;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Abstrato\Entity\AbstractEntity;
use Abstrato\InputFilter\InputFilter;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;

/**
 * Class PulePremiada
 * Representa uma pule que contém aposta(s) premiada(s).
 * @package Cadastro\Model
 * @Entity(repositoryClass="Cadastro\Model\Repository\PulePremiadaRepository")
 * @Table(name="pulespremiadas")
 */
class PulePremiada extends AbstractEntity {

    public function __construct(Pule $pule, Resultado $resultado) {
        $this->pule = $pule;
        $this->resultado = $resultado;
        $this->apostasPremiadas = new ArrayCollection();
    }

    /**
     * @Id @Column(type="integer") @GeneratedValue
     */
    public $id;

    /** @Column(type="float") **/
    public $total_pago;

    /** @Column(type="datetime") **/
    public $data_hora_pagamento;

    /**
     * @var Pule
     * @OnetoOne(targetEntity="Cadastro\Model\Pule")
     * @JoinColumn(name="pule_id", referencedColumnName="id")
     **/
    public $pule;


    /**
     * @var Resultado
     * @OnetoOne(targetEntity="Cadastro\Model\Resultado")
     * @JoinColumn(name="resultado_id", referencedColumnName="id")
     **/
    public $resultado;

    /**
     * @var ArrayCollection
     * @OneToMany(targetEntity="ApostaPremiada", mappedBy="pulePremiada")
     **/
    public $apostasPremiadas;

    /** @Column(type="boolean") **/
    public $reapurado;

    /**
     * Calcula o total de prêmios da pule premiada.
     * @return float
     */
    public function totalPremio() {
        $total = 0.0;
        foreach($this->apostasPremiadas as $apostaP) {
            $total += $apostaP->valor_premio;
        }
        return $total;
    }

    public function addApostaPremiada(ApostaPremiada $apostaPremiada) {
//        array_push($this->apostasPremiadas, $apostaPremiada);
        $this->apostasPremiadas->add($apostaPremiada);
    }

    public function getInputFilter()
    {
        return new InputFilter();
    }
}