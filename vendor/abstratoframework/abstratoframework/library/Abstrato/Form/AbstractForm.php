<?php

namespace Abstrato\Form;

use Zend\Form\Element\Select;
use Zend\Form\Element\Time;
use Zend\Form\Form;

/**
 *
 * @author hlm
 *        
 */
abstract class AbstractForm extends Form
{
    /**
     * @param string $name Valor da propriedade name
     * @param string $type Valor da propriedade type
     * @param string $label Valor opcional do label (ou value quando type é submit)
     * @param array $attributes
     * @param array $options
     * @param bool $required Se o preenchimento é obrigatório (Mais utilizado para o type time)
     */
    protected function addElement($name, $type, $label = null, $attributes = array(), $options = array(), $required = false)
	{
		if ($type == 'select')
		{
			$element = new Select($name);
			$element->setLabel($label)
			->setAttributes($attributes)
			->setOptions($options);
		} else if ($type == 'time') {
            $element = new Time($name);
            $element->setLabel($label)
                ->setAttributes($attributes)
                ->setOptions($options);
            if($required) {
                $element->setAttributes(array(
                    'min'  => '00:00',
                    'max'  => '23:59'
                ));
            }
            $element->setOptions(array('format' => 'H:i'));
        }
		else
		{
			$attributes['type'] = $type;
	
			if ($type == 'submit')
				$attributes['value'] = $label;
			else
				$options['label'] = $label;
				
			$element = array(
					'name' => $name,
					'attributes' => $attributes,
					'options' => $options
			);
		}
	
		$this->add($element);
	}
}

