<?php
/**
 * Created by PhpStorm.
 * User: hlm
 * Date: 01/10/14
 * Time: 12:15
 */

namespace Cadastro\Model\Repository;


use Cadastro\Model\Usuario;
use Doctrine\ORM\EntityRepository;

class UsuarioRepository extends EntityRepository {

    public function renovarToken(Usuario $usuario) {
        $dataAtual = new \DateTime();

        $usuario->token = rand(1111, 999999);
        $usuario->validade = new \DateTime('+1 day');

        //Persistir
        return $usuario;
    }

} 