<?php
require('../session.php');
include('../../model/ModelPoliza.php');
$model_poliza = new ModelPoliza();
include('../../model/ModelCliente.php');
$model_cliente = new ModelCliente();
include('../../model/ModelVehiculo.php');
$model_vehiculo = new ModelVehiculo();
include('../../model/ModelVendedor.php');
$model_vendedor = new ModelVendedor();
include('../../model/ModelCobertura.php');
$model_cobertura = new ModelCobertura();
include('../../model/ModelCompaniaRespaldo.php');
$model_compania_respaldo = new ModelCompaniaRespaldo();

$cliente_id = isset($_GET["cliente_id"])  ? $_GET['cliente_id'] : null;
$cliente = $model_cliente->get_cliente($cliente_id);

$vehiculo_id = isset($_GET["vehiculo_id"])  ? $_GET['vehiculo_id'] : null;
$vehiculo = $model_vehiculo->get_vehiculo($vehiculo_id);

if (!$cliente || !$vehiculo) {
    echo "<script>
        alert('El cliente o el vehículo no existen.');
        window.location.href = '../clientes/index.php'; 
    </script>";
}

$coberturas = $model_cobertura->get_lista_coberturas($vehiculo_id);
$companiasPolizaRespaldo = $model_compania_respaldo->get_lista_companias();

$vendedores = $model_vendedor->get_vendedores();

//Calcular edad del cliente
$fecha_actual = date('Y-m-d');
if ($cliente['fecha_nacimiento'] && strtotime($cliente['fecha_nacimiento']) > 0) {
    $diff = abs(strtotime($fecha_actual) - strtotime($cliente['fecha_nacimiento']));
    $anios = floor($diff / (365 * 60 * 60 * 24));
    $edad = $anios . " Años";
} else {
    $cliente['fecha_nacimiento'] = "No asignada";
    $edad = "No asignada";
}

