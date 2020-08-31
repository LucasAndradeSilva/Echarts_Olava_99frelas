<?php
 class DadosGraficos{
  

   public $P1 = '';
   public $P2 = '';
   public $I1 = '';
   public $T1 = '';
   public $T2 = '';
   public $V1 = '';
   public $TemperaturaSaturada1 = '';
   public $EntalpiaSucção1 = '';
   public $EntalpiaDescarga1 = '';
   public $PotElet1 = '' ;
   public $COP1 = '';

   public $P3 = '';
   public $P4 = '';
   public $I2 = '';
   public $T3 = '';
   public $T4 = '';
   public $V2 = '';
   public $TemperaturaSaturada2 = '';
   public $EntalpiaSucção2 = '';
   public $EntalpiaDescarga2 = '';
   public $PotElet2 = '' ;
   public $COP2 = '' ;
 }



header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header("Content-Type: application/json; charset=utf-8");
date_default_timezone_set('America/Sao_Paulo');
$idigi_username = 'olavor';  // enter your username here.
$idigi_password = 'Garymu261990@';  // enter your password here. Consider your options for securing this info.
$idigi_auth = base64_encode($idigi_username . ":" . $idigi_password);

$getAllValues = $_POST['getAllValues'];
$twoCompressores = $_POST['twoCompressores'];
$gasSelecionado = $_POST['gasSelencionado'];

$time = strtotime("-7 days", time());
$dia_inicio = date("Y-m-d", $time);

$hora_inicio = "00:00";

$dia_fim = date('Y-m-d');
$hora_fim = "23:59";

$dataInicio = $dia_inicio . "T" . $hora_inicio;
$dataFim = $dia_fim . "T" . $hora_fim;

$dispositivos = array('00000000-00000000-0004F3FF-FF157C77/xbee.analog/[00:13:A2:00:41:87:33:4F]!/AD0','00000000-00000000-0004F3FF-FF157C77/xbee.analog/[00:13:A2:00:41:87:33:4F]!/AD1','00000000-00000000-0004F3FF-FF157C77/xbee.analog/[00:13:A2:00:41:87:33:4F]!/AD1','00000000-00000000-0004F3FF-FF157C77/xbee.analog/[00:13:A2:00:41:87:33:4F]!/AD3');
$dispositivosIn = array('00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:31:5D]!','00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:33:4F]!','00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:47:8C]!');
$responseFinal = array('succao'=>'','corrente_eletrica'=>'','temperatura'=>'','descarga'=>'','dispositivos'=>array());

//Array de 2 compressores
$responseTwoCompressores = array('temperatura_saturada1' => '', 'temperatura_saturada2' => '', 'entalpia_sucção1' => '', 'entalpia_sucção2' => '', 'entalpia_descarga1' => '', 'entalpia_descarga2' => '', 'PotElet1' => '', 'PotElet2' => '', 'COP1' => '', 'COP2' => '', 'dispositivos' => array());

//Array Novos Dispositivos In
$dispIn = array();

//Variaveis da nova solicitação xbee.serialIn
$P1=0;
$P2=0;
$P3=0;
$P4=0;

$I1=0;
$I2=0;

$T1=0;
$T2=0;
$T3=0;
$T4=0;

$V1=0;
$V2=0;

// foreach ($dispositivosIn as $key => $value) {
//   $curl = curl_init();
 
//   curl_setopt_array($curl, array(
//     CURLOPT_URL => "http://developer.idigi.com/ws/v1/streams/inventory/$value",
//     CURLOPT_RETURNTRANSFER => true,
//     CURLOPT_ENCODING => "",
//     CURLOPT_MAXREDIRS => 10,
//     CURLOPT_TIMEOUT => 10,
//     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//     CURLOPT_CUSTOMREQUEST => "GET"
//   ));

//   curl_setopt($curl, CURLOPT_HTTPHEADER, array(
//       "Authorization: Basic " . $idigi_auth
//   ));

//   $response = curl_exec($curl);  
//   $err = curl_error($curl);
//   curl_close($curl);
  
//   $dispIn[$key] = json_decode($response,true);

//   $retorno = base64_decode($dispIn[$key]["value"]);

//   $retorno = preg_split("[/]",$retorno);

//   $testeLucas = $retorno;

//   $variavel1 = $retorno[0];
//   $variavel2 = $retorno[1];
//   $LETRA = $retorno[2];

