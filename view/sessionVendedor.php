<?php
session_start();
if(!empty($_SESSION["user"])){
	//Verificamos que el tipo de usuario sea "vendedor" para darle acceso
	if($_SESSION["tipo_usuario"] == 3){

	}else{
		echo "<script> 
				alert('No tienes permiso para acceder a esta sección');
				window.location.href = '/view/productos/index_productos.php?name=&alma=';
			  </script>";
	}
}else{
	echo "<script> 
	alert('Inicia sesión porfavor!');
	window.location.href = '/index.php';
	</script>";
}

?>