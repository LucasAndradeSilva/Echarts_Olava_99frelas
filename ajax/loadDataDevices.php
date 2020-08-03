<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header("Content-Type: application/json; charset=utf-8");
date_default_timezone_set('America/Sao_Paulo');
$idigi_username = 'olavor';  // enter your username here.
$idigi_password = 'Garymu261990@';  // enter your password here. Consider your options for securing this info.
$idigi_auth = base64_encode($idigi_username . ":" . $idigi_password);
$input = file_get_contents('php://input');
$input = json_decode($input, TRUE);


//precisa retornar alguns dados:
// pressão de sucção: 00:13:A2:00:41:87:47:8C - AD3
// Corrente elétrica: 00:13:A2:00:41:87:47:8C - AD1 [' além'] do ultimo, fazer tb gráfico
// temperatura: 00:13:A2:00:41:87:47:8C - AD0 [' além'] do ultimo, fazer tb gráfico
// pressão de descarga: 00:13:A2:00:41:87:47:8C - AD2

//dispositivo usado nos testes
//00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:47:8C]!/AD0

//substr_replace($idigi_auth,"",-1);
//   ws/v1/streams/inventory/00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:47:8C]!/AD0
//   ws/v1/streams/history/00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:47:8C]!/AD0
//echo date('His');
//$idigi_sci_url = "http://developer.idigi.com/ws/v1/streams/history/00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:47:8C]!/AD0?size=5";

// $time = strtotime("-1 year", time());
$time = strtotime("-1 days", time());
$dia_inicio = date("Y-m-d", $time);
$hora_inicio = date("H:i", $time);

$dia_fim = date('Y-m-d');
$hora_fim = "23:59";

$dataInicio = $dia_inicio . "T" . $hora_inicio;
$dataFim = $dia_fim . "T" . $hora_fim;
$dispositivos = $_POST['dispositivos'];
// $dispositivos = array('00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:47:8C]!/AD0','00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:47:8C]!/AD1','00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:47:8C]!/AD2','00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:47:8C]!/AD3');
$responseFinal = array('dispositivos'=>array());

$hasDescarga = false;
$descargaWithDate = array();
$copWithDate = array();
$potEletWithDate = array();
$hasCOP = false;
$hasPotElet = false;

// print_r($dispositivos);

