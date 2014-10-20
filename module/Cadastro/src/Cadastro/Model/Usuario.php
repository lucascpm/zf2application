<?php

namespace Cadastro\Model;

use Doctrine\ORM\Mapping as ORM;

use Zend\Authentication\Adapter\DbTable;
use Zend\Authentication\AuthenticationService;
use Abstrato\Entity\AbstractEntity;
use Abstrato\Authentication\Adapter\DoctrineTable;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;

/**
 * 
 * @author Raponsel
 *
 * @Entity(repositoryClass="Cadastro\Model\Repository\UsuarioRepository")
 * @Table(name="usuarios")
 */
class Usuario extends AbstractEntity
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	public $id;
	
	/** @Column(type="string") **/
	public $login;
	
	/** @Column(type="string") **/
	private $credencial;

    /** @Column(type="integer") **/
    public $token;

    /** @Column(type="date") **/
    public $validade;
	
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

