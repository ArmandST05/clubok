<?php
	include('../../model/ModelFormula.php');	
  	/*variable para llamar metodo de Modelo*/
	$model_formula= new ModelFormula();

	/*Obtenemos los datos*/
	$cantidad = $_POST["caja"];
	$vendedor_id = $_POST["vendedor_id"];

  	$caja = $model_formula->add_caja_vendedor($vendedor_id,$cantidad);

	echo "<script> 
		window.location.href = '../../view/cortes/index.php?vendedor_id=$vendedor_id';
	</script>";
				
?>

 