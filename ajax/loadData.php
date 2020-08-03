<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header("Content-Type: application/json; charset=utf-8");
date_default_timezone_set('America/Sao_Paulo');
$idigi_username = 'olavor';  // enter your username here.
$idigi_password = 'Garymu261990@';  // enter your password here. Consider your options for securing this info.
$idigi_auth = base64_encode($idigi_username . ":" . $idigi_password);

$getAllValues = $_POST['getAllValues'];

$time = strtotime("-7 days", time());
$dia_inicio = date("Y-m-d", $time);
// $dia_inicio = "2020-01-01";
$hora_inicio = "00:00";

$dia_fim = date('Y-m-d');
$hora_fim = "23:59";

$dataInicio = $dia_inicio . "T" . $hora_inicio;
$dataFim = $dia_fim . "T" . $hora_fim;
// $dispositivos = $_POST['dispositivos'];
$dispositivos = array('00000000-00000000-0004F3FF-FF157C77/xbee.analog/[00:13:A2:00:41:87:33:4F]!/AD0','00000000-00000000-0004F3FF-FF157C77/xbee.analog/[00:13:A2:00:41:87:33:4F]!/AD1','00000000-00000000-0004F3FF-FF157C77/xbee.analog/[00:13:A2:00:41:87:33:4F]!/AD1','00000000-00000000-0004F3FF-FF157C77/xbee.analog/[00:13:A2:00:41:87:33:4F]!/AD3');
$responseFinal = array('succao'=>'','corrente_eletrica'=>'','temperatura'=>'','descarga'=>'','dispositivos'=>array());

// $retorno = data.split("/");

// $variavel1 = $retorno[0];
// $variavel2 = $retorno[1];
// $LETRA = $retorno[2];;

// $P1;
// $P2;
// $P3;
// $P4;

// $I1;
// $I2;

// $T1;
// $T2;
// $T3;
// $T4;

// $V1;
// $V2;
// switch($LETRA)
// {
//     case "A":
//         $P1 = $variável1;
//         $P2 = $variável2;
//         break;
//     case "B":
//         $P3 = $variável1;
//         $P4 = $variável2;
//         break;
//     case "C":
//         $I1 = $variável1;
//         $I2 = $variável2;  
//         break;
//     case "D":
//         $T1 = $variável1;
//         $T2 = $variável2;
//         break;
//     case "E":
//         $T3 = $variável1;
//         $T4 = $variável2;
//         break;
//     case "F":
//         $V1 = $variável1;
//         $V2 = $variável2;
//         break;
// }


foreach ($dispositivos as $key => $value) {
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => "http://developer.idigi.com/ws/v1/streams/inventory/$value",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET"
  ));

  curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      "Authorization: Basic " . $idigi_auth
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);
  curl_close($curl);

  $response_array = json_decode($response,true);

  switch ($key) {
    case 0:
      $responseFinal['temperatura'] = number_format(($response_array['value']) ,1,".","");
      break;

    case 1:
      $responseFinal['corrente_eletrica'] = number_format(($response_array['value']) ,1,".","");
      break;

    case 2:
      $responseFinal['descarga'] = number_format(($response_array['value']) ,1,".","");
      // $responseFinal['descarga'] = (0.000002 * pow($responseFinal['descarga'],3)) - (0.0016 * pow($responseFinal['descarga'],2)) + (0.6472 * $responseFinal['descarga']) - 35.567;
      break;

    case 3:
      $responseFinal['succao'] = number_format(($response_array['value']),1,".","");
      // $responseFinal['succao'] = (0.000002 * pow($responseFinal['succao'],3)) - (0.0016 * pow($responseFinal['succao'],2)) + (0.6473 * $responseFinal['succao']) - 35.567;
      break;
    
    default:
      break;
  }
}

