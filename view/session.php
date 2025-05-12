<?php
session_start();
if(!empty($_SESSION["user"])){
	//Verificamos que el tipo de usuario sea "su" para darle solo acceso a los superusuarios
	if($_SESSION["tipo_usuario"]==1 || $_SESSION["tipo_usuario"]==2 || $_SESSION["tipo_usuario"]==3){

	}else{
		echo "<script> 
				alert('No tienes permiso para acceder a esta sección.');
				window.location.href = '/view/productos/index_productos.php?name=&alma=';
			  </script>";
	}
}else{
	echo "<script> 
	alert('Inicia sesion porfavor!');
	window.location.href = '/index.php';
	</script>";
}

?>