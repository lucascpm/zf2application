var persistent = false,
    session = null,
    counter = 0,
    ports = [];

self.addEventListener("connect", function (e) {
    var port = e.ports[0];
    port.start();

    port.addEventListener("message", function (e) {

            if(session === null) {
                session = new WebSocket('ws://127.0.0.1:10000/');
                port.postMessage("Novo WebSocket Criado...");
            }


        switch (e.data){
            case "novoWebSocket":
                session.onmessage = function(ev) {
                    var msg = JSON.parse(ev.data); //PHP sends Json data
                    var umsg = msg.message; //message text
                    port.postMessage(umsg);
                };
                break;

            case "fecharWebSocket":
                //TODO fechar websocket
                break;

            default : //Os demais comandos do servidor que necessitam de dados vindos do cliente (login, novo jogo, etc)
                //prepare json data
                var msg = {
                    message: e.data
                };

                //convert and send data to server
                session.send(JSON.stringify(msg));
                break;

        }

        //#### Message received from server?
        session.onmessage = function(ev) {
            var msg = JSON.parse(ev.data); //PHP sends Json data

            var umsg = msg.message; //message text
            var tipo = msg.type;

            //Manda a resposta do servidor para a instância do worker
            port.postMessage(umsg);

        };

    }, false);

}, false);