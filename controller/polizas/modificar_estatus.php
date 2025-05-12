<?php
	include('../../model/ModelPoliza.php');	
  	/*variable para llamar metodo de Modelo*/
	$model_poliza = new ModelPoliza();

	/*Obtenemos los datos*/
	$poliza_id = $_POST["poliza_id"];
	$estatus = $_POST["estatus"];
	$motivoEstatus = (isset($_POST["motivoEstatus"])) ? $_POST["motivoEstatus"]: "";
	$fechaEstatus = date("Y-m-d");

	$abonos = $model_poliza->get_abonos_poliza($poliza_id);
	if(count($abonos) > 1 || $estatus == 2){
		//Si ya tiene más abonos ocultar la póliza
		$model_poliza->actualizar_estatus($poliza_id,$estatus,$fechaEstatus,$motivoEstatus);
	}else{
		//Si no eliminar definitivamente
		$model_poliza->eliminar_poliza($poliza_id);
	}

	echo "<script>
		alert('Póliza actualizada correctamente.');
		history.back(); 
	</script>";
?>
