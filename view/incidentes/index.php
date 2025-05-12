<?php
require('../session.php');
include('../../model/ModelCliente.php');
$model_cliente = new ModelCliente();
include('../../model/ModelIncidente.php');
$model_incidente = new ModelIncidente();

$cliente_id = isset($_GET["cliente_id"])  ? $_GET['cliente_id'] : null;
if ($cliente_id) {
    $cliente = $model_cliente->get_cliente($cliente_id);
    $incidentes = $model_incidente->get_incidentes_cliente($cliente_id);

    if (!$cliente) {
        echo "<script>
            alert('El cliente no existe.');
            window.location.href = '../clientes/index.php'; 
        </script>";
    }

    //Edad cliente
    $fecha_actual = date('Y-m-d');
    if ($cliente['fecha_nacimiento'] && strtotime($cliente['fecha_nacimiento']) > 0) {
        $diff = abs(strtotime($fecha_actual) - strtotime($cliente['fecha_nacimiento']));
        $anios = floor($diff / (365 * 60 * 60 * 24));
        $edad = $anios . " Años";
    } else {
        $cliente['fecha_nacimiento'] = "No asignada";
        $edad = "No asignada";
    }
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

    <title>Incidentes</title>

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
                <a href="../clientes/index.php" class="btn btn-primary"><i class="fas fa-arrow-left fa-fw"></i> Regresar</a>
            </div>
            <!-- /.row -->
            <div class="row">
                <h1 class="page-header">Historial Incidentes</h1>
                <div class="panel panel-default">
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <?php if ($cliente_id) : ?>
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
                        <?php endif; ?>
                        <hr>
                        <div class="row">
                            <div class="col-lg-10"></div>
                            <div class="col-lg-2">
                                <a href="../incidentes/nuevo.php?cliente_id=<?php echo $cliente['idcliente'] ?>" class="btn btn-default"><i class="fas fa-plus fa-fw"></i> Nuevo Incidente</a>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-striped table-bordered table-hover" id="table">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Cliente</th>
                                        <th>Vehículo</th>
                                        <th>Póliza</th>
                                        <th>Póliza respaldo</th>
                                        <th>Vehículo del Percance</th>
                                        <th>Ajustador</th>
                                        <th>Grúas</th>
                                        <th>Circunstancias</th>
                                        <th>Acciones</th>
                                    </tr>
                                    <?php foreach ($incidentes as $incidente) :
                                    ?>
                                        <tr>
                                            <td><?php echo $incidente['fecha_formato'] ?></td>
                                            <td><?php echo $incidente['cliente_nombre'] ?></td>
                                            <td><?php echo $incidente['vehiculo_marca'] . " " . $incidente['vehiculo_tipo'] ?></td>
                                            <td>
                                                <a href="../polizas/imprimir_poliza.php?poliza_id=<?php echo $incidente['poliza_id'] ?>" target="_blank">
                                                    <?php echo $incidente['poliza_id'] ?></a>
                                            </td>
                                            <td>
                                                <?php if ($incidente['numero_poliza_respaldo']) : ?>
                                                    <?php echo $incidente['poliza_respaldo_compania'] ?>
                                                    <a href="../../<?php echo $incidente['archivo_poliza_respaldo'] ?>" target="_blank">
                                                        <?php echo $incidente['numero_poliza_respaldo'] ?></a>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo $incidente['vehiculo_involucrado'] ?></td>
                                            <td><?php echo $incidente['ajustador'] ?></td>
                                            <td><?php echo $incidente['cantidad_gruas'] ?></td>
                                            <td><?php echo $incidente['circunstancias'] ?></td>
                                            <td>
                                                <a href="editar.php?incidente_id=<?php echo $incidente['idincidente'] ?>" class='btn btn-default btn-sm'><i class="fas fa-pencil-alt fa-fw"></i> Modificar</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
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
                }
            });
        }
    </script>
</body>

</html>