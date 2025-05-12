<?php
include('../../model/ModelPoliza.php');
$model_poliza = new ModelPoliza();
$cliente_id = $_GET["cliente_id"];
$vehiculo_id = $_GET["vehiculo_id"];
$fecha = $_GET["fecha"];

$poliza = $model_poliza->validar_poliza_cliente_fecha($cliente_id,$vehiculo_id,$fecha);
if(isset($poliza) && $poliza){
  return http_response_code(200);
}
else{
  return http_response_code(500);
}

?>
