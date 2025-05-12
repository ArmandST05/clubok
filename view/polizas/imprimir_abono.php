<?php
ob_start();
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

include('../../model/mpdf60/mpdf.php');
include('../../model/ModelFormula.php');
$model_formula = new ModelFormula();
include('../../model/ModelPoliza.php');
$model_poliza = new ModelPoliza();

$datos_empresa = $model_formula->get_configuracion_empresa();

$abono_id = $_GET["abono_id"];

$abono = $model_poliza->get_abono($abono_id);
$abono = reset($abono);

$poliza = $model_poliza->get_poliza($abono["poliza_id"]);
$poliza = reset($poliza);

$filename = "ABONO-" . $poliza_id . ".pdf";
$mpdf = new mPDF(['format' => 'A2']);
$html = '
<style>
    .title{
        background-color: #D6E3BC;
    }
    .subtitle{
        background-color: #B8CCE4;
    }
    td{
        font-size:50px;
    }
</style>
            <html style="margin-top:10px;margin-header:10px;margin-bottom:10px;margin-footer:10px;"><head></head><body>
                <table autosize="2"> 
                    <tr>
                        <td align="center"><img width="200px" src="'.$_SERVER["DOCUMENT_ROOT"].'/images/logo.png"></td>
                    </tr>       
                </tr>   
                <tr><td align="center"><b>' . $datos_empresa["empresa_nombre"] . "</b><br>" . $datos_empresa["direccion_calle"] . " " . $datos_empresa["direccion_numero"] . '
                    <br>' . $datos_empresa["direccion_colonia"] . "," . $datos_empresa["direccion_estado"] . '
                    <br>TELÉFONOS DE EMERGENCIA:<br>' . $datos_empresa["empresa_telefono"] . " y " . $datos_empresa["empresa_telefono_alternativo"] . '
                    </td>
                </tr>";
                $html = $html . "</table>        
                </table>
                <br>
                <table style="width:100%" autosize="2">
                    <tr><td align="center"><b>COMPROBANTE DE PAGO</td><b></tr>
                    <tr><td><b>FECHA:</b> ' . $abono['fecha_formato'] . '</td></tr>
                    <tr><td><b>PÓLIZA:</b> ' . $abono['poliza_id'] . '</td></tr>
                    <tr><td><b>CLIENTE:</b> ' . $poliza['cliente_nombre'] . '</td></tr>
                    <tr><td><b>RECIBIDO POR:</b> $' . number_format($abono['cantidad'],2) . '</td></tr>
                    <tr><td><b>CONCEPTO:</b>PAGO A POLIZA  ' . (floatval($abono['total_abonos_anteriores'])+1) . ' </td></tr>
                    <tr><td><b>COBRADOR:</b> ' . $abono['vendedor_nombre'] . '</td></tr>
                </table></body></html>';

$mpdf->WriteHTML($html);

$mpdf->SetFooter($pie);
ob_end_clean();
$mpdf->Output($filename, "I");
//$mpdf->Output("../../view/HistorialReportes/".$filename,"F");
