<?php

namespace Application\Model;

use Doctrine\ORM\Mapping as ORM;

use Zend\Authentication\Adapter\DbTable;
use Zend\Authentication\AuthenticationService;
use Abstrato\Entity\AbstractEntity;
use Abstrato\Authentication\Adapter\DoctrineTable;


/**
 * 
 * @author Raponsel
 *
 * @ORM\Entity
 * @ORM\Table(name="usuarios")
 */
class Usuario extends AbstractEntity
{
	/** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue **/
	public $id;
	
	/** @ORM\Column(type="string") **/
	public $login;
	
	/** @ORM\Column(type="string") **/
	private $credencial;
	
	public $mensagens = array();
	
	
	public function __construct($login, $credencial) {
		$this->login = $login;
		$this->credencial = $credencial;
	}
	
	public function autenticar()
	{
		// cria o adaptador para o mecanismo contra o qual se fará a autenticação
		$adapter = new DoctrineTable($GLOBALS['entityManager']);
		$adapter->setIdentityColumn('login')
		->setEntityName(__CLASS__)
		->setCredentialColumn('credencial')
		->setIdentity($this->login)
		->setCredential($this->credencial);
	
		// cria o serviço de autenticação e injeta o adaptador nele
		$authentication = new AuthenticationService();
		$authentication->setAdapter($adapter);
	
		// autentica
		$result = $authentication->authenticate();
	
		if ($result->isValid())
		{
			// recupera o registro do usuário como um objeto, sem o campo senha
			$usuario = $authentication->getAdapter()->getResultRowObject(null,'senha');
			$authentication->getStorage()->write($usuario);
			return true;
		}
		else
		{
			$this->messages = $result->getMessages();
			return false;
		}
	}
	


	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
	
	public function getInputFilter() {
		return null;
	}
}

