<?php
require('../session.php');
include_once('../../model/ModelFormula.php');
$model_formula = new ModelFormula();
include_once('../../model/ModelPoliza.php');
$model_poliza = new ModelPoliza();
include_once('../../model/ModelVendedor.php');
$model_vendedor = new ModelVendedor();

$datos_empresa = $model_formula->get_configuracion_empresa();

$poliza_id = isset($_GET["poliza_id"])  ? $_GET['poliza_id'] : null;
$poliza = $model_poliza->get_poliza($poliza_id);

if (!$poliza) {
    echo "<script>
        alert('La póliza no existe.');
        window.location.href = 'index.php'; 
    </script>";
}
$poliza = reset($poliza);
$poliza_detalles = $model_poliza->get_poliza_detalles($poliza_id);

$vendedores = $model_vendedor->get_vendedores();

$abonos = $model_poliza->get_abonos_poliza($poliza_id);
$total_abonos = $model_poliza->get_total_abonos_poliza($poliza_id);
$saldo = (($total_abonos > 0) ? ($poliza['total'] - $total_abonos) : ($poliza['total']));

$fecha_actual = date('Y-m-d');
if ($poliza['cliente_fecha_nacimiento'] && strtotime($poliza['cliente_fecha_nacimiento']) > 0) {
    $diff = abs(strtotime($fecha_actual) - strtotime($poliza['cliente_fecha_nacimiento']));
    $anios = floor($diff / (365 * 60 * 60 * 24));
    $edad = $anios . " Años";
} else {
    $poliza['cliente_fecha_nacimiento'] = "No asignada";
    $edad = "No asignada";
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

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

    <!-- Bootstrap Core CSS -->
    <link href="../../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <script src="../jquery-2.1.1.min.js" type="text/javascript"></script>
    <link href="../select2.min.css" rel="stylesheet" />
    <script src="../select2.min.js"></script>

    <link rel="stylesheet" href="../jquery-ui.css">
    <script src="../jquery-ui.js"></script>

</head>

<body onkeydown="validar_tecla(event);">

    <script type="text/javascript">
        function validar_tecla(e) {
            tecla = (document.all) ? e.keyCode : e.which;
            if (tecla == 16) {
                mostrar_modal_nuevo_abono();
            }
            return false;
        }

        function mostrar_modal_nuevo_abono() {
            $("#modalNuevoAbono").show();
            $("#cantidad").val(0);
            $("#cantidad").focus();
            $('#recibido').val(0);
            $('#cambio').val(0.00);
            $('#saldo').val(0.00);
            $("#btnGuardarAbono").hide();
        }

        function ocultar_modal_nuevo_abono() {
            $("#modalNuevoAbono").hide();
        }

        function calcular_recibido() {
            var cantidad = parseFloat($('#cantidad').val());
            $('#recibido').val(parseFloat(eval(cantidad)).toFixed(2));
            calcular_total();
        }

        function calcular_total() {
            var total = parseFloat($('#total').val());
            var recibido = parseFloat($('#recibido').val());
            var cantidad = parseFloat($('#cantidad').val());

            $('#cambio').val(parseFloat(eval(recibido - cantidad)).toFixed(2));
            var cambio = $('#cambio').val();
            $('#saldo').val(parseFloat(eval(total - cantidad)).toFixed(2));
            var saldo = $('#saldo').val();

            if (cambio >= 0 && saldo >= 0) {
                $('#saldo').val(parseFloat(eval(total - cantidad)).toFixed(2));
                $('#cambio').val(parseFloat(eval(recibido - cantidad)).toFixed(2));

                if (cantidad > 0) {
                    $("#btnGuardarAbono").show();
                }

                $('#btnGuardarAbono').click(function() {
                    document.form1.submit();
                });

            } else {
                alert('Verifica tus cantidades');
                $("#cantidad").val(0);
                $("#cantidad").focus();
                $('#recibido').val(0);
                $('#cambio').val(0.00);
                $('#saldo').val(0.00);
                $("#btnGuardarAbono").hide();
            }
        }

        function soloNumeros(evt) {
            var tecla = String.fromCharCode(evt.which || evt.keyCode);
            if (tecla == 9) return true;
            else if (!/[\d.\b\r]/.test(tecla) || tecla == 9) return false;
            return true;
        }

        function enviar_formulario() {
            document.formulario.submit()
        }

        function validar_acceso() {
            var pass1 = prompt("Ingresa la constraseña", "")
            var superpass = $("#superpass").val();
            if (pass1 == superpass) {
                flag == true
                alert("Contraseña correcta");
                return true;

            } else {
                alert("Contraseña errónea");
                return false;
            }
        }
    </script>

    <?php include("../menu_principal.php") ?>

    <div id="wrapper">
        <!-- Navigation -->
        <div id="page-wrapper">
            <!-- /.row -->
            <div class="row">
                <a href="index.php" class="btn btn-primary"><i class="fas fa-arrow-left fa-fw"></i> Regresar</a>
                <!-- /.col-lg-12 -->
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-lg-9">
                                    <h3>PÓLIZA: <?php echo $poliza_id ?></h3>
                                </div>
                                <div class="col-lg-3">
                                    <a href="../polizas/imprimir_poliza.php?poliza_id=<?php echo $poliza['idpoliza'] ?>" target="_blank" class='btn btn-primary'><i class="fas fa-print"></i> Imprimir Póliza</a>
                                </div>
                            </div>
                            <?php if ($poliza['numero_poliza_respaldo']) : ?>
                                <div class="row">
                                    <div class="col-lg-9">
                                        <h4>Póliza respaldo: <?php echo $poliza['poliza_respaldo_compania_nombre'] . " " . $poliza['numero_poliza_respaldo'] ?></h4>
                                    </div>
                                    <div class="col-lg-3">
                                        <a href="../../<?php echo $poliza['archivo_poliza_respaldo'] ?>" target="_blank" class='btn btn-default'><i class="fas fa-print"></i> Imprimir póliza respaldo</a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-2">
                                            <label>Cliente: </label>
                                        </div>
                                        <div class="col-lg-10">
                                            <?php echo $poliza['cliente_nombre'] ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-2">
                                            <label>Dirección: </label>
                                        </div>
                                        <div class="col-lg-10">
                                            <?php echo $poliza["cliente_direccion_calle"] . " #" . $poliza["cliente_direccion_numero"] . ", " . $poliza["cliente_direccion_colonia"] . ", " . $poliza["cliente_direccion_ciudad"] . ", " . $poliza["cliente_direccion_estado"] . ", CP:" . $poliza["cliente_direccion_codigo_postal"]; ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-2">
                                            <label>Edad: </label>
                                        </div>
                                        <div class="col-lg-4">
                                            <?php echo $edad ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-2">
                                            <label>Teléfonos: </label>
                                        </div>
                                        <div class="col-lg-4">
                                            <?php echo $poliza['cliente_telefono'] . " / " . $poliza['cliente_telefono_alternativo'] ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-2">
                                            <label>Marca: </label>
                                        </div>
                                        <div class="col-lg-4">
                                            <?php echo $poliza['vehiculo_marca'] ?>
                                        </div>
                                        <div class="col-lg-2">
                                            <label>Tipo: </label>
                                        </div>
                                        <div class="col-lg-4">
                                            <?php echo $poliza["vehiculo_tipo"]; ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-2">
                                            <label>Color: </label>
                                        </div>
                                        <div class="col-lg-4">
                                            <?php echo $poliza['vehiculo_color'] ?>
                                        </div>
                                        <div class="col-lg-2">
                                            <label>Año: </label>
                                        </div>
                                        <div class="col-lg-4">
                                            <?php echo $poliza['vehiculo_anio'] ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-2">
                                            <label>Placa: </label>
                                        </div>
                                        <div class="col-lg-4">
                                            <?php echo $poliza['vehiculo_placa']; ?>
                                        </div>
                                        <div class="col-lg-2">
                                            <label>No. de serie: </label>
                                        </div>
                                        <div class="col-lg-4">
                                            <?php echo $poliza['vehiculo_numero_serie'] ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12">
                                    <table class="table table-striped table-bordered table-hover">
                                        <tr>
                                            <th>Descripción</th>
                                            <th align="right">Suma asegurada</th>
                                            <th align="right">Prima neta</th>
                                            <th align="right">Deducible</th>
                                        </tr>
                                        <?php
                                        foreach ($poliza_detalles as $servicio) :
                                            $cantidad_asegurada = (($servicio["cantidad_asegurada_tipo_id"] == 3) ? "$" . number_format($servicio["cantidad_asegurada"], 2) : $servicio["cantidad_asegurada_tipo_nombre"]);
                                            $cantidad_deducible = (($servicio["cantidad_deducible"] > 0) ? number_format($servicio["cantidad_deducible"], 2) . "%" : "NO APLICA");
                                        ?>
                                            <tr>
                                                <td><?php echo $servicio["servicio_nombre"] ?></td>
                                                <td align='right'><?php echo $cantidad_asegurada ?></td>
                                                <td align='right'>$<?php echo number_format($servicio["prima_neta"], 2) ?></td>
                                                <td align='right'><?php echo $cantidad_deducible ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </table>
                                </div>
                            </div>
                            <h4>Abonos</h4>
                            <hr>
                            <?php if ($poliza["estatus"] == 1) : ?>
                                <div class="row">
                                    <div class="col-lg-10"></div>
                                    <div class="col-lg-2">
                                        <button onclick="mostrar_modal_nuevo_abono()" class="btn btn-default"><i class="fas fa-plus fa-fw"></i> Registrar abono</button>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="row">
                                <div class="col-lg-12">
                                    <table class="table table-striped table-bordered table-hover">
                                        <th>No.</th>
                                        <th>Fecha</th>
                                        <th>Cobrador</th>
                                        <th>Pago</th>
                                        <th>Acciones</th>
                                        </tr>
                                        <?php
                                        $numero = count($abonos); //Numerar hacia atrás ya que ordena la última fecha hasta la primera.
                                        foreach ($abonos as $abono) :
                                        ?>
                                            <tr align='center'>
                                                <td><?php echo $numero ?></td>
                                                <td><?php echo $abono["fecha_formato"] ?></td>
                                                <td><?php echo $abono["vendedor_nombre"] ?></td>
                                                <td style='text-align:right;'>$<?php echo number_format($abono["cantidad"], 2) ?></td>
                                                <td>
                                                    <a href="imprimir_abono.php?abono_id=<?php echo $abono['idabono'] ?>" target="__blank" class='btn btn-primary btn-xs'><i class="fas fa-print"></i> Imprimir</a>
                                                    <?php if ($_SESSION["tipo_usuario"] == 1) : // && $abono["incluido_corte"] == 0 
                                                    ?>
                                                        <form action='/controller/polizas/eliminar_abono.php' method='POST'>
                                                            <button type='submit' class='btn btn-default btn-xs' onclick='return validar_acceso();'><i class="fas fa-trash"></i> Eliminar</button>
                                                            <input type='hidden' name='abono_id' value='<?php echo $abono["idabono"] ?>'>
                                                        </form>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php
                                            $numero--;
                                        endforeach; ?>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <h4 style='text-align:right;font-style:bold;'>Total a pagar: $<?php echo number_format($poliza['total'], 2) ?><br>Saldo: $<?php echo number_format($saldo, 2) ?></h4>
                                </div>
                            </div>
                            <!-- Nuebo Abono-->
                            <div class="modal" id="modalNuevoAbono" name="modalNuevoAbono" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style='display:none;'>
                                <div class="modal-dialog">
                                    <input class='btn btn-primary' type="submit" id="bot" name="bot" Value="X" onclick="ocultar_modal_nuevo_abono();" />
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
                                                        <div class="modal-body">
                                                            <form action="/controller/polizas/insertar_abono.php" method="GET" id="form1" autocomplete='off' name="form1">
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div class="form-group">
                                                                            <label>COBRADOR</label>
                                                                            <select name="vendedor" id="vendedor" class="form-control">
                                                                                <?php foreach ($vendedores as $vendedor) {
                                                                                    echo "<option value=" . $vendedor["idvendedor"] . ">" . $vendedor["nombre"] . "</option>";
                                                                                }
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <div class="form-group">
                                                                            <label>FECHA</label>
                                                                            <input type="date" value="<?php echo date('Y-m-d') ?>" max="<?php echo date('Y-m-d') ?>" name="fecha" id="fecha" class="form-control" required>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-12">
                                                                        <div class="form-group">
                                                                            <label>TOTAL VENTA</label>
                                                                            <div class='input-group'>
                                                                                <span class='input-group-addon'>$</span>
                                                                                <input type='number' step='.01' name='total' id='total' class='form-control' value="<?php echo $saldo ?>" required readonly>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-12">
                                                                        <div class="form-group">
                                                                            <label>PAGO</label>
                                                                            <div class='input-group'>
                                                                                <span class='input-group-addon'>$</span>
                                                                                <input type='number' step='.01' min=".01" name='cantidad' id='cantidad' class='form-control' value="0" onchange="calcular_recibido();" required>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-12">
                                                                        <div class="form-group">
                                                                            <label>RECIBÍ</label>
                                                                            <div class='input-group'>
                                                                                <span class='input-group-addon'>$</span>
                                                                                <input type='number' step='.01' min=".01" name='recibido' id='recibido' class='form-control' value="0" onchange="calcular_total();" required>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-12">
                                                                        <div class="form-group">
                                                                            <label>SALDO</label>
                                                                            <div class='input-group'>
                                                                                <span class='input-group-addon'>$</span>
                                                                                <input type='number' step='.01' name='saldo' id='saldo' value="0" class='form-control' required>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-12">
                                                                        <div class="form-group">
                                                                            <label>CAMBIO</label>
                                                                            <div class='input-group'>
                                                                                <span class='input-group-addon'>$</span>
                                                                                <input type='number' step='.01' name='cambio' id='cambio' class='form-control' required readonly>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-10">
                                                                    </div>
                                                                    <div class="col-lg-2">
                                                                        <input class='btn btn-primary' type="submit" id="btnGuardarAbono" value="Guardar">
                                                                        <input type="hidden" id="poliza_id" name="poliza_id" value="<?php echo $poliza_id ?>" required>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- Nuebo Abono-->
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="superpass" id="superpass" value="<?php echo $datos_empresa['clave_admin_eliminar'] ?>">
        <!-- /.row (nested) -->
        <!-- /.row (nested) -->
    </div>
    <!-- /.panel-body -->
    </div>
    <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
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
    <script src="../../bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="../../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../../dist/js/sb-admin-2.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
        $(document).ready(function() {

        });

        function confirmar() {
            var flag = confirm("¿Seguro deseas eliminar el producto?");
            if (flag == true) {
                return true;
            } else {
                return false;
            }
        }

        $('#btnGuardarAbono').click(function() {
            // Ajax request
            var btn = $(this);
            btn.prop('disabled', true);
            setTimeout(function() {
                btn.prop('disabled', false);
            }, 6 * 1000);
        });
    </script>
</body>

</html>