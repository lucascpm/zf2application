<?php
/**
 * Created by PhpStorm.
 * User: hlm
 * Date: 08/07/14
 * Time: 12:30
 */

namespace Cadastro\Model\Repository;


use Doctrine\ORM\EntityRepository;
use Zend\Stdlib\DateTime;

class ExtracaoRepository extends EntityRepository {

    /**
     * Consulta quais as extrações disponíveis na data informada
     * @param DateTime $data
     */
    public function extracoesDisponiveisNaData(DateTime $data) {
        $qBuilder = $this->_em->createQueryBuilder();

        //Convertendo para string com 3 caracteres que representam o dia da semana
        $diaDaSemana = diaSemanaToStr($data->format('d-m-Y'));

        $qBuilder->select('e')->from('Cadastro\Model\Extracao', 'e')->where('e.'.$diaDaSemana . ' = TRUE');
        return $qBuilder->getQuery()->execute();
    }

    /**
     * Consulta se existe alguma extração para o dia da semana
     * @param $colunaDia string Uma string com 3 caracteres que representam o dia da semana
     * @return array Com as extrações ou null caso não tenham extrações para o dia
     */
    public function consultaExtracaoDiaSemana($colunaDia){
//        $em = $GLOBALS['entityManager'];
//        $qBuilder = $em->createQueryBuilder();
//        $qBuilder->select('e.id')->from('Cadastro\Model\Extracao', 'e')->where('e.'.$colunaDia . ' = TRUE');
//        return $qBuilder->getQuery()->execute();
        $result = $this->_em->getRepository('Cadastro\Model\Extracao')->findBy(array($colunaDia=>'TRUE'));
        return $result;
    }

} 