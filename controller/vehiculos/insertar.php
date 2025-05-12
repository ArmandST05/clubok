<?php
include('../../model/ModelVehiculo.php');	
$model_vehiculo = new ModelVehiculo();

/*Obtenemos los datos*/
$cliente_id     = $_POST["cliente_id"];
$marca          = strtoupper(trim($_POST["marca"]));
$submarca       = strtoupper(trim($_POST["submarca"]));
$tipo           = strtoupper(trim($_POST["tipo"]));
$color          = strtoupper(trim($_POST["color"]));
$anio           = $_POST["anio"];
$placa          = strtoupper(trim($_POST["placa"]));
$numero_serie   = strtoupper(trim($_POST["numero_serie"]));
$estado         = strtoupper(trim($_POST["estado"]));
$valor          = floatval($_POST["valor"]);

$vehiculo = $model_vehiculo->agregar_vehiculo($cliente_id, $marca, $submarca, $tipo, $color, $anio, $placa, $numero_serie, $estado, $valor);

if ($vehiculo) {
    echo "<script>
        alert('Vehículo agregado correctamente.');
        window.location.href = '../../view/vehiculos/index.php?cliente_id=" . $_POST["cliente_id"] . "'; 
    </script>";
} else {
    echo "<script>
        alert('Ha ocurrido un error al agregar el vehículo.');
        window.location.href = '../../view/vehiculos/index.php?cliente_id=" . $_POST["cliente_id"] . "'; 
    </script>";
}
