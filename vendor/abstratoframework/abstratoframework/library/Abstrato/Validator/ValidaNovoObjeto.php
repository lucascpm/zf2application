<?php
/**
 * Created by PhpStorm.
 * User: hlm
 * Date: 29/05/14
 * Time: 19:51
 */

namespace Abstrato\Validator;


use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;

class ValidaNovoObjeto extends AbstractValidator {


    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * If $value fails validation, then this method returns false, and
     * getMessages() will return an array of messages that explain why the
     * validation failed.
     *
     * @param  mixed $value
     * @return bool
     * @throws Exception\RuntimeException If validation of $value is impossible
     */
    public function isValid($value)
    {
        // TODO: Implement isValid() method.
    }
}