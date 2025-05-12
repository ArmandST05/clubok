<?php
include('../../model/ModelLogin.php');

$model_login = new ModelLogin();

$tipousuario = $_POST["tipo_usuario"];
$usuario = $_POST["user"];
$password = $_POST["pass"];
$nombre = $_POST["zona"];

$checausuario = $model_login->verifica_usuario($usuario);

if(!empty($checausuario)){
	echo "<script> 
		alert('El usuario ya existe, ingresa otro porfavor');
		window.location.href = '../../view/usuarios/adduser.php';
	  </script>";
}

if(empty($usuario)){
	echo "<script> 
		alert('Ingresa un usuario');
		window.location.href = '../../view/usuarios/adduser.php';
	  </script>";
}else if(empty($password)){
	echo "<script> 
		alert('Ingresa una contrasena');
		window.location.href = '../../view/usuarios/adduser.php';
	  </script>";
}else if(empty($nombre) && $tipousuario == "uc" || $tipousuario == "ud"){
		$nombre = "Todas";
		$model_login->crea_usuario($usuario,$password,$nombre,$tipousuario);
		echo "<script> 
			alert('Usuario creado');
			window.location.href = '../../view/index.php';
		  </script>";
	}else if(empty($nombre) && $tipousuario == "u"){
		echo "<script> 
		alert('Ingresa una zona');
		window.location.href = '../../view/usuarios/adduser.php';
	  </script>";
	}else{
		$model_login->crea_usuario($usuario,$password,$nombre,$tipousuario);
		echo "<script> 
			alert('Usuario creado');
			window.location.href = '../../view/index.php';
		  </script>";
}





?> 