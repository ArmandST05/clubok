<?php
include('../../model/ModelLogin.php');

$model_login = new ModelLogin();

$usuario = $_POST["user"];
$password = $_POST["pass"];

$result = $model_login->valida_login($usuario,$password);

foreach ($result as $data) {
	if(strcmp($password, $data["password"]) == 0){//Comparamos que las contraseñas sean iguales (Sensible a Mayusculas y minusculas)
		session_start();
		$_SESSION["user"] = $usuario;
		$_SESSION["usuario_id"] = $data["idusuario"];
		$_SESSION["tipo_usuario"] = $data["tipo_usuario_id"];
		$_SESSION["vendedor_id"] = $data["vendedor_id"];

		if($_SESSION["tipo_usuario"] == 1){
			echo "<script> 
				window.location.href = '../../view/polizas/index.php';
		  	  </script>";
		}else if($_SESSION["tipo_usuario"] == 2){
			echo "<script> 
				window.location.href = '../../view/polizas/index.php';
		  	  </script>";
		}
		else if($_SESSION["tipo_usuario"] == 3){
			echo "<script> 
				window.location.href = '../../view/polizas/index.php';
		  	  </script>";
		}
	}else{
		echo "<script> 
			alert('El usuario o la contraseña son incorrectos.');
			window.location.href = '../../index.php';
		  </script>";
	}
}

if(empty($result)){
	echo "<script> 
			alert('El usuario no existe o la contraseña es errónea.');
			window.location.href = '../../index.php';
		  </script>";
}
