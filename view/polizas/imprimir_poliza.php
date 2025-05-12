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
include('../../model/ModelCobertura.php');
$model_cobertura = new ModelCobertura();

$datos_empresa = $model_formula->get_configuracion_empresa();

$poliza_id = $_GET["poliza_id"];

$poliza = $model_poliza->get_poliza($poliza_id);
$poliza = reset($poliza);
$poliza_detalles = $model_poliza->get_poliza_detalles($poliza_id);

$paginas_porcentaje_total = $model_cobertura->get_porcentajes_total_cobertura($poliza["cobertura_id"]); //Obtener porcentaje para calcular el total de acuerdo a página de impresión

$servicios = [];
foreach ($poliza_detalles as $detalle) {
    $servicios[$detalle['pagina_impresion']][] = $detalle;
}

$filename = "POLIZA-" . $poliza_id . ".pdf";
$mpdf = new mPDF();
$html = '
<style>
    .title{
        background-color: #D6E3BC;
    }
    .subtitle{
        background-color: #B8CCE4;
    }
    .fa { 
        font-family: fontawesome;
        font-style: normal;
        font-size:12px;
    }
    td{
        font-size:12px;
    }  
    th{
        font-size:13px;
    } 
</style>
                <table border="0" style="width:100%" autosize="2"> 
                    <tr>
                        <td><img width="200px" src="'.$_SERVER["DOCUMENT_ROOT"].'/images/logo.png"></td>
                        <td align="center">' . $datos_empresa['empresa_nombre'] . '<br>' . $datos_empresa['empresa_rfc'] . '<br>' . $datos_empresa['direccion_calle'] . ' ' . $datos_empresa['direccion_numero'] . ' 
                        ' . $datos_empresa['direccion_colonia'] . ',' . $datos_empresa['direccion_estado'] . '<br>' . $datos_empresa['poliza_clave_formato'] . '
                        </td>
                        <td align="center"> TELÉFONOS DE EMERGENCIA LAS 24 HRS <br>' . $datos_empresa['empresa_telefono'] . '<br>' . $datos_empresa['empresa_telefono_alternativo'] . '</td>
                    </tr>                  
                </table>
                <br>
                <table style="width:100%" autosize="2">
                    <tr class="title"><th align="center" colspan="3">PÓLIZA DE AUTOS</th></tr>
                    <tr class="subtitle"><td colspan="3"><b>No. de Póliza</b></td></tr>
                    <tr><td colspan="3">' . $poliza_id . '</td></tr>
                    <tr class="title"><th align="center" colspan="3">DATOS DEL CONTRATANTE</th></tr>
                    <tr class="subtitle"><td colspan="3"><b>Nombre</b></td></tr>
                    <tr><td colspan="3">' . $poliza['cliente_nombre'] . '</td></tr>
                    <tr class="subtitle">
                        <td colspan="2"><b>Dirección</b></td>
                        <td><b>Código postal</b></td>
                    </tr>
                    <tr>
                        <td colspan="2">' .  $poliza["cliente_direccion_calle"] . ' #' . $poliza["cliente_direccion_numero"] . ', ' . $poliza["cliente_direccion_colonia"] . '</td>
                        <td>' . $poliza['cliente_direccion_codigo_postal'] . '</td>
                    </tr>
                    <tr class="subtitle">
                        <td><b>Municipio</b></td>
                        <td><b>Estado</b></td>
                        <td><b>Fecha nacimiento</b></td>
                    </tr>
                    <tr>
                        <td>' . $poliza["cliente_direccion_ciudad"] . '</td>
                        <td>' . $poliza["cliente_direccion_estado"] . '</td>
                        <td>' . $poliza['cliente_fecha_nacimiento_formato'] . '</td>
                    </tr>
                    <tr class="subtitle">
                        <td colspan="2"><b>Teléfono</b></td>
                        <td><b>Teléfono alternativo</b></td>
                    </tr>
                    <tr>
                        <td colspan="2">' . $poliza["cliente_telefono"] . '</td>
                        <td>' . $poliza["cliente_telefono_alternativo"] . '</td>
                    </tr>
                    <tr class="title"><th align="center" colspan="3">DATOS DEL VEHÍCULO</th></tr>
                    <tr class="subtitle">
                        <td><b>Marca</b></td>
                        <td><b>Tipo</b></td>
                        <td><b>Color</b></td>
                    </tr>
                    <tr>
                        <td>' . $poliza["vehiculo_marca"] . '</td>
                        <td>' . $poliza["vehiculo_tipo"] . '</td>
                        <td>' . $poliza['vehiculo_color'] . '</td>
                    </tr>
                    <tr class="subtitle">
                        <td><b>Año</b></td>
                        <td><b>Número placa</b></td>
                        <td><b>Número serie</b></td>
                    </tr>
                    <tr>
                        <td>' . $poliza["vehiculo_anio"] . '</td>
                        <td>' . $poliza["vehiculo_placa"] . '</td>
                        <td>' . $poliza['vehiculo_numero_serie'] . '</td>
                    </tr>
                    <tr class="title"><th align="center" colspan="3">COBERTURA CONTRATADA</th></tr>
                    <tr class="subtitle">
                        <td><b>Tipo de póliza</b></td>
                        <td><b>Vigencia desde</b></td>
                        <td><b>Vigencia hasta</b></td>
                    </tr>
                    <tr>
                        <td>' . $poliza["cobertura_nombre"] . '</td>
                        <td>' . $poliza["fecha_inicio_formato"] . '</td>
                        <td>' . $poliza['fecha_fin_formato'] . '</td>
                    </tr>
                </table>
                <br>';

