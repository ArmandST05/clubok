<?php
require('../session.php');
include('../../model/ModelPoliza.php');
$modelPoliza = new ModelPoliza();

$fecha_actual = date("d-m-Y");

$estatusPoliza = (isset($_GET["estatusPoliza"])) ? $_GET["estatusPoliza"] : 1;

$polizas = $modelPoliza->get_polizas_estatus($estatusPoliza);
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Pólizas</title>

    <!-- Bootstrap Core CSS -->
    <link href="../../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="../../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="../../bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<script type="text/javascript">
    function enviar_formulario() {
        document.formulario.submit()
    }

    function myFunction() {
        // Declare variables 
        var input, filter, table, tr, td, i;
        input = document.getElementById("search");
        filter = input.value.toUpperCase();
        table = document.getElementById("datosexcel");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
            tdPoliza = tr[i].getElementsByTagName("td")[0];
            tdPolizaRespaldo = tr[i].getElementsByTagName("td")[1];
            tdCliente = tr[i].getElementsByTagName("td")[2];
            tdVehiculo = tr[i].getElementsByTagName("td")[3];
            if (tdCliente || tdPoliza) {
                if (tdPoliza.innerHTML.toUpperCase().indexOf(filter) > -1 || tdCliente.innerHTML.toUpperCase().indexOf(filter) > -1 || tdVehiculo.innerHTML.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

    function changeIframeSrc(url) {
        document.getElementById('cible').src = url;
    }

    function seleccionarEstatusPoliza(estatus) {
        window.location.assign("index.php?estatusPoliza=" + estatus);
    }
</script>

<body>
    <?php include("../menu_principal.php") ?>

    <div id="wrapper">

        <!-- Navigation -->

        <div id="page-wrapper">
            <br>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <!-- DataTables Advanced Tables -->
                            <button type="button" class="btn btn-primary btn-sm" id="btnExport"><i class="fas fa-print"></i> Reporte Pólizas</button>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Estatus pólizas:</label>
                                            <select class="form-control" name="cobertura" id="cobertura" onchange="seleccionarEstatusPoliza(this.value)" required>
                                                <option value='1' <?php echo ($estatusPoliza == 1) ? "selected" : "" ?>>ACTIVAS POR PAGAR</option>
                                                <option value='2' <?php echo ($estatusPoliza == 2) ? "selected" : "" ?>>CANCELADAS</option>
                                                <option value='paid' <?php echo ($estatusPoliza == "paid") ? "selected" : "" ?>>PAGADAS</option>
                                                <option value='inactive' <?php echo ($estatusPoliza == "inactive") ? "selected" : "" ?>>INACTIVAS</option>
                                                <!--<option value='pendingToPay' <?php echo ($estatusPoliza == "pendingToPay") ? "selected" : "" ?>>PENDIENTE PAGAR</option>-->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Búsqueda:</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fas fa-search"></i></span>
                                                <input type="text" name="search" id="search" class="form-control" onkeyup="myFunction()" placeholder="Buscar póliza" autofocus autocomplete="off">
                                            </div><br>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table class="table table-striped table-bordered table-hover" id="datosexcel">
                                            <th>Póliza</th>
                                            <th>Póliza respaldo</th>
                                            <th>Cliente</th>
                                            <th>Vehículo</th>
                                            <th>Saldo</th>
                                            <th>Vigencia</th>
                                            <th>Estatus</th>
                                            <th>Acciones</th>
                                            </tr>
                                            <?php
                                            $total_abonado = 0;
                                            $saldo_pendiente = 0;
                                            foreach ($polizas as $poliza) :
                                                if ($poliza["estatus"] == 2) {
                                                    $saldo = 0;
                                                    $polizaTotalAbonos = 0;
                                                } else {
                                                    $saldo = ((floatval($poliza["total_abonos"]) > 0) ? ($poliza["total"] - $poliza["total_abonos"]) : $poliza["total"]);
                                                    $polizaTotalAbonos = floatval($poliza["total_abonos"] - $poliza["costo_expedicion"]);
                                                }
                                                //Lo que se capturó como el primer abono (o costo de expedición no se incluye como parte del saldo total ni del saldo pendiente total)
                                                $total_abonado += $polizaTotalAbonos;
                                                $saldo_pendiente += $saldo;
                                            ?>
                                                <tr align='center'>
                                                    <td style='text-align:justify;'><?php echo $poliza["idpoliza"] ?></td>
                                                    <td style='text-align:justify;'><?php echo $poliza['poliza_respaldo_compania_nombre'] . " " . $poliza['numero_poliza_respaldo'] ?></a>
                                                    </td>
                                                    <td style='text-align:justify;'><?php echo $poliza["cliente_nombre"] ?></td>
                                                    <td style='text-align:justify;'><?php echo $poliza["vehiculo_marca"] . " | " . $poliza["vehiculo_tipo"] . " | " . $poliza["vehiculo_placa"] ?></td>
                                                    <td style='text-align:right;'>$<?php echo number_format(($saldo), 2) ?></td>
                                                    <td><?php echo $poliza["fecha_inicio_formato"] . " - " . $poliza["fecha_fin_formato"] ?></td>
                                                    <?php
                                                    $total_poliza = floatval($poliza["total"]) - floatval($poliza["costo_expedicion"]);

                                                    //Calcular meses transcurridos desde el inicio de la póliza
                                                    $fecha_inicio = new DateTime($poliza["fecha_inicio"]);
                                                    $fecha_fin = new DateTime(date("Y-m-d"));
                                                    $diferencia = $fecha_inicio->diff($fecha_fin);
                                                    $meses = ($diferencia->y * 12) + $diferencia->m;
                                                    //Calcular cuánto debe de abonar cada mes
                                                    $pago_mensual = floatval($total_poliza / $poliza["plazo"]);
                                                    //Se considera que el primer abono registrado es el costo de expedición de la póliza
                                                    $cantidad_pagada_real = floatval($poliza["total_abonos"] - $poliza["costo_expedicion"]);

                                                    if ($estatusPoliza == "inactive") {
                                                        echo "<td><b>INACTIVO (VIGENCIA EXPIRADA)</td>";
                                                    } else if ($poliza["liquidado"] == 1) {
                                                        echo "<td><b>ACTIVO</b><br>PAGADO</td>";
                                                    } else if ($poliza["estatus"] == 2) {
                                                        echo "<td style='background-color:#FC8383'><b>CANCELADA<br> " . $poliza["fecha_estatus_formato"] . "</b><br>
                                                        Motivo: " . $poliza["motivo_estatus"] . "</td>";
                                                    } else if ($cantidad_pagada_real < ($pago_mensual * ($meses - 3))) {
                                                        echo "<td style= 'background-color:#FC8383' ><b>PAGO INACTIVO</b><br>
                                                            Último pago: " . $poliza["fecha_ultimo_abono"] . "</td>";
                                                    } else if ($cantidad_pagada_real < ($pago_mensual * ($meses - 2))) {
                                                        echo "<td style= 'background-color:#F7EC4A;'><b>PAGO MOROSO</b><br>
                                                            Último pago: " . $poliza["fecha_ultimo_abono"] . "</td>";
                                                    } else {
                                                        echo "<td><b>ACTIVO</b></td>";
                                                    }
                                                    ?>
                                                    <td>
                                                        <a href='nuevo_abono.php?poliza_id=<?php echo $poliza["idpoliza"] ?>' class="btn btn-default btn-sm"><i class="fas fa-dollar"></i> Abonos</a>
                                                        <a href="imprimir_poliza.php?poliza_id=<?php echo $poliza['idpoliza'] ?>" target="__blank" class='btn btn-primary btn-sm'><i class="fas fa-print"></i> Imprimir</a>
                                                        <?php if ($poliza["estatus"] == 1) : ?>
                                                            <a href='editar.php?poliza_id=<?php echo $poliza["idpoliza"] ?>' class="btn btn-default btn-sm"><i class="fas fa-pencil-alt"></i> Modificar</a>
                                                        <?php endif; ?>
                                                        <?php if ($_SESSION["tipo_usuario"] == 1 && $poliza["estatus"] == 1) : ?>
                                                            <form action='/controller/polizas/modificar_estatus.php' method='POST'>
                                                                <button type='submit' class='btn btn-default btn-sm' onclick="return confirmar_cancelar(<?php echo $poliza['idpoliza'] ?>);"><i class="fas fa-ban fa-fw"></i> Cancelar</button>
                                                                <input type='hidden' name='poliza_id' value='<?php echo $poliza["idpoliza"] ?>'>
                                                                <input type='hidden' name='motivoEstatus' id="motivoEstatus-<?php echo $poliza['idpoliza'] ?>">
                                                                <input type='hidden' name='estatus' value='2'>
                                                            </form>
                                                            <form action='/controller/polizas/modificar_estatus.php' method='POST'>
                                                                <button type='submit' class='btn btn-default btn-sm' onclick='return confirmar_eliminar();'><i class="fas fa-trash fa-fw"></i> Eliminar</button>
                                                                <input type='hidden' name='poliza_id' value='<?php echo $poliza["idpoliza"] ?>'>
                                                                <input type='hidden' name='estatus' value='0'>
                                                            </form>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </table>
                                        <?php if ($_SESSION["tipo_usuario"] == 1) : ?>
                                            <h4 style='text-align:right;font-style:bold;'>Total abonado: $<?php echo number_format(($total_abonado), 2) ?><br>Saldo pendiente: $<?php echo number_format(($saldo_pendiente), 2) ?></h4>
                                        <?php endif; ?>
                                    </div>
                                </div>
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
    <script src="../../bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../../bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="../../bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="../../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../../dist/js/sb-admin-2.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script type="text/javascript">
        $(document).ready(function() {
            /*$('#dataTables-example').DataTable({
                responsive: true
            });*/

            $("#btnExport").click(function(e) {
                window.open("reporte_polizas.php");
            });
        });

        function confirmar_cancelar(idpoliza) {
            var flag = confirm("¿Estás seguro de CANCELAR la póliza?");
            if (flag == true) {
                let motivoCancelar = prompt("Motivo de CANCELACIÓN:", "");
                if (motivoCancelar == null || motivoCancelar == "") {
                    return false;
                } else {
                    $("#motivoEstatus-" + idpoliza).val(motivoCancelar);
                    return true;
                }
            } else {
                return false;
            }
        }

        function confirmar_eliminar() {
            var flag = confirm("¿Estás seguro de ELIMINAR la póliza?");
            if (flag == true) {
                return true;
            } else {
                return false;
            }
        }
    </script>


</body>

</html>