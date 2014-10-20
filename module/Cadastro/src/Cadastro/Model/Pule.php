<?php

namespace Cadastro\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Abstrato\InputFilter\InputFilter;
use Zend\Stdlib\DateTime;
use Abstrato\Entity\AbstractEntity;
use Zend\I18n\Filter\NumberFormat;

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
 * @author zorro
 *
 * @Entity(repositoryClass="Cadastro\Model\Repository\PuleRepository")
 * @Table(name="pules")
 */
class Pule extends AbstractEntity
{
	/** @Id @Column(type="bigint") @GeneratedValue **/
	public $id;

    /**
     * @Column(type="bigint")
     */
    public $numero;


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
     * @var extracao Cadastro\Model\Extracao
     * @OneToOne(targetEntity="Cadastro\Model\Extracao")
     * @JoinColumn(name="extracao_id", referencedColumnName="id")
     **/
    public $extracao;

    /**
     * @var extracaoProgramada Cadastro\Model\ExtracaoProgramada
     * @OneToOne(targetEntity="Cadastro\Model\ExtracaoProgramada")
     * @JoinColumn(name="extracao_programada_id", referencedColumnName="id")
     **/
    public $extracaoProgramada;
	
	/** @Column(type="datetime") **/
	public $data_hora;

    /**
     * @var ArrayCollection
     * @OneToMany(targetEntity="Aposta", mappedBy="pule")
     **/
    public $apostas;

    /**
     * Calcula o valor total da pule, somando todas suas apostas.
     * @return float
     */
    public function totalPule() {
        $soma = 0.0;
        foreach($this->apostas as $aposta) {
            $soma += $aposta->valor;
        }
        return $soma;
    }



    public function __construct($numero = null,Terminal $terminal = null, ExtracaoProgramada $extracaoProgramada = null) {
        $this->numero = $numero;
        if(isset($terminal) && isset($terminal->operador)) {
            $operador = $terminal->operador;
            $this->agencia = $operador->ponto->rota->agencia;
            $this->rota = $operador->ponto->rota;
            $this->ponto = $operador->ponto;
            $this->operador = $operador;
            $this->terminal = $terminal;
        }
        $this->extracao = $extracaoProgramada->extracao;
        $this->extracaoProgramada = $extracaoProgramada;
        $this->data_hora = new \DateTime();
        $this->apostas = new ArrayCollection();
    }

	public function getInputFilter()
	{
		if (!isset($this->inputFilter)) {
			$inputFilter = new InputFilter();


			$this->inputFilter = $inputFilter;
				
			$this->inputFilter->addChains();
		}

		return $this->inputFilter;
	}

	public function getArrayCopy()
	{
		return get_object_vars($this);
	}

    public function exchangeArray($array)
    {
        $em = $GLOBALS['entityManager'];
        $this->terminal = $em->getRepository('Cadastro\Model\Terminal')->find($array['terminal_id']);
        $this->extracaoProgramada = $em->getRepository('Cadastro\Model\ExtracaoProgramada')->find($array['extracao_programada_id']);
        $this->extracao = $this->extracaoProgramada->extracao;
        $this->operador = $em->getRepository('Cadastro\Model\Operador')->find($this->terminal->operador);
        $this->ponto = $this->operador->ponto;
        $this->rota = $this->ponto->rota;
        $this->agencia = $this->rota->agencia;
        $this->data_hora = new DateTime('now');
        $this->numero = $this->terminal->getProxNumeroPule();
    }


}
