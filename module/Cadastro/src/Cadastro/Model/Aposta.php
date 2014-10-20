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
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;


/**
 * Class Aposta
 * @package Cadastro\Model
 * @Entity(repositoryClass="Cadastro\Model\Repository\ApostaRepository")
 * @Table(name="apostas")
 */
class Aposta extends AbstractEntity {

    public function __construct($pule = null, $tipoJogo = null, $escopo = null, $numero = null, $valor = null) {
        $this->pule = $pule;
        $this->tipoJogo = $tipoJogo;
        $this->escopoPremio = $escopo;
        $this->numero = $numero;
        $this->valor = $valor;

    }

    /** @Id @Column(type="bigint") @GeneratedValue **/
    public $id;

    /**
     * @ManyToOne(targetEntity="Pule", inversedBy="apostas")
     * @JoinColumn(name="pule_id", referencedColumnName="id")
     **/
    public $pule;

    /**
     * @var TipoJogo
     * @OneToOne(targetEntity="Cadastro\Model\TipoJogo")
     * @JoinColumn(name="tipo_jogo_id", referencedColumnName="id")
     **/
    public $tipoJogo;

    /**
     * @var EscopoPremio
     * @OneToOne(targetEntity="Cadastro\Model\EscopoPremio")
     * @JoinColumn(name="escopo_id", referencedColumnName="id")
     **/
    public $escopoPremio;

    /** @Column(type="integer") **/
    public $numero;

    /** @Column(type="decimal") **/
    public $valor;


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
        $em = $GLOBALS['entityManager'];
        $this->pule = $em->getRepository('Cadastro\Model\Pule')->find($array['pule_id']);
        $this->tipoJogo = $em->getRepository('Cadastro\Model\TipoJogo')->find($array['tipo_jogo_id']);
        $this->escopoPremio = $em->getRepository('Cadastro\Model\EscopoPremio')->find($array['escopo_id']);

    }
}