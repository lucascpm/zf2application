<?php
/**
 * Created by PhpStorm.
 * User: hlm
 * Date: 03/06/14
 * Time: 20:20
 */

namespace Cadastro\Model;

use Doctrine\ORM\Mapping as ORM;

use Abstrato\Entity\AbstractEntity;
use Zend\Filter\Int;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\I18n\Filter\NumberFormat;
use Abstrato\InputFilter\InputFilter;
use Zend\Validator\Between;
use Zend\Validator\StringLength;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;


/**
 *
 * @Entity(repositoryClass="Cadastro\Model\Repository\FechamentoRepository")
 * @Table(name="fechamentos")
 */
class Fechamento extends AbstractEntity {

    /**
     * @Id @Column(type="integer") @GeneratedValue
     */
    public $id;

    /**
     * @OneToOne(targetEntity="Agencia")
     * @JoinColumn(name="agencia_id", referencedColumnName="id")
     **/
    public $agencia;

    /**
     * @OneToOne(targetEntity="Ponto")
     * @JoinColumn(name="ponto_id", referencedColumnName="id")
     **/
    public $ponto;

    /**
     * @OneToOne(targetEntity="Terminal")
     * @JoinColumn(name="terminal_id", referencedColumnName="id")
     **/
    public $terminal;

    /**
     * @OneToOne(targetEntity="ExtracaoProgramada")
     * @JoinColumn(name="extracao_programada_id", referencedColumnName="id")
     **/
    public $extracaoProgramada;

    /**
     * @OneToOne(targetEntity="Operador")
     * @JoinColumn(name="operador_id", referencedColumnName="id")
     **/
    public $operador;

    /**
     * @Column(type="datetime")
     */
    public $data_abertura;

    /**
     * @Column(type="datetime")
     */
    public $data_fechamento;

    /**
     * @Column(type="string")
     */
    public $obs;

    public function getInputFilter()
    {
        if (!$this->inputFilter) {

            $inputFilter = new InputFilter();

            $inputFilter->addInput('codigo');
            $inputFilter->addFilter('codigo', new Int());
            $inputFilter->addValidator('codigo', new Between(array(
                        'min'      => 1,
                        'max'      => 9999
                    )
                )
            );

            $inputFilter->addInput('nome');
            $inputFilter->addFilter('nome', new StripTags());
            $inputFilter->addFilter('nome', new StringTrim());
            $inputFilter->addValidator('nome', new StringLength(array(
                        'encoding' => 'UTF-8',
                        'min'      => 2,
                        'max'      => 41,
                    )
                )
            );

            $inputFilter->addInput('sigla');
            $inputFilter->addFilter('sigla', new StripTags());
            $inputFilter->addFilter('sigla', new StringTrim());
            $inputFilter->addValidator('sigla', new StringLength(array(
                        'encoding' => 'UTF-8',
                        'min'      => 1,
                        'max'      => 4,
                    )
                )
            );

            // Verificar filtro para BOOLEAN
            $inputFilter->addInput('ativo');
            $inputFilter->addFilter('ativo', new Int());
            $inputFilter->addValidator('ativo', new Between(array(
                        'min'      => 0,
                        'max'      => 1,
                    )
                )
            );

            // Verificar filtro para BOOLEAN
            $inputFilter->addInput('descarga');
            $inputFilter->addFilter('descarga', new Int());
            $inputFilter->addValidator('descarga', new Between(array(
                        'min'      => 0,
                        'max'      => 1,
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
}