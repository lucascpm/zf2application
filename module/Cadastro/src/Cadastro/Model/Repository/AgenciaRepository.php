<?php
/**
 * Created by PhpStorm.
 * User: hlm
 * Date: 29/05/14
 * Time: 20:58
 */

namespace Cadastro\Model;


use Abstrato\InputFilter\InputFilter;
use Doctrine\ORM\EntityRepository;
use Zend\Filter\Int;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\I18n\Filter\NumberFormat;
use Zend\Validator\Between;
use Zend\Validator\StringLength;

class AgenciaRepository extends EntityRepository {

//    public function getInputFilter()
//    {
//        $inputFilter = new InputFilter();
//        $factory = new Factory();
//
//        $inputFilter->add($factory->createInput(array(
//            'name' => 'codigo',
//            'validators' => array(
//                'name' => 'DoctrineModule\Validator\NoObjectExists',
//                'options' => array(
//                    'object_repository' => $this,
//                    'fields' => array('codigo'),
//                ),
//            ),
//        )));
//
//        return $inputFilter;
//    }

    public function getInputFilter()
    {
        if (!isset($this->inputFilter)) {
            $inputFilter = new InputFilter();
            $inputFilter->addInput('codigo');
            $inputFilter->addFilter('codigo', new Int());
            $inputFilter->addValidator('codigo', new Between(array(
                        'min'      => 1,
                        'max'      => 99
                    )
                )
            );

            $inputFilter->addInput('nome');
            $inputFilter->addFilter('nome', new StripTags());
            $inputFilter->addFilter('nome', new StringTrim());
            $inputFilter->addValidator('nome', new StringLength(array(
                        'encoding' => 'UTF-8',
                        'min'      => 2,
                        'max'      => 30,
                    )
                )
            );

            $inputFilter->addInput('nome_terminal');
            $inputFilter->addFilter('nome_terminal', new StripTags());
            $inputFilter->addFilter('nome_terminal', new StringTrim());
            $inputFilter->addValidator('nome_terminal', new StringLength(array(
                        'encoding' => 'UTF-8',
                        'min'      => 1,
                        'max'      => 15
                    )
                )
            );

            $inputFilter->addInput('nome_pule');
            $inputFilter->addFilter('nome_pule', new StripTags());
            $inputFilter->addFilter('nome_pule', new StringTrim());
            $inputFilter->addValidator('nome_pule', new StringLength(array(
                        'encoding' => 'UTF-8',
                        'min'      => 1,
                        'max'      => 15,
                    )
                )
            );

            // Verificar valor do addValidador para Filtro Moeda
            $inputFilter->addInput('valor_bancado');
            $inputFilter->addFilter('valor_bancado', new NumberFormat());
            $inputFilter->addFilter('valor_bancado', new NumberFormat());
            $inputFilter->addValidator('valor_bancado', new Between(array(
                        'min'      => 1,
                        'max'      => 200000,
                    )
                )
            );

            // Verificar valor do addValidador para Filtro Moeda
            $inputFilter->addInput('descarga');
            $inputFilter->addFilter('descarga', new NumberFormat());
            $inputFilter->addFilter('descarga', new NumberFormat());
            $inputFilter->addValidator('descarga', new Between(array(
                        'min'      => 1,
                        'max'      => 200000,
                    )
                )
            );

            $inputFilter->addInput('cod_cliente_recarga');
            $inputFilter->addFilter('cod_cliente_recarga', new Int());
            $inputFilter->addFilter('cod_cliente_recarga', new Int());
            $inputFilter->addValidator('cod_cliente_recarga', new Between(array(
                        'min'      => 0,
                        'max'      => 99999,
                    )
                )
            );

            $this->inputFilter = $inputFilter;

            $this->inputFilter->addChains();
        }

        return $this->inputFilter;
    }
} 