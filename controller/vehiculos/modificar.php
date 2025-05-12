<?php
	include('../../model/ModelVehiculo.php');	
  	$model_vehiculo = new ModelVehiculo();

	// Obtenemos los datos del formulario
	$vehiculo_id   = $_POST["vehiculo_id"];
	$marca         = strtoupper(trim($_POST["marca"]));
	$submarca      = strtoupper(trim($_POST["submarca"]));
	$tipo          = $_POST["tipo"]; // no aplicar strtoupper, es un ID
	$estado        = $_POST["estado"]; // no aplicar strtoupper, es un ID
	$color         = strtoupper(trim($_POST["color"]));
	$anio          = $_POST["anio"];
	$placa         = strtoupper(trim($_POST["placa"]));
	$numero_serie  = strtoupper(trim($_POST["numero_serie"]));
	$valor         = $_POST["valor"];
	$cliente_id    = $_POST["cliente_id"];

	// Llamada al modelo para actualizar el vehículo
	$vehiculo = $model_vehiculo->actualizar_vehiculo(
		$vehiculo_id,
		$marca,
		$submarca,
		$tipo,
		$estado,
		$color,
		$anio,
		$placa,
		$numero_serie,
		$valor
	);

	if ($vehiculo) {
		echo "<script>
			alert('Vehículo actualizado correctamente.');
			window.location.href = '../../view/vehiculos/index.php?cliente_id=$cliente_id';
		</script>";
	} else {
		echo "<script>
			alert('Ha ocurrido un error al actualizar el vehículo.');
			window.location.href = '../../view/vehiculos/index.php?cliente_id=$cliente_id';
		</script>";
	}
?>
