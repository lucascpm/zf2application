<?php
namespace Cadastro\Model\Repository;

use Doctrine\ORM\EntityRepository;

class TipoJogoRepository extends EntityRepository {


    /**
     * @param string $result
     */
    public function getTamanhoInputPelaSigla($result){
        //Estrutura dos jogos
        //          sigla   descrição           dígitos fator
        $tps = 'M,Milhar,4,4000;C,Centena,3,500;D,Dezeeeeena,2,100;MC,Milhar Centena,4,4500;CI,Centena Invertida,12,500;MI,Milhar Invertida,12,4500;CD,Centena Dezena,3,150;DI,Dezena Invertida,2,50;MCI,MC Invertida,12,4500;TG,Terno de Grupo,6,100;DG,Duque de Grupo,2,100;MCD,Milhar Cen Dez,4,100;G,Grupo,2,100;CG,Combinado Grupo,4,100;DD,Duque de Dezena,2,100;TD,Terno Dezena,6,100;MD,Milhar Dezena,4,100';

        $tipos = '';

        $tps = explode(';', $tps);
        foreach($tps as $tp){
            $tips = explode(',', $tp);
            $tipos[] = array(
                'sigla' => $tips[0],
                'descricao' => $tips[1],
                'digitos' => $tips[2],
                'fator' => $tips[3]);
        }

        for($i=0; $i < count($this->tipos); $i++){
            if($tipos[$i]['sigla'] == $result){
                return $this->tipos[$i]['digitos'];
            }
        }
        //Não encontrou o tipo
        return 0;
    }
}