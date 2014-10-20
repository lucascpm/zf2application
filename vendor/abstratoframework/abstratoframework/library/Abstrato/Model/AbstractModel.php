<?php

namespace Abstrato\Model;

use Zend\Db\RowGateway\RowGateway;
/**
 *
 * @author hlm
 *        
 */
abstract class AbstractModel extends RowGateway
{
	protected $inputFilter;
	
		
	abstract public function getInputFilter();
	
	public function getArrayCopy()
	{
		return $this->data;
	}
	
	public function toArray()
	{
		return get_object_vars($this);
	}
}

