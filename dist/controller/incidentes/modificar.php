<?php
	include('../../model/ModelIncidente.php');	
	/*variable para llamar metodo de Modelo*/
  	$model_incidente = new ModelIncidente();

	/*Obtenemos los datos*/
	$incidente_id = $_POST["incidente_id"];
	$vehiculo_involucrado = trim($_POST["vehiculo_involucrado"]);
	$ajustador = trim($_POST["ajustador"]);
	$circunstancias = trim($_POST["circunstancias"]);
	$cantidad_gruas = trim($_POST["cantidad_gruas"]);
	

	$incidente = $model_incidente->actualizar_incidente($incidente_id,$vehiculo_involucrado,$ajustador,$cantidad_gruas,$circunstancias);

	if($incidente){
		echo "<script>
			alert('Incidente actualizado correctamente.');
			window.location.href = '../../view/incidentes/index.php?cliente_id=".$_POST["cliente_id"]."'; 
			</script>";
	}
	else{
		echo "<script>
			alert('Ha ocurrido un error al actualizar el incidente.');
			window.location.href = '../../view/incidentes/index.php?cliente_id=".$_POST["cliente_id"]."'; 
			</script>";
	}					
?>

 