//   switch($LETRA)
//   {
//       case "A":     
//           $P1 = $variavel1;          
//           $P2 = $variavel2;
//           break;
//       case "B":
//           if($twoCompressores){
//             $P3 = $variavel1;
//             $P4 = $variavel2;
//           }
//           break;
//       case "C":        
//           $I1 = $variavel1;
//           if($twoCompressores) $I2 = $variavel2;  
//           break;
//       case "D":
//           $T1 = $variavel1;
//           $T2 = $variavel2;
//           break;
//       case "E":
//           if($twoCompressores){
//             $T3 = $variavel1;
//             $T4 = $variavel2;
//           }
//           break;
//       case "F":
//           $V1 = $variavel1;
//           if($twoCompressores) $V2 = $variavel2;
//           break;
//   }

//   //Switch do Gas Selecionado
//   switch ($gasSelecionado) {
//     case 'R22':
//       // Calculo com 1 compressor
//       $responseTwoCompressores['temperatura_saturada1'] = pow(-4,-9) * pow(($P2), 4) + pow(5,-6) * pow(($P2), 3) - 0.0026 * pow(($P2), 2) + 0.7124 * $P2 - 34.757;
//       $responseTwoCompressores['entalpia_sucção1'] = -pow(-4,-9) * pow(($P2), 4) + pow(5,-6) * pow(($P2), 3) - 0.0027 * pow(($P2), 2) + 0.7965 * ($P2) + 5.2636;
//       $responseTwoCompressores['entalpia_descarga1'] = pow(-2,-9) * pow(($P1), 4) + pow(3,-6) * pow(($P1), 3) - 0.0013 * pow(($P1), 2) + 0.3004 * ($P1) + 235.84;
//       $responseTwoCompressores['PotElet1'] = 3 * 220 * $I1 * 0.85;
//       //$responseTwoCompressores['COP1'] = ($responseTwoCompressores['entalpia_sucção1'] - $responseTwoCompressores['entalpia_descarga1'] ) / $responseTwoCompressores['PotElet1'];
    
//       // Calculo com 2 compressores
//       if ($twoCompressores != "false") {    
//         $responseTwoCompressores['temperatura_saturada2'] = pow(-4,-9) * pow(($P4), 4) + pow(5,-6) * pow(($P4), 3) - 0.0026 * pow(($P4), 2) + 0.7124 * $P4 - 34.757;
//         $responseTwoCompressores['entalpia_sucção2'] = pow(-4,-9) * pow(($P2), 4) + pow(5,-6) * pow(($P2), 3) - 0.0027 * pow(($P2), 2) + 0.7965 * ($P2) + 5.2636;
//         $responseTwoCompressores['entalpia_descarga2'] = pow(-2,-9) * pow(($P2), 4) + pow(3,-6) * pow(($P2), 3) - 0.0013 * pow(($P2) ,2) + 0.3004 * ($P2) + 235.84;
//         $responseTwoCompressores['PotElet2'] = 3 * 220 * $I2 * 0.85;
//         //$responseTwoCompressores['COP2'] = ($responseTwoCompressores['entalpia_sucção2'] - $responseTwoCompressores['entalpia_descarga2'] ) / $responseTwoCompressores['PotElet2'];
//       }
//       break;
    
//     case 'R404A':
//       // Calculo com 1 compressor
//       $responseTwoCompressores['temperatura_saturada1'] =  1;
//       $responseTwoCompressores['entalpia_sucção1'] = 1;
//       $responseTwoCompressores['entalpia_descarga1'] = 1;
//       $responseTwoCompressores['PotElet1'] = 1;
//       $responseTwoCompressores['COP1'] = 1;
    
//       // Calculo com 2 compressores
//       if ($twoCompressores != "false") {   
//         $responseTwoCompressores['temperatura_saturada2'] = 1;
//         $responseTwoCompressores['entalpia_sucção2'] = 1;
//         $responseTwoCompressores['entalpia_descarga2'] = 1;
//         $responseTwoCompressores['PotElet2'] = 1;
//         $responseTwoCompressores['COP2'] = 1;
//       }
//       break;   
//     case 'R402B':
//       // Calculo com 1 compressor
//       $responseTwoCompressores['temperatura_saturada1'] =  1;
//       $responseTwoCompressores['entalpia_sucção1'] = 1;
//       $responseTwoCompressores['entalpia_descarga1'] = 1;
//       $responseTwoCompressores['PotElet1'] = 1;
//       $responseTwoCompressores['COP1'] = 1;
    
