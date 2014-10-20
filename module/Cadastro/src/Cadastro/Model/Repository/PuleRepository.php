<?php
/**
 * Created by PhpStorm.
 * User: hlm
 * Date: 01/07/14
 * Time: 21:55
 */

namespace Cadastro\Model\Repository;


use Cadastro\Model\Agencia;
use Cadastro\Model\ApostaPremiada;
use Cadastro\Model\Pule;
use Cadastro\Model\PulePremiada;
use Cadastro\Model\Resultado;
use Cadastro\Model\Rota;
use Doctrine\ORM\EntityRepository;
use \DateTime;

class PuleRepository extends EntityRepository {

    protected $modelClass = 'Cadastro\Model\Pule';

    /**
     * @deprecated
     * Calcula o valor total das apostas da pule
     * @param integer $pule_id Id da pule
     * @return float Valor total calculado
     */
    public function calculaValorTotalPule($pule_id) {
        $qBuilder = $this->_em->createQueryBuilder();
        $qBuilder->select('SUM(a.valor) as total')->from('Cadastro\Model\Aposta', 'a')
            ->where('a.pule = '.$pule_id);
        $result = $qBuilder->getQuery()->getSingleResult();

        return floatval($result['total']);
    }

    public function pulesPremiadasDaExtracaoProgramada($extracao_programada_cod) {
        $qBuilder = $this->_em->createQueryBuilder();

        $pulePremiada = 'Cadastro\Model\PulePremiada';

        $qBuilder->select('pp')->from($pulePremiada, "pp");
        $qBuilder->join('pp.pule', 'p');
//        die($qBuilder->getQuery()->getSQL());
        $qBuilder->where('p.extracaoProgramada = '.$extracao_programada_cod);



        $result = $qBuilder->getQuery()->getResult();

        return $result;
    }


    /**
     * Pesquisa todas as pules efetuadas em um determinado dia, opcionalmente com filtros para agência e rota.
     * @param DateTime $data
     * @param integer $agencia_id A agência como filtro para a pesquisa. Caso null, pesquisa todas as agências.
     * @param integer $rota_id A rota como filtro para a pesquisa. Caso null, pesquisa todas as rotas.
     * @return array Pule
     */
    public function  boletim(DateTime $data, $agencia_id = null, $rota_id = null, $ponto_id = null) {
        //obtendo o Entity Manager e criando um query builder para montar a query
        $qBuilder = $this->_em->createQueryBuilder();

        //iniciando a query
        $qBuilder->select('p')->from($this->modelClass, 'p');

        //Transforma $data em string já na formatação adequada para a query
        $data = $data->format('Y-m-d');
        //iniciando com string vazia para adicionar os possíveis WHERE (e AND)
        $where = "p.data_hora BETWEEN '$data 00:00:00' AND '$data 23:59:59'";

        // monta pesquisa com base nos filtros passados como parâmetros
        if($agencia_id) {
            $where .= ' AND p.agencia = '.$agencia_id;
        }
        if($rota_id) {
            $where .= ' AND p.rota = '.$rota_id;
        }
        if($ponto_id) {
            $where .= ' AND p.ponto = '.$ponto_id;
        }

        $where .= ' ORDER BY p.agencia, p.rota, p.ponto';

        //Adiciona a string $where à query
        $qBuilder->where($where);

        // Executa a query e retorna os objetos
        return $qBuilder->getQuery()->getResult();
    }

    /**
     * Obtém as pules das extrações programadas informadas e opcionalmente filtrando o ponto
     * @param array $extracoesProgramadas ExtracaoProgramada
     * @param null $ponto_id integer
     * @return array
     */
    public function guia(array $extracoesProgramadas, $ponto_id = null) {
        $pules = array();
        if($ponto_id) {
            foreach($extracoesProgramadas as $extracaoProg) {
                $pulesDaExtracao = $this->findBy(array('ponto'=>$ponto_id,'extracaoProgramada'=>$extracaoProg),
                    array('agencia'=>'ASC','rota'=>'ASC','ponto'=>'ASC'));
                foreach($pulesDaExtracao as $pule) {
                    array_push($pules, $pule);
                }
            }
        } else {
            foreach($extracoesProgramadas as $extracaoProg) {
                $pulesDaExtracao = $this->findBy(array('extracaoProgramada'=>$extracaoProg),
                    array('agencia'=>'ASC','rota'=>'ASC','ponto'=>'ASC'));
                foreach($pulesDaExtracao as $pule) {
                    array_push($pules, $pule);
                }
            }
        }

        return $pules;
    }

