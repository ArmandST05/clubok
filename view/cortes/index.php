<?php
require('../session.php');
include('../../model/ModelPoliza.php');
$model_poliza = new ModelPoliza();
include('../../model/ModelFormula.php');
$model_formula = new ModelFormula();
include('../../model/ModelVendedor.php');
$model_vendedor = new ModelVendedor();

$vendedor_id = isset($_GET["vendedor_id"])  ? $_GET['vendedor_id'] : null;

$datos_empresa = $model_formula->get_configuracion_empresa();

//Obtener datos del corte
$total_abonos = floatval($model_poliza->get_total_corte_abonos_vendedor($vendedor_id));
$abonos_detalle = $model_poliza->get_corte_abonos_vendedor_detalle($vendedor_id);
$caja = $model_formula->get_caja_vendedor($vendedor_id);
$gastos = $model_formula->get_gastos_vendedor($vendedor_id);
$vendedores = $model_vendedor->get_vendedores();

foreach ($caja as $key) {
    $total_caja = $key["caja"];
}
foreach ($gastos as $key) {
    $total_gastos = $key["gastos"];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Cortes</title>

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

<body onload="submit()" onkeydown="mostrar(event);">
    <?php include("../menu_principal.php") ?>

    <div id="wrapper">

        <!-- Navigation -->
        <div id="page-wrapper">

            <div class="row">
                <div class="col-lg-12">

                    <h1 class="page-header">Cortes</h1>

                    <!-- Ver detalle de los cortes  -->

                    <div class="panel panel-default">

                        <div class="panel-heading">
                            <div align="right">
                                <!--<button type="submit" class='btn btn-primary' onclick="validarAcceso();"><i class="fas fa-file fa-fw"></i>Reporte cortes</button>-->
                            </div>
                            <!-- DataTables Advanced Tables -->
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <form method="GET" name="corte_caja" id="corte_caja" action="index.php">
                                        <label>Vendedor</label>
                                        <select name="vendedor_id" id="vendedor_id" class="form-control" onchange="recargarCorte()">
                                            <option <?php echo (isset($vendedor_id)) ? '' : 'selected' ?> disabled>SELECCIONAR VENDEDOR</option>
                                            <?php foreach ($vendedores as $vendedor) : ?>
                                                <option value="<?php echo $vendedor['idvendedor'] ?>" <?php echo ($vendedor_id == $vendedor['idvendedor']) ? "selected" : "" ?>>
                                                    <?php echo $vendedor["nombre"] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </form>
                                </div>
                            </div>
                            <?php if ($vendedor_id) : ?>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <form action='/controller/cortes/insertar_caja.php' method='POST'>
                                            <label>Caja</label>
                                            <input type="number" step=".01" min=".01" placeholder="Caja" class="form-control" name="caja" id="caja" value="" onkeypress="return SoloNumerosDecimales3(event, '0.0', 2)" required>
                                            <button type="submit" class='btn btn-default btn-sm' name="iniciar" onclick="load();"><i class="fas fa-plus"></i> Agregar caja</button>
                                            <input type="hidden" name="vendedor_id" value="<?php echo $vendedor_id ?>">
                                        </form>
                                    </div>
                                    <div class="col-lg-2">
                                        <label>Gastos</label><br>
                                        <button class='btn btn-default btn-sm' onclick="mostrarModalAgregarGasto()"><i class="fas fa-plus"></i> Agregar gasto</button>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="dataTable_wrapper">
                                        <div class="col-lg-8">
                                            <div class="form-group">
                                                <input class="form-control" value="<?php echo $datos_empresa['clave_admin_vistas'] ?>" name="superpass" id="superpass" type="hidden">

                                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                                    <tr>
                                                        <th>CONCEPTOS</th>
                                                        <th style="text-align:right">TOTAL</th>
                                                    </tr>
                                                    <tr>
                                                        <td>Total abonos</td>
                                                        <td align='right'>$<?php echo number_format(($total_abonos), 2) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Caja</td>
                                                        <td align='right'>$<?php echo number_format(($total_caja), 2) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Gastos</td>
                                                        <td align='right'>$<?php echo number_format(($total_gastos), 2) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Cantidad a entregar</td>
                                                        <td align='right'>$<?php echo number_format(($total_abonos + $total_caja) - ($total_gastos), 2) ?></td>
                                                    </tr>
                                                </table>
                                                <form action='/controller/cortes/insertar_corte.php' method='POST'>
                                                    <button type='submit' class='btn btn-primary onclick=' return confirmarGuardarCorte();'>Guardar corte</button>
                                                    <input type='hidden' name='total_abonos' value='<?php echo $total_abonos ?>'>
                                                    <input type='hidden' name='total_caja' value='<?php echo $total_caja ?>'>
                                                    <input type='hidden' name='total_gastos' value='<?php echo $total_gastos ?>'>
                                                    <input type="hidden" name="vendedor_id" value="<?php echo $vendedor_id ?>">
                                                </form>
                                                <h3>Detalle corte</h3>
                                                <table class="table table-bordered table-hover" name="" id="">
                                                    <tr>
                                                        <th>Fecha abono</th>
                                                        <th>Póliza</th>
                                                        <th>Cliente</th>
                                                        <th>Vehículo</th>
                                                        <th>Total</th>
                                                        <th>Fecha registro</th>
                                                    </tr>
                                                    <?php
                                                    $total_general_abono = 0;
                                                    foreach ($abonos_detalle as $abono) :
                                                        $total_general_abono += $abono["cantidad"];
                                                    ?>
                                                        <tr align='center'>
                                                            <td><?php echo $abono["fecha_formato"] ?></td>
                                                            <td><?php echo $abono["poliza_id"] ?></td>
                                                            <td><?php echo $abono["cliente_nombre"] ?></td>
                                                            <td><?php echo $abono["vehiculo_marca"]." | ".$abono["vehiculo_tipo"] ?></td>
                                                            <td>$<?php echo $abono["cantidad"] ?></td>
                                                            <td><?php echo $abono["created_at_formato"] ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </table>
                                                <h4 style='text-align:left;font-style:bold;'>Total: $<?php echo number_format(($total_general_abono), 2) ?></h4>
                                            </div>
                                        </div>
                                        <!-- Ventana Modal-->
                                        <div class="modal" id="modalAgregarGasto" name="modalAgregarGasto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style='display:none;'>
                                            <div class="modal-dialog">
                                                <input class='btn btn-primary' type="submit" id="bot" name="bot" Value="X" onclick="ocultarModalGasto();" />
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <div class="col-sm-16">
                                                            <div class="widget-box">
                                                                <div class="widget-header">
                                                                    <div class="widget-toolbar">
                                                                        <a href="#" data-action="reload">
                                                                            <i class="ace-icon fas fa-refresh"></i>
                                                                        </a>
                                                                        <a href="#" data-action="collapse">
                                                                            <i class="ace-icon fas fa-chevron-up"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="widget-body">
                                                                    <div class="modal-body datagrid table-responsive">
                                                                        <center>
                                                                            <div id="cargar_reporte">
                                                                                <form action="/controller/cortes/insertar_gasto.php" method="POST" id="form1" name="form1">
                                                                                    <div class="col-lg-12">
                                                                                        <b id="" style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;GASTO:&nbsp;&nbsp;&nbsp;&nbsp;</b><input type="number" step=".01" style="text-align:right;" id="gasto" name="gasto" value="0" min=".01" onkeypress="return soloNumeros(event);" autocomplete="off" required>
                                                                                        <br><br><b id="" style="text-align:center;">&nbsp;&nbsp;CONCEPTO:&nbsp;</b><input type="text" style="text-align:right;" id="concepto" name="concepto" required>
                                                                                        <br><br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class='btn btn-primary' type="submit" Value="REALIZAR GASTO" /></input>
                                                                                    </div>
                                                                                    <input type="hidden" name="vendedor_id" value="<?php echo $vendedor_id ?>">
                                                                                </form>
                                                                            </div>
                                                                        </center>
                                                                    </div>
                                                                    <div class="panel-body" id="editar_resul">

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
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

    <!-- Ventana Modal-->
    <div class="modal" id="modalValidarAcceso" name="modalValidarAcceso" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style='display:none;'>
        <div class="modal-dialog">
            <input class='btn btn-primary' type="submit" id="bot" name="bot" Value="X" onclick="ocultarModalValidarAcceso();" />
            <div class="modal-content">
                <div class="modal-body">
                    <div class="col-sm-16">
                        <div class="widget-box">
                            <div class="widget-header">

                                <div class="widget-toolbar">
                                    <a href="#" data-action="reload">
                                        <i class="ace-icon fas fa-refresh"></i>
                                    </a>
                                    <a href="#" data-action="collapse">
                                        <i class="ace-icon fas fa-chevron-up"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="widget-body">
                                <div class="modal-body datagrid table-responsive">
                                    <center>
                                        <div id="cargar_reporte">
                                            <b id="" style="">CONTRASEÑA:&nbsp;</b><input type="password" onkeydown="redirectReporteCorte(event);" id="pass" name="pass" style="text-align:left;" autofocus>
                                            <!--button type="hidden" class="btn btn-primary" onclick="redirect();">Generar Reporte</button-->
                                        </div>
                                    </center>
                                </div>
                                <div class="panel-body" id="editar_resul">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
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
    <script>
        $(document).ready(function() {
            $('#dataTables-example').DataTable({
                responsive: true
            });
        });

        function SoloNumerosDecimales3(e, valInicial, nDecimal) {
            var obj = e.srcElement || e.target;
            var tecla_codigo = (document.all) ? e.keyCode : e.which;
            var tecla_valor = String.fromCharCode(tecla_codigo);
            var patron2 = /[\d.]/;
            var control = (tecla_codigo === 46 && (/[.]/).test(obj.value)) ? false : true;
            var existePto = (/[.]/).test(obj.value);

            nEntero = 100;


            //el tab
            if (tecla_codigo === 8)
                return true;

            if (valInicial !== obj.value) {
                var TControl = obj.value.length;
                if (existePto === false && tecla_codigo !== 46) {
                    if (TControl === nEntero) {
                        obj.value = obj.value + ".";
                    }
                }

                if (existePto === true) {
                    var subVal = obj.value.substring(obj.value.indexOf(".") + 1, obj.value.length);

                    if (subVal.length > 1) {
                        return false;
                    }
                }

                return patron2.test(tecla_valor) && control;
            } else {
                if (valInicial === obj.value) {
                    obj.value = '';
                }
                return patron2.test(tecla_valor) && control;
            }
        }

        function popup() {
            var opciones = "width=700,height=350,scrollbars=YES";
            mipopup = window.open("insertar_caja.php", "neo", opciones);
            mipopup.focus()
        }

        function enviar_formulario() {
            document.formulario.submit()
        }

        function load() {
            location.reload(true);
        }

        function mostrar(e) {
            tecla = (document.all) ? e.keyCode : e.which;
            if (tecla == 16) {
                //Shift
                mostrarModalAgregarGasto();
            }
            return false;
        }

        function mostrarModalAgregarGasto() {
            document.getElementById("modalAgregarGasto").style.display = 'block';
            document.getElementById("guar").style.display = 'block';
            document.getElementById("gasto").style.display = 'block';
        }

        function ocultarModalGasto() {
            document.getElementById("modalAgregarGasto").style.display = 'none';
        }

        function soloNumeros(evt) {
            var tecla = String.fromCharCode(evt.which || evt.keyCode);
            if (tecla == 9) return true;
            else if (!/[\d.\b\r]/.test(tecla) || tecla == 9) return false;
            return true;
        }

        function ocultarModalValidarAcceso() {
            document.getElementById("modalValidarAcceso").style.display = 'none';
        }

        function validarAcceso() {
            document.getElementById("modalValidarAcceso").style.display = 'block';
        }

        function redirectReporteCorte(e) {
            tecla = (document.all) ? e.keyCode : e.which;
            if (tecla == 13) {
                var pass1 = document.getElementById("pass").value;
                var superpass = document.getElementById("superpass").value;

                if (pass1 == "") {

                } else if (pass1 == superpass) {
                    window.location.href = "reporte_corte.php";
                } else {
                    alert("Contraseña errónea");
                }
            }
        }

        function confirmarGuardarCorte() {
            var flag = confirm("¿Seguro que deseas guardar el corte? No podrás revertirlo.");
            if (flag == true) {
                return true;
            } else {
                return false;
            }
        }

        function recargarCorte() {
            document.corte_caja.submit();
        }
    </script>

</body>

</html>