<?php
/**
 * Created by PhpStorm.
 * User: alvaro
 * Date: 6/18/14
 * Time: 8:48 PM
 */

namespace Cadastro\Model;

use Doctrine\ORM\Mapping as ORM;

use Abstrato\Entity\AbstractEntity;
use Abstrato\InputFilter\InputFilter;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="premios")
 */
class Premio extends AbstractEntity {

    public function getInputFilter()
    {
        // TODO: Implement getInputFilter() method.
        return new InputFilter();
    }
}