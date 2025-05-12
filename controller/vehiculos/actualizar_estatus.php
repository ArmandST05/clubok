<?php
	include('../../model/ModelVehiculo.php');	
  	/*variable para llamar metodo de Modelo*/
	$model_vehiculo = new ModelVehiculo();

	/*Obtenemos los datos*/
	$vehiculo_id = $_POST["vehiculo_id"];
	$estatus = $_POST["estatus"];

	$vehiculo = $model_vehiculo->actualizar_estatus($vehiculo_id,$estatus);

	if($vehiculo){
		echo "<script>
			alert('Vehículo eliminado correctamente.');
			window.location.href = '../../view/vehiculos/index.php?cliente_id=". $_POST["cliente_id"]."'; 
			</script>";
	}
	else{
		echo "<script>
			alert('Ha ocurrido un error al eliminar el vehículo.');
			window.location.href = '../../view/vehiculos/index.php?cliente_id=". $_POST["cliente_id"]."'; 
			</script>";
	}	
?>

 