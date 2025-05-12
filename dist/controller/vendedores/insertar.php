<?php
	include('../../model/ModelVendedor.php');
	include('../../model/ModelLogin.php');		
  	/*variable para llamar metodo de Modelo*/
	$model_vendedor = new ModelVendedor();
	/*variable para llamar metodo de Modelo*/
  	$model_login = new ModelLogin();

	/*Obtenemos los datos*/
	$nombre = $_POST["nombre"];
	$nombre_usuario = $_POST["nombre_usuario"];
	$contrasena = $_POST["contrasena"];
	
    $validar_usuario = $model_login->verifica_usuario($nombre_usuario);

	if(!empty($validar_usuario)){
		echo "<script> 
				alert('El nombre de usuario ya existe.');
				window.location.href = '../../view/vendedores/nuevo.php';
			  </script>";
	}

	else {
	//Todo esta bien, agregamos a la BD
	$vendedor_id = $model_vendedor->agregar_vendedor($nombre);

	if($vendedor_id){
		$usuario = $model_login->agregar_usuario('3',$vendedor_id,$nombre_usuario,$contrasena,$nombre);
	}

	echo "<script>
			alert('Vendedor agregado correctamente');
			window.location.href = '../../view/vendedores/index.php?name='; 
			</script>";
	}		
?>
