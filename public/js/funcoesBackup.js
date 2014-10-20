/**
 * Created by glayson on 9/8/14.
 */

/******************** Criação de inputs **************************************/
/* Função para inserir inputs dinamicamente */
var qtdJogos = 1;
/* Entra aqui quando for pressionada uma tecla em um campo com a classe teste */
$('body').on('keydown', '.teste', function(e) {
    //9 é TAB
    if (e.which == 9) {
        //procura o último input com a classe teste e copia
        novoCampo = $("input.teste:last").clone();
        //insere a cópia depois do último input com a classe teste
        novoCampo.insertAfter("input.teste:last");
        var cod = qtdJogos-1;
        //Ao chegar aqui, os dois últimos inputs estão com id igual, o id do último é corrigido
        $("input#jogo_"+cod+":last").attr("id", "jogo_"+qtdJogos);
        qtdJogos++;
        //Ao inserir um input, a quantidade de jogos aumenta
        $("input#qtd:first").attr("value", qtdJogos);
        //verifica se é milhar invertida
        if(document.getElementById('jogo_0').maxLength == 12)
            $(".teste").attr("maxlength", document.getElementById('jogo_0').value.length);
        document.getElementById('qtd_0').innerText = qtdJogos;
    }
});

$(document).ready(function(){
    //setaTextoElementoTabela('extracao', 'extracao_0');
    //document.getElementById('pule_0').innerText = document.getElementById('numPule').innerText;
    //textoPremio = document.getElementsByName('premioini')[0].value + ' - ' + document.getElementsByName('premiofim')[0].value;
    //document.getElementById('premio_0').innerText = textoPremio;
    //adiciona a classe teste ao elemento com id jogo_0 para poder manipulá-lo
    //e manipular também os que forem criados
    $('#jogo_0').addClass('teste');

    $("select[name='extracao']").change(function(){
        setaTextoElementoTabela('extracao', 'extracao_0');
    });

    $("select[name='tipojogo']").change(function(){
        //índice 0 é o objeto(select, no caso) e índice name é o atributo name do objeto
        var jogos = document.getElementsByName("tipojogo")[0]['value'];
        setaTexto(jogos);
    });
    /*$("input[name='premioini']").change(function(){
        textoPremio = document.getElementsByName('premioini')[0].value + ' / ' + document.getElementsByName('premiofim')[0].value;
        document.getElementById('premio_0').innerText = textoPremio;
    });
    $("input[name='premiofim']").change(function(){
        textoPremio = document.getElementsByName('premioini')[0].value + ' / ' + document.getElementsByName('premiofim')[0].value;
        document.getElementById('premio_0').innerText = textoPremio;
    });*/
    $("input[name='valorjogo']").change(function(){
        document.getElementById('valorjogo_0').innerText = document.getElementsByName('valorjogo')[0].value;
        //document.getElementById('valorjogo_0').style.color = 'red';
    });
});

function setaTextoElementoTabela(nomeElementoPai, idElementoFilho){
    texto = document.getElementsByName(nomeElementoPai)[0].options[document.getElementsByName(nomeElementoPai)[0].selectedIndex].innerText;

    document.getElementById(idElementoFilho).innerText = texto;
}

/* Coloca na label a sigla correspondente ao tipo de jogo */
function setaTexto(valueSelect) {
    var urlBusca = "http://localhost/pjbCliente/application/busca?busca="+valueSelect+"&submit=Pesquisar";
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            var tamInputs;
            var sel = xmlhttp.responseText;
            var valortotal;
            tamInputs = sel.substring(sel.search("<tamanho>")+9, sel.search("</tamanho>"));
            sel = sel.substring(sel.search("<response>")+10, sel.search("</response>"));
            document.getElementById('siglajogos').innerText = sel;
            document.getElementById('tipojogo_0').innerText = sel;
            $(".teste").attr("maxlength", tamInputs);
            //Coloca os elementos da classe teste na variável input(array)
            var input = document.getElementsByClassName('teste');
            //Limpa os campos
            for(var i=0; i<input.length; i++)
                input[i].value = '';
            //alert('Tamanho máximo do input: ' + tamInputs);
        }
    }
        //get na url, de forma assíncrona
    xmlhttp.open("GET",urlBusca,true);
    xmlhttp.send();
}

/* Função que realiza a pesquisa e altera o(s) campo(s) necessário(s) */
/**
 * arrVariaveisGet: array com as variáveis que serão passadas por get
 * arrElementos: os elementos a serem alterados
 * arrTagsXML: tags onde ficam os contéudos de cada elemento
 * arrBy: informa o modo como vai pegar o elemento (ById, ByName ...)
 * **/
function pesquisaEAlteraCampos(arrVariaveisGet, arrElementos, arrTagsXML, arrBy) {
    var urlBusca = "http://localhost/pjbCliente/application/busca?busca="+valueSelect+"&submit=Pesquisar";
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            var tamInputs;
            var sel = xmlhttp.responseText;
            var valortotal;
            tamInputs = sel.substring(sel.search("<tamanho>")+9, sel.search("</tamanho>"));
            sel = sel.substring(sel.search("<response>")+10, sel.search("</response>"));
            document.getElementById('siglajogos').innerText = sel;
            document.getElementById('tipojogo_0').innerText = sel;
            $(".teste").attr("maxlength", tamInputs);
            //Coloca os elementos da classe teste na variável input(array)
            var input = document.getElementsByClassName('teste');
            //Limpa os campos
            for(var i=0; i<input.length; i++)
                input[i].value = '';
            //alert('Tamanho máximo do input: ' + tamInputs);
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


/* Função que realiza a pesquisa e altera o(s) campo(s) necessário(s) */
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
//                alert(conteudosTags[i]);
            }
            for(i=0; i<arrBy.length; i++){
                if(arrBy[i].toLowerCase() == 'id'){
                    document.getElementById(arrElementos[i]).innerText = conteudosTags[i];
                }
                else if(arrBy[i].toLowerCase() == 'name'){
                    document.getElementsByName(arrElementos[i])[0].innerText = conteudosTags[i];
                }
                else if(arrBy[i].toLowerCase() == 'attr'){
//                    alert(arrIdOuClasse[i]);
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




