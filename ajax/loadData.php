<?php
 class DadosGraficos{
   public $GasSelecionado  = '';
   public $Compressores = false;
   public $DispositivosSelecionado = '';
   public $Erro = '';

   public $P1 = '';
   public $P2 = '';
   public $I1 = '';
   public $T1 = '';
   public $T2 = '';
   public $V1 = '';
   public $TemperaturaSaturada1 = '';
   public $EntalpiaSuccao1 = '';
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
   public $EntalpiaSuccao2 = '';
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

$twoCompressores = $_POST['twoCompressores'];
$gasSelecionado = $_POST['gasSelencionado'];
$dispositivoSelecionado = $_POST['dispositivo'];

$time = strtotime("-7 days", time());
$dia_inicio = date("Y-m-d", $time);

$hora_inicio = "00:00";

$dia_fim = date('Y-m-d');
$hora_fim = "23:59";

$dataInicio = $dia_inicio . "T" . $hora_inicio;
$dataFim = $dia_fim . "T" . $hora_fim;

//Array de dispositivos
$dispositivos = array('4F' => '00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:33:4F]!', '5D' => '00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:31:5D]!', '8C', '00000000-00000000-0004F3FF-FF157C77/xbee.serialIn/[00:13:A2:00:41:87:47:8C]!');

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

//Array Novos Dispositivos In
$dispIn = array();

//Resultado da Solicitação
$result = new DadosGraficos;

$value = $dispositivos[$dispositivoSelecionado];
try {  
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

  $retorno = json_decode($response,true);
  $retorno = base64_decode($retorno['value']);
  
  $splited = preg_split("[/]",$retorno);  
 
  $variavel1 = $splited[0];
  $variavel2 = $splited[1];
  $LETRA = $splited[2];

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

  //Switch do Gas Selecionado
  switch ($gasSelecionado) {
  case 'R22':
    // Calculo com 1 compressor
    if ($P1 != 0 && $P2 != 0) {
      $result -> {'TemperaturaSaturada1'} = pow(-4,-9) * pow(($P2), 4) + pow(5,-6) * pow(($P2), 3) - 0.0026 * pow(($P2), 2) + 0.7124 * $P2 - 34.757;
      $result -> {'EntalpiaSuccao1'} = -pow(-4,-9) * pow(($P2), 4) + pow(5,-6) * pow(($P2), 3) - 0.0027 * pow(($P2), 2) + 0.7965 * ($P2) + 5.2636;
      $result -> {'EntalpiaDescarga1'} = pow(-2,-9) * pow(($P1), 4) + pow(3,-6) * pow(($P1), 3) - 0.0013 * pow(($P1), 2) + 0.3004 * ($P1) + 235.84;
      $result -> {'COP1'} = ($result -> {'EntalpiaSuccao1'} - $result -> {'EntalpiaDescarga1 '}) / $result -> {'PotElet1'};

    }
  
    if ($I1 != 0) {    
      $result -> {'PotElet1'} = 3 * 220 * $I1 * 0.85;
    }

    // Calculo com 2 compressores
    if ($twoCompressores != "false") {
      if ($P2 != 0) {    
        $result ->{'EntalpiaSuccao2'} = pow(-4,-9) * pow(($P2), 4) + pow(5,-6) * pow(($P2), 3) - 0.0027 * pow(($P2), 2) + 0.7965 * ($P2) + 5.2636;
        $result ->{'EntalpiaDescarga2'} = pow(-2,-9) * pow(($P2), 4) + pow(3,-6) * pow(($P2), 3) - 0.0013 * pow(($P2) ,2) + 0.3004 * ($P2) + 235.84;      
        $result ->{'COP2'} = ($result ->{'EntalpiaSuccao2'} - $result ->{'EntalpiaDescarga2'} ) / $result ->{'PotElet2'};
      }
      
      if ($P4 != 0) {
        $result ->{'TemperaturaSaturada2'} = pow(-4,-9) * pow(($P4), 4) + pow(5,-6) * pow(($P4), 3) - 0.0026 * pow(($P4), 2) + 0.7124 * $P4 - 34.757;      
      }
      if ($I2 != 0) {
        $result ->{'PotElet2'} = 3 * 220 * $I2 * 0.85;
      }
    }
    break;

  case 'R404A':
    // Calculo com 1 compressor
    if ($P1 != 0 && $P2 != 0) {
      $result -> {'TemperaturaSaturada1'} = pow(-4,-9) * pow(($P2), 4) + pow(5,-6) * pow(($P2), 3) - 0.0026 * pow(($P2), 2) + 0.7124 * $P2 - 34.757;
      $result -> {'EntalpiaSuccao1'} = -pow(-4,-9) * pow(($P2), 4) + pow(5,-6) * pow(($P2), 3) - 0.0027 * pow(($P2), 2) + 0.7965 * ($P2) + 5.2636;
      $result -> {'EntalpiaDescarga1'} = pow(-2,-9) * pow(($P1), 4) + pow(3,-6) * pow(($P1), 3) - 0.0013 * pow(($P1), 2) + 0.3004 * ($P1) + 235.84;
      $result -> {'COP1'} = ($result -> {'EntalpiaSuccao1'} - $result -> {'EntalpiaDescarga1 '}) / $result -> {'PotElet1'};

    }
  
    if ($I1 != 0) {    
      $result -> {'PotElet1'} = 3 * 220 * $I1 * 0.85;
    }

    // Calculo com 2 compressores
    if ($twoCompressores != "false") {
      if ($P2 != 0) {    
        $result ->{'EntalpiaSuccao2'} = pow(-4,-9) * pow(($P2), 4) + pow(5,-6) * pow(($P2), 3) - 0.0027 * pow(($P2), 2) + 0.7965 * ($P2) + 5.2636;
        $result ->{'EntalpiaDescarga2'} = pow(-2,-9) * pow(($P2), 4) + pow(3,-6) * pow(($P2), 3) - 0.0013 * pow(($P2) ,2) + 0.3004 * ($P2) + 235.84;      
        $result ->{'COP2'} = ($result ->{'EntalpiaSuccao2'} - $result ->{'EntalpiaDescarga2'} ) / $result ->{'PotElet2'};
      }
      if ($P4 != 0) {      
        $result ->{'TemperaturaSaturada2'} = pow(-4,-9) * pow(($P4), 4) + pow(5,-6) * pow(($P4), 3) - 0.0026 * pow(($P4), 2) + 0.7124 * $P4 - 34.757;
      }
      if ($I2 != 0) {
        $result ->{'PotElet2'} = 3 * 220 * $I2 * 0.85;
      }
    }
    break;
  case 'R402B':
    // Calculo com 1 compressor
    if ($P1 != 0 && $P2 != 0) {
      $result -> {'TemperaturaSaturada1'} = pow(-4,-9) * pow(($P2), 4) + pow(5,-6) * pow(($P2), 3) - 0.0026 * pow(($P2), 2) + 0.7124 * $P2 - 34.757;
      $result -> {'EntalpiaSuccao1'} = -pow(-4,-9) * pow(($P2), 4) + pow(5,-6) * pow(($P2), 3) - 0.0027 * pow(($P2), 2) + 0.7965 * ($P2) + 5.2636;
      $result -> {'EntalpiaDescarga1'} = pow(-2,-9) * pow(($P1), 4) + pow(3,-6) * pow(($P1), 3) - 0.0013 * pow(($P1), 2) + 0.3004 * ($P1) + 235.84;
      $result -> {'COP1'} = ($result -> {'EntalpiaSuccao1'} - $result -> {'EntalpiaDescarga1 '}) / $result -> {'PotElet1'};

    }
  
    if ($I1 != 0) {    
      $result -> {'PotElet1'} = 3 * 220 * $I1 * 0.85;
    }

    // Calculo com 2 compressores
    if ($twoCompressores != "false") {
      if ($P2 != 0) {    
        $result ->{'EntalpiaSuccao2'} = pow(-4,-9) * pow(($P2), 4) + pow(5,-6) * pow(($P2), 3) - 0.0027 * pow(($P2), 2) + 0.7965 * ($P2) + 5.2636;
        $result ->{'EntalpiaDescarga2'} = pow(-2,-9) * pow(($P2), 4) + pow(3,-6) * pow(($P2), 3) - 0.0013 * pow(($P2) ,2) + 0.3004 * ($P2) + 235.84;      
        $result ->{'COP2'} = ($result ->{'EntalpiaSuccao2'} - $result ->{'EntalpiaDescarga2'} ) / $result ->{'PotElet2'};
      }
      if ($P4 != 0) {
        $result ->{'TemperaturaSaturada2'} = pow(-4,-9) * pow(($P4), 4) + pow(5,-6) * pow(($P4), 3) - 0.0026 * pow(($P4), 2) + 0.7124 * $P4 - 34.757;      
      }
      if ($I2 != 0) {
        $result ->{'PotElet2'} = 3 * 220 * $I2 * 0.85;
      }
    }
    break;
  case 'R507':
    // Calculo com 1 compressor
    if ($P1 != 0 && $P2 != 0) {
      $result -> {'TemperaturaSaturada1'} = pow(-4,-9) * pow(($P2), 4) + pow(5,-6) * pow(($P2), 3) - 0.0026 * pow(($P2), 2) + 0.7124 * $P2 - 34.757;
      $result -> {'EntalpiaSuccao1'} = -pow(-4,-9) * pow(($P2), 4) + pow(5,-6) * pow(($P2), 3) - 0.0027 * pow(($P2), 2) + 0.7965 * ($P2) + 5.2636;
      $result -> {'EntalpiaDescarga1'} = pow(-2,-9) * pow(($P1), 4) + pow(3,-6) * pow(($P1), 3) - 0.0013 * pow(($P1), 2) + 0.3004 * ($P1) + 235.84;
      $result -> {'COP1'} = ($result -> {'EntalpiaSuccao1'} - $result -> {'EntalpiaDescarga1 '}) / $result -> {'PotElet1'};

    }  
    if ($I1 != 0) {    
      $result -> {'PotElet1'} = 3 * 220 * $I1 * 0.85;
    }

    // Calculo com 2 compressores
    if ($twoCompressores != "false") {
      if ($P1 != 0 && $P2 != 0) {    
        $result ->{'EntalpiaSuccao2'} = pow(-4,-9) * pow(($P2), 4) + pow(5,-6) * pow(($P2), 3) - 0.0027 * pow(($P2), 2) + 0.7965 * ($P2) + 5.2636;
        $result ->{'EntalpiaDescarga2'} = pow(-2,-9) * pow(($P2), 4) + pow(3,-6) * pow(($P2), 3) - 0.0013 * pow(($P2) ,2) + 0.3004 * ($P2) + 235.84;      
        $result ->{'COP2'} = ($result ->{'EntalpiaSuccao2'} - $result ->{'EntalpiaDescarga2'} ) / $result ->{'PotElet2'};
      }
      if ($P4 != 0) {
        $result ->{'TemperaturaSaturada2'} = pow(-4,-9) * pow(($P4), 4) + pow(5,-6) * pow(($P4), 3) - 0.0026 * pow(($P4), 2) + 0.7124 * $P4 - 34.757;      
      }
      if ($I2 != 0) {
        $result ->{'PotElet2'} = 3 * 220 * $I2 * 0.85;
      }
    }
    break;
  }
    
  $result -> {'GasSelecionado'} = $gasSelecionado;
  $result -> {'Compressores'} = $twoCompressores;
  $result -> {'DispositivosSelecionado'} = $dispositivoSelecionado;
  $result -> {'P1'} = $P1;
  $result -> {'P2'} = $P2;
  $result -> {'P3'} = $P3;
  $result -> {'P4'} = $P4;

  $result -> {'I1'} = $I1;
  $result -> {'I2'} = $I2;

  $result -> {'T1'} = $T1;
  $result -> {'T2'} = $T2;
  $result -> {'T3'} = $T3;
  $result -> {'T4'} = $T4;

  $result -> {'V1'} = $V1;
  $result -> {'V2'} = $V2;
  echo json_encode($result);

}
catch (Exception $e) {
  $result -> {'Erro'}  = $e->getMessage();
}

if (!$err) {  
  return "success";
} else {  
	return "error";
}

?>
