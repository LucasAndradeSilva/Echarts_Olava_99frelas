console.log('Node funcionando!');

const dispositivos = new Array({dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:47:8C]!/AD0',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:47:8C',porta:'AD0'},{dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:47:8C]!/AD1',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:47:8C',porta:'AD1'},{dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:47:8C]!/AD2',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:47:8C',porta:'AD2'},{dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:47:8C]!/AD3',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:47:8C',porta:'AD3'},{dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.digitalIn/[00:13:A2:00:41:87:47:8C]!/DIO2',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:47:8C',porta:'DIO2'},{dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.digitalIn/[00:13:A2:00:41:87:47:8C]!/DIO4',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:47:8C',porta:'DIO4'},{dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.digitalIn/[00:13:A2:00:41:87:47:8C]!/DIO5',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:47:8C',porta:'DIO5'},{dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.digitalIn/[00:13:A2:00:41:87:47:8C]!/DIO6',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:47:8C',porta:'DIO6'},{dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.digitalIn/[00:13:A2:00:41:87:47:8C]!/DIO7',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:47:8C',porta:'DIO7'},{dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.digitalIn/[00:13:A2:00:41:87:47:8C]!/DIO8',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:47:8C',porta:'DIO8'},{dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.digitalIn/[00:13:A2:00:41:87:47:8C]!/DIO10',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:47:8C',porta:'DIO10'},{dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.digitalIn/[00:13:A2:00:41:87:47:8C]!/DIO11',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:47:8C',porta:'DIO11'},{dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.digitalIn/[00:13:A2:00:41:87:47:8C]!/DIO12',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:47:8C',porta:'DIO12'},{dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:33:4F]!/AD0',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:33:4F',porta:'AD0'},{dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:33:4F]!/AD1',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:33:4F',porta:'AD1'},{dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:33:4F]!/AD2',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:33:4F',porta:'AD2'},{dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:33:4F]!/AD3',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:33:4F',porta:'AD3'},{dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.digitalIn/[00:13:A2:00:41:87:33:4F]!/DIO3',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:33:4F',porta:'DIO3'},{dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.digitalIn/[00:13:A2:00:41:87:33:4F]!/DIO4',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:33:4F',porta:'DIO4'},{dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.digitalIn/[00:13:A2:00:41:87:33:4F]!/DIO5',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:33:4F',porta:'DIO5'},{dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.digitalIn/[00:13:A2:00:41:87:33:4F]!/DIO6',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:33:4F',porta:'DIO6'},{dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.digitalIn/[00:13:A2:00:41:87:33:4F]!/DIO7',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:33:4F',porta:'DIO7'},{dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.digitalIn/[00:13:A2:00:41:87:33:4F]!/DIO11',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:33:4F',porta:'DIO11'},{dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.digitalIn/[00:13:A2:00:41:87:33:4F]!/DIO12',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:33:4F',porta:'DIO12'},{dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:31:5D]!/AD0',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:31:5D',porta:'AD0'},{dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:31:5D]!/AD1',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:31:5D',porta:'AD1'},{dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:31:5D]!/AD2',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:31:5D',porta:'AD2'},{dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:31:5D]!/AD3',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:31:5D',porta:'AD3'},{dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.digitalIn/[00:13:A2:00:41:87:31:5D]!/DIO2',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:31:5D',porta:'DIO2'},{dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.digitalIn/[00:13:A2:00:41:87:31:5D]!/DIO4',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:31:5D',porta:'DIO4'},{dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.digitalIn/[00:13:A2:00:41:87:31:5D]!/DIO6',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:31:5D',porta:'DIO6'},{dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.digitalIn/[00:13:A2:00:41:87:31:5D]!/DIO7',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:31:5D',porta:'DIO7'},{dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.digitalIn/[00:13:A2:00:41:87:31:5D]!/DIO10',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:31:5D',porta:'DIO10'},{dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.digitalIn/[00:13:A2:00:41:87:31:5D]!/DIO11',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:31:5D',porta:'DIO11'},{dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.digitalIn/[00:13:A2:00:41:87:31:5D]!/DIO12',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:31:5D',porta:'DIO12'})
// const dispositivos = new Array({dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:47:8C]!/AD0',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:47:8C',porta:'AD0',passou:false,entrou:false})
// const dispositivoASerUsado = {dispositivo:'00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:47:8C]!/AD0',gateway:'00000000-00000000-0004F3FF-FF157C77',mac:'00:13:A2:00:41:87:47:8C',porta:'AD0'}

