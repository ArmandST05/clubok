<?php
require('../session.php');
include('../../model/ModelFormula.php');
$mode_formula = new ModelFormula();
include('../../model/ModelCliente.php');
$model_cliente = new ModelCliente();
include('../../model/ModelPoliza.php');
$model_poliza = new ModelPoliza();
include('../../model/ModelVendedor.php');
$model_vendedor = new ModelVendedor();

$vendedor_id = isset($_GET["vendedor"])  ? $_GET['vendedor'] : 0;
$cliente_id = isset($_GET["cliente"])  ? $_GET['cliente'] : 0;
$poliza_id = isset($_GET["poliza"])  ? $_GET['poliza'] : 0;
$fecha_inicial = isset($_GET["fecha_inicial"])  ? $_GET['fecha_inicial'] : date("Y-m-d");
$fecha_final = isset($_GET["fecha_final"])  ? $_GET['fecha_final'] : date("Y-m-d");

$vendedores = $model_vendedor->get_vendedores();
$clientes = $model_cliente->get_lista_clientes();

if ($cliente_id != 0) {
    $polizas_pendientes = $model_poliza->polizas_pendientes_cliente($cliente_id);
    $polizas = $model_poliza->get_polizas_cliente($cliente_id);
    if ($poliza_id) {
        //Buscar por póliza
        $abonos = $model_poliza->abonos_cliente_poliza($poliza_id);
    } else {
        //Buscar por cliente
        $abonos = $model_poliza->abonos_cliente($cliente_id);
    }
} elseif ($vendedor_id != 0) {
    //Buscar por vendedor
    $polizas_pendientes = $model_poliza->polizas_pendientes();
    $abonos = $model_poliza->abonos_vendedor_fecha($vendedor_id, $fecha_inicial, $fecha_final);
    $vendedor_reporte = $model_vendedor->get_vendedor($vendedor_id);
}
$actual_date = date("Y-m-d");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Cobranza</title>

    <!-- Bootstrap Core CSS -->
    <link href="../../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="../../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css"
        rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="../../bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- Excel -->
    <!--script src="../jquery-3.1.1.min.js"></script-->
    <script src="../jquery-2.1.1.min.js" type="text/javascript"></script>
    <script src="../jquery.btechco.excelexport.js"></script>
    <script src="../jquery.base64.js"></script>

</head>

