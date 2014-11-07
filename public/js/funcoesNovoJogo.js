/**
 * Created by alvaro on 9/23/14.
 */
/**
 * Created by glayson on 9/8/14.
 */

/******************** Criação de inputs **************************************/
/* Função para inserir inputs dinamicamente */
var qtdJogos = 1; //Quantidade de jogos por tipo de jogo
var codLinhaPule = 0; //Código da aposta atual
var totalApostas = 0;
var nomesCamposForm = ['numPule', 'extracao', 'tipojogo', 'qtd', 'premio', 'valorjogo'];
var idsCamposDaTabela  = ['pule_', 'extracao_', 'tipojogo_', 'qtd_', 'premio_', 'valorjogo_', 'valortotal_'];
//Teclas de eventos
var TECLA_CRIA_CAMPO_JOGO = 9;              //TAB
var TECLA_ENCERRA_CRIACAO_DE_CAMPOS = 32;   //Espaço
var TECLA_CONFIRMA_APOSTA1 = 67;             //C
var TECLA_CONFIRMA_APOSTA2 = 99;             //c
var TECLA_TOTALIZADOR1 = 84;                //T
var TECLA_TOTALIZADOR2 = 116;               //t
var totalizadorAtivado = false;
var valores = ['2', '1', '1', '1', '1', '9999'];
var escopo = ['01/05', '06/10', '01/10'];
var premiosEscopo = 1;
//TODO verificar o valor de cada tipo de jogo
var valorTipoJogo = 2;
var mascaraMoeda = "###.###.###.###,##";
var prefixoMoeda = "";
var mascaraData = "##/##/####";
var mascaraHora = "##:##";

/* Entra aqui quando for pressionada uma tecla em um campo com a classe teste */
$('body').on('keydown', '.teste', function(e) {
    if (e.which == TECLA_CRIA_CAMPO_JOGO) {
        //procura o último input com a classe teste e copia
        novoCampo = $("input.teste:last").clone();
        //insere a cópia depois do último input com a classe teste
        novoCampo.insertAfter("input.teste:last");
        //Ao chegar aqui, os dois últimos inputs estão com id igual, o id do último é corrigido
        novoCampo.prop('id', "jogo_"+qtdJogos);
        //$("input#jogo_"+cod+":last").attr("id", "jogo_"+qtdJogos);
        qtdJogos++;
        //Ao inserir um input, a quantidade de jogos aumenta
        atualizaQtdJogos();
    }
    else if(e.which == TECLA_ENCERRA_CRIACAO_DE_CAMPOS){
        //Evita que o caractere digitado seja inserido no input
        e.preventDefault();
        document.getElementsByName('premioini')[0].focus();
    }
});

