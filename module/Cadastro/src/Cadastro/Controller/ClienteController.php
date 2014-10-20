<?php
/**
 * Created by PhpStorm.
 * User: hlm
 * Date: 09/06/14
 * Time: 21:30
 */

namespace Cadastro\Controller;


use Abstrato\Mvc\Controller\AbstractDoctrineCrudController;

class ClienteController extends AbstractDoctrineCrudController {

    public function __construct()
    {
        $this->formClass = 'Cadastro\Form\ClienteForm';
        $this->modelClass = 'Cadastro\Model\Cliente';
        $this->route = 'cliente';
        $this->title = 'Cadastro de Clientes';
        $this->campoBusca = 'razao_social';
        $this->label['yes']	= 'Sim';
        $this->label['no']	= 'Não';
        $this->label['add']	= 'Incluir';
        $this->label['edit'] = 'Alterar';
    }

    public function indexAction() {
        $viewModel = parent::indexAction();

        $form = $this->getPesquisaForm();
        $viewModel->setVariable('formPesquisa', $form);

        return $viewModel;
    }
} 