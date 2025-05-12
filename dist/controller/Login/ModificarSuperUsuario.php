<?php
include('../../model/ModelLogin.php');

$model_login = new ModelLogin();

$usuario = $_POST["user"];
$password = $_POST["pass"];
$id = $_POST["id"];

if(empty($usuario)){
	echo "<script> 
		alert('Ingrese el usuario');
		window.location.href = '../../view/usuarios/modsuperuser.php?user=".$usuario."';
	  </script>";
}else if(empty($password)){
	echo "<script> 
		alert('Ingrese la contrasena');
		window.location.href = '../../view/usuarios/modsuperuser.php?user=".$usuario."';
	  </script>";
}else{

	$model_login->modifica_superusuario($usuario,$password,$id);
	echo "<script> 
			alert('Usuario modificado');
			window.location.href = '../../view/index.php';
	  </script>";
}
?> 