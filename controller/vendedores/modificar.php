<?php
	include('../../model/ModelVendedor.php');	
	include('../../model/ModelLogin.php');	
  	/*variable para llamar metodo de Modelo*/
	$model_vendedores = new ModelVendedor();
	$model_login = new ModelLogin();
	
	/*Obtenemos los datos*/
	$vendedor_id = $_POST["vendedor_id"];
	$nombre = $_POST["nombre"];
	$nombre_usuario = trim($_POST["nombre_usuario"]);
	$contrasena = trim($_POST["contrasena"]);

	$validar_usuario = $model_login->verifica_usuario($nombre_usuario);

	if(!empty($validar_usuario) && $validar_usuario[0]['vendedor_id'] != $vendedor_id){
		echo "<script> 
				alert('El nombre de usuario ya existe.');
				window.location.href = '../../view/vendedores/index.php?name='; 
			  </script>";
	}else{
		$model_vendedores->editar_vendedor($vendedor_id,$nombre);
		if(!empty($contrasena)){
			$model_login->editar_vendedor_contrasena($vendedor_id,$nombre_usuario,$nombre,$contrasena);
		}
		else{
			$model_login->editar_nombre_usuario_vendedor($vendedor_id,$nombre_usuario,$nombre);
		}

		echo "<script>
				alert('Vendedor actualizado correctamente');
				window.location.href = '../../view/vendedores/index.php?name='; 
				</script>";
	}
					
?>

 