// PEGAR APENAS O GRÁFICO TEMPERATURA E AMPERAGEM
foreach ($dispositivos as $key => $val) {
  $value = $val['value'];
  $type = $val['type'];

  $valueToApi = $value;
  if($valueToApi == "00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:33:4F]!/AD2"){
    $valueToApi = substr_replace($value, "AD1", -3);
  }
  // $value = explode("|", $val)[1];
  // $type = explode("|", $val)[0];

  $idigi_sci_url = "http://developer.idigi.com/ws/v1/streams/history/$valueToApi?start_time=$dataInicio:00.000&end_time=$dataFim:59.590";

  // print_r($idigi_sci_url);

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


  $response_array = json_decode($response,true);
  // print_r($response_array);

  $responseFinal['dispositivos'][$key]['dispositivo'] = $value;
  $responseFinal['dispositivos'][$key]['list'] = $response_array['list'];

  while($response_array['count'] == 1000){

    $idigi_sci_url = "http://developer.idigi.com/ws/v1/streams/history/$valueToApi?start_time=".$response_array["list"][999]['timestamp']."&end_time=$dataFim:59.590";
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

    $response_array = json_decode($response,true);

    $responseFinal['dispositivos'][$key]['list'] = array_merge($responseFinal['dispositivos'][$key]['list'], $response_array["list"]);
  }

  for($y = 0; $y < count($responseFinal['dispositivos'][$key]['list']); $y++){
    if($type == "AD2" || $type == "descarga"){
      $responseFinal['dispositivos'][$key]['list'][$y]['value'] = ($responseFinal['dispositivos'][$key]['list'][$y]['value']) ;
    }
    if($type == "AD3" || $type == "succao"){
      $responseFinal['dispositivos'][$key]['list'][$y]['value'] = ($responseFinal['dispositivos'][$key]['list'][$y]['value']) ;
    }
    if($type == "AD0"){
      $responseFinal['dispositivos'][$key]['list'][$y]['value'] = ($responseFinal['dispositivos'][$key]['list'][$y]['value']) ;
    }
    if($type == "AD1"){
      $responseFinal['dispositivos'][$key]['list'][$y]['value'] = ($responseFinal['dispositivos'][$key]['list'][$y]['value']) ;
    }


    if($type == "descarga"){
      $hasDescarga = true;
      $responseFinal['dispositivos'][$key]['list'][$y]['value'] = (0.000002 * pow($responseFinal['dispositivos'][$key]['list'][$y]['value'],3)) - (0.0016 * pow($responseFinal['dispositivos'][$key]['list'][$y]['value'],2)) + (0.6472 * $responseFinal['dispositivos'][$key]['list'][$y]['value']) - 35.567;
      $descargaWithDate["'" + $responseFinal['dispositivos'][$key]['list'][$y]['timestamp'] + "'"] = $responseFinal['dispositivos'][$key]['list'][$y]['value'];
    } 
    if($type == "succao"){
      $responseFinal['dispositivos'][$key]['list'][$y]['value'] = (0.000002 * pow($responseFinal['dispositivos'][$key]['list'][$y]['value'],3)) - (0.0016 * pow($responseFinal['dispositivos'][$key]['list'][$y]['value'],2)) + (0.6473 * $responseFinal['dispositivos'][$key]['list'][$y]['value']) - 35.567;
    }
    if($type == "POT"){
      $hasPotElet = true;
      $responseFinal['dispositivos'][$key]['list'][$y]['value'] = (($responseFinal['dispositivos'][$key]['list'][$y]['value'])) * 380;
      $potEletWithDate["'" + $responseFinal['dispositivos'][$key]['list'][$y]['timestamp'] + "'"] = $responseFinal['dispositivos'][$key]['list'][$y]['value'];
    }
    if($type == "COP"){
      $hasCOP = true;
      $responseFinal['dispositivos'][$key]['list'][$y]['value'] = ($responseFinal['dispositivos'][$key]['list'][$y]['value']) ;
      $copWithDate["'" + $responseFinal['dispositivos'][$key]['list'][$y]['timestamp'] + "'"] = $responseFinal['dispositivos'][$key]['list'][$y]['value'];
    }

  }
}

