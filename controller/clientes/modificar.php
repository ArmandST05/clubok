<?php
	include('../../model/ModelCliente.php');	
	/*variable para llamar metodo de Modelo*/
  	$model_cliente = new ModelCliente();

	/*Obtenemos los datos*/
	$cliente_id = $_POST["cliente_id"];
	$nombre = strtoupper(trim($_POST["nombre"]));
	$fecha_nacimiento = (($_POST["fecha_nacimiento"] != "") ? (date("Y-m-d", strtotime(str_replace('/', '-',$_POST["fecha_nacimiento"])))): "");
	$calle = strtoupper(trim($_POST["calle"]));
	$numero = strtoupper(trim($_POST["numero"]));
	$colonia = strtoupper(trim($_POST["colonia"]));
	$ciudad = strtoupper(trim($_POST["ciudad"]));
	$codigo_postal = trim($_POST["codigo_postal"]);
	$estado = strtoupper($_POST["estado"]);
	$telefono = trim($_POST["telefono"]);
	$telefono_alternativo = trim($_POST["telefono_alternativo"]);
	$vendedor_id = $_POST["vendedor"];
	$curp = $_POST["curp"];
	$tipo_cliente = $_POST["tipo_cliente"];
$fecha_registro = $_POST["fecha_registro"];
	$cliente = $model_cliente->actualizar_cliente($cliente_id,$nombre,$fecha_nacimiento,$calle,$numero,$colonia,$ciudad,$codigo_postal,$estado,$telefono,$telefono_alternativo,$vendedor_id, $curp, $tipo_cliente, $fecha_registro);

	if($cliente){
		echo "<script>
			alert('Cliente actualizado correctamente.');
			window.location.href = '../../view/clientes/index.php'; 
			</script>";
	}
	else{
		echo "<script>
			alert('Ha ocurrido un error al actualizar el cliente.');
			window.location.href = '../../view/clientes/index.php'; 
			</script>";
	}
					
?>

 