<?php
/**
 * Created by PhpStorm.
 * User: hlm
 * Date: 14/07/14
 * Time: 19:48
 */

namespace Cadastro\Model;

use Cadastro\Model\Aposta;
use Doctrine\ORM\Mapping as ORM;

use Abstrato\Entity\AbstractEntity;
use Abstrato\InputFilter\InputFilter;
use Cadastro\Model\PulePremiada;

/**
 * Class PulePremiada
 * Representa uma aposta premiada.
 * @package Cadastro\Model
 * @ORM\Entity
 * @ORM\Table(name="apostaspremiadas")
 */
class ApostaPremiada extends AbstractEntity {


    public function __construct(PulePremiada $pulePremiada = null, Aposta $aposta = null, $escopo = null, $valorPremio = null) {
        if($aposta) {
            $this->aposta = $aposta;
            $this->posicao_escopo = $escopo;
            $this->valor_premio = $valorPremio;
            $this->pulePremiada = $pulePremiada;
        }
    }

    /**
     * @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue
     */
    public $id;

    /**
     * @var PulePremiada
     * @ORM\ManyToOne(targetEntity="PulePremiada", inversedBy="apostasPremiadas")
     * @ORM\JoinColumn(name="pule_premiada_id", referencedColumnName="id")
     **/
    public $pulePremiada;


    /**
     * @var Aposta
     * @ORM\OnetoOne(targetEntity="Cadastro\Model\Aposta")
     * @ORM\JoinColumn(name="aposta_id", referencedColumnName="id")
     **/
    public $aposta;

    /** @ORM\Column(type="integer") **/
    public $posicao_escopo;

    /** @ORM\Column(type="float") **/
    public $valor_premio;


    public function getInputFilter()
    {
        return new InputFilter();
    }
}