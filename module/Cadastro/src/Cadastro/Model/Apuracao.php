<?php
/**
 * Created by PhpStorm.
 * User: SM
 * Date: 5/21/14
 * Time: 8:42 PM
 */

namespace Cadastro\Model;

use Abstrato\Entity\AbstractEntity;
use Abstrato\InputFilter\InputFilter;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Apuracao
 * @package Movimento\Model
 * @ORM\Entity(repositoryClass="Cadastro\Model\Repository\ApuracaoRepository")
 * @ORM\Table(name="apuracoes")
 */
class Apuracao extends AbstractEntity {

    /** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue **/
    public $id;

    /** @ORM\OnetoOne(targetEntity="Cadastro\Model\ExtracaoProgramada")
     * @ORM\JoinColumn(name="extracao_programada_id", referencedColumnName="id") **/
    public $extracaoProgramada;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    public $fechamentos_pendentes;

    /** @ORM\Column(type="datetime") **/
    public $data_hora;

    /** @ORM\Column(type="decimal") **/
    public $tot_premios;

    /** @ORM\Column(type="decimal") **/
    public $tot_vendas;

    /**
     * @ORM\OnetoOne(targetEntity="Cadastro\Model\Usuario")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     **/
    public $usuario;

    /** @ORM\Column(type="string") **/
    public $status;

    /**
     * @ORM\OnetoOne(targetEntity="Cadastro\Model\Resultado")
     * @ORM\JoinColumn(name="resultado_id", referencedColumnName="id")
     **/
    public $resultado;





    public function getInputFilter()
    {
        // TODO: Implement getInputFilter() method.
        return new InputFilter();
    }


    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}