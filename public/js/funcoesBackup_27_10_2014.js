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
/* Entra aqui quando for pressionada uma tecla em um campo com a classe teste */
$('body').on('keydown', '.teste', function(e) {
    if (e.which == TECLA_CRIA_CAMPO_JOGO) {
        //procura o último input com a classe teste e copia
        novoCampo = $("input.teste:last").clone();
        //insere a cópia depois do último input com a classe teste
        novoCampo.insertAfter("input.teste:last");
        var cod = qtdJogos-1;
        //Ao chegar aqui, os dois últimos inputs estão com id igual, o id do último é corrigido
        novoCampo.prop('id', "jogo_"+qtdJogos);
        //$("input#jogo_"+cod+":last").attr("id", "jogo_"+qtdJogos);
        qtdJogos++;
        //Ao inserir um input, a quantidade de jogos aumenta
        $("input#qtd:first").attr("value", qtdJogos);
        //verifica se é milhar invertida
        if(document.getElementById('jogo_0').maxLength == 12)
            $(".teste").attr("maxlength", document.getElementById('jogo_0').value.length);
        document.getElementById('qtd_'+codLinhaPule).innerText = qtdJogos;
        $('#qtd').val(qtdJogos);
    }
    else if(e.which == TECLA_ENCERRA_CRIACAO_DE_CAMPOS){
        //Evita que o caractere digitado seja inserido no input
        e.preventDefault();
        document.getElementsByName('premioini')[0].focus();
    }
});