//Calcular nuevo número de póliza
$ultima_poliza = $model_poliza->get_ultima_poliza_cliente($cliente_id, $vehiculo_id);
if ($ultima_poliza) {
    $ultima_poliza = reset($ultima_poliza);
    $poliza_id = floatval($ultima_poliza["idpoliza"]) + 1;
} else {
    $numero_aleatorio = rand(01, 99);
    $poliza_id = date("y") . $cliente_id . $vehiculo_id . $numero_aleatorio . "1";
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

    <title>Póliza</title>

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
</head>

<body>

    <script type="text/javascript">
        function enviar_formulario() {
            document.formulario.submit()
        }
    </script>
    <?php include("../menu_principal.php") ?>

    <div id="wrapper">
        <!-- Navigation -->
        <div id="page-wrapper">
            <div class="row">
                <a href="../vehiculos/index.php?cliente_id=<?php echo $cliente_id ?>" class="btn btn-primary"><i class="fas fa-arrow-left fa-fw"></i> Regresar</a>
            </div>
            <!-- /.row -->
            <div class="row">
                <h1 class="page-header">Póliza: <?php echo $poliza_id; ?></h1>
                <div class="panel panel-default">
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-2">
                                        <label>Cliente: </label>
                                    </div>
                                    <div class="col-lg-10">
                                        <?php echo $cliente['nombre'] ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-2">
                                        <label>Dirección: </label>
                                    </div>
                                    <div class="col-lg-10">
                                        <?php echo $cliente["direccion_calle"] . " #" . $cliente["direccion_numero"] . ", " . $cliente["direccion_colonia"] . ", " . $cliente["direccion_ciudad"] . ", " . $cliente["direccion_estado"] . ", CP:" . $cliente["direccion_codigo_postal"]; ?>
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
                                        <?php echo $cliente['telefono'] . " / " . $cliente['telefono_alternativo'] ?>
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
                                        <?php echo $vehiculo['marca'] ?>
                                    </div>
                                    <div class="col-lg-2">
                                        <label>Tipo: </label>
                                    </div>
                                    <div class="col-lg-4">
                                        <?php echo $vehiculo["tipo"]; ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-2">
                                        <label>Color: </label>
                                    </div>
                                    <div class="col-lg-4">
                                        <?php echo $vehiculo['color'] ?>
                                    </div>
                                    <div class="col-lg-2">
                                        <label>Año: </label>
                                    </div>
                                    <div class="col-lg-4">
                                        <?php echo $vehiculo['anio'] ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-2">
                                        <label>Placa: </label>
                                    </div>
                                    <div class="col-lg-4">
                                        <?php echo $vehiculo['placa']; ?>
                                    </div>
                                    <div class="col-lg-2">
                                        <label>No. de serie: </label>
                                    </div>
                                    <div class="col-lg-4">
                                        <?php echo $vehiculo['numero_serie'] ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Detalle de la Póliza
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <form method="POST" action="/controller/polizas/insertar.php" autocomplete="off" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Tipo Cobertura:</label>
                                        <select class="form-control" name="cobertura" id="cobertura" onchange="seleccionar_cobertura(this.value)" required>
                                            <option disabled selected>SELECCIONAR COBERTURA</option>
                                            <?php foreach ($coberturas as $cobertura) : ?>
                                                <option value='<?php echo $cobertura["idcobertura"] ?>'><?php echo $cobertura["nombre"] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Vendedor:</label>
                                        <select class="form-control" name="vendedor" id="vendedor" required>
                                            <?php foreach ($vendedores as $vendedor) : ?>
                                                <option value='<?php echo $vendedor["idvendedor"] ?>'><?php echo $vendedor["nombre"] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Fecha Inicio:</label>
                                        <input class="form-control" name="fecha_inicio" id="fecha_inicio" type="date" value="<?php echo date('Y-m-d') ?>" required onchange="seleccionar_fecha_inicio()">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Fecha Fin:</label>
                                        <input class="form-control" name="fecha_fin" id="fecha_fin" type="date" value="<?php echo date('Y-m-d', strtotime('1 year')) ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="divErrorPoliza">
                                <div class="col-lg-12">
                                    <label class="bg-primary"><i class="fas fa-exclamation-circle fa-fw"></i> Ya tienes una póliza para este vehículo dentro del periodo seleccionado. No podrás generar una nueva.</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Plazo de pagos:</label>
                                        <div class='input-group'>
                                            <input type='number' step='1' min="1" name='plazo' id='plazo' value="10" class='form-control' required>
                                            <span class='input-group-addon'> meses</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Beneficiarios de servicios funerarios:</label>
                                        <textarea class='form-control' name='beneficiarios_funeraria' id='beneficiarios_funeraria'></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Número póliza respaldo:</label>
                                        <input type='text' name='numero_poliza_respaldo' id='numero_poliza_respaldo' class='form-control'>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Compañía póliza respaldo:</label>
                                        <select class="form-control" name="compania_poliza_respaldo" id="compania_poliza_respaldo">
                                            <option selected value="0">SELECCIONAR COMPAÑÍA</option>
                                            <?php foreach ($companiasPolizaRespaldo as $companiaRespaldo) : ?>
                                                <option value='<?php echo $companiaRespaldo["idpolizarespaldocompania"] ?>'><?php echo $companiaRespaldo["nombre"] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Subir PDF póliza respaldo:</label>
                                        <input type='file' accept="application/pdf" name='archivo_poliza_respaldo' id='archivo_poliza_respaldo' class='form-control'>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-lg-12">
                                    <table style='width:100%' class="table table-striped table-bordered table-responsive" id="tabla_servicios" name='tabla_servicios'>
                                    </table>
                                </div>
                            </div>
                            <div class="row" id="divTotales">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label>Gastos de expedición:</label>
                                        <div class='input-group'>
                                            <span class='input-group-addon'>$</span>
                                            <input type='number' step='.01' value="500" name='costo_expedicion' id='costo_expedicion' class='form-control input-sm' required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label>IVA:</label>
                                        <div class='input-group'>
                                            <span class='input-group-addon'>$</span>
                                            <input type='number' step='.01' value="0" name='iva' id='iva' class='form-control input-sm' required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label>Total:</label>
                                        <div class='input-group'>
                                            <span class='input-group-addon'>$</span>
                                            <input type='number' step='.01' value="0" name='total' id='total' class='form-control input-sm' required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="btnGuardar">
                                <div class="col-lg-10"></div>
                                <div class="col-lg-2">
                                    <button type="submit" class="btn btn-primary"> Guardar</button>
                                    <input type="hidden" id="cliente_id" name="cliente_id" value="<?php echo $cliente_id ?>" required>
                                    <input type="hidden" id="vehiculo_id" name="vehiculo_id" value="<?php echo $vehiculo_id ?>" required>
                                    <input type="hidden" name="poliza_id" value="<?php echo $poliza_id ?>" required>
                                </div>
                            </div>
                        </form>
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
            $("#btnGuardar").hide();
            $("#divTotales").hide();
            validar_poliza_existente();
        });

        function seleccionar_cobertura(valor) {
            $.ajax({
                type: "GET",
                url: "../../action/coberturas/obtener_servicios.php",
                data: {
                    cobertura_id: valor
                },
                success: function(data) {
                    $("#tabla_servicios tr").remove()
                    $("#tabla_servicios").append(data);
                    $("#btnGuardar").show();
                    $("#divTotales").show();
                }
            });
        }

        function seleccionar_servicio(servicio) {
            if ($("#checkbox" + servicio).is(':checked')) {
                $('#tr' + servicio).find('.tr-input').prop("disabled", false);
                $('#tr' + servicio).removeClass();
            } else {
                $('#tr' + servicio).find('.tr-input').prop("disabled", true);
                $('#primaneta' + servicio).val(0);
                $('#deducible' + servicio).val(0);
                $('#tr' + servicio).removeClass();
                $('#tr' + servicio).addClass('bg-danger');
            }
        }

        function seleccionar_cantidad_asegurada_tipo(servicio) {
            if ($("#cantidadaseguradatipo" + servicio).val() == 3) {
                $("#cantidadasegurada" + servicio).parent().show();
            } else {
                $("#cantidadasegurada" + servicio).val(0);
                $("#cantidadasegurada" + servicio).parent().hide();
                $("#cantidadaseguradapersonalizar" + servicio).parent().hide();
            }
        }

        function seleccionar_cantidad_asegurada_personalizar(servicio) {
            //Si selecciona una de las cantidades personalizadas predeterminadas el valor seleccionado se asigna al input de valor personalizado pero sigue oculto.
            let cantidad_asegurada = $("#cantidadaseguradapersonalizar" + servicio).val();
            if (cantidad_asegurada != "otro") {
                $("#cantidadasegurada" + servicio).val(parseFloat(cantidad_asegurada)); //Asignar cantidad asegurada_personalizada al input
                $("#cantidadasegurada" + servicio).parent().hide(); //Ocultar input de captura de cantidad
            } else {
                //Si selecciona otro cuando tiene cantidades personalizadas personalizadas, se muestra el input para que capture la cantidad deseada.
                $("#cantidadasegurada" + servicio).parent().show(); //Mostrar input de captura de cantidad
                $("#cantidadasegurada" + servicio).val(0);
            }
        }

        function seleccionar_fecha_inicio() {
            //Calcular fecha de fin de la póliza
            let fecha_final = new Date($("#fecha_inicio").val());
            fecha_final.setFullYear(fecha_final.getFullYear() + 1);
            fecha_final.setDate(fecha_final.getDate() + 1);

            let anio_final = fecha_final.getFullYear();
            let mes_final = ("0" + (fecha_final.getMonth() + 1)).slice(-2);
            let dia_final = ("0" + fecha_final.getDate()).slice(-2);

            $("#fecha_fin").val(anio_final + "-" + mes_final + "-" + dia_final);

            validar_poliza_existente();
        }

        function validar_poliza_existente() {
            //Validar si en la fecha seleccionada ya se creó una póliza para ese vehículo
            $.ajax({
                type: "GET",
                url: "../../action/polizas/validar_polizas_cliente_fecha.php",
                data: {
                    cliente_id: $("#cliente_id").val(),
                    vehiculo_id: $("#vehiculo_id").val(),
                    fecha: $("#fecha_inicio").val(),
                },
                success: function(data) {
                    //Ya existe una póliza en esas fechas, mostrar error y no permitir guardar.
                    $("#btnGuardar").hide();
                    $("#divErrorPoliza").show();

                },
                error: function() {
                    //No hay una póliza en esa fecha, permitir guardar
                    if ($("#cobertura").val()) {
                        $("#btnGuardar").show();
                    }
                    $("#divErrorPoliza").hide();

                }
            });
        }
    </script>
</body>

</html>