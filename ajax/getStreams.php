<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header("Content-Type: application/json; charset=utf-8");
date_default_timezone_set('America/Sao_Paulo');
$idigi_username = 'olavor';  // enter your username here.
$idigi_password = 'Garymu261990@';  // enter your password here. Consider your options for securing this info.
$idigi_auth = base64_encode($idigi_username . ":" . $idigi_password);

//precisa retornar alguns dados:
// pressão de sucção: 00:13:A2:00:41:87:47:8C - AD3
// Corrente elétrica: 00:13:A2:00:41:87:47:8C - AD1 -> além do ultimo, fazer tb gráfico
// temperatura: 00:13:A2:00:41:87:47:8C - AD0 -> além do ultimo, fazer tb gráfico
// pressão de descarga: 00:13:A2:00:41:87:47:8C - AD2

//dispositivo usado nos testes
//00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:47:8C]!/AD0

//substr_replace($idigi_auth,"",-1);
//   ws/v1/streams/inventory/00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:47:8C]!/AD0
//   ws/v1/streams/history/00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:47:8C]!/AD0
//echo date('His');
//$idigi_sci_url = "http://developer.idigi.com/ws/v1/streams/history/00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:47:8C]!/AD0?size=5";

$dia_inicio     = $_POST["dia_inicio"];
$hora_inicio    = $_POST["hora_inicio"];
$dia_fim      = $_POST["dia_fim"];
$hora_fim     = $_POST["hora_fim"];

if ($dia_inicio == ""){
  $dia_inicio = date('Y-m-d');
}
else{
  $dia_inicio = explode("/", $dia_inicio)[2] . "-" . explode("/", $dia_inicio)[1] . "-" . explode("/", $dia_inicio)[0];
}

if ($hora_inicio == ""){
  $hora_inicio = "00:00";
}

if ($dia_fim == ""){
  $dia_fim = date('Y-m-d');
}
else{
  $dia_fim = explode("/", $dia_fim)[2] . "-" . explode("/", $dia_fim)[1] . "-" . explode("/", $dia_fim)[0];
}

if ($hora_fim == ""){
  $hora_fim .= "23:59";
}

$dataInicio = $dia_inicio . "T" . $hora_inicio;
$dataFim = $dia_fim . "T" . $hora_fim;
$dispositivos = $_POST['dispositivos'];
// $dispositivos = array('00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:47:8C]!/AD0');
$responseFinal = array('dispositivos'=>array());

foreach ($dispositivos as $key => $value) {
  $idigi_sci_url = "http://developer.idigi.com/ws/v1/streams/history/$value?start_time=$dataInicio:00.000&end_time=$dataFim:59.590";
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

  $responseFinal['dispositivos'][$key]['dispositivo'] = $value;
  $responseFinal['dispositivos'][$key]['list'] = $response_array->list;

  while($response_array->count == 1000){
    $idigi_sci_url = "http://developer.idigi.com/ws/v1/streams/history/00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:47:8C]!/AD0?start_time=".$response_array->list[999]->timestamp."&end_time=$dataFim:59.590";
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

    array_push($responseFinal['dispositivos'][$key]['list'], $response_array->list);
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