<?php
	include('../../model/ModelCliente.php');	
  	/*variable para llamar metodo de Modelo*/
	$model_cliente = new ModelCliente();

	/*Obtenemos los datos*/
	$cliente_id = $_POST["cliente_id"];
	$estatus = $_POST["estatus"];

	$model_cliente->actualizar_estatus($cliente_id,$estatus);

	echo "<script>
		alert('Cliente eliminado correctamente.');
		window.location.href = '../../view/clientes/index.php'; 
	</script>";
?>

 