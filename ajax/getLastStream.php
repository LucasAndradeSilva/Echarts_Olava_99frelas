<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header("Content-Type: application/json; charset=utf-8");
$idigi_username = 'olavor';  // enter your username here.
$idigi_password = 'Garymu261990@';  // enter your password here. Consider your options for securing this info.

// HTTP post code with basic auth below, thanks to Jonas John: http://www.jonasjohn.de/snippets/php/post-request.htm
// Set up basic auth string
$idigi_auth = base64_encode($idigi_username . ":" . $idigi_password);

//substr_replace($idigi_auth,"",-1);

//   ws/v1/streams/inventory/00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:47:8C]!
//   ws/v1/streams/history/00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:47:8C]!
// parse the given URL     start_time=2019-10-16T00:01:00.000&end_time=2019-10-16T18:59:59.590&order=desc
//echo date('His');   
//$idigi_sci_url = "http://developer.idigi.com/ws/v1/streams/history/00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:47:8C]!?size=5";
//$idigi_sci_url = "http://developer.idigi.com/ws/v1/streams/inventory/00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:47:8C]!?";

$dia_fim      = $_POST["dia_fim"];
$hora_fim     = $_POST["hora_fim"];

$dispositivos = $_POST['dispositivos'];
// $dispositivos = array('00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:47:8C]!');
$responseFinal = array('dispositivos'=>array());

if ($dia_fim != "" || $hora_fim != ""){
  echo $responseFinal;
  exit;
}

// NÂO USAR FSOCKOPEN

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

  $response_array = json_decode($response);

  array_push($responseFinal['dispositivos'], $response_array);

}

echo json_encode($responseFinal);
?>