$html = $html . '<br><table style="width:100%;" autosize="2">
                <tr class="subtitle">
                    <th align="left"> <font size="10">Descripción </font></th>
                    <th align="right"> <font size="10">Suma asegurada </font></th>
                    <th align="right"> <font size="10">Prima neta</font></th>
                    <th align="right"> <font size="10">Deducible</font></th>
                </tr>';
$total_prima_neta = 0;
foreach ($servicios[0] as $servicio) {
    $total_prima_neta += floatval($servicio["prima_neta"]);
    $cantidad_asegurada = (($servicio["cantidad_asegurada_tipo_id"] == 3) ? "$" . number_format($servicio["cantidad_asegurada"], 2) : $servicio["cantidad_asegurada_tipo_nombre"]);
    $cantidad_deducible  = (($servicio["cantidad_deducible"] > 0) ? number_format($servicio["cantidad_deducible"], 2) . "%" : "NO APLICA");
    //Tomar la configuración si es un servicio predeterminado de la cobertura si no, tomar la configuración de cantidad del servicio.
    $cantidad_servicio = ((isset($servicio['cobertura_servicio_editar_cantidad']) && $servicio['cobertura_servicio_editar_cantidad'] == true) || (!isset($servicio['cobertura_servicio_editar_cantidad']) && $servicio['servicio_editar_cantidad'] == true)) ? " (" . $servicio['cantidad'] . ")" : "";
    $html = $html . "<tr>
                                        <td align='left'> <font size='10'>" . $servicio["servicio_nombre"] . $cantidad_servicio . "</font></td>
                                        <td align='right'> <font size='10'>" . $cantidad_asegurada . "</font></td>
                                        <td align='right'> <font size='10'>$" . number_format($servicio["prima_neta"], 2) . "</font></td>
                                        <td align='right'> <font size='10'>" . $cantidad_deducible . "</font></td>
                                    </tr>";
}
$html = $html . "</table><br>";

$html = $html . '<br><table style="width:100%;" autosize="2">
                <tr class="title">
                    <th align="right"> <font size="10">Gastos de expedición </font></th>
                    <th align="right"> <font size="10">IVA</font></th>
                    <th align="right"> <font size="10">TOTAL</font></th>
                </tr>';
//Calcular total en base a porcentaje por página y cobertura.
$porcentaje_total = ((count($servicios) > 1 && isset($paginas_porcentaje_total[0])) ? $paginas_porcentaje_total[0]["porcentaje"] : 1);
$poliza_total = (isset($servicios[2])) ? ($poliza["total"]-1250): $poliza["total"];
$total = ($poliza_total * $porcentaje_total);
$html = $html . "<tr>
                    <td align='right'> <font size='10'>$" . number_format($poliza["costo_expedicion"], 2) . "</font></td>
                    <td align='right'> <font size='10'>$" . number_format($poliza["iva"], 2) . "</font></td>
                    <td align='right'> <font size='10'>$" . number_format($total, 2) . "</font></td>
                </tr>";
