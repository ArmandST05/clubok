<?php
	include('../../model/ModelVendedor.php');	
  	/*variable para llamar metodo de Modelo*/
	$model_vendedores = new ModelVendedor();
	include('../../model/ModelLogin.php');	
	/*variable para llamar metodo de Modelo*/
  	$model_login = new ModelLogin();

	/*Obtenemos los datos*/
	$id = $_POST["id"];
	$estatus = $_POST["estatus"];

	$model_vendedores->actualizar_estatus($id,$estatus);
	$model_login->actualizar_estatus_usuario_vendedor($id,$estatus);

	echo "<script>
		alert('Vendedor eliminado correctamente');
		window.location.href = '../../view/vendedores/index.php?name='; 
		</script>";
?>

 