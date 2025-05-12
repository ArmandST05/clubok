<?php
	include('../../model/ModelFormula.php');	
  	/*variable para llamar metodo de Modelo*/
	$model_formula= new ModelFormula();
	include('../../model/ModelPoliza.php');	
	$model_poliza= new ModelPoliza();

	/*Obtenemos los datos*/
	$vendedor_id = $_POST["vendedor_id"];
	$total_abonos = $_POST["total_abonos"];
	$total_caja = $_POST["total_caja"];
	$total_gastos = $_POST["total_gastos"];

	$abonos = $model_poliza->actualizar_corte_abonos_vendedor($vendedor_id);
	$caja_detalles = $model_formula->actualizar_corte_caja_vendedor($vendedor_id);
    
	echo "<script> 
			window.location.href = '../../view/cortes/index.php?vendedor_id=$vendedor_id';
			//window.open('impresion_corte.php?t_contado=".$total_abonos."&t_caja=".$total_caja."&t_gastos=".$total_gastos."'); 
		</script>";				
?>

 