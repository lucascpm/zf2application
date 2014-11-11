<?php
/**
 * Created by PhpStorm.
 * User: alvaro
 * Date: 5/21/14
 * Time: 8:42 PM
 */

namespace Cadastro\Model;

use Abstrato\Entity\AbstractEntity;
use Cadastro\Model\TipoJogo;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;

/**
 * Class Aposta
 * @package Cadastro\Model
 * @Entity
 * @Table(name="apostasCanceladas")
 */
class ApostaCancelada extends AbstractEntity {

    /** @Id @Column(type="bigint") @GeneratedValue **/
    public $id;

    /**
     * @ManyToOne(targetEntity="PuleCancelada", inversedBy="apostasCanceladas")
     * @JoinColumn(name="pule_cancelada_id", referencedColumnName="id")
     **/
    public $puleCancelada;

    /**
     * @var TipoJogo
     * @OneToOne(targetEntity="Cadastro\Model\TipoJogo")
     * @JoinColumn(name="tipo_jogo_id", referencedColumnName="id")
     **/
    public $tipoJogo;

    /**
     * @var escopo
     * @OneToOne(targetEntity="Cadastro\Model\EscopoPremio")
     * @JoinColumn(name="escopo_id", referencedColumnName="id")
     **/
    public $escopoPremio;

    /** @Column(type="integer") **/
    public $numero;

    /** @Column(type="decimal") **/
    public $valor;


    public function __construct(PuleCancelada $puleCancelada, Aposta $aposta = null) {
        $this->puleCancelada = $puleCancelada;
        if(!is_null($aposta)){
            $this->escopoPremio = $aposta->escopoPremio;
            $this->tipoJogo = $aposta->tipoJogo;
            $this->numero = $aposta->numero;
            $this->valor = $aposta->valor;
        }
    }

    public function getInputFilter()
    {
        // TODO: Implement getInputFilter() method.
        return new InputFilter();
    }


    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function exchangeArray($array)
    {
        foreach($array as $attribute => $value)
        {
            $this->$attribute = $value;
        }
    }
}