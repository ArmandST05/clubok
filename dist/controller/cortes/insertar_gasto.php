<?php
	include('../../model/ModelFormula.php');	
  	/*variable para llamar metodo de Modelo*/
	$model_formula= new ModelFormula();

	/*Obtenemos los datos*/
	$gasto = $_POST["gasto"];
	$concepto = $_POST["concepto"];
	$vendedor_id = $_POST["vendedor_id"];

    $caja = $model_formula->add_gasto_vendedor($vendedor_id,$gasto,$concepto);
    
	echo "<script> 
		window.location.href = '../../view/cortes/index.php?vendedor_id=$vendedor_id';
		//window.open('impresion_gasto.php?gasto=".$gasto."&concepto=".$concepto."'); 
		</script>";				
?>

 