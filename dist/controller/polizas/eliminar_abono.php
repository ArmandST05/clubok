<?php
	include('../../model/ModelPoliza.php');	
  	/*variable para llamar metodo de Modelo*/
	$model_poliza = new ModelPoliza();

	$abono_id = $_POST["abono_id"];
	
	$eliminar = $model_poliza->eliminar_abono($abono_id);
	echo "<script>
		window.history.back();
	</script>";
?>
