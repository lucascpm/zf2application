<?php
/**
 * Created by PhpStorm.
 * User: hlm
 * Date: 09/06/14
 * Time: 21:31
 */

namespace Cadastro\Model;

use Abstrato\InputFilter\InputFilter;
use Doctrine\ORM\Mapping as ORM;

use Abstrato\Entity\AbstractEntity;
use Zend\Filter\Int;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Validator\Between;
use Zend\Validator\StringLength;

/**
 *
 * @author hlm
 *
 * @ORM\Entity
 * @ORM\Table(name="clientes")
 */
class Cliente extends AbstractEntity {

    /** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue **/
    public $id;

    /** @ORM\Column(type="string") **/
    public $razao_social;

    /** @ORM\Column(type="smallint") **/
    public $situacao;

    /** @ORM\Column(type="boolean") **/
    public $ativo;

    /** @ORM\Column(type="string") **/
    public $estado;

    /** @ORM\Column(type="string") **/
    public $municipio;

    /** @ORM\Column(type="string") **/
    public $responsavel;


    public function getInputFilter()
    {
        if (!$this->inputFilter) {

            $inputFilter = new InputFilter();

            $inputFilter->addInput('razao_social');
            $inputFilter->addFilter('razao_social', new StripTags());
            $inputFilter->addFilter('razao_social', new StringTrim());
            $inputFilter->addValidator('razao_social', new StringLength(array(
                        'encoding' => 'UTF-8',
                        'min'      => 2,
                        'max'      => 20,
                    )
                )
            );

            $inputFilter->addInput('situacao');
            $inputFilter->addFilter('situacao', new Int());
            $inputFilter->addValidator('situacao', new Between(array(
                        'min'      => 1,
                        'max'      => 9999
                    )
                )
            );

            $inputFilter->addInput('ativo');
            $inputFilter->addFilter('ativo', new Int());
            $inputFilter->addValidator('ativo', new Between(array(
                        'min'      => 0,
                        'max'      => 1,
                    )
                )
            );

            $inputFilter->addInput('estado');
            $inputFilter->addFilter('estado', new StripTags());
            $inputFilter->addFilter('estado', new StringTrim());
            $inputFilter->addValidator('estado', new StringLength(array(
                        'encoding' => 'UTF-8',
                        'min'      => 2,
                        'max'      => 2,
                    )
                )
            );

            $inputFilter->addInput('municipio');
            $inputFilter->addFilter('municipio', new StripTags());
            $inputFilter->addFilter('municipio', new StringTrim());
            $inputFilter->addValidator('municipio', new StringLength(array(
                        'encoding' => 'UTF-8',
                        'min'      => 2,
                        'max'      => 30,
                    )
                )
            );

            $inputFilter->addInput('responsavel');
            $inputFilter->addFilter('responsavel', new StripTags());
            $inputFilter->addFilter('responsavel', new StringTrim());
            $inputFilter->addValidator('responsavel', new StringLength(array(
                        'encoding' => 'UTF-8',
//                        'min'      => 2,
                        'max'      => 30,
                    )
                )
            );

            $this->inputFilter = $inputFilter;
            $this->inputFilter->addChains();
        }

        return $this->inputFilter;    }
}