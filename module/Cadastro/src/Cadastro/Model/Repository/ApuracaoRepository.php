<?php
/**
 * Created by PhpStorm.
 * User: hlm
 * Date: 07/07/14
 * Time: 11:23
 */

namespace Cadastro\Model\Repository;


use Doctrine\ORM\EntityRepository;
use \DateTime;

class ApuracaoRepository extends EntityRepository {

    protected $modelClass = 'Cadastro\Model\Apuracao';

    /**
     * Verifica se existe registro de apuração referente à extração programada informada por parâmetro
     * @param $extracao_programada_cod int Código da extração programada
     * @return boolean
     */
    public function  existeApuracao($extracao_programadaId) {

        if(!isset($extracao_programadaId)) {
            throw new \Exception('Não existe Id da extração prog');
        }

        $qBuilder = $this->_em->createQueryBuilder();
        //iniciando a query
        $qBuilder->select('COUNT(a) as apurado')->from($this->modelClass, 'a')->where('a.extracaoProgramada = ' . $extracao_programadaId);

        // Executa a query e retorna os objetos
        $result = $qBuilder->getQuery()->getSingleResult();
        $apurado = $result['apurado']? true : false;

        return $apurado;
    }

    /**
     * Busca as apurações entre duas datas(data inclusiva).
     * @param DateTime $data1
     * @param DateTime $data2
     * @return Array Apuracao
     */
    public function apuracoesEntre($data1, $data2) {
        /*
        $select = 'id,  extracao_programada_id, data_hora, usuario_id, status, resultado_id, tot_vendas';
        $from ='APURACOES';
        $where = 'to_char(data_hora, \'yyyy-mm-dd\') >= '."'".$data1->format('Y-m-d')."'".
                 ' AND to_char(data_hora, \'yyyy-mm-dd\') <= '.dataBanco($data2);

        $rsm = new \Doctrine\ORM\Query\ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('extracao_programada_id', 'extracao_programada_id');
        $rsm->addScalarResult('data_hora', 'data_hora');
        $rsm->addScalarResult('usuario_id', 'usuario_id');
        $rsm->addScalarResult('status', 'status');
        $rsm->addScalarResult('resultado_id', 'resultado_id');
        $rsm->addScalarResult('tot_vendas', 'tot_vendas');
        $queryMontada = 'SELECT '.$select.' FROM '.$from.' WHERE '.$where;

        $nq = $em->createNativeQuery( $queryMontada, $rsm);

        $result = $nq->getResult();
        */

        $qBuilder = $this->_em->createQueryBuilder();
        $qBuilder->select('a')->from($this->modelClass, 'a')
            ->join('a.extracaoProgramada', 'ep')
            ->where("ep.data_extracao BETWEEN '".$data1->format('Y-m-d').
                                "' AND '".$data2->format('Y-m-d')."'");
        $result = $qBuilder->getQuery()->getResult();

        return $result;
    }
}


