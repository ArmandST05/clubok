<?php
include('../../model/ModelLogin.php');

$model_login = new ModelLogin();

$tipousuario = $_POST["tipo_usuario"];
$usuario = $_POST["user"];
$password = $_POST["pass"];
$nombre = $_POST["zona"];
$id = $_POST["id"];

if(empty($nombre)){
	$nombre = "Todas";
}

if(strcmp($tipousuario, "uc") == 0){
	$nombre = "Todas";
}

$model_login->modifica_usuario($usuario,$password,$nombre,$tipousuario,$id);
echo "<script> 
		alert('Usuario modificado');
		window.location.href = '../../view/index.php';
	  </script>";
?> 