var ID_LINHA = 0;
var TECLA1_CRIA_LINHA = 67;
var TECLA2_CRIA_LINHA = 99;

function criaLinhaTabelaPadrao(evt){
    var strTable = '<tr>';
    //Se o evento for keyCode e não digitou a tecla para criar linha, a linha não deve ser criada
    if(evt){
        if(evt.keyCode != TECLA1_CRIA_LINHA && evt.keyCode != TECLA2_CRIA_LINHA){
            return;
        }
        //Se digitou a tecla para criar linha, não escreve ela no input
        evt.preventDefault();
    }
    strTable += criaLinhaTabela(
        'tag:label;id:pule_',
        'tag:label;id:extracao_',
        'tag:label;id:tipojogo_',
        'tag:label;id:qtd_',
        'tag:label;id:premio_',
        'tag:label;id:valorjogo_',
        'tag:label;id:valortotal_',
        'tag:input;type:button;id:excluir_;onclick:RemoveTableRow(this,event);value:Excluir',
        //Campos hidden
        'tag:input;type:hidden;id:pule_hidden_',
        'tag:input;type:hidden;id:extracao_hidden_',
        'tag:input;type:hidden;id:tipojogo_hidden_',
        'tag:input;type:hidden;id:qtd_hidden_',
        'tag:input;type:hidden;id:premio_hidden_',
        'tag:input;type:hidden;id:valorjogo_hidden_',
        'tag:input;type:hidden;id:valortotal_hidden_'
    );

    strTable += '</tr>';
    $("#novojogo tbody").append(strTable);
    $("#nome_"+(ID_LINHA-1)).focus(); //Coloca o focus na última linha criada
}

function criaLinhaTabela(){
    var argumentoAtual;
    var INDICE_TAG = 0;
    var tag;
    var valorEntreTags ='';
    var strTable = '';
    for (var i=0; i<arguments.length; ++i){
        argumentoAtual = arguments[i].split(';');
        tag = argumentoAtual[INDICE_TAG].split(':')[1];
        if(tag == 'input' && argumentoAtual[1].split(':')[1] == 'hidden'){
            strTable += '<' + tag + ' ';
        }
        else{
            strTable += '<td><' + tag + ' ';
        }
        for(j=1; j<argumentoAtual.length; j++){
            if(tag != 'input' && argumentoAtual[j].split(':')[0] == 'value'){
                valorEntreTags = argumentoAtual[j].split(':')[1];
            }
            else{
                if(argumentoAtual[j].split(':')[0] == 'id'){
                    strTable += argumentoAtual[j].split(':')[0] + '=\"' + argumentoAtual[j].split(':')[1] + ID_LINHA + '\"';
                } else{
                    strTable += argumentoAtual[j].split(':')[0] + '=\"' + argumentoAtual[j].split(':')[1] + '\"';
                }

            }
        }
        strTable += '>';
        if(tag != 'input'){
            strTable += valorEntreTags + '</' + tag + '>';
        }
        if(tag != 'input' && argumentoAtual[1].split(':')[1] != 'hidden'){
            strTable += '</td>';
        }
    }
    ID_LINHA++;
    return strTable;
}

/* http://webdevacademy.com.br/tutoriais/javascript-ajax/remover-linhas-tabela-jquery/ */
function RemoveTableRow(handler, evt) {
    evt.preventDefault();
    var tr = $(handler).closest('tr');
    //tr.fadeOut(400, function(){
        tr.remove();
    //});
    return false;
}