$(document).ready(function(){
    alert('OK');
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
    document.getElementById('pule_0').innerText = document.getElementById('numPule').innerText;
    textoPremio = document.getElementsByName('premioini')[0].value;// + ' - ' + document.getElementsByName('premiofim')[0].value;
    document.getElementById('premio_'+codLinhaPule).innerText = textoPremio;

    //adiciona a classe teste ao elemento com id jogo_0 para poder manipulá-lo
    //e manipular também os que forem criados
    $('#jogo_0').addClass('teste');

    $("select[name='extracao']").change(function(){
        setaTextoElementoTabela('extracao', 'extracao_'+codLinhaPule);
    });

    $("select[name='tipojogo']").change(function(){
        //índice 0 é o objeto(select, no caso) e índice value é o atributo value do objeto
        var jogos = document.getElementsByName("tipojogo")[0]['value'];
        jogos[0] = jogos;
        setaTexto(jogos);
        //Remove os campos
        for(var i=0; i< qtdJogos-1; i++){
            $('#'+$("input.teste:last").attr('id')).remove();
        }
        qtdJogos=1;
        $('#qtd').val(qtdJogos);
        document.getElementById('qtd_'+(qtdJogos-1)).innerText = qtdJogos;
        valorTipoJogo = valores[(document.getElementsByName("tipojogo")[0]['value'])-1];
        document.getElementById('valortotal_'+codLinhaPule).innerText = atualizaValorTotal();
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
                    premioini = document.getElementById('premio_'+codLinhaPule).innerText.substring(0, 2);
                    premiofim = document.getElementById('premio_'+codLinhaPule).innerText.substring(3, 5);
                    premiosEscopo = premiofim - premioini + 1;
                    document.getElementById('valortotal_'+codLinhaPule).innerText = atualizaValorTotal();
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
        document.getElementById('premio_'+codLinhaPule).innerText = $("input[name='premioini']").val();
        premiosEscopo = premiofim - premioini + 1;
        document.getElementById('valortotal_'+codLinhaPule).innerText = atualizaValorTotal();
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
            document.getElementById('valortotal_'+codLinhaPule).innerText = atualizaValorTotal();
        }
    });

    $("input[name='valorjogo']").keyup(function(){
        //var tamanhoInput = $("input[name='valorjogo']").val().length;
        //var textoInput = $("input[name='valorjogo']").val();
        //if(tamanhoInput >= 3){
        //Remove a vírgula, para saber o valor total em centavos
        //textoInput = textoInput.replace(",", "");
        //alert(textoInput);
        //var reais = parseInt(textoInput/100);
        //alert(reais);
        //var centavos = textoInput.substr(-2);
        //alert(textoInput.substring(tamanhoInput-2, tamanhoInput));
        //$("input[name='valorjogo']").val(reais + ',' + centavos);
        //Formata o campo de acordo com a máscara da moeda
        //$("input[name='valorjogo']").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
        $("input[name='valorjogo']").val(Moeda($("input[name='valorjogo']").val(), ','));
        $("input[name='valorjogo']").val(moedaMask($("input[name='valorjogo']").val(),"###.###.###,##",true, "R$ "));
        //Atualiza o valor do campo na tabela
        document.getElementById('valorjogo_'+codLinhaPule).innerText = document.getElementsByName('valorjogo')[0].value;
        //}
        //else if(tamanhoInput == 1)
        //document.getElementById('valorjogo_'+codLinhaPule).innerText = "00,"+document.getElementsByName('valorjogo')[0].value;
        //else if(tamanhoInput == 2)
        //document.getElementById('valorjogo_'+codLinhaPule).innerText = "0,"+document.getElementsByName('valorjogo')[0].value;
        //Atualiza o valor total
        document.getElementById('valortotal_'+codLinhaPule).innerText = atualizaValorTotal();
    });

//    /* Elemento genérico, altera o filho, de acordo com o valor selecionado do pai */
//    $("select").change(function () {
//        var elemento = this.name;
//        if(elemento != 'tipojogo'){
//            var texto = document.getElementsByName(elemento)[0].options[document.getElementsByName(elemento)[0].selectedIndex].innerText;
//            document.getElementById(elemento+"_"+codLinhaPule).innerText = texto;
//        }
//    });

    $("input[name='premiofim']").change(function(){
        textoPremio = document.getElementsByName('premioini')[0].value + ' / ' + document.getElementsByName('premiofim')[0].value;
        document.getElementById('premio_'+codLinhaPule).innerText = textoPremio;
    });
    $("input[name='valorjogo']").change(function(){
        document.getElementById('valorjogo_'+codLinhaPule).innerText = document.getElementsByName('valorjogo')[0].value;
    });
    $("input[name='valorjogo']").keypress(function(e){
        if (e.which == TECLA_CONFIRMA_APOSTA1 || e.which == TECLA_CONFIRMA_APOSTA2) {
            //TODO corrigir id de cada linha
            //Inserção de linhas na tabela
            //novaLinha = $("tr.linhapule:last").clone();
            //Evita conflitos
            //novaLinha.removeClass('linhapule');
            var codProxLinha;
            codProxLinha = codLinhaPule+1;
            //Insere uma linha na tabela
            $('table:last').append(
                '<tr class="linhapule">'+
                    '<td><label id="'+ idsCamposDaTabela[0]+codProxLinha +'"</label>'+document.getElementById(idsCamposDaTabela[0]+codLinhaPule).innerText+'</td>'+
                    '<td><label id="'+ idsCamposDaTabela[1]+codProxLinha +'"</label>'+document.getElementById(idsCamposDaTabela[1]+codLinhaPule).innerText+'</td>'+
                    '<td><label id="'+ idsCamposDaTabela[2]+codProxLinha +'"</label>'+document.getElementById(idsCamposDaTabela[2]+codLinhaPule).innerText+'</td>'+
                    '<td><label id="'+ idsCamposDaTabela[3]+codProxLinha +'"</label>'+document.getElementById(idsCamposDaTabela[3]+codLinhaPule).innerText+'</td>'+
                    '<td><label id="'+ idsCamposDaTabela[4]+codProxLinha +'"</label>'+document.getElementById(idsCamposDaTabela[4]+codLinhaPule).innerText+'</td>'+
                    '<td><label id="'+ idsCamposDaTabela[5]+codProxLinha +'"</label>'+document.getElementById(idsCamposDaTabela[5]+codLinhaPule).innerText+'</td>'+
                    '<td><label id="'+ idsCamposDaTabela[6]+codProxLinha +'"</label>'+document.getElementById(idsCamposDaTabela[6]+codLinhaPule).innerText+'</td>'+
                    '<td><label id="excluir_' +codProxLinha +'"</label>x</td>'+
                    '</tr>'
            );
            //insere a cópia depois do último tr(linha da tabela) com a classe linhapule
//            novaLinha.insertAfter("tr.linhapule:last");
//            var codLinhaAnt;
//            codLinhaAnt = codLinhaPule;
//            codLinhaPule++;
//            novaLinha.addClass('cod_'+codLinhaPule);
//            for(var i=0; i<idsCamposDaTabela.length; i++){
//                //Altera o id da última linha da pule, para corrigir o valor de cada campo
//                $("#"+idsCamposDaTabela[i]+codLinhaAnt+":last").prop("id", idsCamposDaTabela[i]+codLinhaPule);
//                alert('Antes: ' + idsCamposDaTabela[i]+codLinhaAnt + '\nDepois: ' + idsCamposDaTabela[i]+codLinhaPule);
//            }
            //procura e copia cada elemento
            //Ao chegar aqui, os dois últimos inputs estão com id igual, o id do último é corrigido
            //TODO Criar um elemento hidden para cada campo
            var tipoElemento;
            for(var i=0; i < idsCamposDaTabela.length; i++){
//                alert('campo inserido: ' + idsCamposDaTabela[i]+codLinhaPule+"_hidden" +
//                    '\nvalor: ' + document.getElementById(idsCamposDaTabela[i]+codLinhaPule).innerText);
                novoCampo = $("input[name='valorjogo']").clone();
                //insere o campo hidden dentro do form
                novoCampo.insertAfter("input[name='valorjogo']");
                novoCampo.prop('id', idsCamposDaTabela[i]+codLinhaPule+"_hidden");
                novoCampo.prop('name', idsCamposDaTabela[i]+codLinhaPule+"_hidden");
                novoCampo.prop('type', "hidden");
                tipoElemento = document.getElementById(idsCamposDaTabela[i]);
                try{
                    novoCampo.prop('value', document.getElementById(idsCamposDaTabela[i]+codLinhaPule).innerText);
                }
                catch (e){
                    console.log(e);
                }
            }
            //For para salvar todos os jogos de cada tipo(M, C, D, ...) em inputs do tipo hidden
            //Formato de cada jogo: jogo_linha_id, onde linha é a linha da tabela e id é o jogo feito em cada linha
            for(var i=0; i < qtdJogos; i++){
                novoCampo = $("input[name='valorjogo']").clone();
                //insere o campo hidden dentro do form
                novoCampo.insertAfter("input[name='valorjogo']");
                novoCampo.prop('id', 'jogo_'+codLinhaPule+'_'+i);
                novoCampo.prop('name', 'jogo_'+codLinhaPule+'_'+i);
                novoCampo.prop('type', "hidden");
                try{
                    novoCampo.prop('value', $('#jogo_'+i).val());
                }
                catch (e){
                    console.log(e);
                }
            }
            //Inicia uma nova aposta
            codLinhaPule++;
            var nameJogo;
            for(var i=qtdJogos-1; i>0; i--){ //i > 0 para não remover o primeiro jogo
                $('#'+$("input.teste:last").attr('id')).remove();
            }
            //Atualiza a quantidade de jogos
            qtdJogos = 1;
            $('#qtd').val(qtdJogos);
            $('#qtd_jogos_hidden').val(codLinhaPule);
            document.getElementById('qtd_'+codLinhaPule).innerText = qtdJogos;
            document.getElementsByName('tipojogo')[0].focus();
        }
    });

});

