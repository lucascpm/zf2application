<?php


namespace Cadastro\Model;
require_once 'vendor/pjb_etc/constantes.php';

use Doctrine\ORM\Mapping as ORM;

use Abstrato\InputFilter\InputFilter;
use Zend\Filter\Int;
use Zend\Filter\StripTags;
use Zend\Filter\StringTrim;
use Zend\Validator\Between;
use Zend\Validator\Date;
use Zend\Validator\StringLength;
use Abstrato\Entity\AbstractEntity;
use Zend\I18n\Filter\NumberFormat;
use Zend\Stdlib\DateTime;

/**
 * Class Mensagem
 * @package Cadastro\Model
 * @ORM\Entity
 * @ORM\Table(name="Mensagens")
 */
class Mensagem extends AbstractEntity
{
    /** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue **/
    public $id;

    /** @ORM\Column(type="date") **/
    public $vigencia_ini;

    /** @ORM\Column(type="date") **/
    public $vigencia_fim;

    /** @ORM\Column(type="string") **/
    public $tipo_msg;

    /**
     * @ORM\OnetoOne(targetEntity="Cadastro\Model\Agencia")
     * @ORM\JoinColumn(name="agencia_id", referencedColumnName="id")
     **/
    public $agencia;

    /**
     * @ORM\OnetoOne(targetEntity="Cadastro\Model\Ponto")
     * @ORM\JoinColumn(name="ponto_id", referencedColumnName="id")
     **/
    public $ponto;

    /**
     * @ORM\OnetoOne(targetEntity="Cadastro\Model\Rota")
     * @ORM\JoinColumn(name="rota_id", referencedColumnName="id")
     **/
    public $rota;

    /**
     * @ORM\OnetoOne(targetEntity="Cadastro\Model\Operador")
     * @ORM\JoinColumn(name="operador_id", referencedColumnName="id")
     **/
    public $operador;

    /**
     * @ORM\OnetoOne(targetEntity="Cadastro\Model\Terminal")
     * @ORM\JoinColumn(name="terminal_id", referencedColumnName="id")
     **/
    public $terminal;

    /** @ORM\Column(type="string") **/
    public $msg;

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

        if(is_array($array)) {
            /* Converte as datas para salvar no banco */
            $this->vigencia_ini = new DateTime($array['vigencia_ini']);
            $this->vigencia_fim = new DateTime($array['vigencia_fim']);
            $em=$GLOBALS['entityManager'];

            if( $array['agencia'] != TODOS ) {
                $this->agencia = $em->getRepository('Cadastro\Model\Agencia')->find($array['agencia']);
            } else {
                $this->agencia = null;
            }
            if( $array['ponto'] != TODOS  ) {
                $this->ponto = $em->getRepository('Cadastro\Model\Ponto')->find($array['ponto']);
            } else {
                $this->ponto = null;
            }
            if( $array['rota'] != TODOS  ) {
                $this->rota = $em->getRepository('Cadastro\Model\Rota')->find($array['rota' ] );
            } else {
                $this->rota = null;
            }

            if( $array['terminal'] != TODOS  ) {
                $this->terminal = $em->getRepository('Cadastro\Model\Terminal')->find($array['terminal']);
            } else {
                $this->terminal = null;
            }
//            $this->msg = $array['msg'];

        }
    }
}
