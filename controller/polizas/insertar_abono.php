<?php
	include_once('../../model/ModelPoliza.php');	
  /*variable para llamar metodo de Modelo*/
	$model_poliza= new ModelPoliza();

	/*Obtenemos los datos*/
  $fecha = $_GET["fecha"];
	$vendedor_id = $_GET["vendedor"];
  $poliza_id = $_GET["poliza_id"];
  $cantidad = floatval($_GET["cantidad"]);
  

  $poliza = $model_poliza->get_poliza($poliza_id);
  $poliza = reset($poliza);
  $total = floatval($poliza["total"]);
  $total_abonos = floatval($model_poliza->get_total_abonos_poliza($poliza_id));

  $abonado_actual = ($total_abonos + $cantidad);
  $restante_pagar = $poliza['total'] - $abonado_actual;
 
  if($cantidad == 0){
    echo "<script>
      alert('Agrega un cantidad válida para el abono.');
      window.history.back(); 
      </script>";
  }
  if($abonado_actual > $total){
    echo "<script>
      alert('Los abonos superan el total de la venta, elimina los abonos incorrectos.');
      window.history.back(); 
      </script>";
  }
  elseif(number_format($restante_pagar,2,'.','') == 0 || number_format($restante_pagar,2,'.','') == 0.00){
    $abono_id = $model_poliza->agregar_abono($poliza_id,$cantidad,$fecha,$vendedor_id);
    $model_poliza->liquidar_poliza($poliza_id);
    echo "<script>
          alert('Cuenta liquidada');
          window.location.href='../../view/polizas/nuevo_abono.php?poliza_id=".$poliza_id."'; 
          window.open('../../view/polizas/imprimir_abono.php?abono_id=".$abono_id."'); 
        </script>";
  }
  else{
    $abono_id = $model_poliza->agregar_abono($poliza_id,$cantidad,$fecha,$vendedor_id);
    echo "<script>
            alert('Cuenta abonada');
            window.location.href='../../view/polizas/nuevo_abono.php?poliza_id=".$poliza_id."'; 
            window.open('../../view/polizas/imprimir_abono.php?abono_id=".$abono_id."'); 
          </script>";
  }				
?>