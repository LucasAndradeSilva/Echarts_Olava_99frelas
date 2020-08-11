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

$dispositivos = $_POST['dispositivos'];
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
  if($valueToApi == "00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:33:4F]!"){
    $valueToApi = substr_replace($value, "AD1", -3);
  }

  $response_array = getLastValue($valueToApi);
  // print_r($response_array);

  $responseFinal['dispositivos'][$key]['dispositivo'] = $value;
  $responseFinal['dispositivos'][$key]['value'] = $response_array['value'];
  $responseFinal['dispositivos'][$key]['timestamp'] = $response_array['timestamp'];

  if($type == "AD2" || $type == "descarga"){
    $responseFinal['dispositivos'][$key]['value'] = ($responseFinal['dispositivos'][$key]['value']);
  }
  if($type == "AD3" || $type == "succao"){
    $responseFinal['dispositivos'][$key]['value'] = ($responseFinal['dispositivos'][$key]['value']);
  }
  if($type == "AD0"){
    $responseFinal['dispositivos'][$key]['value'] = ($responseFinal['dispositivos'][$key]['value']);
  }
  if($type == "AD1"){
    $responseFinal['dispositivos'][$key]['value'] = ($responseFinal['dispositivos'][$key]['value']);
  }


  if($type == "descarga"){
    $hasDescarga = true;
    $responseFinal['dispositivos'][$key]['value'] = (0.000002 * pow($responseFinal['dispositivos'][$key]['value'],3)) - (0.0016 * pow($responseFinal['dispositivos'][$key]['value'],2)) + (0.6472 * $responseFinal['dispositivos'][$key]['value']) - 35.567;
    $descargaWithDate["'" + $responseFinal['dispositivos'][$key]['timestamp'] + "'"] = $responseFinal['dispositivos'][$key]['value'];
  } 
  if($type == "succao"){
    $responseFinal['dispositivos'][$key]['value'] = (0.000002 * pow($responseFinal['dispositivos'][$key]['value'],3)) - (0.0016 * pow($responseFinal['dispositivos'][$key]['value'],2)) + (0.6473 * $responseFinal['dispositivos'][$key]['value']) - 35.567;
  }
  if($type == "POT"){
    $hasPotElet = true;
    $responseFinal['dispositivos'][$key]['value'] = (($responseFinal['dispositivos'][$key]['value'])) * 380;
    $potEletWithDate["'" + $responseFinal['dispositivos'][$key]['timestamp'] + "'"] = $responseFinal['dispositivos'][$key]['value'];
  }
  if($type == "COP"){
    $hasCOP = true;
    $responseFinal['dispositivos'][$key]['value'] = ($responseFinal['dispositivos'][$key]['value']);
    $copWithDate["'" + $responseFinal['dispositivos'][$key]['timestamp'] + "'"] = $responseFinal['dispositivos'][$key]['value'];
  }
}

foreach ($dispositivos as $key => $val) {
  $value = $val['value'];
  $type = $val['type'];

  if($type == "COP"){
    if(!$hasDescarga){
      $valueToApi = substr_replace($value, "AD2", -3);
      if($valueToApi == "00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:33:4F]!"){
        $valueToApi = substr_replace($value, "AD1", -3);
      }
      $array = getLastValue($valueToApi);

      $array['value'] = ($array['value']) ;
      $array['value'] = (0.000002 * pow($array['value'],3)) - (0.0016 * pow($array['value'],2)) + (0.6472 * $array['value'] = $array['value']) - 35.567;
      $descargaWithDate["'" + $array['timestamp'] + "'"] = $array['value'];

      $hasDescarga = true;
    }
    $responseFinal['dispositivos'][$key]['value'] = $responseFinal['dispositivos'][$key]['value'] / ($descargaWithDate["'" + $responseFinal['dispositivos'][$key]['timestamp'] + "'"] - $responseFinal['dispositivos'][$key]['value']);
    $hasCOP = true;
    $copWithDate["'" + $responseFinal['dispositivos'][$key]['timestamp'] + "'"] = $responseFinal['dispositivos'][$key]['value'];
  }

  if($type == "REND"){
    if(!$hasDescarga){
      $valueToApi = substr_replace($value, "AD2", -3);
      if($valueToApi == "00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:33:4F]!"){
        $valueToApi = substr_replace($value, "AD1", -3);
      }
      $array = getLastValue($valueToApi);
      
      $array['value'] = ($array['value']);
      $array['value'] = (0.000002 * pow($array['value'],3)) - (0.0016 * pow($array['value'],2)) + (0.6472 * $array['value'] = $array['value']) - 35.567;
      $descargaWithDate["'" + $array['timestamp'] + "'"] = $array['value'];

      $hasDescarga = true;
    }

    if(!$hasCOP){
      $array = getLastValue(substr_replace($value, "AD0", -3));

      $array['value'] = ($array['value']);
      $array['value'] = $array['value'] / ($descargaWithDate["'" + $array['timestamp'] + "'"] - $array['value']);
      $copWithDate["'" + $array['timestamp'] + "'"] = $array['value'];

      $hasCOP = true;

    }

    if(!$hasPotElet){
      $array = getLastValue(substr_replace($value, "AD1", -3));

      $array['value'] = (($array['value'])) * 380;
      $potEletWithDate["'" + $array['timestamp'] + "'"] = $array['value'];

      $hasPotElet = true;
    }

    // print_r($descargaWithDate);
    $responseFinal['dispositivos'][$key]['value'] = $copWithDate["'" + $responseFinal['dispositivos'][$key]['timestamp'] + "'"] / $potEletWithDate["'" + $responseFinal['dispositivos'][$key]['timestamp'] + "'"] ;
  }

  $responseFinal['dispositivos'][$key]['x'] = date('m/d/Y H:i', strtotime('-0 hours', strtotime($responseFinal['dispositivos'][$key]['timestamp'])));
  $responseFinal['dispositivos'][$key]['y'] = number_format($responseFinal['dispositivos'][$key]['value'],1,".","");

}

echo json_encode($responseFinal);

if (!$err) {
  // echo "Respota: " . $response . "\n";
  return "success";
} else {
  // echo "cURL Error #:" . $err;
	return "error";
}

function getLastValue($device){
  $arrayToReturn = array();
  $idigi_username = 'olavor';  // enter your username here.
  $idigi_password = 'Garymu261990@';  // enter your password here. Consider your options for securing this info.
  $idigi_auth = base64_encode($idigi_username . ":" . $idigi_password);

  $idigi_sci_url = "http://developer.idigi.com/ws/v1/streams/inventory/$device";

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
  
  return $response_array;
}
?>