$(document).ready(function(){
    alert('OK');
    criaLinhaTabelaPadrao();
    atualizaQtdJogos();
    atualizaTipoJogo(document.getElementsByName("tipojogo")[0]['value'][0]);
    var arrNomes = ['busca'];
    var arrValores = ['1'];
    var arrElementos = ['null'];
    var arrTagsXML = ['tamanho'];
    var arrBy = ['attr'];
    var arrAtributoElementos = ['maxlength'];
    var arrIdOuClasse = ['.teste'];

    totalJogos = $("input[name='valorjogo']").clone();
    //insere o campo hidden dentro do form
    totalJogos.insertAfter("input[name='valorjogo']");
    totalJogos.prop('id', "qtd_jogos_hidden");
    totalJogos.prop('name', "qtd_jogos_hidden");
    totalJogos.prop('type', "hidden");
    $('#qtd_jogos_hidden').val(codLinhaPule);


    pesquisaEAlteraCampos(arrNomes, arrValores, arrElementos, arrTagsXML, arrBy, arrAtributoElementos, arrIdOuClasse);
    try{
        setaTextoElementoTabela('extracao', 'extracao_0');
    }
    catch (e) {
        alert('Não há extrações cadastradas para hoje\nCadastre uma Extração');
    }
    document.getElementById('pule_'+codLinhaPule).innerHTML = document.getElementById('numPule').innerHTML;
    $('#pule_hidden_'+codLinhaPule).val(document.getElementById('numPule').innerHTML);
    textoPremio = document.getElementsByName('premioini')[0].value;// + ' - ' + document.getElementsByName('premiofim')[0].value;
    document.getElementById('premio_'+codLinhaPule).innerHTML = textoPremio;
    $("#premio_hidden_"+codLinhaPule).val(textoPremio);

    //adiciona a classe teste ao elemento com id jogo_0 para poder manipulá-lo
    //e manipular também os que forem criados
    $('#jogo_0').addClass('teste');

    $("select[name='extracao']").change(function(){
        setaTextoElementoTabela('extracao', 'extracao_'+codLinhaPule);
        setaTextoElementoTabela('extracao', 'extracao_hidden_'+codLinhaPule);
    });

    $("#imprimir").click(function(){
        $('#qtd_jogos_hidden').val(totalApostas);
    });

    $("select[name='tipojogo']").change(function(){
        //índice 0 é o objeto(select, no caso) e índice value é o atributo value do objeto
        var jogos = document.getElementsByName("tipojogo")[0]['value'];
        jogos[0] = jogos;
        atualizaTipoJogo(jogos);
        //Remove os campos
        for(var i=0; i< qtdJogos-1; i++){
            $('#'+$("input.teste:last").attr('id')).remove();
        }
        document.getElementById('qtd_'+(codLinhaPule)).innerHTML = 1;
        qtdJogos=1;
        $('#qtd').val(qtdJogos);
        valorTipoJogo = valores[(document.getElementsByName("tipojogo")[0]['value'])-1];
        document.getElementById('valortotal_'+codLinhaPule).innerHTML = atualizaValorTotal();
        $("#valortotal_hidden_"+codLinhaPule).val(atualizaValorTotal());
    });

    $("input[name='premioini']").keyup(function(){
        var tamanhoInput = $("input[name='premioini']").val().length;
        var textoInput = $("input[name='premioini']").val();
        var premioini = 1;
        var premiofim = 1;
        switch(tamanhoInput){
            case 1:
                if(textoInput <= escopo.length && textoInput > 0){
                    document.getElementById("premio_"+codLinhaPule).innerHTML = escopo[textoInput- 1];
                    $("#premio_hidden_"+codLinhaPule).val(escopo[textoInput- 1]);
                    premioini = document.getElementById('premio_'+codLinhaPule).innerHTML.substring(0, 2);
                    premiofim = document.getElementById('premio_'+codLinhaPule).innerHTML.substring(3, 5);
                    premiosEscopo = premiofim - premioini + 1;
                    document.getElementById('valortotal_'+codLinhaPule).innerHTML = atualizaValorTotal();
                }
                return;
            case 2:
                if(textoInput >= 0 && textoInput <= 10){
                    $("input[name='premioini']").val(textoInput.concat('/'));
                }
                else{
                    $("input[name='premioini']").val('');
                }
                break;
            case 5:
                premioini = textoInput.substring(0, 2);
                premiofim = textoInput.substring(3, 5);
                if(premiofim < premioini || premiofim > 10){
                    $("input[name='premioini']").val(textoInput.substring(0, 3));
                }
                break;
        }
        document.getElementById('premio_'+codLinhaPule).innerHTML = $("input[name='premioini']").val();
        ("#premio_hidden_"+codLinhaPule).val($("input[name='premioini']").val());

        premiosEscopo = premiofim - premioini + 1;
        document.getElementById('valortotal_'+codLinhaPule).innerHTML = atualizaValorTotal();
        $("#valortotal_hidden_"+codLinhaPule).val(atualizaValorTotal());
    });

    $("input[name='valorjogo']").keypress(function(e){
        if(e.which == TECLA_TOTALIZADOR1 || e.which == TECLA_TOTALIZADOR2){
            e.preventDefault();
            if(totalizadorAtivado){
                totalizadorAtivado = false;
                //remove a label totalizador
                $('.totalizador').remove();
            }
            else{
                totalizadorAtivado = true;
                //insere a label totalizador
                $("input[name='valorjogo']").after(
                    '<label class="totalizador">T</label>'
                );
            }
            document.getElementById('valortotal_'+codLinhaPule).innerHTML = atualizaValorTotal();
            $("#valortotal_hidden_"+codLinhaPule).val(atualizaValorTotal());
        }
    });

    $("input[name='valorjogo']").keyup(function(){
        $("input[name='valorjogo']").val(mascaraGenerica($("input[name='valorjogo']").val(),mascaraMoeda,true, prefixoMoeda));
        //Atualiza o valor do campo na tabela
        document.getElementById('valorjogo_'+codLinhaPule).innerHTML = document.getElementsByName('valorjogo')[0].value;
        //Atualiza o valor total
        document.getElementById('valortotal_'+codLinhaPule).innerHTML = atualizaValorTotal();
        //Atualiza os valores dos campos hidden
        $("#valorjogo_hidden_"+codLinhaPule).val(document.getElementsByName('valorjogo')[0].value);
        $("#valortotal_hidden_"+codLinhaPule).val(atualizaValorTotal());
    });

    $("input[name='premiofim']").change(function(){
        textoPremio = document.getElementsByName('premioini')[0].value + ' / ' + document.getElementsByName('premiofim')[0].value;
        document.getElementById('premio_'+codLinhaPule).innerHTML = textoPremio;
    });
    $("input[name='valorjogo']").change(function(){
        document.getElementById('valorjogo_'+codLinhaPule).innerHTML = document.getElementsByName('valorjogo')[0].value;
    });

});

