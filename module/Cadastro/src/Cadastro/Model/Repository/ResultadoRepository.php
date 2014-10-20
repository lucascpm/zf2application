<?php
/**
 * Created by PhpStorm.
 * User: hlm
 * Date: 07/07/14
 * Time: 11:58
 */

namespace Cadastro\Model\Repository;


use Cadastro\Model\Resultado;
use Doctrine\ORM\EntityRepository;

class ResultadoRepository extends EntityRepository {

    protected $modelClass = 'Cadastro\Model\Resultado';

    /**
     * Retorna o resultado referente à extração programada
     * @param integer $extracao_programada_cod
     * @return Resultado
     * @throws \Exception
     */
    public function resultadoNaExtracaoProgramada($extracao_programada_cod) {
        if(!isset($extracao_programada_cod)) {
            throw new \Exception('Não existe codigo da extração prog');
        }

        $qBuilder = $this->_em->createQueryBuilder();
        //iniciando a query
        $result = $qBuilder->select('r')->from($this->modelClass, 'r')->where('r.extracaoProgramada = '.$extracao_programada_cod)
                ->getQuery()->getOneOrNullResult();

        return $result;
    }

    public function existeResultado($extracaoProgramadaCod) {
        if(!isset($extracaoProgramadaCod)) {
            throw new \Exception('Não existe codigo da extração prog');
        }

        $qBuilder = $this->_em->createQueryBuilder();
        //iniciando a query
        $qBuilder->select('COUNT(r) as resultado')->from($this->modelClass, 'r')->where('r.extracaoProgramada = ' . $extracaoProgramadaCod);

        // Executa a query e retorna os objetos
        $result = $qBuilder->getQuery()->getSingleResult();
        $existe = $result['resultado']? true : false;

        return $existe;
    }

    /**
     * Pesquisa quais os resultados que não possuem uma data de confirmação cadastrada.
     * @return array Resultado
     */
    public function resultadosNaoConfirmados() {
        $qBuilder = $this->_em->createQueryBuilder();
        $qBuilder->select('r')->from($this->modelClass, 'r')
            ->join('r.extracaoProgramada', 'ep')
            ->where('r.data_confirmacao IS NULL')
            ->orderBy('ep.data_extracao', 'ASC');
        return $qBuilder->getQuery()->getResult();
    }

    /**
     * Função que busca os resultados de uma data, podendo passar opcionalmente o id da extração.
     * @param \DateTime $data
     * @param boolean $confirmados
     * @param null $extracaoId
     * @return array Resultado
     */
    public function buscaResultados(\DateTime $data, $confirmados = false, $extracaoId = null) {
        $qBuilder = $this->_em->createQueryBuilder();
        $qBuilder->select('r')->from($this->modelClass, 'r')
            ->join('r.extracaoProgramada', 'ep');

        $where = "ep.data_extracao = '".$data->format('Y-m-d')."'";
        if($extracaoId) {
            $where .= ' AND ep.extracao = '.$extracaoId;
        }
        if($confirmados) {
            $where .= ' AND r.data_confirmacao IS NOT NULL';
        }
        $qBuilder->where($where);
        return $qBuilder->getQuery()->getResult();
    }

} 