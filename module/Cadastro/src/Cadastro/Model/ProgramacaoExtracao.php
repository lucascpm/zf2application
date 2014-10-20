<?php
namespace Cadastro\Model;

use Abstrato\InputFilter\InputFilter;
use Abstrato\Model\AbstractModel;
use Zend\Filter\Int;
use Zend\Filter\StripTags;
use Zend\Filter\StringTrim;
use Zend\Validator\Between;
use Zend\Validator\StringLength;
use Doctrine\ORM\Mapping as ORM;
use Abstrato\Entity\AbstractEntity;
use Zend\Stdlib\DateTime;
/**
 * Class ProgramacaoExtracao
 * @deprecated Utilizar a classe Cadastro\Model\ExtracaoProgramada
 * @package Configuracao\Model
 * @ORM\Entity
 * @ORM\Table(name="ExtracoesProgramadas")
 *
 */
class ProgramacaoExtracao extends AbstractEntity{
    /** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue **/
    public $codigo;

    /** @ORM\Column(type="date") **/
    public $data_extracao;

    /**
     * @var Extracao
     * /**
     * @ORM\OnetoOne(targetEntity="Extracao")
     * @ORM\JoinColumn(name="extracao_cod", referencedColumnName="codigo")
     **/
    public $extracao;

    /** @ORM\Column(type="date") **/
    public $data_atualizacao;

    /**
     * @ORM\OnetoOne(targetEntity="Application\Model\Usuario")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     **/
    public $usuario;

    public function getInputFilter()
    {
        // TODO: Implement getInputFilter() method.
        return new InputFilter();
    }
    /*
    public function getInputFilter()
    {
        if (!isset($this->inputFilter)) {
            $inputFilter = new InputFilter();

            $inputFilter->addInput('codigo');
            $inputFilter->addFilter('codigo', new Int());
            $inputFilter->addValidator('codigo', new Between(array(
                        'min'      => 1,
                        'max'      => 99999999
                    )
                )
            );
//Corrigir datas
            $inputFilter->addInput('data_extracao');
            $inputFilter->addFilter('data_extracao', new StripTags());
            $inputFilter->addValidator('data_extracao', new Between(array(
                        'min'      => 1,
                        'max'      => 99999999
                    )
                )
            );

            $inputFilter->addInput('data_atualizacao');
            $inputFilter->addFilter('data_atualizacao', new Int());
            $inputFilter->addValidator('data_atualizacao', new Between(array(
                        'min'      => 1,
                        'max'      => 9999
                    )
                )
            );

            $inputFilter->addInput('usuario_id');
            $inputFilter->addFilter('usuario_id', new Int());
            $inputFilter->addValidator('usuario_id', new Between(array(
                        'min'      => 1,
                        'max'      => 9999
                    )
                )
            );

            $inputFilter->addInput('extracao_cod');
            $inputFilter->addFilter('extracao_cod', new Int());
            $inputFilter->addValidator('extracao_cod', new Between(array(
                        'min'      => 1,
                        'max'      => 9999,
                    )
                )
            );
        }
    }
*/
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function exchangeArray($array)
    {
        //die(var_dump($this));
        foreach($array as $attribute => $value)
        {
            $this->$attribute = $value;
        }
        /* Converte as datas para salvar no banco */
        //$this->data_extracao = new DateTime($this->data_extracao);
        //$this->data_atualizacao = new DateTime($this->data_atualizacao);
    }
}