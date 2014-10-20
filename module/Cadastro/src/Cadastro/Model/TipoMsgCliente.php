<?php

    namespace Cadastro\Model;

    use Doctrine\ORM\Mapping as ORM;

    use Abstrato\InputFilter\InputFilter;
    use Zend\Filter\Int;
    use Zend\Filter\StripTags;
    use Zend\Filter\StringTrim;
    use Zend\Validator\Between;
    use Zend\Validator\StringLength;
    use Abstrato\Entity\AbstractEntity;
    use Zend\I18n\Filter\NumberFormat;

    use Doctrine\ORM\Mapping\Entity;
    use Doctrine\ORM\Mapping\Table;
    use Doctrine\ORM\Mapping\Id;
    use Doctrine\ORM\Mapping\Column;
    use Doctrine\ORM\Mapping\GeneratedValue;

    /**
     *
     *
     * @author ls
     *
     * @Entity
     * @Table(name="tipomsgcliente")
     */
    class TipoMsgCliente extends AbstractEntity
    {
        /** @Id @Column(type="integer") @GeneratedValue **/
        public $id;

        /** @Column(type="string") **/
        public $codigo;

        /** @Column(type="string") **/
        public $sigla;

        /** @Column(type="string") **/
        public $descricao;

        /** @Column(type="string") **/
        public $obs;



        public function getInputFilter()
        {
            if (!isset($this->inputFilter)) {
                $inputFilter = new InputFilter();

                $inputFilter->addInput('codigo');
                $inputFilter->addFilter('codigo', new Int());
                $inputFilter->addValidator('codigo', new Between(array(
                            'min'      => 1,
                            'max'      => 999
                        )
                    )
                );

                $this->inputFilter = $inputFilter;

                $this->inputFilter->addChains();
            }

            return $this->inputFilter;
        }

        public function exchangeArray($array)
        {
            foreach($array as $attribute => $value)
            {
                $this->$attribute = is_string($value) ? strtoupper($value) : $value;
            }

        }

        public function getArrayCopy()
        {
            return get_object_vars($this);
        }
    }


