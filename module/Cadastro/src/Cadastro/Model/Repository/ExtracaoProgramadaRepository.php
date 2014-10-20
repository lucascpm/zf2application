<?php
/**
 * Created by PhpStorm.
 * User: hlm
 * Date: 02/07/14
 * Time: 21:35
 */

namespace Cadastro\Model\Repository;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use \DateTime;

class ExtracaoProgramadaRepository extends EntityRepository {

    protected $modelClass = 'Cadastro\Model\ExtracaoProgramada';


    public function extracoesDeHoje() {
        $extracoesProgramadas = $this->extracaoPNaData(new DateTime());

        return $extracoesProgramadas;
    }

    public function proximaExtracao() {
        //TODO Remover horário atual quando for entrar em produção
        $dataHoraAtual = new DateTime('10:40');

        $extracoesProgHoje = $this->extracoesDeHoje();

        //Verifica qual a próxima extração
        $extracoesCorretes = array();
        //Elimina as extrações já bloqueadas
        foreach($extracoesProgHoje as $eProg) {
            if($eProg->extracao->hora_bloqueio > $dataHoraAtual) {
                array_push($extracoesCorretes, $eProg);
            }
        }

        $extracaoProgramada = isset($extracoesCorretes[0]) ? $extracoesCorretes[0] : null;
        return $extracaoProgramada;
    }

    /**
     * Busca as extrações programadas existentes a partir de uma data base (data inclusiva).
     * @param DateTime $data
     * @return Array ExtracaoProgramada
     */
    public function extracoesAPartir(DateTime $data) {
        $qBuilder = $this->_em->createQueryBuilder();
        $qBuilder->select('ep')->from($this->modelClass, 'ep')
            ->where('ep.data_extracao >= '."'".$data->format('Y-m-d')."'");
        $result = $qBuilder->getQuery()->execute();
        return $result;
    }

    /**
     * Pesquisa se existem extrações programadas para a data informada, opcionalmente também leva em consederação o
     * tipo da extração. Nesse caso, será retornado um objeto ExtracaoProgramada.
     * @param DateTime $data A data que deve ser pesquisado
     * @param integer $extracao_id Caso seja informado um código de extração a consulta será para apenas
     *                              aquele tipo de extração na data informada, retornando apenas um resultado.
     * @return mixed Retorna um array, null ou, no caso de ser informado um id de extração, retorna um objeto.
     */
    public function extracaoPNaData(DateTime $data, $extracao_id = null, $ordem = null) {
        $qBuilder = $this->_em->createQueryBuilder();
        $qBuilder->select('ep')->from($this->modelClass, 'ep')->join('ep.extracao', 'e');
        $where = 'ep.data_extracao = '."'".$data->format('Y-m-d')."'";
        if($extracao_id) {
            $where .= ' AND ep.extracao = '.$extracao_id;
        }

        $qBuilder->where($where)->orderBy('e.hora_bloqueio','ASC');
        try {
            $result = $qBuilder->getQuery()->getResult();
        } catch(NoResultException $nre) {
            echo('Extração não encontrada!');
            return null;
        }
        if($extracao_id && $result) {
//            die(var_dump($result));
            return $result[0];
        }
        return $result;
    }

    /**
     * @deprecated
     * Verifica se já existe alguma extração programada
     * @param $extracao_id integer
     * @param $data_extracao DateTime data da extração
     * @return bool True se o registro já existe ou false
     */
    private function existeExtracaoProgramada($extracao_id, $data_extracao){
        $em = $GLOBALS['entityManager'];
        $qBuilder = $em->createQueryBuilder();
        $qBuilder->select('count(ep) as ocorrencias')->from($this->modelClass, 'ep')
            ->where('ep.extracao='.$extracao_id . ' and ep.data_extracao = ' . dataBanco(dateToStr($data_extracao)));
        $result = $qBuilder->getQuery()->getSingleResult();
        return $result['ocorrencias'] ? true : false;
    }

    /**
     * Retorna as extrações programradas existentes entre duas datas (inclusivas), opcionalmente filtrando as extrações
     * programadas passadas pelo paràmetro $extracoes
     * @param DateTime $data1
     * @param DateTime $data2
     * @return array
     */
    public function extracoesPEntre(DateTime $data1, DateTime $data2, $extracoes = null) {
        $qBuilder = $this->_em->createQueryBuilder();
        $qBuilder->select('ep')->from($this->modelClass, 'ep');
        $where = '(ep.data_extracao BETWEEN '."'".$data1->format('Y-m-d')."'
                                        AND '".$data2->format('Y-m-d')."')";

        if($extracoes != null && count($extracoes)) {
            $where .= ' AND (';
            $i=0;
            foreach($extracoes as $extracao) {
                if($i==0) {
                    $where .= 'ep.extracao = '.$extracao->id;
                } else {
                    $where .= ' OR ep.extracao = '.$extracao->id;
                }
                $i++;
            }
            $where .= ')';
        }
        $qBuilder->where($where);

        return $qBuilder->getQuery()->getResult();
    }


} 