$html = $html . "</table>";

$html = $html . '<br>
                <table align="right">
                <tr>
                    <td colspan="3" width="300px" align="center" style="font-size:12px">NUESTRO COMPROMISO <br></td>
                    <td rowspan="2" align="center" style="font-size:9px"><br><img width="50px" src="'.$_SERVER["DOCUMENT_ROOT"].'/images/qr-contrato-servicios.png"><br>CONTRATO<br>PRESTACIÓN<br>SERVICIOS</td>
                    <td rowspan="2" align="center" style="font-size:12px"><img width="50px" src="'.$_SERVER["DOCUMENT_ROOT"].'/images/qr-rfc.png"><br>RFC <i class="fa" style="color:#335EFF">&#xf05a;</i></td>
                    <td rowspan="2" align="center" style="font-size:12px"><img width="50px" src="'.$_SERVER["DOCUMENT_ROOT"].'/images/qr-facebook.png"><br><i class="fa" style="color:#335EFF">&#xf09a;</i></td>
                    <td rowspan="2" align="center" style="font-size:12px"><img width="50px" src="'.$_SERVER["DOCUMENT_ROOT"].'/images/qr-google-maps.png"><br><i class="fa" style="color:#335EFF">&#xf1a0;</i><i class="fa" style="color:#335EFF">&#xf041;</i></td>
                </tr>
                <tr>
                <td align="center" width="20px" style="font-size:9px"><i class="fa" style="color:#335EFF;font-size:15px;">&#xf091;</i><br>Servicio y calidad</td>
                <td align="center"  width="20px" style="font-size:9px"><i class="fa" style="color:#335EFF;font-size:15px;">&#xf00c;</i><i class="fa" style="color:#335EFF;font-size:15px">&#xf15c;</i><br>Cumplir el contrato pactado</td>
                <td align="center"  width="20px" style="font-size:9px"><i class="fa" style="color:#335EFF;font-size:15px;">&#xf1ae;</i><i class="fa" style="color:#335EFF;font-size:15px;">&#xf164;</i><br>Satisfacer plenamente sus necesidades</td>
                </tr></table>';
