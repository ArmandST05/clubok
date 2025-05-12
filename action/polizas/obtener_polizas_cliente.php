<?php
include('../../model/ModelPoliza.php');
$model_poliza = new ModelPoliza();
$cliente_id = $_GET["cliente_id"];

$polizas = $model_poliza->get_polizas_cliente($cliente_id);
  echo "<option value='0'>TODOS</option>";
    foreach ($polizas as $poliza) {
      echo "<option value='".$poliza['idpoliza']."'>".$poliza['idpoliza']."</option>";
    }
?>