    /**
     * @param array $extracoesProgramadas
     * @param array $rotas
     * @return array
     */
    public function pulesDasExtracoes(array $extracoesProgramadas, $agenciaId = null, array $rotas=null, $pontoId = null) {
        $agenciaId = intval($agenciaId);
        $pontoId = intval($pontoId);
        $qBuilder = $this->_em->createQueryBuilder();


        //primeiro constroi as condições necessárias no WHERE
        foreach($extracoesProgramadas as $ep) {
            if(isset($where)) {
                $where .= ' OR ep.id = '.$ep->id;
            } else {
                $where = '(ep.id = '.$ep->id;
            }
        }
        //Fecha o parêntese do prmeiro bloco de condições
        $where .= ')';

        if($agenciaId) {
            $where .= ' AND (p.agencia = '.$agenciaId.')';
        }

        if($pontoId) {
            $where .= ' AND (p.ponto = '.$pontoId.')';
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

        $qBuilder->select('p')->from($this->modelClass, 'p')
                            ->join('p.extracaoProgramada', 'ep');
        if($rotas !== null && count($rotas)>0) {
            $qBuilder->join('p.rota', 'r');
        }
        $qBuilder->where($where)->orderBy('p.ponto');
//        die(var_dump($qBuilder->getQuery()->getDQL()));

        return $qBuilder->getQuery()->getResult();
    }

    /**
     * Função que pesquisa as pules do relatório de cobrança,
     * separando em um array que possui o código da rota como chave.
     * @param array $extracoesProgramadas
     * @param array $pontos
     * @return mixed
     */
    public function cobrancaPorPonto(array $extracoesProgramadas, $agenciaId, array $rotas=null) {
        $qBuilder = $this->_em->createQueryBuilder();


        //primeiro constroi as condições necessárias no WHERE
        foreach($extracoesProgramadas as $ep) {
            if(isset($where)) {
                $where .= ' OR ep.id = '.$ep->id;
            } else {
                $where = '(ep.id = '.$ep->id;
            }
        }
        //Fecha o parêntese do prmeiro bloco de condições
        $where .= ')';

        if( (int)$agenciaId) {
            $where .= ' AND (p.agencia = '.(int)$agenciaId.')';
        }

        if($rotas !== null && count($rotas) > 0) {
            $where .= 'AND (';

            for($i = 0; $i < count($rotas); $i++) {
                if($i != 0) {
                    $where .= ' OR r.codigo = '.$rotas[$i];
                } else {
                    $where .= 'r.codigo = '.$rotas[$i];
                }
            }
            $where .= ')';
        }

        $qBuilder->select('pon.id as id_ponto, pon.codigo as codigo_ponto, pon.nome as nome_ponto,
          r.id, r.codigo as codigo_rota, r.nome as nome_rota, SUM(a.valor) as vendas,
        (SUM(a.valor) * (1-(pon.per_comissao/100))) as liquido')
            ->from('Cadastro\Model\Aposta', 'a')
            ->join('a.pule', 'p')
            ->join('p.extracaoProgramada', 'ep')
            ->join('p.ponto', 'pon')
            ->join('p.rota', 'r')
            ->where($where)->groupBy('pon.id, r.id')->orderBy('pon.id');

//        die($qBuilder->getDQL());
        return $qBuilder->getQuery()->getResult();
    }

    /**
     * Função que busca uma pule no banco de dados através do seu número e do número serial do terminal.
     * @param $puleNumero
     * @param $terminalSerial
     * @return array
     */
    public function buscaPule($puleNumero, $terminalSerial) {
        $qBuilder = $this->_em->createQueryBuilder();

        $qBuilder->select('p')->from($this->modelClass, 'p')
            ->join('p.terminal', 't')
            ->where('p.numero = '.$puleNumero.' AND t.serial = '.$terminalSerial);
        return $qBuilder->getQuery()->getOneOrNullResult();
    }

    /**
     * Pesquisa as apostas de uma extração programada e verifica se existem premiadas comparando com o resultado passado
     * por parâmetro. Retornando um array de objetos Aposta.
     * @param $extracaoProgramada
     * @param Resultado $resultado
     * @return array Aposta
     */
    public function verificaApostasPremiadas($extracaoProgramada, Resultado $resultado) {
        $pules = $this->_em->getRepository($this->modelClass)->findBy(array('extracaoProgramada'=>$extracaoProgramada));
        var_dump($resultado->getArrayNumeros());
        echo "<br>";
        $apostasPremiadas = array();
        foreach($pules as $p) {
            foreach($p->apostas as $a) {

                $retorno = eh_premiada_aposta($a->tipoJogo->sigla, $a->numero, $a->escopoPremio->intervalo, $resultado->getArrayNumeros());
                if($retorno) {
                    echo "<br>";var_dump($retorno);echo "<br>";

                    echo "<br>Aposta pule ".$a->pule->numero." de número $a->numero, escopo ".$a->escopoPremio->intervalo." sorteado! <br>";
                    foreach($retorno as $r) {
                        echo "<br>Tipo ".$r['tipo']. " - ".$a->numero . " sorteado na posição ".$r['escopo']."<br>";
                    }

                    array_push($apostasPremiadas, $a);
                }
            }
        }
//        die();
        return $apostasPremiadas;
    }

    public function novaPule(Pule $pule) {
        $apostas = $pule->apostas;

        $pule->apostas = array();

        try{
            $this->_em->persist($pule);
            $this->_em->persist($pule->terminal->incrementaNumeroPule());
            $this->_em->flush();

            foreach($apostas as $aposta) {
                $aposta->pule = $pule;
                $this->_em->persist($aposta);
            }
            $this->_em->flush();
        } catch(\Exception $e) {
            //TODO Tratar melhor exceções para chamadas vindas do Servidor de Comunicação
            echo "Erro ao persistir pule: ".$e->getMessage();
            return false;
        }

        return true;
    }

} 