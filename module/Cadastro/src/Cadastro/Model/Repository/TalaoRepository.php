<?php
/**
 * Created by PhpStorm.
 * User: hlm
 * Date: 21/08/14
 * Time: 18:16
 */

namespace Cadastro\Model\Repository;


use Doctrine\ORM\EntityRepository;

class TalaoRepository extends EntityRepository {

    /**
     * @param array $extracoesProgramadas
     * @param integer $agenciaId
     * @param array $rotas
     * @return mixed
     */
    public function taloesPorPonto(array $extracoesProgramadas, $agenciaId, array $rotas) {
        $agenciaId = intval($agenciaId);

        $qBuilder = $this->_em->createQueryBuilder();
        $qBuilder->select('t')->from('Cadastro\Model\Talao', 't')
                ->join('t.rota', 'r');

        //primeiro constroi as condições necessárias no WHERE
        foreach($extracoesProgramadas as $ep) {
            if(isset($where)) {
                $where .= ' OR t.extracaoProgramada = '.$ep->id;
            } else {
                $where = '(t.extracaoProgramada = '.$ep->id;
            }
        }
        //Fecha o parêntese do prmeiro bloco de condições
        $where .= ')';

        if($agenciaId) {
            $where .= ' AND (t.agencia = '.$agenciaId.')';
        }

        if($rotas !== null && count($rotas) > 0) {
            $where .= ' AND (';

            for($i = 0; $i < count($rotas); $i++) {
                if($i != 0) {
                    $where .= ' OR r.codigo = '.$rotas[$i];
                } else {
                    $where .= 'r.codigo = '.$rotas[$i];
                }
            }
            $where .= ')';
        }

        $qBuilder->where($where);


        return $qBuilder->getQuery()->getResult();
    }


    public function taloesTotalizadosPorExtracao(array $extracoesProgramadas, $agenciaId, array $rotas) {
        $agenciaId = intval($agenciaId);

        $qBuilder = $this->_em->createQueryBuilder();
        $qBuilder->select('e.id as e_id, e.nome as extracao, p.id as p_id, p.per_comissao as comissao, SUM(t.valor) as total_bruto')
            ->from('Cadastro\Model\Talao', 't')
            ->join('t.rota', 'r')
            ->join('t.ponto', 'p')
            ->join('t.extracaoProgramada', 'ep')
            ->join('ep.extracao', 'e');

        //primeiro constroi as condições necessárias no WHERE
        foreach($extracoesProgramadas as $ep) {
            if(isset($where)) {
                $where .= ' OR t.extracaoProgramada = '.$ep->id;
            } else {
                $where = '(t.extracaoProgramada = '.$ep->id;
            }
        }
        //Fecha o parêntese do prmeiro bloco de condições
        $where .= ')';

        if($agenciaId) {
            $where .= ' AND (t.agencia = '.$agenciaId.')';
        }

        if($rotas !== null && count($rotas) > 0) {
            $where .= ' AND (';

            for($i = 0; $i < count($rotas); $i++) {
                if($i != 0) {
                    $where .= ' OR r.codigo = '.$rotas[$i];
                } else {
                    $where .= 'r.codigo = '.$rotas[$i];
                }
            }
            $where .= ')';
        }

        $qBuilder->where($where)->groupBy('e.id, p.id')->orderBy('e.nome');
//        die($qBuilder->getDQL());
        return $qBuilder->getQuery()->getResult();
    }

    public function buscaTaloesNaData(\DateTime $data, $agenciaId = null, $rotaId = null, $pontoId = null) {
        $agenciaId = intval($agenciaId);
        $rotaId = intval($rotaId);
        $pontoId = intval($pontoId);


        if($pontoId) {
            $taloes = $this->_em->getRepository('Cadastro\Model\Talao')
                ->findBy(array('data_lancamento'=>$data,
                    'ponto'=>$pontoId));
        } elseif($rotaId) {
            $taloes = $this->_em->getRepository('Cadastro\Model\Talao')
                ->findBy(array('data_lancamento'=>$data,
                    'rota'=>$rotaId));
        } elseif($agenciaId) {
            $taloes = $this->_em->getRepository('Cadastro\Model\Talao')
                ->findBy(array('data_lancamento'=>$data,
                    'agencia'=>$agenciaId, 'rota'=>$rotaId));
        } else {
            $taloes = $this->_em->getRepository('Cadastro\Model\Talao')
                ->findBy(array('data_lancamento'=>$data));
        }

        return $taloes;
    }

} 