function atualizaQtdJogos(){
    var cod = qtdJogos-1;
    $("#qtd").val(qtdJogos);
    //verifica se é milhar invertida
    if(document.getElementById('jogo_0').maxLength == 12)
        $(".teste").attr("maxlength", document.getElementById('jogo_0').value.length);
    document.getElementById('qtd_'+codLinhaPule).innerHTML = qtdJogos;
    $("#qtd_hidden_"+codLinhaPule).val(qtdJogos);
    //permite que o campo adicionado obtenha o foco
    $("#jogo_"+(cod-1)).focus();
    //$('#qtd').val(qtdJogos);
}

function salvaJogos(e){
    if (e.which == TECLA_CONFIRMA_APOSTA1 || e.which == TECLA_CONFIRMA_APOSTA2) {
        //For para salvar todos os jogos de cada tipo(M, C, D, ...) em inputs do tipo hidden
        //Formato de cada jogo: jogo_linha_id, onde linha é a linha da tabela e id é o jogo feito em cada linha
        for(var i=0; i < qtdJogos; i++){
            $('<input>',{
                type : 'hidden',
                id : 'jogo_'+codLinhaPule+'_'+i,
                name : 'jogo_'+codLinhaPule+'_'+i,
                value : $('#jogo_'+i).val()
            }).insertAfter("#excluir_"+codLinhaPule);
        }
        //Inicia uma nova aposta
        codLinhaPule++;
        for(var i=qtdJogos-1; i>0; i--){ //i > 0 para não remover o primeiro jogo
            $('#'+$("input.teste:last").attr('id')).remove();
        }

        totalApostas = $("#novojogo tr").length - 1; //-1 por causa da linha do cabeçalho

        //Atualiza a quantidade de jogos
        qtdJogos = 1;
        $('#qtd').val(qtdJogos);
        document.getElementById('qtd_'+codLinhaPule).innerHTML = qtdJogos;
        document.getElementsByName('tipojogo')[0].focus();
    }
}

function setaTextoElementoTabela(nomeElementoPai, idElementoFilho){
    texto = document.getElementsByName(nomeElementoPai)[0].options[document.getElementsByName(nomeElementoPai)[0].selectedIndex].innerHTML;
    document.getElementById(idElementoFilho).innerHTML = texto;
}

/* Coloca na label a sigla correspondente ao tipo de jogo */
function atualizaTipoJogo(valueSelect) {
    var urlBusca = "http://localhost/terminal-web/application/busca?busca="+valueSelect+"&submit=Pesquisar";
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            var tamInputs;
            var sel = xmlhttp.responseText;
            var valortotal;
            tamInputs = sel.substring(sel.search("<tamanho>")+9, sel.search("</tamanho>"));
            sel = sel.substring(sel.search("<response>")+10, sel.search("</response>"));
            document.getElementById('siglajogos').innerHTML = sel;
            document.getElementById('tipojogo_'+codLinhaPule).innerHTML = sel;
            $("#tipojogo_hidden_"+codLinhaPule).val(sel);
            $(".teste").attr("maxlength", tamInputs);
            $(".teste").attr("minlength", tamInputs);
            //$(".teste").attr("pattern", '^\d{'+tamInputs+'}');
            //Coloca os elementos da classe teste na variável input(array)
            var input = document.getElementsByClassName('teste');
            //Limpa os campos
            for(var i=0; i<input.length; i++)
                input[i].value = '';
        }
    }
    //get na url, de forma assíncrona
    xmlhttp.open("GET",urlBusca,true);
    xmlhttp.send();
}

