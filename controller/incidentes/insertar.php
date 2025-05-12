<?php
	include('../../model/ModelIncidente.php');	
  	/*variable para llamar metodo de Modelo*/
	$model_incidente = new ModelIncidente();
	include('../../model/ModelPoliza.php');	
  	$model_poliza = new ModelPoliza();

	/*Obtenemos los datos*/
	$cliente_id = $_POST["cliente"];
	$vehiculo_id = $_POST["vehiculo"];
	$fecha = $_POST["fecha"];
	$vehiculo_involucrado = trim($_POST["vehiculo_involucrado"]);
	$ajustador = trim($_POST["ajustador"]);
	$cantidad_gruas = trim($_POST["cantidad_gruas"]);
	$circunstancias = trim($_POST["circunstancias"]);

	$poliza = $model_poliza->get_poliza_fecha($cliente_id,$vehiculo_id,$fecha);

	if(!$poliza){
		echo "<script>
				alert('No tienes registrada una póliza en esa fecha.');
				window.location.href = '../../view/incidentes/index.php?cliente_id=". $cliente_id."'; 
			</script>";
	}

	$poliza_id = $poliza["idpoliza"];
	
	$incidente = $model_incidente->agregar_incidente($cliente_id,$vehiculo_id,$poliza_id,$fecha,$vehiculo_involucrado,$ajustador,$cantidad_gruas,$circunstancias);

	if($incidente){
		echo "<script>
			alert('Incidente agregado correctamente.');
			window.location.href = '../../view/incidentes/index.php?cliente_id=". $cliente_id."'; 
			</script>";
	}
	else{
		echo "<script>
			alert('Ha ocurrido un error al agregar el incidente.');
			window.location.href = '../../view/incidentes/index.php?cliente_id=". $cliente_id."'; 
			</script>";
	}		
?>

 