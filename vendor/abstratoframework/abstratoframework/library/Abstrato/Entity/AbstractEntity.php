<?php

namespace Abstrato\Entity;

abstract class AbstractEntity
{
	protected $inputFilter;
	
	abstract public function getInputFilter();

// 	abstract public function getArrayCopy();

	public function getArrayCopy() {
		return get_object_vars($this);
	}
	
	
	public function exchangeArray($array)
	{
		foreach($array as $attribute => $value)
		{
			$this->$attribute = $value;
		}
	}
	
	
}