foreach ($dispositivos as $key => $val) {
  $value = $val['value'];
  $type = $val['type'];
  // $value = explode("|", $val)[1];
  // $type = explode("|", $val)[0];

  for($y = 0; $y < count($responseFinal['dispositivos'][$key]['list']); $y++){
    if($type == "COP"){
      if(!$hasDescarga){
        $valueToApi = substr_replace($value, "AD2", -3);
        if($valueToApi == "00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:33:4F]!/AD2"){
          $valueToApi = substr_replace($value, "AD1", -3);
        }
        $array = getList($valueToApi);

        for($j = 0; $j < count($array); $j++){
          $array[$j]['value'] = ($array[$j]['value']);
          $array[$j]['value'] = (0.000002 * pow($array[$j]['value'],3)) - (0.0016 * pow($array[$j]['value'],2)) + (0.6472 * $array[$j]['value'] = $array[$j]['value']) - 35.567;
          $descargaWithDate["'" + $array[$j]['timestamp'] + "'"] = $array[$j]['value'];
        }

        $hasDescarga = true;
      }
      $responseFinal['dispositivos'][$key]['list'][$y]['value'] = $responseFinal['dispositivos'][$key]['list'][$y]['value'] / ($descargaWithDate["'" + $responseFinal['dispositivos'][$key]['list'][$y]['timestamp'] + "'"] - $responseFinal['dispositivos'][$key]['list'][$y]['value']);
      $hasCOP = true;
      $copWithDate["'" + $responseFinal['dispositivos'][$key]['list'][$y]['timestamp'] + "'"] = $responseFinal['dispositivos'][$key]['list'][$y]['value'];
    }

    if($type == "REND"){
      if(!$hasDescarga){
        $valueToApi = substr_replace($value, "AD2", -3);
        if($valueToApi == "00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:33:4F]!/AD2"){
          $valueToApi = substr_replace($value, "AD1", -3);
        }
        $array = getList($valueToApi);
        
        for($j = 0; $j < count($array); $j++){
          $array[$j]['value'] = ($array[$j]['value']) ;
          $array[$j]['value'] = (0.000002 * pow($array[$j]['value'],3)) - (0.0016 * pow($array[$j]['value'],2)) + (0.6472 * $array[$j]['value'] = $array[$j]['value']) - 35.567;
          $descargaWithDate["'" + $array[$j]['timestamp'] + "'"] = $array[$j]['value'];
        }

        $hasDescarga = true;
      }

      if(!$hasCOP){
        $array = getList(substr_replace($value, "AD0", -3));
        // print_r($array);
        // echo "kkk2";
        for($j = 0; $j < count($array); $j++){
          $array[$j]['value'] = ($array[$j]['value']);
          $array[$j]['value'] = $array[$j]['value'] / ($descargaWithDate["'" + $array[$j]['timestamp'] + "'"] - $array[$j]['value']);
          $copWithDate["'" + $array[$j]['timestamp'] + "'"] = $array[$j]['value'];
        }

        $hasCOP = true;

      }

      if(!$hasPotElet){
        $array = getList(substr_replace($value, "AD1", -3));

        for($j = 0; $j < count($array); $j++){
          $array[$j]['value'] = (($array[$j]['value'])) * 380;
          $potEletWithDate["'" + $array[$j]['timestamp'] + "'"] = $array[$j]['value'];
        }

        $hasPotElet = true;
      }

      // print_r($descargaWithDate);
      $responseFinal['dispositivos'][$key]['list'][$y]['value'] = $copWithDate["'" + $responseFinal['dispositivos'][$key]['list'][$y]['timestamp'] + "'"] / $potEletWithDate["'" + $responseFinal['dispositivos'][$key]['list'][$y]['timestamp'] + "'"] ;
    }

    $responseFinal['dispositivos'][$key]['list'][$y]['x'] = date('m/d/Y H:i', strtotime('-0 hours', strtotime($responseFinal['dispositivos'][$key]['list'][$y]['timestamp'])));
    $responseFinal['dispositivos'][$key]['list'][$y]['y'] = number_format($responseFinal['dispositivos'][$key]['list'][$y]['value'],1,".","");

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

function getList($device){
  $arrayToReturn = array();
  $idigi_username = 'olavor';  // enter your username here.
  $idigi_password = 'Garymu261990@';  // enter your password here. Consider your options for securing this info.
  $idigi_auth = base64_encode($idigi_username . ":" . $idigi_password);

  $time = strtotime("-1 days", time());
  $dia_inicio = date("Y-m-d", $time);
  $hora_inicio = date("H:i", $time);

  $dia_fim = date('Y-m-d');
  $hora_fim = "23:59";

  $dataInicio = $dia_inicio . "T" . $hora_inicio;
  $dataFim = $dia_fim . "T" . $hora_fim;

  $idigi_sci_url = "http://developer.idigi.com/ws/v1/streams/history/$device?start_time=$dataInicio:00.000&end_time=$dataFim:59.590";

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

  $response_array = json_decode($response,true);
  
  $arrayToReturn = $response_array['list'];

  while(($response_array['count'] == 1000) == 1){
    $idigi_sci_url = "http://developer.idigi.com/ws/v1/streams/history/$device?start_time=".$response_array['list'][999]['timestamp']."&end_time=$dataFim:59.590";
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

    $response_array = json_decode($response,true);
    if($idigi_sci_url == "http://developer.idigi.com/ws/v1/streams/history/00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:47:8C]!/AD0?start_time=2019-01-16T00:00:00.000&end_time=2020-01-16T23:59:59.590"){
      echo($response);
    }

    $arrayToReturn = array_merge($arrayToReturn, $response_array['list']);
  }

  return $arrayToReturn;
}
?>