//       // Calculo com 2 compressores
//       if ($twoCompressores != "false") {   
//         $responseTwoCompressores['temperatura_saturada2'] = 1;
//         $responseTwoCompressores['entalpia_sucção2'] = 1;
//         $responseTwoCompressores['entalpia_descarga2'] = 1;
//         $responseTwoCompressores['PotElet2'] = 1;
//         $responseTwoCompressores['COP2'] = 1;
//       }
//       break;  
//     case 'R507':
//       // Calculo com 1 compressor
//       $responseTwoCompressores['temperatura_saturada1'] =  1;
//       $responseTwoCompressores['entalpia_sucção1'] = 1;
//       $responseTwoCompressores['entalpia_descarga1'] = 1;
//       $responseTwoCompressores['PotElet1'] = 1;
//       $responseTwoCompressores['COP1'] = 1;
    
//       // Calculo com 2 compressores
//       if ($twoCompressores != "false") { 
//         $responseTwoCompressores['temperatura_saturada2'] = 1;
//         $responseTwoCompressores['entalpia_sucção2'] = 1;
//         $responseTwoCompressores['entalpia_descarga2'] = 1;
//         $responseTwoCompressores['PotElet2'] = 1;
//         $responseTwoCompressores['COP2'] = 1;
//       }
//       break;
//   }
// }

