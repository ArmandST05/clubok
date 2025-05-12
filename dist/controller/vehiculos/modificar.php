<?php
	include('../../model/ModelVehiculo.php');	
	/*variable para llamar metodo de Modelo*/
  	$model_vehiculo = new ModelVehiculo();

	/*Obtenemos los datos*/
	
	$vehiculo_id = $_POST["vehiculo_id"];
	$marca = strtoupper(trim($_POST["marca"]));
	$tipo = strtoupper(trim($_POST["tipo"]));
	$color = strtoupper(trim($_POST["color"]));
	$anio = $_POST["anio"];
	$placa = strtoupper(trim($_POST["placa"]));
	$numero_serie = strtoupper(trim($_POST["numero_serie"]));
	

	$vehiculo = $model_vehiculo->actualizar_vehiculo($vehiculo_id,$marca,$tipo,$color,$anio,$placa,$numero_serie);

	if($vehiculo){
		echo "<script>
			alert('Vehículo actualizado correctamente.');
			window.location.href = '../../view/vehiculos/index.php?cliente_id=". $_POST["cliente_id"]."'; 
			</script>";
	}
	else{
		echo "<script>
			alert('Ha ocurrido un error al actualizar el vehículo.');
			window.location.href = '../../view/vehiculos/index.php?cliente_id=". $_POST["cliente_id"]."'; 
			</script>";
	}					
?>

 