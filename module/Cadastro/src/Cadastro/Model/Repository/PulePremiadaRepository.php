<?php
/**
 * Created by PhpStorm.
 * User: hlm
 * Date: 14/08/14
 * Time: 17:26
 */

namespace Cadastro\Model\Repository;


use Cadastro\Model\Aposta;
use Cadastro\Model\ApostaPremiada;
use Cadastro\Model\Pule;
use Cadastro\Model\PulePremiada;
use Cadastro\Model\Resultado;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityRepository;

class PulePremiadaRepository extends EntityRepository {

    protected $modelClass = 'Cadastro\Model\PulePremiada';

    /**
     * @param array $resultados
     */
    public function totalPremiosExtracaoPOperador(array $resultados)
    {
        $where = '(';
        foreach($resultados as $resultado) {
            if(strlen($where) == 1 ) {
                $where .= 'pp.resultado = '.$resultado->id;
            } else {
                $where .= ' OR pp.resultado = '.$resultado->id;
            }
        }
        $where .= ')';


        $qBuilder = $this->_em->createQueryBuilder();
        $qBuilder->select('IDENTITY(r.extracaoProgramada) as extracaoProgramada , IDENTITY(p.operador) as operador, SUM(ap.valor_premio ) as totalPremios')
                ->from('Cadastro\Model\ApostaPremiada', 'ap')
                ->join('ap.pulePremiada', 'pp')
                ->join('pp.resultado', 'r')
                ->join('pp.pule', 'p')
                ->join('p.extracao', 'e')
                ->where($where)
        ->groupBy('r.extracaoProgramada, p.operador')->orderBy('r.extracaoProgramada');

//        die($qBuilder->getDQL());
        return $qBuilder->getQuery()->getResult();
    }

