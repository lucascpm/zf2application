<?php
/**
 * Created by PhpStorm.
 * User: Zorro
 * Date: 6/18/14
 * Time: 8:51 PM
 */

namespace Cadastro\Model;

use Doctrine\ORM\Mapping as ORM;

use Abstrato\Entity\AbstractEntity;
use Abstrato\InputFilter\InputFilter;
use Zend\Authentication\AuthenticationService;
use \DateTime;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;

/**
 *
 * @Entity(repositoryClass="Cadastro\Model\Repository\ResultadoRepository")
 * @Table(name="resultados")
 */
class Resultado extends AbstractEntity {

    /** @Id @Column(type="bigint") @GeneratedValue **/
    public $id;

    /**
     * @var ExtracaoProgramada
     * /**
     * @OnetoOne(targetEntity="Cadastro\Model\ExtracaoProgramada")
     * @JoinColumn(name="extracao_programada_id", referencedColumnName="id")
     **/
    public $extracaoProgramada;

    /** @Column(type="smallint") **/
    public $n1;
    /** @Column(type="smallint") **/
    public $n2;
    /** @Column(type="smallint") **/
    public $n3;
    /** @Column(type="smallint") **/
    public $n4;
    /** @Column(type="smallint") **/
    public $n5;
    /** @Column(type="smallint") **/
    public $n6;
    /** @Column(type="smallint") **/
    public $n7;
    /** @Column(type="smallint") **/
    public $n8;
    /** @Column(type="smallint") **/
    public $n9;
    /** @Column(type="smallint") **/
    public $n10;

    /** @Column(type="datetime") **/
    public $data_digitacao;                       /* data digitação */

    /** @Column(type="datetime") **/
    public $data_confirmacao;                       /* data da confirmação */

    /**
     * @var Usuario
     * @OnetoOne(targetEntity="Cadastro\Model\Usuario")
     * @JoinColumn(name="usuario_digitacao_id", referencedColumnName="id")
     ***/
    public $usuarioDigitacao;

    /**
     * @var Usuario
     * @OnetoOne(targetEntity="Cadastro\Model\Usuario")
     * @JoinColumn(name="usuario_confirmacao_id", referencedColumnName="id")
     ***/
    public $usuarioConfirmacao;

    public function grupo($numeroPremio) {
        $numeroPremio = intval($numeroPremio);

        //Formatando o numero para string de 4 caracteres, dependendo
        switch($numeroPremio) {
            case 1 : {
                $numeroStr = str_pad($this->n1, 4, '0', STR_PAD_LEFT);
            }break;
            case 2 : {
                $numeroStr = str_pad($this->n2, 4, '0', STR_PAD_LEFT);
            }break;
            case 3 : {
                $numeroStr = str_pad($this->n3, 4, '0', STR_PAD_LEFT);
            }break;
            case 4 : {
                $numeroStr = str_pad($this->n4, 4, '0', STR_PAD_LEFT);
            }break;
            case 5 : {
                $numeroStr = str_pad($this->n5, 4, '0', STR_PAD_LEFT);
            }break;
            case 6 : {
                $numeroStr = str_pad($this->n6, 4, '0', STR_PAD_LEFT);
            }break;
            case 7 : {
                $numeroStr = str_pad($this->n7, 4, '0', STR_PAD_LEFT);
            }break;
            case 8 : {
                $numeroStr = str_pad($this->n8, 4, '0', STR_PAD_LEFT);
            }break;
            case 9 : {
                $numeroStr = str_pad($this->n9, 4, '0', STR_PAD_LEFT);
            }break;
            case 10 : {
                $numeroStr = str_pad($this->n10, 4, '0', STR_PAD_LEFT);
            }break;
            default :
            return $numeroPremio;
        }
        $dezena = substr($numeroStr, 2, 2);

        //Se o valor da dezena for 0 é o grupo 25 (vaca)
        if($dezena == 0 ) {
            return 0;
        }

        //Encontrando o grupo da dezena
        $grupo = $dezena/4;

        //Testa se o número contem parte decimal (Se o valor float for maior que a parte inteira do valor)
        if($grupo > intval($grupo)) {
            //Caso contenha uma parte decimal, deve ser acrescentado mais 1 no número do grupo
            return intval($grupo)+1;
        } else {
            return $grupo;
        }

    }

    public function getArrayNumeros() {
        return array($this->n1,
                        $this->n2,
                        $this->n3,
                        $this->n4,
                        $this->n5,
                        $this->n6,
                        $this->n7,
                        $this->n8,
                        $this->n9,
                        $this->n10,
                        );
    }

    public function getInputFilter()
    {
        // TODO: Implement getInputFilter() method.
        return new InputFilter();
    }


    public function exchangeArray($array)
    {

        foreach($array as $attribute => $value)
        {
            $this->$attribute = $value;
        }

        if(is_array($array)) {
            $em = $GLOBALS['entityManager'];

            $this->extracao = $em->getRepository('Cadastro\Model\Extracao')->find($array['extracao']);

            //Criando objeto DateTime para campo data_lancamento
            if(isset($array['data'])) {
                $data = DateTime::createFromFormat('d/m/Y', $array['data']);
                $this->data_digitacao = $data;
            }

            //Extração Programada

//            $this->extracaoProgramada = $extracaoProgramada;
            /*
             * Gera os outros cinco números do resultado a partir dos cinco digitados
             */
            $resultadoLoteria[] = str_pad($array['n1'], 4, 0, STR_PAD_LEFT);
            $resultadoLoteria[] = str_pad($array['n2'], 4, 0, STR_PAD_LEFT);
            $resultadoLoteria[] = str_pad($array['n3'], 4, 0, STR_PAD_LEFT);
            $resultadoLoteria[] = str_pad($array['n4'], 4, 0, STR_PAD_LEFT);
            $resultadoLoteria[] = str_pad($array['n5'], 4, 0, STR_PAD_LEFT);

            $numerosGeradosDaExtracao = geraResultados( $resultadoLoteria );

            $this->n6 = $numerosGeradosDaExtracao[5];   /* o indice começa de 0 e vai até 9 */
            $this->n7 = $numerosGeradosDaExtracao[6];
            $this->n8 = $numerosGeradosDaExtracao[7];
            $this->n9 = $numerosGeradosDaExtracao[8];
            $this->n10 = $numerosGeradosDaExtracao[9];
        }

    }

}