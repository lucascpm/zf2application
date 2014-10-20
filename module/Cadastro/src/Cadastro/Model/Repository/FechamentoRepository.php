<?php
/**
 * Created by PhpStorm.
 * User: hlm
 * Date: 09/07/14
 * Time: 16:13
 */

namespace Cadastro\Model\Repository;


use Doctrine\ORM\EntityRepository;
use DoctrineProxy\__CG__\Cadastro\Model\ExtracaoProgramada;
use Zend\Stdlib\DateTime;

/**
 *
 * Class FechamentoRepository
 * @package Cadastro\Model\Repository
 */
class FechamentoRepository extends EntityRepository {

    protected $modelClass = 'Cadastro\Model\Fechamento';

    /**
     * Busca um objeto Fechamento a partir de sua extraçãoProgramada
     * @param $extracaoProgramadaCod
     * @param $terminalSerial
     * @return mixed Fechamento ou null
     */
    public function buscaFechamento($extracaoProgramadaCod, $terminalSerial) {
        $qBuilder = $this->_em->createQueryBuilder();
        $qBuilder->select('f')->from($this->modelClass, 'f');

        $qBuilder->where('f.extracaoProgramada = '. $extracaoProgramadaCod.
                        ' AND f.terminal = '. $terminalSerial);

        return $qBuilder->getQuery()->getOneOrNullResult();

    }

    /**
     * Obtém a quantidade de fechamentos pendentes.
     * @param $filtros
     * @return integer
     */
    public function queryQntFechamentos($filtros) {
        //Testa o conteúdo do filtro data
        if(is_string($filtros['data']) ) {
            //Possibilidade de testar se está com apostrofos
            $data = dataBanco($filtros['data']);
        } elseif(is_a($filtros['data'], 'DateTime')) {
            $data = dataBanco($filtros['data']->format('d-m-Y') );
        }

        //obtendo o Entity Manager e criando um query builder para montar a query
        $qBuilder = $this->_em->createQueryBuilder();

        //iniciando a query
        $qBuilder->select('COUNT(f.id) as quantidade')->from('Cadastro\Model\Fechamento f JOIN f.extracaoProgramada ep ', null);

        $where = ' f.data_fechamento is null AND ep.extracao='.$filtros['extracao_id'];

        $where .= 'AND ep.data_extracao = ' . $data;

        $qBuilder->where($where);

        // Executa a query e retorna os objetos
        return $qBuilder->getQuery()->getSingleResult()['quantidade'];
    }


    public function queryListaFechamentos($filtros) {
        //obtendo o Entity Manager e criando um query builder para montar a query
        $qBuilder = $this->_em->createQueryBuilder();

        $qBuilder->select('f')->from('Cadastro\Model\Fechamento f JOIN f.extracaoProgramada ep ', null);

        $where = ' f.data_fechamento is null AND ep.extracao='.$filtros['extracao_id']
            .' AND ep.data_extracao=\''.$filtros['data']."' ";

        $qBuilder->where($where);
        // Executa a query e retorna os objetos
        return $qBuilder->getQuery()->execute();
    }
} 