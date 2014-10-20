<?php
/**
 * Created by PhpStorm.
 * User: hlm
 * Date: 09/09/14
 * Time: 20:34
 */

namespace Cadastro\Model\Repository;


use Doctrine\ORM\EntityRepository;

class PontoRepository extends EntityRepository {

    protected $modelClass = 'Cadastro\Model\Ponto';

    public function buscaListaPontos($agenciaId=null, $rotaId=null, $pontoId=null, $ativo=null) {
        $agenciaId = intval($agenciaId);
        $rotaId = intval($rotaId);
        $pontoId = intval($pontoId);

        $qBuilder = $this->_em->createQueryBuilder();

        $qBuilder->select('p')->from($this->modelClass, 'p');

        $where = '';

        if($pontoId) {
            $where .= 'p.id = '.$pontoId;
        } elseif($rotaId) {
            $where .= 'p.rota = '.$rotaId;
        } elseif($agenciaId) {
            $qBuilder->join('p.rota', 'r');
            $where .= 'r.agencia = '.$agenciaId;
        }

        if(!is_null($ativo) && $ativo) {
            $where .= $where == '' ? 'p.ativo = true' : ' AND p.ativo = true';
        } elseif(!is_null($ativo) && !$ativo) {
            $where .= $where == '' ? 'p.ativo = false' : ' AND p.ativo = false';
        }

        if($where != '') {
            $qBuilder->where($where);
        }

        return $qBuilder->getQuery()->getResult();
    }
} 