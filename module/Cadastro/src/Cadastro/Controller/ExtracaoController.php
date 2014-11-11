<?php

namespace Cadastro\Controller;

use Abstrato\Mvc\Controller\AbstractDoctrineCrudController;


class ExtracaoController extends AbstractDoctrineCrudController
{
	public function __construct()
	{
		$this->formClass = 'Cadastro\Form\ExtracaoForm';
		$this->modelClass = 'Cadastro\Model\Extracao';
		$this->route = 'extracao';
		$this->title = 'Cadastro de Extracões';
		$this->label['yes']	= 'Sim';
		$this->label['no']	= 'Não';
		$this->label['add']	= 'Incluir';
		$this->label['edit'] = 'Alterar';
	}

}

?>