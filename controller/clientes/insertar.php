<?php
include('../../model/ModelCliente.php');
/*variable para llamar metodo de Modelo*/
$model_cliente = new ModelCliente();

/*Obtenemos los datos*/
$nombre = strtoupper(trim($_POST["nombre"]));
$fecha_nacimiento = (($_POST["fecha_nacimiento"] != "") ? (date("Y-m-d", strtotime(str_replace('/', '-', $_POST["fecha_nacimiento"])))) : "");
$calle = strtoupper(trim($_POST["calle"]));
$numero = strtoupper(trim($_POST["numero"]));
$colonia = strtoupper(trim($_POST["colonia"]));
$ciudad = strtoupper(trim($_POST["ciudad"]));
$codigo_postal = trim($_POST["codigo_postal"]);
$estado = strtoupper($_POST["estado"]);
$telefono = trim($_POST["telefono"]);
$telefono_alternativo = trim($_POST["telefono_alternativo"]);
$vendedor_id = $_POST["vendedor"];

$validarCliente = $model_cliente->validar_cliente($nombre);

if ($validarCliente) {
	echo "<script>
				alert('El cliente ya existe, no se ha podido agregar.');
				window.location.href = '../../view/clientes/nuevo.php'; 
				</script>";
} else {
	$cliente = $model_cliente->agregar_cliente($nombre, $fecha_nacimiento, $calle, $numero, $colonia, $ciudad, $codigo_postal, $estado, $telefono, $telefono_alternativo, $vendedor_id);

	if ($cliente) {
		echo "<script>
				alert('Cliente agregado correctamente.');
				window.location.href = '../../view/clientes/index.php'; 
				</script>";
	} else {
		echo "<script>
				alert('Ha ocurrido un error al agregar el cliente.');
				window.location.href = '../../view/clientes/index.php'; 
				</script>";
	}
}