function setaTextoElementoTabela(nomeElementoPai, idElementoFilho){
    texto = document.getElementsByName(nomeElementoPai)[0].options[document.getElementsByName(nomeElementoPai)[0].selectedIndex].innerText;
    document.getElementById(idElementoFilho).innerText = texto;
}

/* Coloca na label a sigla correspondente ao tipo de jogo */
function setaTexto(valueSelect) {
    var urlBusca = "http://localhost/terminal-web/application/busca?busca="+valueSelect+"&submit=Pesquisar";
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            var tamInputs;
            var sel = xmlhttp.responseText;
            var valortotal;
            tamInputs = sel.substring(sel.search("<tamanho>")+9, sel.search("</tamanho>"));
            sel = sel.substring(sel.search("<response>")+10, sel.search("</response>"));
            document.getElementById('siglajogos').innerText = sel;
            document.getElementById('tipojogo_'+codLinhaPule).innerText = sel;
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
                    document.getElementById(arrElementos[i]).innerText = conteudosTags[i];
                }
                else if(arrBy[i].toLowerCase() == 'name'){
                    document.getElementsByName(arrElementos[i])[0].innerText = conteudosTags[i];
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

/*
 * Função: Moeda
 * Descrição: Formata o valor para o formato de moeda e retorna o novo valor
 * Parâmetros:
 *      valor: O texto do input, por exemplo
 *      separadorCentavos: O caractere usado para separar os reais dos centavos
 * Retorno:  O valor no formato de moeda
 *
 */
function Moeda(valor, sepadorCentavos){
    var valorMascara;
    var strReais = "";
    var reaisFormatado;
    var pontosAdicionados = 0;
    //Retira tudo o que não for dígito do texto do input
    var textoInput = valor.replace(/[^\d]+/g,'');
    var tamanhoInput = valor.length;
    if(tamanhoInput >= 3){
        //Remove a vírgula, para saber o valor total em centavos
        //textoInput = textoInput.replace(sepadorCentavos, "");
        var reais = parseInt(textoInput/100);
        //strReais = reais.ToCharArray();
        /*var qtdPontos = (tamanhoInput-1)/3;
        var i = tamanhoInput+qtdPontos;
        while(pontosAdicionados< qtdPontos){
            for(j=0; j<3; j++){
                reaisFormatado[i] = strReais[i-qtdPontos];
                i--;
            }
            reaisFormatado[i--] = ".";
            pontosAdicionados++;
        }*/
        //strReais = reaisFormatado.toString().replace(',','');
        var centavos = textoInput.substr(-2);
        //valorMascara = strReais + sepadorCentavos + centavos;
        valorMascara = reais + sepadorCentavos + centavos;
    }
    else if(tamanhoInput == 1)
        valorMascara = "00" + sepadorCentavos + valor;
    else if(tamanhoInput == 2)
        valorMascara = "0" + sepadorCentavos + valor;

    return valorMascara;
}
///////////

//-----------------------------------------------------
//Funcao: MascaraMoeda
//Sinopse: Mascara de preenchimento de moeda
//Parametro:
//   objTextBox : Objeto (TextBox)
//   SeparadorMilesimo : Caracter separador de milésimos
//   SeparadorDecimal : Caracter separador de decimais
//   e : Evento
//Retorno: Booleano
//Autor: Gabriel Fróes - www.codigofonte.com.br
function MascaraMoeda(objTextBox, SeparadorMilesimo, SeparadorDecimal, e){
    var sep = 0;
    var key = '';
    var i = j = 0;
    var len = len2 = 0;
    var strCheck = '0123456789';
    var aux = aux2 = '';
    var whichCode = (window.Event) ? e.which : e.keyCode;
    if (whichCode == 13) return true;
    key = String.fromCharCode(whichCode); // Valor para o código da Chave
    if (strCheck.indexOf(key) == -1) return false; // Chave inválida
    len = objTextBox.value.length;
    for(i = 0; i < len; i++)
        if ((objTextBox.value.charAt(i) != '0') && (objTextBox.value.charAt(i) != SeparadorDecimal)) break;
    aux = '';
    for(; i < len; i++)
        if (strCheck.indexOf(objTextBox.value.charAt(i))!=-1) aux += objTextBox.value.charAt(i);
    aux += key;
    len = aux.length;
    if (len == 0) objTextBox.value = '';
    if (len == 1) objTextBox.value = '0'+ SeparadorDecimal + '0' + aux;
    if (len == 2) objTextBox.value = '0'+ SeparadorDecimal + aux;
    if (len > 2) {
        aux2 = '';
        for (j = 0, i = len - 3; i >= 0; i--) {
            if (j == 3) {
                aux2 += SeparadorMilesimo;
                j = 0;
            }
            aux2 += aux.charAt(i);
            j++;
        }
        objTextBox.value = '';
        len2 = aux2.length;
        for (i = len2 - 1; i >= 0; i--)
            objTextBox.value += aux2.charAt(i);
        objTextBox.value += SeparadorDecimal + aux.substr(len - 2, len);
    }
    return false;
}

function inverte(str){
    return str.split('').reverse().join('');
}

function maskIt(w,e,m,r,a){
    // Cancela se o evento for Backspace
    if (!e) {
        var e = window.event;
    }
    if (e.keyCode) {
        code = e.keyCode;
    }
    else if (e.which) {
        code = e.which;
    }
    // Variáveis da função
    var txt  = (!r) ? w.value.replace(/[^\d]+/gi,'') : w.value.replace(/[^\d]+/gi,'').reverse();
    var mask = (!r) ? m : m.reverse();
    var pre  = (a ) ? a.pre : "";
    var pos  = (a ) ? a.pos : "";
    var ret  = "";
    if(code == 9 || code == 8 || txt.length == mask.replace(/[^#]+/g,'').length) {
        return false;
    }
    // Loop na máscara para aplicar os caracteres
    for(var x=0,y=0, z=mask.length;x<z && y<txt.length;){
        if(mask.charAt(x)!='#'){
            ret += mask.charAt(x); x++; }
        else {
            ret += txt.charAt(y); y++; x++; }
    }
    // Retorno da função
    ret = (!r) ? ret : ret.reverse();
    w.value = pre+ret+pos;
}

/* http://forum.imasters.com.br/topic/344232-resolvidoclculo-e-resultado-no-formato-moeda-em-javascript/ */
function moedaMask(valor,m,r,prefixo, sufixo){
    // Variáveis da função
    var txt  = (!r) ? valor.replace(/[^\d]+/gi,'') : valor.replace(/[^\d]+/gi,'').reverse();
    var mask = (!r) ? m : m.reverse();
    //a.pre e a.pos podem ser, por exemplo, R$, $, ...
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