$testeLucas = "";
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
  
  $curl = curl_init();
 
  curl_setopt_array($curl, array(
    CURLOPT_URL => "http://developer.idigi.com/ws/v1/streams/inventory//00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:31:5D]!",
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

  $response2 = curl_exec($curl);  
  $err = curl_error($curl);
  curl_close($curl);

  $response_array2 = json_decode($response2,true);  
      
  $retorno = base64_decode($response_array2["value"]);

  $retorno = preg_split("[/]",$retorno);

  $testeLucas = $retorno;

  $variavel1 = $retorno[0];
  $variavel2 = $retorno[1];
  $LETRA = $retorno[2];

  switch($LETRA)
  {
      case "A":     
          $P1 = $variavel1;          
          $P2 = $variavel2;
          break;
      case "B":
          if($twoCompressores){
            $P3 = $variavel1;
            $P4 = $variavel2;
          }
          break;
      case "C":        
          $I1 = $variavel1;
          if($twoCompressores) $I2 = $variavel2;  
          break;
      case "D":
          $T1 = $variavel1;
          $T2 = $variavel2;
          break;
      case "E":
          if($twoCompressores){
            $T3 = $variavel1;
            $T4 = $variavel2;
          }
          break;
      case "F":
          $V1 = $variavel1;
          if($twoCompressores) $V2 = $variavel2;
          break;
  }
 
  switch ($key) {
    case 0:
      $responseFinal['temperatura'] = number_format(($response_array['value']) ,1,".","");
      break;

    case 1:
      $responseFinal['corrente_eletrica'] = number_format(($response_array['value']) ,1,".","");
      break;

    case 2:
      $responseFinal['descarga'] = number_format(($response_array['value']) ,1,".","");      
      break;

    case 3:
      $responseFinal['succao'] = number_format(($response_array['value']),1,".","");      
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
  
  // Switch do Gas Selecionado
  switch ($gasSelecionado) {
    case 'R22':
      // Calculo com 1 compressor
      $responseTwoCompressores['temperatura_saturada1'] = pow(-4,-9) * pow(($P2), 4) + pow(5,-6) * pow(($P2), 3) - 0.0026 * pow(($P2), 2) + 0.7124 * $P2 - 34.757;
      $responseTwoCompressores['entalpia_sucção1'] = -pow(-4,-9) * pow(($P2), 4) + pow(5,-6) * pow(($P2), 3) - 0.0027 * pow(($P2), 2) + 0.7965 * ($P2) + 5.2636;
      $responseTwoCompressores['entalpia_descarga1'] = pow(-2,-9) * pow(($P1), 4) + pow(3,-6) * pow(($P1), 3) - 0.0013 * pow(($P1), 2) + 0.3004 * ($P1) + 235.84;
      $responseTwoCompressores['PotElet1'] = 3 * 220 * $I1 * 0.85;
      //$responseTwoCompressores['COP1'] = ($responseTwoCompressores['entalpia_sucção1'] - $responseTwoCompressores['entalpia_descarga1'] ) / $responseTwoCompressores['PotElet1'];
    
      // Calculo com 2 compressores
      if ($twoCompressores != "false") {    
        $responseTwoCompressores['temperatura_saturada2'] = pow(-4,-9) * pow(($P4), 4) + pow(5,-6) * pow(($P4), 3) - 0.0026 * pow(($P4), 2) + 0.7124 * $P4 - 34.757;
        $responseTwoCompressores['entalpia_sucção2'] = pow(-4,-9) * pow(($P2), 4) + pow(5,-6) * pow(($P2), 3) - 0.0027 * pow(($P2), 2) + 0.7965 * ($P2) + 5.2636;
        $responseTwoCompressores['entalpia_descarga2'] = pow(-2,-9) * pow(($P2), 4) + pow(3,-6) * pow(($P2), 3) - 0.0013 * pow(($P2) ,2) + 0.3004 * ($P2) + 235.84;
        $responseTwoCompressores['PotElet2'] = 3 * 220 * $I2 * 0.85;
        //$responseTwoCompressores['COP2'] = ($responseTwoCompressores['entalpia_sucção2'] - $responseTwoCompressores['entalpia_descarga2'] ) / $responseTwoCompressores['PotElet2'];
      }
      break;
    
    case 'R404A':
      // Calculo com 1 compressor
      $responseTwoCompressores['temperatura_saturada1'] =  1;
      $responseTwoCompressores['entalpia_sucção1'] = 1;
      $responseTwoCompressores['entalpia_descarga1'] = 1;
      $responseTwoCompressores['PotElet1'] = 1;
      $responseTwoCompressores['COP1'] = 1;
    
      // Calculo com 2 compressores
      if ($twoCompressores != "false") {   
        $responseTwoCompressores['temperatura_saturada2'] = 1;
        $responseTwoCompressores['entalpia_sucção2'] = 1;
        $responseTwoCompressores['entalpia_descarga2'] = 1;
        $responseTwoCompressores['PotElet2'] = 1;
        $responseTwoCompressores['COP2'] = 1;
      }
      break;   
    case 'R402B':
      // Calculo com 1 compressor
      $responseTwoCompressores['temperatura_saturada1'] =  1;
      $responseTwoCompressores['entalpia_sucção1'] = 1;
      $responseTwoCompressores['entalpia_descarga1'] = 1;
      $responseTwoCompressores['PotElet1'] = 1;
      $responseTwoCompressores['COP1'] = 1;
    
      // Calculo com 2 compressores
      if ($twoCompressores != "false") {   
        $responseTwoCompressores['temperatura_saturada2'] = 1;
        $responseTwoCompressores['entalpia_sucção2'] = 1;
        $responseTwoCompressores['entalpia_descarga2'] = 1;
        $responseTwoCompressores['PotElet2'] = 1;
        $responseTwoCompressores['COP2'] = 1;
      }
      break;  
    case 'R507':
      // Calculo com 1 compressor
      $responseTwoCompressores['temperatura_saturada1'] =  1;
      $responseTwoCompressores['entalpia_sucção1'] = 1;
      $responseTwoCompressores['entalpia_descarga1'] = 1;
      $responseTwoCompressores['PotElet1'] = 1;
      $responseTwoCompressores['COP1'] = 1;
    
      // Calculo com 2 compressores
      if ($twoCompressores != "false") { 
        $responseTwoCompressores['temperatura_saturada2'] = 1;
        $responseTwoCompressores['entalpia_sucção2'] = 1;
        $responseTwoCompressores['entalpia_descarga2'] = 1;
        $responseTwoCompressores['PotElet2'] = 1;
        $responseTwoCompressores['COP2'] = 1;
      }
      break;
  }
  
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

$responseFinal['dispositivos']['dispositivosIn'] = $responseTwoCompressores;
$responseFinal['dispositivos']['arrayDispositivos'] = $dispIn;
$responseFinal['dispositivos']['gas'] = $gasSelecionado;
$responseFinal['dispositivos']['doisCompressores'] = $twoCompressores;
echo json_encode($responseFinal);


if (!$err) {  
  return "success";
} else {  
	return "error";
}

?>