<body>
    <?php include("../menu_principal.php") ?>

    <div id="wrapper">
        <!-- Navigation -->
        <div id="page-wrapper">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Reporte cobranza</h1>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <button type="submit" class="btn btn-primary btn-sm" id="btnExport"><i class="fas fa-print"></i> Imprimir Reporte</button>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <form method="GET" name="reporte_cobranza" id="reporte_cobranza"
                                    action="reporte_cobranza.php">
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <label>Vendedor:</label>
                                            <select class="form-control" name="vendedor" id="vendedor"
                                                onchange="seleccionar_vendedor()">
                                                <option value="0">TODOS</option>
                                                <?php foreach ($vendedores as $vendedor) : ?>
                                                <option value='<?php echo $vendedor["idvendedor"] ?>'
                                                    <?php echo ($vendedor_id == $vendedor["idvendedor"]) ? 'selected' : '' ?>>
                                                    <?php echo $vendedor["nombre"] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-5">
                                            <label>Cliente:</label>
                                            <select class="form-control" name="cliente" id="cliente"
                                                onchange="seleccionar_cliente()">
                                                <option value="0">TODOS</option>
                                                <?php foreach ($clientes as $cliente) : ?>
                                                <option value='<?php echo $cliente["idcliente"] ?>'
                                                    <?php echo ($cliente_id == $cliente["idcliente"]) ? "selected" : "" ?>>
                                                    <?php echo $cliente["idcliente"] . "|" . $cliente["nombre"] ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-10">
                                            <label>Póliza:</label>
                                            <select class="form-control" name="poliza" id="poliza"
                                                <?php echo ($cliente_id == 0) ? "disabled": ""?>>
                                                <option value="0">TODOS</option>
                                                <?php foreach ($polizas as $poliza) : ?>
                                                    <option value='<?php echo $poliza["idpoliza"] ?>' <?php echo ($poliza_id == $poliza["idpoliza"]) ? "selected" : "" ?>>
                                                        <?php echo $poliza["idpoliza"]." | ".$poliza["fecha_inicio_formato"]." - ".$poliza["fecha_fin_formato"]." | ".$poliza["vehiculo_marca"]." ".$poliza["vehiculo_tipo"] ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div id="divFecha">
                                            <div class="col-lg-5">
                                                <label>Desde:</label>
                                                <input type="date" id="fecha_inicial" name="fecha_inicial"
                                                    class="form-control" max="<?php echo $actual_date ?>"
                                                    value="<?php echo $fecha_inicial ?>">
                                            </div>
                                            <div class="col-lg-5">
                                                <label>Hasta:</label>
                                                <input type="date" id="fecha_final" name="fecha_final"
                                                    class="form-control" max="<?php echo $actual_date ?>"
                                                    value="<?php echo $fecha_final ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <br>
                                            <button type="hidden" class="btn btn-primary" id="submit">Buscar</button>
                                        </div>
                                    </div>
                                </form>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>Abonos</label>
                                        <?php if (isset($abonos)) : ?>
                                        <table class="table table-striped table-bordered table-hover" id="datosexcel">
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Póliza</th>
                                                <th>Cliente</th>
                                                <th>Vehículo</th>
                                                <th>Vendedor</th>
                                                <th>Cantidad</th>
                                            </tr>
                                            <?php
                                                $total_abonado = 0;
                                                foreach ($abonos as $abono) :
                                                    $total_abonado += floatval($abono["cantidad"]);
                                                ?>
                                            <tr>
                                                <td><?php echo $abono["fecha_formato"] ?></td>
                                                <td><?php echo $abono["poliza_id"] ?></td>
                                                <td><?php echo $abono["cliente_nombre"] ?></td>
                                                <td><?php echo $abono["vehiculo_marca"] . "|" . $abono["vehiculo_tipo"] ?>
                                                </td>
                                                <td><?php echo $abono["vendedor_nombre"] ?></td>
                                                <td style='text-align:right;'>
                                                    $<?php echo number_format($abono["cantidad"], 2) ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </table>
                                        <h4 style='text-align:left;font-style:bold;'> Total pagado: <font
                                                color='#2C63D6'> <?php echo "$" . number_format(($total_abonado), 2) ?>
                                                <font color='black'>
                                        </h4>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>Pólizas pendientes</label>
                                        <?php if (isset($polizas_pendientes)) : ?>
                                        <table class="table table-striped table-bordered table-hover" id="datosexcel"
                                            border="1">
                                            <h3 style="display:none"><label>Ventas a crédito</label></h3>
                                            <tr>
                                                <th>Vigencia</th>
                                                <th>Póliza</th>
                                                <th>Cliente</th>
                                                <th>Vehículo</th>
                                                <th>Total</th>
                                                <th>Abonado</th>
                                                <th>Saldo Pendiente</th>
                                            </tr>
                                            <?php
                                                $total_pendiente = 0;
                                                if (count($polizas_pendientes) > 0) :
                                                    foreach ($polizas_pendientes as $poliza_pendiente) :
                                                        $saldo_pendiente = floatval($poliza_pendiente["total"]) - floatval($poliza_pendiente["total_abonos"]);
                                                        $total_pendiente += $saldo_pendiente;
                                                ?>
                                            <tr align='center'>
                                                <td style='text-align:right;'>
                                                    <?php echo $poliza_pendiente["fecha_inicio_formato"] . " - " . $poliza_pendiente["fecha_fin_formato"]  ?>
                                                </td>
                                                <td><?php echo $poliza_pendiente["idpoliza"] ?></td>
                                                <td><?php echo $poliza_pendiente["cliente_nombre"] ?></td>
                                                <td><?php echo $poliza_pendiente["vehiculo_marca"] . " | " . $poliza_pendiente["vehiculo_tipo"] ?>
                                                </td>
                                                <td style='text-align:right;'>
                                                    $<?php echo number_format($poliza_pendiente["total"], 2) ?></td>
                                                <td style='text-align:right;'>
                                                    $<?php echo number_format($poliza_pendiente["total_abonos"], 2) ?>
                                                </td>
                                                <td style='text-align:right;'>$<?php echo number_format($saldo_pendiente, 2) ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                        </table>
                                        <h4 style='text-align:left;font-style:bold;'> Total pendiente: <font
                                                color='#2C63D6'>
                                                <?php echo "$" . number_format(($total_pendiente), 2) ?><font
                                                    color='black'>
                                        </h4>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <!-- /.table-responsive -->
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->

            </div>
            <!-- /.col-lg-6 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
    <!-- jQuery -->


    <!-- Bootstrap Core JavaScript -->
    <script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../../bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <!--script src="../../bower_components/datatables/media/js/jquery.dataTables.min.js"></script-->
    <script src="../../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js">
    </script>

    <!-- Custom Theme JavaScript -->
    <script src="../../dist/js/sb-admin-2.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
</body>

<script type="text/javascript">
$(document).ready(function() {

    $("#btnExport").click(function(e) {

        $("#datosexcel").btechco_excelexport({
            containerid: "datosexcel",
            datatype: $datatype.Table,
            filename: 'Cobranza'
        });

    });

    if ("<?php echo $vendedor_id ?>" != 0) {
        $("#divFecha").show();
    } else {
        $("#divFecha").hide();
    }

});

function generar_reporte() {
    $("#reporte_cobranza").submit();
}

function seleccionar_vendedor() {
    $("#cliente").val(0);
    $("#poliza").val(0);
    $("#poliza").attr('disabled', true);
    $("#divFecha").show();
}

function seleccionar_cliente() {
    $("#poliza").attr('disabled', false);
    $("#vendedor").val(0);
    $("#divFecha").hide();
    polizas_cliente();
}

function polizas_cliente() {
    //Cargar pólizas
    $.ajax({
        type: "GET",
        url: "../../action/polizas/obtener_polizas_cliente.php",
        data: {
            cliente_id: $("#cliente").val()
        },
        success: function(data) {
            $("#poliza option").remove()
            $('#poliza').html(data).fadeIn();
        }
    });
}

function enviar_formulario() {
    document.formulario.submit()
}
</script>

</html>