<?php

namespace Cadastro\Model;

use Abstrato\InputFilter\InputFilter;
use Abstrato\Model\AbstractModel;
use Zend\Authentication\AuthenticationService;
use Zend\Filter\Int;
use Zend\Filter\StripTags;
use Zend\Filter\StringTrim;
use Zend\I18n\Filter\NumberFormat;
use Zend\Stdlib\DateTime;
use Zend\Validator\Between;
use Zend\Validator\Date;
use Zend\Validator\StringLength;
use Abstrato\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
/**
 *
 *
 * @author arcanjo
 *
 * @ORM\Entity(repositoryClass="Cadastro\Model\Repository\TalaoRepository")
 * @ORM\Table(name="taloes")
 */
class Talao extends AbstractEntity
{
    /** @ORM\Id @ORM\Column(type="bigint") @ORM\GeneratedValue **/
    public $id;

    /**
     * @var Agencia
     * @ORM\OnetoOne(targetEntity="Cadastro\Model\Agencia")
     * @ORM\JoinColumn(name="agencia_id", referencedColumnName="id")
     **/
    public $agencia;

    /**
     * @var Ponto
     * @ORM\OnetoOne(targetEntity="Cadastro\Model\Ponto")
     * @ORM\JoinColumn(name="ponto_id", referencedColumnName="id")
     **/
    public $ponto;

    /**
     * @var Rota
     * @ORM\OnetoOne(targetEntity="Cadastro\Model\Rota")
     * @ORM\JoinColumn(name="rota_id", referencedColumnName="id")
     **/
    public $rota;

    /**
     * @var Operador
     * @ORM\OnetoOne(targetEntity="Cadastro\Model\Operador")
     * @ORM\JoinColumn(name="operador_id", referencedColumnName="id")
     **/
    public $operador;


    /**
     * @var ExtracaoProgramada
     * @ORM\OneToOne(targetEntity="Cadastro\Model\ExtracaoProgramada")
     * @ORM\JoinColumn(name="extracao_programada_id", referencedColumnName="id")
     **/
    public $extracaoProgramada;

    /**
     * @var extracao Cadastro\Model\Extracao
     * @ORM\OneToOne(targetEntity="Cadastro\Model\Extracao")
     * @ORM\JoinColumn(name="extracao_id", referencedColumnName="id")
     **/
    public $extracao;

    /** @ORM\Column(type="date") **/
    public $data_lancamento;

    /** @ORM\Column(type="decimal") **/
    public $valor;


    /**
     * @ORM\OnetoOne(targetEntity="Cadastro\Model\Usuario")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     **/
    public $usuario;

    /** @ORM\Column(type="string") **/
    public $obs;

    public function __construct() {

    }

    public function getInputFilter()
    {
        if (!isset($this->inputFilter)) {
            $inputFilter = new InputFilter();

            $inputFilter->addInput('data_lancamento');
            $inputFilter->addFilter('data_lancamento', new StripTags());
            $inputFilter->addFilter('data_lancamento', new StringTrim());
            $inputFilter->addValidator('data_lancamento', new Date(array(
                        'format'      => 'd/m/Y')
                )
            );

            $inputFilter->addInput('rota');
            $inputFilter->addFilter('rota', new Int());
            $inputFilter->addValidator('rota', new Between(array(
                        'min'      => 1,
                        'max'      => 9999
                    )
                )
            );

            $inputFilter->addInput('ponto');
            $inputFilter->addFilter('ponto', new Int());
            $inputFilter->addValidator('ponto', new Between(array(
                        'min'      => 1,
                        'max'      => 999999
                    )
                )
            );

            $inputFilter->addInput('agencia');
            $inputFilter->addFilter('agencia', new Int());
            $inputFilter->addValidator('agencia', new Between(array(
                        'min'      => 1,
                        'max'      => 99
                    )
                )
            );

            $inputFilter->addInput('extracao');
            $inputFilter->addFilter('extracao', new Int());
            $inputFilter->addValidator('extracao', new Between(array(
                        'min'      => 1,
                        'max'      => 999999
                    )
                )
            );

            $inputFilter->addInput('valor');
            $inputFilter->addFilter('valor', new NumberFormat());
            $inputFilter->addValidator('valor', new StringLength(array(
                        'encoding' => 'UTF-8',
                        'min'      => 1,
                        'max'      => 15,
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

    public function exchangeArray($array)
    {
        foreach($array as $attribute => $value)
        {
            $this->$attribute = $value;
        }


        if(is_array($array)) {
            $em = $GLOBALS['entityManager'];
            $this->agencia = $em->getRepository('Cadastro\Model\Agencia')->find($array['agencia']);
            $this->ponto = $em->getRepository('Cadastro\Model\Ponto')->find($array['ponto']);
            $this->rota = $em->getRepository('Cadastro\Model\Rota')->find($array['rota']);
            $this->operador = $em->getRepository('Cadastro\Model\Operador')->find($array['operador']);
            $this->extracao = $em->getRepository('Cadastro\Model\Extracao')->find($array['extracao']);

            $data = DateTime::createFromFormat('d/m/Y', $array['data_lancamento']);
            //Criando objeto DateTime para campo data_lancamento
            $this->data_lancamento = $data;


            //Pegando o login do usuário autenticado
            $auth = new AuthenticationService();
            $this->usuario = $em->getRepository('Cadastro\Model\Usuario')->findOneBy(array('login' => $auth->getIdentity()->login.''));

        }


    }
}
