<?php

namespace Abstrato\Db\TableGateway;

use Abstrato\Model\AbstractModel;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

/**
 *
 * @author hlm
 *        
 */
abstract class AbstractTableGateway
{
	protected $primaryKey;
	protected $tableGateway;
	
	public function getPrimaryKey() {
		return $this->primaryKey;
	}
	
	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}
	
	public function fetchAll()
	{
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	}
	
	public function pesquisar($key)
	{
		$key = (int) $key;
		$rowset = $this->tableGateway->select(array($this->primaryKey => $key));
		$row = $rowset->current();
		return $row;
	}
	
	public function salvar(AbstractModel $model)
	{
		$primaryKey = $this->primaryKey;
		$key = $model->$primaryKey;
		$data = $this->getData($model);
	
		if (!$this->pesquisar($key))
		{
			$this->tableGateway->insert($data);
		}
		else
		{
			$this->tableGateway->update($data, array( $this->primaryKey => $key));
		}
	}
	
	abstract protected function getData(AbstractModel $model);
	
	public function deletar($key)
	{
		$this->tableGateway->delete(array($this->primaryKey => $key));
	}
	
	public function getSql()
	{
		return $this->tableGateway->getSql();
	}
	
	public function getSelect()
	{
		$select = new Select($this->tableGateway->getTable());
		return $select;
	}
	
	/**
	 * Permite chamar métodos de Zend\Db\TableGateway\TableGateway
	 * como se fossem de Fgsl\Db\TableGateway\AbstractTableGateway.
	 * @param string $method
	 * @param array $args
	 * @return mixed
	 */
	public function __call($method, array $args)
	{
		return call_user_func_array(array($this->tableGateway,$method), $args);
	}
}

