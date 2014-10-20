<?php
/**
 * Created by PhpStorm.
 * User: hlm
 * Date: 25/07/14
 * Time: 12:33
 */

namespace Cadastro\Model;


use Doctrine\ORM\Mapping as ORM;


use Abstrato\Entity\AbstractEntity;
use Zend\InputFilter\InputFilter;

/**
 * @ORM\Entity
 * @ORM\Table(name="reaberturas")
 */
class Reabertura extends AbstractEntity {

    /** @ORM\Id @ORM\Column(type="bigint") @ORM\GeneratedValue **/
    public $id;

    /** @ORM\Column(type="datetime") **/
    public $data_hora;

    /** @ORM\Column(type="datetime") **/
    public $data_hora_fechamento;

    /**
     * @var Fechamento
     * @ORM\OnetoOne(targetEntity="Cadastro\Model\Fechamento")
     * @ORM\JoinColumn(name="fechamento_id", referencedColumnName="id")
     **/
    public $fechamento;

    /**
     * @var Usuario
     * @ORM\OnetoOne(targetEntity="Cadastro\Model\Usuario")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     **/
    public $usuario;


    public function getInputFilter()
    {
        return new InputFilter();
    }
}