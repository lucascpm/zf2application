/**
 * Created by alvaro on 9/12/14.
 */
const ID_TIPOJOGO = 0;
const SIGLA_TIPOJOGO = 1;
const NOME_TIPOJOGO = 2;
const DIGITOS_TIPOJOGO = 3;
const PREMIO_VALOR_MULT_TIPOJOGO = 4;
const APOSTA_VALOR_MULT_TIPOJOGO = 5;

const ID_EXTRACAO = 0;
const NOME_EXTRACAO = 1;

var escopo = ["01/05", "06/10", "01/10"];


$(document).ready(function(){
    var listaTipo = '';
    var listaExtracao = '';
    var nSerial = '';
    var proxMsg = '';
    var proxNumPule = '';
    var data = null;

    var worker = new SharedWorker("/pjbCliente/public/js/WebWorker2.js");
    // call start before posting
    worker.port.start();
    // post a message to the shared web worker
//        worker.port.postMessage("novoWebSocket");

    //TODO Pedir listas de Extrações e Tipos de Jogo ao Servidor / Msg Sequencial / Número do Terminal
    //TODO VERIFICAR O PORQUE DE O SERVIDOR NÃO RECEBER A MENSAGEM NA PRIMEIRA TENTATIVA DE CARREGAR A PÁGINA.
    alert("solicitando listas");
    worker.port.postMessage("listas;0;16529800158");
//    worker.port.postMessage("listas;0;16529800158");
//    worker.port.postMessage("listas;0;16529800158");
    alert("listas solicitadas");

    // e.data é a resposta do Worker (resposta do servidor)
    worker.port.addEventListener("message", function(e) {
            document.getElementById("response").innerHTML = "Mensagem do Sevidor: " + e.data;
//            alert(e.data);
            if( e.data.indexOf("listatipojogo") == 0 ){
                var options = e.data.replace("listatipojogo;", "");
                listaTipo = options.split(";");
                populaSelect(options, 'tipojogo', ID_TIPOJOGO, NOME_TIPOJOGO);
            }
            else
            if( e.data.indexOf("listaextracao") == 0 ){
                var options = e.data.replace("listaextracao;", "");
                populaSelect(options, 'extracao', ID_EXTRACAO, NOME_EXTRACAO);

            }
            if( e.data.indexOf("proxMsg") == 0 ){
                proxMsg = e.data.replace("proxMsg;", "");
                }
            if( e.data.indexOf("nSerial") == 0 ){
                nSerial = e.data.replace("nSerial;", "");
            }
            if( e.data.indexOf("proxNumPule") == 0 ){
                proxNumPule = e.data.replace("proxNumPule;", "");
            }

}, false);

//Ao alterar o select tipojogo
$("select[name='tipojogo']").change(function(){
        //índice 0 é o objeto(select, no caso) e índice name é o atributo name do objeto
        var valueSelect = document.getElementsByName("tipojogo")[0]['value'];
        var elemento = listaTipo[valueSelect-1].split(",");
        var sigla = elemento[SIGLA_TIPOJOGO];
        var tam = elemento[DIGITOS_TIPOJOGO];
        setaTexto(sigla, tam);
    });



    $("input[name='data']").change(function(){
        worker.port.postMessage("listaextracao;"+$("input[name='data']").val());
        data = $("input[name='data']").val();
    });


    $("input[name='premioini']").keyup(function(){
        var tamanhoInput = $("input[name='premioini']").val().length;
        var textoInput = $("input[name='premioini']").val();
//        var str = $("input[name='premioini']").val();
//        var patt1 = /(([0][0-9])|([1][0]))\/(([0][0-9])|([1][0]))/g;
//        var result = str.match(patt1);
//        document.getElementById("premio_0").innerHTML = result;
        switch(tamanhoInput){
            case 1:
                if(textoInput >=1 && textoInput <= 3)
                    document.getElementById("premio_0").innerHTML = escopo[textoInput- 1];
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
                var premioini = textoInput.substring(0, 2);
                var premiofim = textoInput.substring(3, 5);
                if(premiofim < premioini || premiofim > 10){
                    $("input[name='premioini']").val(textoInput.substring(0, 3));
                }
                break;
        }

        //textoPremio = document.getElementsByName('premioini')[0].value + ' / ' + document.getElementsByName('premiofim')[0].value;
        //document.getElementById('premio_0').innerText = textoPremio;
        document.getElementById('premio_0').innerText = $("input[name='premioini']").val();
    });


//Ao alterar o escopo
//TODO adicionar barra após digitar 2 dígitos
//var ok = 0;
//$("input[name='premioini']").keydown(function(e){
//    //e.preventDefault();
//    //Verifica se não há caracteres no input ainda
//    var tamanhoInput = $("input[name='premioini']").val().length;
//    var digito = e.which-48;
//
//    if((digito >= 0 && digito <= 9) && tamanhoInput < 5){
//    //Concatena o valor do input, com o dígito
//    //$("input[name='premioini']").val($("input[name='premioini']").val().concat((digito)));
//    //if(tamanhoInput <= 2){
//    if(tamanhoInput == 2){
//    if($("input[name='premioini']").val() >= 1 && $("input[name='premioini']").val() <= 9){
//    $("input[name='premioini']").val($("input[name='premioini']").val().concat('/'));
//    }
//else{
//    $("input[name='premioini']").val('10/10');
//    }
//}
///*else if(tamanhoInput == 4){
//    var num1 = $("input[name='premioini']").val().substring(0, 2);
//    var num2 = $("input[name='premioini']").val().substring(3, 4).concat(digito);
//    if(num2 >= num1 && num2 <= 10){
//    $("input[name='premioini']").val($("input[name='premioini']").val().substring(0, 3));
//    }
//}*/
////                        if(digito > 0 && digito <= escopo.length) {
////                            document.getElementById('premio_0').innerText = escopo[digito -1];
////                        }
////}
//}
//if(tamanhoInput == 0){
//    if(digito > 0 && digito <= escopo.length) {
//    document.getElementById('premio_0').innerText = escopo[digito -1];
//    }
//}
//else{
//    document.getElementById('premio_0').innerText = $("input[name='premioini']").val();
//    }
////
////                //Opções predefinidas
//
////// else{
//////                        // document.getElementById('premio_0').innerText = $("input[name='premioini']").val();
//////                        var valor = $("input[name='premioini']").val();
//////                        valor = valor.concat('/').concat(valor);
//////                        $("input[name='premioini']").val(valor);
//////                        document.getElementById('premio_0').innerText = valor;
//////                    }
////                }
////
////                //Já há algo inserido no input
////                else if(digito >= 0 && digito <= 9){
////                    if($("input[name='premioini']").val().length == 1 && $("input[name='premioini']").val().indexOf('0') == 0){
////                        alteraInput(digito);
////                    }
//    //                    else if($("input[name='premioini']").val().length == 1 && $("input[name='premioini']").val().indexOf('1') == 0) {
////                        preencheInput($("input[name='premioini']"), '10/10');
////                        document.getElementById('premio_0').innerText = '10/10';
////                    }
//    //                    if($("input[name='premioini']").val().length == 4)
//    //                        document.getElementById('premio_0').innerText = $("input[name='premioini']").val();
////                }
////                if($("input[name='premioini']").val().indexOf('0') != -1 && $("input[name='premioini']").val().length == 2){
////                    alert($("input[name='premioini']").val().concat('/'));
////                    alert(document.getElementsByName('premioini')[0].value.concat('/'));
////                    var concatenada = $("input[name='premioini']").val().concat('/');
////
////                    document.getElementsByName('valorjogo')[0].focus();
////                    $("input[name='premioini']").attr("value", concatenada);
////                    document.getElementsByName('premioini')[0].focus();
////
////    //                document.getElementsByName('premioini')[0].innerText = "hahahhhahaha";
////                //}
//    });




    //Ao aclicar no botão:
    $('#jogar').click(function(){ //use clicks message send button
        //            var numPule = $('#numPule').val(); //get message text
                    var stringApostas = '';
                    var apostaN = '';
                    var idjogo = '';
                    var qtdeApostas = $('[name="qtd"]').val();

            //Pega todos os jogos (números das apostas) e forma uma string num1#num2#num3
            for(var i = 0; i < qtdeApostas; i++){
                    idjogo = 'jogo_'+i;
                    apostaN = document.getElementById(idjogo).value;
                    if(i == qtdeApostas -1){
                    stringApostas = stringApostas.concat(apostaN);
                    break;
                    }
                stringApostas = stringApostas.concat(apostaN + '#');
                }

                /*  pule;nMsg; terminalSerial;proxNumPule;extrProgrmId; qtdeApostas;          ap1           ;       ap2       ;      ap3    ;  ap4;ap5;apN;    */
                /*  pule; 0;    16529800158;       1     ;     2      ;      3     ;  M:1-5:123#456#789:150 ; MC:6-10:123:220 ; C:1:100:170 ;                  */
                var extracao = $('[name="extracao"]').val();
//                (pule;11;18521300987; 4 ;null; 1 ; 1 ;3:3:333#444#555:130) Direto no console
                var novaPule = "pule;" + proxMsg+";" + nSerial +";"+ proxNumPule +";"+ data + ";"+ extracao +";"+ 1 +";"+   $("select[name='tipojogo']").val()+":4:"+ stringApostas +":"+$('[name="valorjogo"]').val();
//                var novaPule = $('[name="numPule"]').val();
                alert(novaPule);
                worker.port.postMessage(novaPule);
                window.location.href = window.location.href;
        });

        }, false);

        function sleep(milliseconds) {
            var start = new Date().getTime();
            for (var i = 0; i < 1e7; i++) {
            if ((new Date().getTime() - start) > milliseconds){
            break;
            }
        }
        }

        function populaSelect(options, elemento, indiceValor, indiceTexto) {
            var option = '';
            var valor = '';
            var texto = '';
            options = options.split(";");

            var i = 0;
            for(i; i < options.length -1; i++){
            valor = options[i].split(",")[indiceValor];
            texto = options[i].split(",")[indiceTexto];
            option = option.concat('<option value='+valor+'>'+texto+'</option>');
            }

        document.getElementsByName(elemento)[0].innerHTML = option;
        }

        function setaTexto(sigla, tamInputs) {
            document.getElementById('siglajogos').innerText = sigla;
            document.getElementById('tipojogo_0').innerText = sigla;
            $(".teste").attr("maxlength", tamInputs);
            var input = document.getElementsByClassName('teste');
            //Limpa os campos
            for(var i=0; i<input.length; i++)
            input[i].value = '';
            }

        function alteraInput(digito){
            valor = $("input[name='premioini']").val().concat(digito);
            //alert(valor);
            valor = valor.concat('/');
            $("input[name='premioini']").val("");
            $("input[name='premioini']").val(valor);
            }

        function preencheInput(input, texto){
            input.val("");
            input.val(texto);
        }