/*** Função que realiza a pesquisa e altera o(s) campo(s) necessário(s) ***/
/**
 * arrNomesVariaveisGet: array com os nomes das variáveis que serão passadas por get
 * arrNomesVariaveisGet: array com os valores das variáveis que serão passadas por get
 * arrElementos: os elementos a serem alterados
 * arrTagsXML: tags onde ficam os contéudos de cada elemento
 * arrBy: informa o modo como vai pegar o elemento (id, name, attr ...)
 * arrAtributoElementos: informa o atributo a ser alterado de cada elemento (usar se arrBy for attr)
 * arrIdOuClasse: informa o id ou classe do(s) elemento(s) a ser(em) alterados(s), usa # para id e . para classe (usar se arrBy for attr)
 * **/
function pesquisaEAlteraCampos(arrNomesVariaveisGet, arrValoresVariaveisGet, arrElementos, arrTagsXML, arrBy, arrAtributoElementos, arrIdOuClasse) {
    var i;
    //Url com as variáveis passadas por get e seus respectivos valores
    var urlBusca = "http://localhost/pjbCliente/application/busca?";
    for(i = 0; i < arrNomesVariaveisGet.length; i++){
        urlBusca = urlBusca.concat(arrNomesVariaveisGet[i]+"="+arrValoresVariaveisGet[i]+"&");
    }
    urlBusca = urlBusca.concat("submit=Pesquisar");
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            var conteudosTags = [];
            var sel = xmlhttp.responseText;
            //Pega o contéudo contido em cada tag XML
            for(i=0; i<arrTagsXML.length; i++){
                conteudosTags[i] = getConteudoTagXML(sel, arrTagsXML[i]);
            }
            for(i=0; i<arrBy.length; i++){
                if(arrBy[i].toLowerCase() == 'id'){
                    document.getElementById(arrElementos[i]).innerHTML = conteudosTags[i];
                }
                else if(arrBy[i].toLowerCase() == 'name'){
                    document.getElementsByName(arrElementos[i])[0].innerHTML = conteudosTags[i];
                }
                else if(arrBy[i].toLowerCase() == 'attr'){
                    $(arrIdOuClasse[i]).attr(arrAtributoElementos[i], conteudosTags[i]);
                }
            }
        }
    }
    //get na url, de forma assíncrona
    xmlhttp.open("GET",urlBusca,true);
    xmlhttp.send();
}

/* Função que retorna o conteúdo de uma tagXML */
/**
 * str: a string com a página
 * tagXML: a tag a ser procurada
 * **/
function getConteudoTagXML(str, tagXML){
    return str.substring(str.search('<'+tagXML+'>')+tagXML.length+2, str.search('</'+tagXML+'>'));
}

/* Função que atualiza o valor total
 * Parâmetros: sem parâmetros
 * Retorno:    o valor total atualizado
 * **/
function atualizaValorTotal(){
    if(totalizadorAtivado)
        return parseFloat(document.getElementsByName('valorjogo')[0].value.replace(',','.')).toFixed(2);
    return parseFloat(((document.getElementsByName('valorjogo')[0].value.replace(',','.'))*premiosEscopo*(qtdJogos)*(valorTipoJogo))).toFixed(2);
}

function atualizaTotalApostas(inc){
    totalApostas += inc;
}

function mascaraGenerica(valor,m,r,prefixo, sufixo){
    // Variáveis da função
    var txt  = (!r) ? valor.replace(/[^\d]+/gi,'') : valor.replace(/[^\d]+/gi,'').reverse();
    var mask = (!r) ? m : m.reverse();
    var pre  = (prefixo ) ? prefixo : "";
    var pos  = (sufixo ) ? sufixo : "";
    var ret  = "";
    // Loop na máscara para aplicar os caracteres
    for(var x=0,y=0, z=mask.length;x<z && y<txt.length;){
        if(mask.charAt(x)!='#'){
            ret += mask.charAt(x); x++; }
        else {
            ret += txt.charAt(y); y++; x++; }
    }
    // Retorno da função
    ret = (!r) ? ret : ret.reverse();
    return pre+ret+pos;
}

// Novo método para o objeto 'String'
String.prototype.reverse = function(){
    return this.split('').reverse().join('');
};

function habilitaDesabilitaInput(nomeInput, valorCheckBox){
    document.getElementsByName(nomeInput)[0].disabled = true;
    //se o checkbox está marcado
    if(valorCheckBox == true){
        document.getElementsByName(nomeInput)[0].disabled = false;
    }
}