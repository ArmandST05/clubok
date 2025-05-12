<?php
  include('../../model/ModelFormula.php');	
  $modelFormula= new ModelFormula();

  if($_GET["gasto"]==""){ 
    $gasto = 0;
  }else{
    $gasto = $_GET["gasto"];
  }

  $concepto = $_GET["concepto"];

  $TOPE=1;
  date_default_timezone_set('America/Mexico_City');

  $datos_empresa = $modelFormula->get_configuracion_empresa();
  $fecha = date("d")."-".date("m")."-".date("Y")."   HORA:".date("h:i:s");
?>

<script type="text/javascript"> 
var NEW_LOC = "http://www.astalaweb.com/"; 
function goNow() { document.location=NEW_LOC; } 
function printPage() { 
  if (confirm("¿Imprimir página?")) {
    window.print();
  }
  // La redirección ocurre incluso cuando la página no se ha imprimido
  // si quieres hacer la redirección sólo si la página se ha imprimido
  // inserta la siguiente frase arriba 
  goNow();
}
function IMPRIME()
{
	window.print();
	window.close();
} 
</script>

<!--/head-->

<!--
La llamada a la función podría haber sido hecha en el body tag 
o dentro de la sección de javascript: printpage
-->
<?php
  echo "<html style='width: 320px;height: 480px;'><head></head><body><div id='test'> <font size=2 face='arial'><p align='center'><b>".$datos_empresa['empresa_nombre']."<br>";
  echo "GASTO <br></b><br>";
  echo "<p align='justify'>*********FECHA:".$fecha."*********<br>";

  echo "</p><p align='justify'>".'GASTO:  '.number_format(($gasto),2)."<br>";
  echo "</p><p align='justify'>".'CONCEPTO:  '.$concepto."<br>";
  echo "</p>";

  echo "</font></div></body></html>";
  if ($TOPE > 0)
  {
  ?>
  <script type="text/javascript">
  window.print();
  setTimeout(function(){window.close();}, 500);
  </script> 
  <?php
  }
  else
  {
  ?>
  <script type="text/javascript">
  window.close();

  </script> 
  <?php
  }
?>