var idigi_username = 'olavor'; 
var idigi_password = 'Garymu261990@'; 

var idigi_auth = Buffer.from(idigi_username + ":" + idigi_password).toString('base64');

var request = require('sync-request');

var ontem = new Date();
ontem.setDate( ontem.getDate() - 1 );
ontem = ontem.getFullYear() + "-" + zeroPad(ontem.getMonth() + 1,2) + "-" + zeroPad(ontem.getDate(),2)

var antesDeOntem = new Date();
antesDeOntem.setDate( antesDeOntem.getDate() - 2 );
antesDeOntem = antesDeOntem.getFullYear() + "-" + zeroPad(antesDeOntem.getMonth() + 1,2) + "-" + zeroPad(antesDeOntem.getDate(),2)

console.log(ontem)
console.log(antesDeOntem)

var total = 0;
var x = 0;
var MongoClient = require('mongodb').MongoClient;

while(x < dispositivos.length){

    dispositivoASerUsado = dispositivos[x];

    var states = consultarSistema(dispositivoASerUsado.dispositivo,antesDeOntem +  "T21:00:00.000",ontem,dispositivoASerUsado);

    // console.log("kkk1")
    // console.log(dispositivoASerUsado)


    // console.log(states.length)
    // total += states.length

    // inserirDados(dispositivoASerUsado,states);

    x++;
}

console.log("Finalizou")
console.log(total)

function consultarSistema(dispositivoToSearch,dataInicio,dataFim,dispositivoASerUsado){

    MongoClient.connect('mongodb://localhost', function (err, client) {
        var res = request('GET','http://developer.idigi.com/ws/v1/streams/history/'+dispositivoToSearch+'?start_time=' + dataInicio + '&end_time=' + dataFim + 'T21:00:00.000',{
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Authorization': 'Basic ' + idigi_auth
                },
        });

        var body = JSON.parse(res.getBody('utf8'));
            // inserirDados(dispositivoASerUsado,body.list);   
        var states = body.list
        var y = 0;

        if (err) throw err;
        var db = client.db('digi');

        while(y < states.length){
            console.log(dispositivoASerUsado)
            console.log("entrou aqui")
            var hora_local = new Date(states[y].timestamp);
            hora_local.setMinutes(hora_local.getMinutes() - 180)
            db.collection('states').insertOne({
                gateway: dispositivoASerUsado.gateway,
                mac: dispositivoASerUsado.mac,
                input_output: dispositivoASerUsado.porta,
                data_nosql: new Date(),
                timestamp: states[y].timestamp,
                server_timestamp: states[y].server_timestamp,
                status: true,
                type: 'DOUBLE',
                units: 'CELSIUS',
                hora_local: hora_local,
                valor: states[y].value
            }, function (error, response){
                if(error) {
                    console.log(error)
                    console.log('Error occurred while inserting');
                   // return 
                } else {
                    console.log('inserted record');
                  // return 
                }
            });
            
            y++;
        }

        client.close()
        if(body.count == 1000){
            body.list = body.list.concat(consultarSistema(dispositivoToSearch,body.list[body.count - 1].timestamp,dataFim,dispositivoASerUsado));
        }

        return body.list;
    });  
    
}

function inserirDados(dispositivoASerUsado,states){
    
}

function zeroPad(num, numZeros) {
    var n = Math.abs(num);
    var zeros = Math.max(0, numZeros - Math.floor(n).toString().length );
    var zeroString = Math.pow(10,zeros).toString().substr(1);
    if( num < 0 ) {
        zeroString = '-' + zeroString;
    }

    return zeroString+n;
}