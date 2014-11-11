<?php
/**
 * Created by PhpStorm.
 * User: hlm
 * Date: 03/06/14
 * Time: 21:12
 */

namespace Cadastro\Controller;


use Abstrato\Mvc\Controller\AbstractDoctrineCrudController;

class TipoJogoController extends AbstractDoctrineCrudController {

    public function __construct()
    {
        $this->formClass = 'Cadastro\Form\TipoJogoForm';
        $this->modelClass = 'Cadastro\Model\TipoJogo';
        $this->route = 'tipojogo';
        $this->title = 'Cadastro de Tipos de Jogo';
        $this->campoBusca = 'nome';
        $this->label['yes']	= 'Sim';
        $this->label['no']	= 'Não';
        $this->label['add']	= 'Incluir';
        $this->label['edit'] = 'Alterar';
    }

    public function indexAction() {
        $viewModel = parent::indexAction();

        $viewModel->setVariable('formPesquisa', $this->getPesquisaForm());

        return $viewModel;
    }

} 