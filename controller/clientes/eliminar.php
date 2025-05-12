<?php
	include('../../model/ModelCliente.php');	
  	/*variable para llamar metodo de Modelo*/
	$model_cliente = new ModelCliente();

	/*Obtenemos los datos*/
	$cliente_id = $_POST["cliente"];

	$model_cliente->delete_cliente($cliente_id);

	echo "<script>
		alert('Cliente eliminado correctamente');
		window.location.href = '../../view/clientes/index.php'; 
	</script>";
?>

 