if($getAllValues == "0"){
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => "http://developer.idigi.com/ws/v1/streams/inventory/00000000-00000000-0004F3FF-FF157C77/xbee.analog/[00:13:A2:00:41:87:33:4F]!/AD0",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET"
  ));

  curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      "Authorization: Basic " . $idigi_auth
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);
  curl_close($curl);

  $response_array = json_decode($response,true);

  $responseFinal['temperaturaValue'] = $response_array;
  $responseFinal['temperaturaValue']['value'] = number_format(($response_array['value']) ,1,".","");
  $responseFinal['temperaturaValue']['x'] = date('m/d/Y H:i', strtotime('-0 hours', strtotime($responseFinal['temperaturaValue']['timestamp']))); 

  $responseFinal['temperaturaValue']['y'] = $responseFinal['temperaturaValue']['value']; 

  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => "http://developer.idigi.com/ws/v1/streams/inventory/00000000-00000000-0004F3FF-FF157C77/xbee.analog/[00:13:A2:00:41:87:33:4F]!/AD1",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET"
  ));

  curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      "Authorization: Basic " . $idigi_auth
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);
  curl_close($curl);

  $response_array = json_decode($response,true);

  $responseFinal['corrente_eletricaValue'] = $response_array;
  $responseFinal['corrente_eletricaValue']['value'] = number_format(($response_array['value']) ,1,".","");
$responseFinal['corrente_eletricaValue']['x'] = date('m/d/Y H:i', strtotime('-0 hours', strtotime($responseFinal['corrente_eletricaValue']['timestamp']))); 

  $responseFinal['corrente_eletricaValue']['y'] = $responseFinal['corrente_eletricaValue']['value'];
}


if($getAllValues == "1"){
// PEGAR APENAS O GRÁFICO TEMPERATURA E AMPERAGEM
for ($x = 0; $x < 2; $x++) {
  $value = $dispositivos[$x];
  $idigi_sci_url = "http://developer.idigi.com/ws/v1/streams/history/$value?start_time=$dataInicio:00.000&end_time=$dataFim:59.590";

  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => $idigi_sci_url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 60,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET"
  ));

  curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      "Authorization: Basic " . $idigi_auth
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);
  curl_close($curl);

  $response_array = json_decode($response);

  $responseFinal['dispositivos'][$x]['dispositivo'] = $value;
  $responseFinal['dispositivos'][$x]['list'] = $response_array->list;

  while($response_array->count == 1000){
    $idigi_sci_url = "http://developer.idigi.com/ws/v1/streams/history/$value?start_time=".$response_array->list[999]->timestamp."&end_time=$dataFim:59.590";
    // echo $idigi_sci_url;
    // die();

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $idigi_sci_url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 60,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET"
    ));

    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Authorization: Basic " . $idigi_auth
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    $response_array = json_decode($response);

    $responseFinal['dispositivos'][$x]['list'] = array_merge($responseFinal['dispositivos'][$x]['list'], $response_array->list);
  }

  for($y = 0; $y < count($responseFinal['dispositivos'][$x]['list']); $y++){
    $responseFinal['dispositivos'][$x]['list'][$y]->x = date('m/d/Y H:i', strtotime('-0 hours', strtotime($responseFinal['dispositivos'][$x]['list'][$y]->timestamp))); 
    if($x == 0){
      $responseFinal['dispositivos'][$x]['list'][$y]->value = ($responseFinal['dispositivos'][$x]['list'][$y]->value);
    }
    else{
      $responseFinal['dispositivos'][$x]['list'][$y]->value = ($responseFinal['dispositivos'][$x]['list'][$y]->value) ;
    }
    $responseFinal['dispositivos'][$x]['list'][$y]->y = number_format($responseFinal['dispositivos'][$x]['list'][$y]->value,1,".","");
  }
}
}

echo json_encode($responseFinal);

if (!$err) {
  // echo "Respota: " . $response . "\n";
  return "success";
} else {
  // echo "cURL Error #:" . $err;
	return "error";
}

?>
