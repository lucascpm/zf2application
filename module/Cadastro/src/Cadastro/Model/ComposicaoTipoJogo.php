<?php
/**
 * Created by PhpStorm.
 * User: hlm
 * Date: 15/09/14
 * Time: 23:50
 */

namespace Cadastro\Model;


use Abstrato\Entity\AbstractEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Zend\InputFilter\InputFilter;

/**
 *
 * @Entity
 * @Table(name="composicaotiposjogo")
 */
class ComposicaoTipoJogo extends AbstractEntity {

    /**
     * @Id @Column(type="integer") @GeneratedValue
     */
    public $id;

    /**
     * @var ArrayCollection
     * @OneToMany(targetEntity="TipoJogo", mappedBy="pule")
     **/
    public $tiposJogo;

    public function getInputFilter()
    {
        return new InputFilter();
    }
}