/*PÓLIZA JURÍDICA */
if (count($servicios) > 1) {
    $html = $html . '<pagebreak />';
    $html = $html . '<table border="0" style="width:100%" autosize="2"> 
                            <tr>
                                <td><img width="200px" src="'.$_SERVER["DOCUMENT_ROOT"].'/images/logo.png"></td>
                                <td align="center">' . $datos_empresa['empresa_alternativa_nombre'] . '<br>' . $datos_empresa['empresa_alternativa_direccion'] . '<br>' . $datos_empresa['poliza_clave_formato'] . '
                                </td>
                                <td align="center"> TELÉFONOS DE EMERGENCIA LAS 24 HRS <br>' . $datos_empresa['empresa_telefono'] . '<br>' . $datos_empresa['empresa_telefono_alternativo'] . '</td>
                            </tr>                   
                        </table>
                        <br>
                        <table style="width:100%" autosize="2">
                            <tr class="title"><th align="center" colspan="3">PÓLIZA JURÍDICA</th></tr>
                            <tr class="subtitle"><td colspan="3"><b>No. de Póliza</b></td></tr>
                            <tr><td colspan="3">' . $poliza_id . '</td></tr>
                            <tr class="title"><th align="center" colspan="3">DATOS DEL CONTRATANTE</th></tr>
                            <tr class="subtitle"><td colspan="3"><b>Nombre</b></td></tr>
                            <tr><td colspan="3">' . $poliza['cliente_nombre'] . '</td></tr>
                            <tr class="subtitle">
                                <td colspan="2"><b>Dirección</b></td>
                                <td><b>Código postal</b></td>
                            </tr>
                            <tr>
                                <td colspan="2">' .  $poliza["cliente_direccion_calle"] . ' #' . $poliza["cliente_direccion_numero"] . ', ' . $poliza["cliente_direccion_colonia"] . ', ' . ', CP: ' . $poliza["cliente_direccion_codigo_postal"] . '</td>
                                <td>' . $poliza['cliente_direccion_codigo_postal'] . '</td>
                            </tr>
                            <tr class="subtitle">
                                <td><b>Municipio</b></td>
                                <td><b>Estado</b></td>
                                <td><b>Fecha nacimiento</b></td>
                            </tr>
                            <tr>
                                <td>' . $poliza["cliente_direccion_ciudad"] . '</td>
                                <td>' . $poliza["cliente_direccion_estado"] . '</td>
                                <td>' . $poliza['cliente_fecha_nacimiento_formato'] . '</td>
                            </tr>
                            <tr class="subtitle">
                                <td colspan="2"><b>Teléfono</b></td>
                                <td><b>Teléfono alternativo</b></td>
                            </tr>
                            <tr>
                                <td colspan="2">' . $poliza["cliente_telefono"] . '</td>
                                <td>' . $poliza["cliente_telefono_alternativo"] . '</td>
                            </tr>
                            <tr class="title"><th align="center" colspan="3">DATOS DEL VEHÍCULO</th></tr>
                            <tr class="subtitle">
                                <td><b>Marca</b></td>
                                <td><b>Tipo</b></td>
                                <td><b>Color</b></td>
                            </tr>
                            <tr>
                                <td>' . $poliza["vehiculo_marca"] . '</td>
                                <td>' . $poliza["vehiculo_tipo"] . '</td>
                                <td>' . $poliza['vehiculo_color'] . '</td>
                            </tr>
                            <tr class="subtitle">
                                <td><b>Año</b></td>
                                <td><b>Número placa</b></td>
                                <td><b>Número serie</b></td>
                            </tr>
                            <tr>
                                <td>' . $poliza["vehiculo_anio"] . '</td>
                                <td>' . $poliza["vehiculo_placa"] . '</td>
                                <td>' . $poliza['vehiculo_numero_serie'] . '</td>
                            </tr>
                            <tr class="title"><th align="center" colspan="3">COBERTURA CONTRATADA</th></tr>
                            <tr class="subtitle">
                                <td><b>Tipo de póliza</b></td>
                                <td><b>Vigencia desde</b></td>
                                <td><b>Vigencia hasta</b></td>
                            </tr>
                            <tr>
                                <td>' . $poliza["cobertura_nombre"] . '</td>
                                <td>' . $poliza["fecha_inicio_formato"] . '</td>
                                <td>' . $poliza['fecha_fin_formato'] . '</td>
                            </tr>
                        </table>
                        <br>';

    $html = $html . '<br><table style="width:100%;" autosize="2">
                        <tr class="subtitle">
                            <th align="left"> <font size="10">Descripción </font></th>
                            <th align="right"> <font size="10">Suma asegurada </font></th>
                            <th align="right"> <font size="10">Prima neta</font></th>
                            <th align="right"> <font size="10">Deducible</font></th>
                        </tr>';
    $total_prima_neta = 0;
    foreach ($servicios[1] as $servicio) {
        $total_prima_neta += floatval($servicio["prima_neta"]);
        $cantidad_asegurada = (($servicio["cantidad_asegurada_tipo_id"] == 3) ? "$" . number_format($servicio["cantidad_asegurada"], 2) : $servicio["cantidad_asegurada_tipo_nombre"]);
        $cantidad_deducible  = (($servicio["cantidad_deducible"] > 0) ? number_format($servicio["cantidad_deducible"], 2) . "%" : "NO APLICA");
        $html = $html . "<tr>
                                                <td align='left'> <font size='10'>" . $servicio["servicio_nombre"] . "</font></td>
                                                <td align='right'> <font size='10'>" . $cantidad_asegurada . "</font></td>
                                                <td align='right'> <font size='10'>$" . number_format($servicio["prima_neta"], 2) . "</font></td>
                                                <td align='right'> <font size='10'>" . $cantidad_deducible . "</font></td>
                                            </tr>";
    }
    $html = $html . "</table><br>";

    $html = $html . '<br><table style="width:100%;" autosize="2">
                        <tr class="title">
                            <th align="right"> <font size="10">Gastos de expedición </font></th>
                            <th align="right"> <font size="10">IVA</font></th>
                            <th align="right"> <font size="10">TOTAL</font></th>
                        </tr>';

    //Calcular total en base a porcentaje por página y cobertura.
    $porcentaje_total = ((count($servicios) > 1 && isset($paginas_porcentaje_total[1])) ? $paginas_porcentaje_total[1]["porcentaje"] : 0);
    $poliza_total = (isset($servicios[2])) ? ($poliza["total"]-1250): $poliza["total"];
    $total = ($poliza_total * $porcentaje_total);

    $html = $html . "<tr>
                                            <td align='right'> <font size='10'>$" . number_format(0, 2) . "</font></td>
                                            <td align='right'> <font size='10'>$" . number_format(0, 2) . "</font></td>
                                            <td align='right'> <font size='10'>$" . number_format($total, 2) . "</font></td>
                                        </tr>";
    $html = $html . "</table>";

    $html = $html . '<br>
    <table align="right">
    <tr>
        <td colspan="3" width="300px" align="center" style="font-size:12px">NUESTRO COMPROMISO <br></td>
        <td rowspan="2" align="center" style="font-size:9px"><br><img width="50px" src="'.$_SERVER["DOCUMENT_ROOT"].'/images/qr-contrato-servicios.png"><br>CONTRATO<br>PRESTACIÓN<br>SERVICIOS</td>
        <td rowspan="2" align="center" style="font-size:12px"><img width="50px" src="'.$_SERVER["DOCUMENT_ROOT"].'/images/qr-rfc.png"><br>RFC <i class="fa" style="color:#335EFF">&#xf05a;</i></td>
        <td rowspan="2" align="center" style="font-size:12px"><img width="50px" src="'.$_SERVER["DOCUMENT_ROOT"].'/images/qr-facebook.png"><br><i class="fa" style="color:#335EFF">&#xf09a;</i></td>
        <td rowspan="2" align="center" style="font-size:12px"><img width="50px" src="'.$_SERVER["DOCUMENT_ROOT"].'/images/qr-google-maps.png"><br><i class="fa" style="color:#335EFF">&#xf1a0;</i><i class="fa" style="color:#335EFF">&#xf041;</i></td>
    </tr>
    <tr>
    <td align="center" width="20px" style="font-size:9px"><i class="fa" style="color:#335EFF;font-size:15px;">&#xf091;</i><br>Servicio y calidad</td>
    <td align="center"  width="20px" style="font-size:9px"><i class="fa" style="color:#335EFF;font-size:15px;">&#xf00c;</i><i class="fa" style="color:#335EFF;font-size:15px">&#xf15c;</i><br>Cumplir el contrato pactado</td>
    <td align="center"  width="20px" style="font-size:9px"><i class="fa" style="color:#335EFF;font-size:15px;">&#xf1ae;</i><i class="fa" style="color:#335EFF;font-size:15px;">&#xf164;</i><br>Satisfacer plenamente sus necesidades</td>
    </tr></table>';
}
/*PÓLIZA SERVICIOS FUNERARIOS */
if (count($servicios) > 1 && $servicios[2]) {
    $html = $html . '<pagebreak />';
    $html = $html . '<table border="0" style="width:100%" autosize="2"> 
                            <tr>
                                <td><img width="200px" src="'.$_SERVER["DOCUMENT_ROOT"].'/images/logo.png"></td>
                                <td align="center">"MUTUAL SAGRADO CORAZÓN"<br>' . $datos_empresa['empresa_alternativa_direccion'] . '<br>' . $datos_empresa['poliza_clave_formato'] . '
                                </td>
                                <td align="center"> TELÉFONOS DE EMERGENCIA LAS 24 HRS <br>' . $datos_empresa['empresa_telefono'] . '<br>' . $datos_empresa['empresa_telefono_alternativo'] . '</td>
                            </tr>                   
                        </table>
                        <br>
                        <table style="width:100%" autosize="2">
                            <tr class="title"><th align="center" colspan="3">PÓLIZA ASISTENCIA SERVICIOS FUNERARIOS</th></tr>
                            <tr class="subtitle"><td colspan="3"><b>No. de Póliza</b></td></tr>
                            <tr><td colspan="3">' . $poliza_id . '</td></tr>
                            <tr class="title"><th align="center" colspan="3">DATOS DEL CONTRATANTE</th></tr>
                            <tr class="subtitle"><td colspan="3"><b>Nombre</b></td></tr>
                            <tr><td colspan="3">' . $poliza['cliente_nombre'] . '</td></tr>
                            <tr class="subtitle">
                                <td colspan="2"><b>Dirección</b></td>
                                <td><b>Código postal</b></td>
                            </tr>
                            <tr>
                                <td colspan="2">' .  $poliza["cliente_direccion_calle"] . ' #' . $poliza["cliente_direccion_numero"] . ', ' . $poliza["cliente_direccion_colonia"] . ', ' . ', CP: ' . $poliza["cliente_direccion_codigo_postal"] . '</td>
                                <td>' . $poliza['cliente_direccion_codigo_postal'] . '</td>
                            </tr>
                            <tr class="subtitle">
                                <td><b>Municipio</b></td>
                                <td><b>Estado</b></td>
                                <td><b>Fecha nacimiento</b></td>
                            </tr>
                            <tr>
                                <td>' . $poliza["cliente_direccion_ciudad"] . '</td>
                                <td>' . $poliza["cliente_direccion_estado"] . '</td>
                                <td>' . $poliza['cliente_fecha_nacimiento_formato'] . '</td>
                            </tr>
                            <tr class="subtitle">
                                <td colspan="2"><b>Teléfono</b></td>
                                <td><b>Teléfono alternativo</b></td>
                            </tr>
                            <tr>
                                <td colspan="2">' . $poliza["cliente_telefono"] . '</td>
                                <td>' . $poliza["cliente_telefono_alternativo"] . '</td>
                            </tr>
                            <tr class="title"><th align="center" colspan="3">DATOS DEL VEHÍCULO</th></tr>
                            <tr class="subtitle">
                                <td><b>Marca</b></td>
                                <td><b>Tipo</b></td>
                                <td><b>Color</b></td>
                            </tr>
                            <tr>
                                <td>' . $poliza["vehiculo_marca"] . '</td>
                                <td>' . $poliza["vehiculo_tipo"] . '</td>
                                <td>' . $poliza['vehiculo_color'] . '</td>
                            </tr>
                            <tr class="subtitle">
                                <td><b>Año</b></td>
                                <td><b>Número placa</b></td>
                                <td><b>Número serie</b></td>
                            </tr>
                            <tr>
                                <td>' . $poliza["vehiculo_anio"] . '</td>
                                <td>' . $poliza["vehiculo_placa"] . '</td>
                                <td>' . $poliza['vehiculo_numero_serie'] . '</td>
                            </tr>
                            <tr class="title"><th align="center" colspan="3">COBERTURA CONTRATADA</th></tr>
                            <tr class="subtitle">
                                <td><b>Tipo de póliza</b></td>
                                <td><b>Vigencia desde</b></td>
                                <td><b>Vigencia hasta</b></td>
                            </tr>
                            <tr>
                                <td>' . $poliza["cobertura_nombre"] . '</td>
                                <td>' . $poliza["fecha_inicio_formato"] . '</td>
                                <td>' . $poliza['fecha_fin_formato'] . '</td>
                            </tr>
                        </table>
                        <br>';

    $html = $html . '<br><table style="width:100%;" autosize="2">
                        <tr class="subtitle">
                            <th align="left"> <font size="10">Descripción </font></th>
                            <th align="right"> <font size="10">Suma asegurada </font></th>
                            <th align="right"> <font size="10">Prima neta</font></th>
                            <th align="right"> <font size="10">Deducible</font></th>
                        </tr>';
    $total_prima_neta = 0;
    foreach ($servicios[2] as $servicio) {
        $total_prima_neta += floatval($servicio["prima_neta"]);
        $cantidad_asegurada = (($servicio["cantidad_asegurada_tipo_id"] == 3) ? "$" . number_format($servicio["cantidad_asegurada"], 2) : $servicio["cantidad_asegurada_tipo_nombre"]);
        $cantidad_deducible  = (($servicio["cantidad_deducible"] > 0) ? number_format($servicio["cantidad_deducible"], 2) . "%" : "NO APLICA");
        $html = $html . "<tr>
                                                <td align='left'> <font size='10'>" . $servicio["servicio_nombre"] . "<br>
                                                <b>Beneficiarios:</b><br>" . $poliza["beneficiarios_funeraria"] . "
                                                </font></td>
                                                <td align='right'> <font size='10'>" . $cantidad_asegurada . "</font></td>
                                                <td align='right'> <font size='10'>$" . number_format($servicio["prima_neta"], 2) . "</font></td>
                                                <td align='right'> <font size='10'>" . $cantidad_deducible . "</font></td>
                                            </tr>";
    }
    $html = $html . "</table><br>";

    $html = $html . '<br><table style="width:100%;" autosize="2">
                        <tr class="title">
                            <th align="right"> <font size="10">Gastos de expedición </font></th>
                            <th align="right"> <font size="10">IVA</font></th>
                            <th align="right"> <font size="10">TOTAL</font></th>
                        </tr>';

    //Calcular total en base a porcentaje por página y cobertura.
    /*$porcentaje_total = ((count($servicios) > 1 && isset($paginas_porcentaje_total[1])) ? $paginas_porcentaje_total[1]["porcentaje"] : 0);
    $total = ($poliza["total"] * $porcentaje_total);*/
    $total = 1250;

    $html = $html . "<tr>
                                            <td align='right'> <font size='10'>$" . number_format(0, 2) . "</font></td>
                                            <td align='right'> <font size='10'>$" . number_format(0, 2) . "</font></td>
                                            <td align='right'> <font size='10'>$" . number_format($total, 2) . "</font></td>
                                        </tr>";
    $html = $html . "</table>";

    $html = $html . '<br>
    <table align="right">
    <tr>
        <td colspan="3" width="300px" align="center" style="font-size:12px">NUESTRO COMPROMISO <br></td>
        <td rowspan="2" align="center" style="font-size:9px"><br><img width="50px" src="'.$_SERVER["DOCUMENT_ROOT"].'/images/qr-contrato-servicios.png"><br>CONTRATO<br>PRESTACIÓN<br>SERVICIOS</td>
        <td rowspan="2" align="center" style="font-size:12px"><img width="50px" src="'.$_SERVER["DOCUMENT_ROOT"].'/images/qr-rfc.png"><br>RFC <i class="fa" style="color:#335EFF">&#xf05a;</i></td>
        <td rowspan="2" align="center" style="font-size:12px"><img width="50px" src="'.$_SERVER["DOCUMENT_ROOT"].'/images/qr-facebook.png"><br><i class="fa" style="color:#335EFF">&#xf09a;</i></td>
        <td rowspan="2" align="center" style="font-size:12px"><img width="50px" src="'.$_SERVER["DOCUMENT_ROOT"].'/images/qr-google-maps.png"><br><i class="fa" style="color:#335EFF">&#xf1a0;</i><i class="fa" style="color:#335EFF">&#xf041;</i></td>
    </tr>
    <tr>
    <td align="center" width="20px" style="font-size:9px"><i class="fa" style="color:#335EFF;font-size:15px;">&#xf091;</i><br>Servicio y calidad</td>
    <td align="center"  width="20px" style="font-size:9px"><i class="fa" style="color:#335EFF;font-size:15px;">&#xf00c;</i><i class="fa" style="color:#335EFF;font-size:15px">&#xf15c;</i><br>Cumplir el contrato pactado</td>
    <td align="center"  width="20px" style="font-size:9px"><i class="fa" style="color:#335EFF;font-size:15px;">&#xf1ae;</i><i class="fa" style="color:#335EFF;font-size:15px;">&#xf164;</i><br>Satisfacer plenamente sus necesidades</td>
    </tr></table>';
}

$mpdf->WriteHTML($html);

$mpdf->SetFooter($pie);
ob_end_clean();
$mpdf->Output($filename, "I");
//$mpdf->Output("../../view/HistorialReportes/".$filename,"F");
