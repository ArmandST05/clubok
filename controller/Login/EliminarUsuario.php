<?php
include('../../model/ModelLogin.php');

$model_login = new ModelLogin();

$id = $_POST["user"];

$model_login->eliminar_usuario($id);
echo "<script> 
		alert('Usuario eliminado');
		window.location.href = '../../view/index.php';
	  </script>";
?> 