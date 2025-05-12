<?php
require('../../view/session.php');
include('../../model/ModelPoliza.php');	
$model_poliza= new ModelPoliza();
include('../../model/ModelVehiculo.php');	
$model_vehiculo = new ModelVehiculo();
include('../../model/ModelCliente.php');	
$model_cliente = new ModelCliente();

$poliza_id = $_POST["poliza_id"];
$cobertura_id = $_POST["cobertura"];
$cliente_id = $_POST["cliente_id"];
$usuario_id = $_SESSION["usuario_id"];
$vehiculo_id = $_POST["vehiculo_id"];
$fecha_inicio = $_POST["fecha_inicio"];
$fecha_fin = $_POST["fecha_fin"];
$vendedor_id = $_POST["vendedor"];
$plazo = $_POST["plazo"];
$beneficiarios_funeraria = $_POST["beneficiarios_funeraria"];

$numero_poliza_respaldo = trim($_POST["numero_poliza_respaldo"]);
$compania_poliza_respaldo = $_POST["compania_poliza_respaldo"];

$total = floatval($_POST["total"]);
$costo_expedicion = floatval($_POST["costo_expedicion"]);
$iva = floatval($_POST["iva"]);

if($poliza_id && $cobertura_id && $cliente_id && $usuario_id && $vehiculo_id && $fecha_inicio && $fecha_fin && $plazo && $total){
  
  $polizaOriginal = $model_poliza->get_poliza($poliza_id);
  $polizaOriginal = reset($polizaOriginal);
  
  $archivo_poliza_respaldo = $polizaOriginal["archivo_poliza_respaldo"];
  
  if (!empty($_FILES["archivo_poliza_respaldo"]["name"])) {

    unlink("../../".$polizaOriginal["archivo_poliza_respaldo"]);

    $nombreArchivoOriginal = $_FILES["archivo_poliza_respaldo"]["name"];

    $nombreArchivo = $poliza_id."-respaldo-".$numero_poliza_respaldo;

    // Image temp source 
    $fileTemp = $_FILES["archivo_poliza_respaldo"]["tmp_name"];

    $path = pathinfo($nombreArchivoOriginal);
    $ext = $path['extension'];

    //Crear carpeta de archivos si no existe
    if (!file_exists("../../storage_data/polizas_respaldo/")) {
      mkdir("../../storage_data/polizas_respaldo/", 0777, true);
    }

    $targetFilePath = "storage_data/polizas_respaldo/" . $nombreArchivo . "." . $ext;

    // Check if file already exists
    if (file_exists("../../".$targetFilePath)) {
      unlink("../../".$targetFilePath);
    }

    if (move_uploaded_file($fileTemp, "../../".$targetFilePath)) {
      $archivo_poliza_respaldo = $targetFilePath;
    }
  }
  
  $poliza = $model_poliza->actualizar_poliza($poliza_id,$cobertura_id,$cliente_id,$usuario_id,$vehiculo_id,$fecha_inicio,$fecha_fin,$vendedor_id,$total,$costo_expedicion,$iva,$plazo,$beneficiarios_funeraria,$numero_poliza_respaldo,$compania_poliza_respaldo,$archivo_poliza_respaldo);

  $eliminados = $model_poliza->eliminar_poliza_detalles($poliza_id);
  foreach($_POST["servicios"] as $servicio_id => $servicio){
    if(isset($servicio['seleccionado'])){
      $detalle_poliza = $model_poliza->agregar_poliza_detalle($poliza_id,$servicio_id,$servicio['cantidad'],floatval($servicio['cantidad_deducible']),floatval($servicio['prima_neta']),floatval($servicio['cantidad_asegurada']),$servicio['cantidad_asegurada_tipo_id']);
    }
  }
  
  echo "<script>
          window.location.href = '../../view/polizas/index.php';
          window.open('../../view/polizas/imprimir_poliza.php?poliza_id=".$poliza_id."');
        </script>";
}
else{
  
  echo "<script>
          alert('Introduce todos los datos por favor.');
          history.back();
        </script>";
}
?>