    /**
     * @param array $extracoesProgramadas
     * @param null $ponto_id
     * @param null $premiosPagos
     * @return float
     */
    public function totalPremiosExtracoesProgramadas(array $extracoesProgramadas, $agenciaId = null, array $rotas = null, $pontoId = null, $premiosPagos=null) {
        $agenciaId = intval($agenciaId);
        $pontoId = intval($pontoId);

        //Primeiramente monta o where com as restrições de extrações programadas passadas por parâmetro.
        $where = '(';
        foreach($extracoesProgramadas as $extracaoProgramada) {
            if(strlen($where) == 1 ) {
                $where .= 'r.extracaoProgramada = '.$extracaoProgramada->id;
            } else {
                $where .= ' OR r.extracaoProgramada = '.$extracaoProgramada->id;
            }
        }
        $where .= ')';

        if($agenciaId) {
            $where .= ' AND (p.agencia = '.$agenciaId.')';
        }

        if($rotas !== null && count($rotas)>0) {
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


        if($pontoId) {
            $where .= ' AND (p.ponto = '.$pontoId.')';
        }

        //Testa se foi filtrado pelos prêmios pagos
        if(!is_null($premiosPagos)) {
            //pega apenas os prêmios pagos (que tem data de pagamento)
            if($premiosPagos) {
                $where .= ' AND (pp.data_hora_pagamento IS NOT NULL )';
            } else {
                $where .= ' AND (pp.data_hora_pagamento IS NULL )';
            }
        }

        $qBuilder = $this->_em->createQueryBuilder();
        $qBuilder->select('SUM(ap.valor_premio ) as total_premios')
            ->from('Cadastro\Model\ApostaPremiada', 'ap')
            ->join('ap.pulePremiada', 'pp')
            ->join('pp.resultado', 'r');
        if($pontoId !== null) {
            $qBuilder->join('pp.pule', 'p');
        }

        $qBuilder->where($where);

//        die($qBuilder->getDQL());
//        die(var_dump($qBuilder->getQuery()->getOneOrNullResult()['total_premios']));
        $resultadoTratado = (double) $qBuilder->getQuery()->getOneOrNullResult()['total_premios'];
        return $resultadoTratado;
    }


    public function totalPremioPorPonto(array $extracoesProgramadas, $pontoId) {
        $qBuilder = $this->_em->createQueryBuilder();
        $qBuilder->select('SUM(ap.valor_premio) as total_premio')
            ->from('Cadastro\Model\ApostaPremiada', 'ap')
            ->join('ap.pulePremiada', 'pp')
            ->join('pp.pule', 'p');

        //Monta o where com as restrições de extrações programadas passadas por parâmetro.
        $where = '(';
        foreach($extracoesProgramadas as $extracaoProgramada) {
            if(strlen($where) == 1 ) {
                $where .= 'p.extracaoProgramada = '.$extracaoProgramada->id;
            } else {
                $where .= ' OR p.extracaoProgramada = '.$extracaoProgramada->id;
            }
        }
        $where .= ')';

        if($pontoId !== null) {
            $where .= ' AND (p.ponto = '.$pontoId.')';
        }

        $qBuilder->where($where);

        $result = $qBuilder->getQuery()->getOneOrNullResult();
        $totalPremio = $result['total_premio'] == null? 0 : doubleval($result['total_premio']);

        return (double) $totalPremio;
    }


    public function pulesPremiadasNoResultado($resultado, $agenciaId = null, array $rotas = null,$pontoId = null,
                                              $extracaoId = null, $valorMin = 0, $origem = 'todos', $premiosPagos = null) {
        $agenciaId = intval($agenciaId);
        $pontoId = intval($pontoId);

        $qBuilder = $this->_em->createQueryBuilder();
        $qBuilder->select('pp')->from('Cadastro\Model\PulePremiada pp, Cadastro\Model\ApostaPremiada ap,
                    Cadastro\Model\Pule p, Cadastro\Model\ExtracaoProgramada ep, Cadastro\Model\Rota r', null);
//                ->join('ap.pulePremiada', 'pp')
//                ->join('pp.pule', 'p');
//                ->join('p.extracaoProgramada', 'ep');

        //Monta o where com as restrições de extrações programadas passadas por parâmetro.
        $where = '(pp.resultado = '.$resultado->id.')';

        if($rotas !== null && count($rotas)>0) {
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

        if($agenciaId) {
            $where .= ' AND p.agencia = '.$agenciaId;
        }

        if($pontoId) {
            $where .= ' AND p.ponto = '.$pontoId;
        }

        if($extracaoId) {
            $where .= ' AND ep.extracao = '.$extracaoId;
        }

        if($valorMin) {
            $where .= ' AND ap.valor_premio >= '.$valorMin;
        }

        //Valores da variável $origem devem conferir com o formulário PremioForm (e obviamente com a especificação)
        if($origem == 'apurados') {
            //Origem 0 significa apuração inicial
            $where .= ' AND pp.reapurado = FALSE';
        } elseif($origem == 'reapurados') {
            $where .= ' AND pp.reapurado = TRUE';
        }

        //Testa se foi filtrado pelos prêmios pagos
        if(!is_null($premiosPagos)) {
            //pega apenas os prêmios pagos (que tem data de pagamento)
            if($premiosPagos) {
                $where .= ' AND (pp.data_hora_pagamento IS NOT NULL )';
            } else {
                $where .= ' AND (pp.data_hora_pagamento IS NULL )';
            }
        }

        $where .= ' AND ap.pulePremiada = pp.id AND pp.pule = p.id AND p.extracaoProgramada = ep.id AND p.rota = r.id';
        $qBuilder->where($where);
//        die($qBuilder->getDQL());
        return $qBuilder->getQuery()->getResult();

    }

    public function gerarPulesPremiadas($extracaoProgramada, Resultado $resultado) {
        $pules = $this->_em->getRepository('Cadastro\Model\Pule')->findBy(array('extracaoProgramada'=>$extracaoProgramada));

        $pulesPremiadas = array();

        foreach($pules as $pule) {
            //Zerando
            $apostasSorteadas = array();
            foreach($pule->apostas as $a) {
                //Testa se é premiada
                $arraySorteadas = eh_premiada_aposta($a->tipoJogo->sigla, $a->numero, $a->escopoPremio->intervalo, $resultado->getArrayNumeros());
                if($arraySorteadas) {
                    //Cria as apostas premiadas
//                    echo "<br />Pule $pule->numero do terminal ".$pule->terminal->serial.
//                        " com Aposta premiada ($a->numero - ".$a->tipoJogo->sigla." - ".$a->escopoPremio->intervalo.")<br />".
//                                        "Valor ".$this->calculaValorPremio($a);
//                    echo "<br />".var_dump($arraySorteadas)."<br />";

                    //Separa as apostas sorteadas
                    foreach($arraySorteadas as $sorteada) {
                        //Cria uma nova chave ['aposta'] no array, contendo o objeto da aposta sorteada.
                        //Ficando assim as chaves ['escopo'], ['tipo'] e ['aposta']
                        $sorteada['aposta'] = $a;
                        array_push($apostasSorteadas, $sorteada);
                    }

                }
            }
            if(count($apostasSorteadas)) {
                //Existe(m) aposta(s) premiada(s) para essa pule

                //Cria uma nova pule premiada
                $pulePremiada = new PulePremiada($pule, $resultado);

                foreach($apostasSorteadas as $sorteada) {
                    $aposta = $sorteada['aposta'];
                    $escopo = $sorteada['escopo'];
                    //Calcula o valor do prêmio
                    $valorPremio = $this->calculaValorPremio($aposta);

                    //Cria a ApostaSorteada vinculando-a à PulePremiada
                    $apostaPremiada = new ApostaPremiada($pulePremiada, $aposta, $escopo, $valorPremio);

                    $pulePremiada->addApostaPremiada($apostaPremiada);
                }

                //Adiciona ao array de pules premiadas
                array_push($pulesPremiadas, $pulePremiada);
            }
        }
        return $pulesPremiadas;
    }

    public function calculaValorPremio(Aposta $aposta) {
        return ( (($aposta->valor / $aposta->tipoJogo->divisor_valor_aposta)/$aposta->escopoPremio->multip_valor_aposta)
            * $aposta->tipoJogo->premio_valor_mult);
    }

    /**
     * Pesquisa se a pule foi premiada e a exclui, caso positivo.
     * @param Pule $pule
     * @return bool Se obteve sucesso ou não.
     * @throws DBALException
     */
    public function excluirPulePremiada(Pule $pule) {
        $pulePremiada = $this->findBy(array('pule'=>$pule));

        if($pulePremiada) {
            $this->_em->remove($pulePremiada);
            $this->_em->flush();
            return true;
        } else {
            return false;
        }
    }


}

