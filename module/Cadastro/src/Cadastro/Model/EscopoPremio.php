<?php

namespace Cadastro\Model;

use Doctrine\ORM\Mapping as ORM;

use Abstrato\Entity\AbstractEntity;
use Abstrato\InputFilter\InputFilter;
use Zend\Filter\StripTags;
use Zend\Filter\StringTrim;
use Zend\Validator\StringLength;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;

/**
 *
 * @Entity
 * @Table(name="escopospremio")
 */

class EscopoPremio extends AbstractEntity
{
    /**
     * @Id @Column(type="integer") @GeneratedValue
     */
    public $id;

    /**
     * @Column(type="integer")
     */
    public $codigo;

    /**
     * @Column(type="string")
     */
    public $intervalo;

    /**
     * @Column(type="integer")
     */
    public $multip_valor_aposta;


    public function getInputFilter() {
        if (!$this->inputFilter) {

            $inputFilter = new InputFilter();


            $inputFilter->addInput('descricao');
            $inputFilter->addFilter('descricao', new StripTags());
            $inputFilter->addFilter('descricao', new StringTrim());
            $inputFilter->addValidator('descricao', new StringLength(array(
                        'encoding' => 'UTF-8',
                        'min'      => 2,
                        'max'      => 20,
                    )
                )
            );

            $this->inputFilter = $inputFilter;
            $this->inputFilter->addChains();
        }

        return $this->inputFilter;
    }

}

?>