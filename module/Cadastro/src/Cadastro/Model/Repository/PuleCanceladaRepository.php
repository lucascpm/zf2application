<?php
/**
 * Created by PhpStorm.
 * User: hlm
 * Date: 07/09/14
 * Time: 15:25
 */

namespace Cadastro\Model\Repository;


use Cadastro\Model\ApostaCancelada;
use Cadastro\Model\Pule;
use Cadastro\Model\PuleCancelada;
use Doctrine\ORM\EntityRepository;

class PuleCanceladaRepository extends EntityRepository {

    protected $modelClass = 'Cadastro\Model\PuleCancelada';


    public function buscaPulesCanceladas(\DateTime $data, $extracaoId = null, $agenciaId=null, $rotaId=null, $pontoId=null, $invalidadas = null, $valorMinimo = null) {
        $extracaoId = intval($extracaoId);
        $rotaId = intval($rotaId);
        $valorMinimo = floatval($valorMinimo);

        $qBuilder = $this->_em->createQueryBuilder();

        $qBuilder->select('pc')->from($this->modelClass, 'pc')
                ->join('pc.extracaoProgramada', 'ep');
        $where = "ep.data_extracao = '".$data->format('Y-m-d')."'";

        if($rotaId) {
            $where .= ' AND pc.rota = '.$rotaId;
        }

        if($extracaoId) {
            $where .= ' AND ep.extracao = '.$extracaoId;
        }

        if($valorMinimo) {
            $where .= ' AND pc.valor_total > '.$valorMinimo;
        }

        if(!is_null($invalidadas) && $invalidadas) {
            $where .= ' AND pc.invalidada = true';
        } elseif(!is_null($invalidadas)) {
            $where .= ' AND pc.invalidada = false';
        }

        $qBuilder->where($where);

        return $qBuilder->getQuery()->execute();
    }

    public function cancelarPule(Pule $pule) {
        if(!$pule) {
            return false;
        }

        $pulePremiadaRepository = $this->_em->getRepository('Cadastro\Model\PulePremiada');
        //Exclui algum possível prêmio
        $pulePremiadaRepository->excluirPulePremiada($pule);

        //Instanciando a pule cancelada
        $puleCancelada = new PuleCancelada($pule);

        //Persiste a pule cancelada
        $this->_em->persist($puleCancelada);
        $this->_em->flush();

        //Instancia e persiste as Apostas Canceladas
        foreach($pule->apostas as $aposta) {
            $apostaCancelada = new ApostaCancelada($puleCancelada, $aposta);
            $this->_em->persist($apostaCancelada);
        }
        $this->_em->flush();

        //Excluir as apostas e em seguida a pule.
        foreach($pule->apostas as $aposta) {
            $this->_em->remove($aposta);
        }
        $this->_em->remove($pule);
        $this->_em->flush();

        return true;
    }
} 