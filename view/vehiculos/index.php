<?php
require('../session.php');
include('../../model/ModelCliente.php');
$model_cliente = new ModelCliente();
include('../../model/ModelVehiculo.php');
$model_vehiculo = new ModelVehiculo();
include('../../model/ModelPoliza.php');
$model_poliza = new ModelPoliza();
$cliente_id = isset($_GET["cliente_id"])  ? $_GET['cliente_id'] : null;
$cliente = $model_cliente->get_cliente($cliente_id);

if (!$cliente) {
    echo "<script>
        alert('El cliente no existe.');
        window.location.href = '../clientes/index.php'; 
    </script>";
}

$fecha_actual = date('Y-m-d');
if ($cliente['fecha_nacimiento'] && strtotime($cliente['fecha_nacimiento']) > 0) {
    $diff = abs(strtotime($fecha_actual) - strtotime($cliente['fecha_nacimiento']));
    $anios = floor($diff / (365 * 60 * 60 * 24));
    $edad = $anios . " Años";
} else {
    $cliente['fecha_nacimiento'] = "No asignada";
    $edad = "No asignada";
}

$vehiculos = $model_vehiculo->get_vehiculos_cliente($cliente_id);
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Clientes</title>

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
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <h1 class="page-header">Expediente cliente</h1>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <!-- DataTables Advanced Tables -->
                    </div>
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
                        <?php if ($_SESSION["tipo_usuario"] == 1) : ?>
                            <div class="row">
                                <div class="col-lg-10"></div>
                                <div class="col-lg-2">
                                    <a href="../vehiculos/nuevo.php?cliente_id=<?php echo $cliente['idcliente'] ?>" class="btn btn-default"><i class="fas fa-plus fa-fw"></i> Nuevo Vehículo</a>
                                </div>
                            </div>
                        <?php endif; ?>
                        <br>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fas fa-search"></i></span>
                                    <input type="text" name="search" id="search" class="form-control" onkeyup="myFunction()" placeholder="Buscar vehículo" autofocus autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-striped table-bordered table-hover" id="table">
                                    <tr>
                                        <th>Marca</th>
                                        <th>Tipo</th>
                                        <th>Color</th>
                                        <th>Año</th>
                                        <th>Placa</th>
                                        <th>No. de Serie</th>
                                        <th>Pólizas</th>
                                        <th>Acciones</th>
                                    </tr>
                                    <?php foreach ($vehiculos as $vehiculo) :
                                        $polizas = $model_poliza->get_polizas_vehiculo($vehiculo['idvehiculo']);
                                    ?>
                                        <tr>
                                            <td><?php echo $vehiculo['marca'] ?></td>
                                            <td><?php echo $vehiculo['tipo'] ?></td>
                                            <td><?php echo $vehiculo['color'] ?></td>
                                            <td><?php echo $vehiculo['anio'] ?></td>
                                            <td><?php echo $vehiculo['placa'] ?></td>
                                            <td><?php echo $vehiculo['numero_serie'] ?></td>
                                            <td>
                                                <?php foreach ($polizas as $poliza) :
                                                    if ($poliza["estatus"] == 2) {
                                                        $btnClass = "danger";
                                                        $btnIcon = "<i class='fas fa-ban'></i>";
                                                    } else {
                                                        $btnClass = "primary";
                                                        $btnIcon = "";
                                                    }

                                                ?>
                                                    <label><?php echo $poliza['fecha_inicio_formato'] . "-" . $poliza['fecha_fin_formato'] ?></label>
                                                    <a href="../polizas/imprimir_poliza.php?poliza_id=<?php echo $poliza['idpoliza'] ?>" target="__blank" class="btn btn-<?php echo $btnClass ?> btn-sm">
                                                        <?php echo $poliza['idpoliza'] . " " . $btnIcon ?>
                                                    </a>
                                                    <?php if ($poliza['numero_poliza_respaldo']) : ?>
                                                        <a href="../../<?php echo $poliza['archivo_poliza_respaldo'] ?>" target="_blank" class="btn btn-default btn-sm">
                                                            <?php echo "RESPALDO " . $poliza['poliza_respaldo_compania_nombre'] . " " . $poliza['numero_poliza_respaldo'] ?></a>
                                                    <?php endif; ?>
                                                    <br>
                                                <?php endforeach; ?>
                                            </td>
                                            <td>
                                                <?php if ($_SESSION["tipo_usuario"] == 1) : ?>
                                                    <a href="../polizas/nuevo.php?vehiculo_id=<?php echo $vehiculo['idvehiculo'] ?>&cliente_id=<?php echo $cliente_id ?>" class='btn btn-default btn-sm'><i class="fas fa-copy fa-fw"></i> Generar Póliza</a>
                                                    <a href="../vehiculos/editar.php?vehiculo_id=<?php echo $vehiculo['idvehiculo'] ?>&cliente_id=<?php echo $cliente_id ?>" class='btn btn-default btn-sm'><i class="fas fa-pencil-alt fa-fw"></i> Modificar</a>
                                                    <form action='/controller/vehiculos/actualizar_estatus.php' method='POST'>
                                                        <button type='submit' class='btn btn-default btn-sm' onclick='return confirmar_eliminar();'><i class="fas fa-trash fa-fw"></i> Eliminar</button>
                                                        <input type='hidden' name='vehiculo_id' value='<?php echo $vehiculo["idvehiculo"] ?>'>
                                                        <input type='hidden' name='estatus' value='0'>
                                                        <input type='hidden' name='cliente_id' value='<?php echo $vehiculo["cliente_id"] ?>'>
                                                    </form>
                                                <?php endif; ?>
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
    </div>
    <!-- /.col-lg-6 -->
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


        function confirmar_eliminar() {
            var flag = confirm("¿Estás seguro de eliminar el vehículo?");
            if (flag == true) {
                return true;
            } else {
                return false;
            }
        }

        function myFunction() {
            // Declare variables 
            var input, filter, table, tr, td, i;
            input = document.getElementById("search");
            filter = input.value.toUpperCase();
            table = document.getElementById("table");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0];
                if (td) {
                    if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>

</body>

</html>