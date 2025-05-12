<?php
require('../session.php');
include('../../model/ModelIncidente.php');
$model_incidente = new ModelIncidente();

$incidente_id = isset($_GET["incidente_id"])  ? $_GET['incidente_id'] : null;
if ($incidente_id) {
    $incidente = $model_incidente->get_incidente($incidente_id);
    $incidente = reset($incidente);
}

if (!$incidente) {
    echo "<script>
        alert('El incidente no existe.');
        window.location.href = '../clientes/index.php'; 
    </script>";
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
                <a href="../incidentes/index.php?cliente_id=<?php echo $incidente['cliente_id'] ?>" class="btn btn-primary"><i class="fas fa-arrow-left fa-fw"></i> Regresar</a>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Modificar Incidente</h1>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <!-- DataTables Advanced Tables -->
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="col-lg-12">

                                <form method="post" name="form" id="form" action="/controller/incidentes/modificar.php" autocomplete="off">
                                    <div class="form-group">
                                        <div class="col-lg-6">
                                            <label>Cliente</label>
                                            <input class="form-control" placeholder="Cliente" type="text" value="<?php echo $incidente['cliente_nombre'] ?>" readonly>
                                            <input id="cliente_id" name="cliente_id" type="hidden" value="<?php echo $incidente['cliente_id'] ?>" required>
                                        </div>
                                        <div class="col-lg-6">
                                            <label>Vehículo</label>
                                            <input class="form-control" placeholder="Vehículo" type="text" value="<?php echo $incidente['vehiculo_marca']." ".$incidente['vehiculo_tipo'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-6">
                                            <label>Póliza</label>
                                            <input class="form-control" placeholder="Póliza" id="poliza" name="poliza" type="text" value="<?php echo $incidente['poliza_id'] ?>" readonly required>
                                        </div>
                                        <div class="col-lg-6">
                                            <label>Fecha</label>
                                            <input class="form-control" id="fecha" name="fecha" type="date" value="<?php echo $incidente['fecha'] ?>" readonly required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-6">
                                            <label>Vehículo del percance (Contra que chocó)</label>
                                            <input class="form-control" placeholder="Vehículo del percance" id="vehiculo_involucrado" name="vehiculo_involucrado" type="text" value="<?php echo $incidente['vehiculo_involucrado'] ?>" required>
                                        </div>
                                        <div class="col-lg-6">
                                            <label>Ajustador</label>
                                            <input class="form-control" placeholder="Ajustador" id="ajustador" name="ajustador" type="text" value="<?php echo $incidente['ajustador'] ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-6">
                                            <label>Grúas utilizadas</label>
                                            <input class="form-control" placeholder="Grúas utilizadas" id="cantidad_gruas" name="cantidad_gruas" type="number" min="0" step="1" value="<?php echo $incidente['cantidad_gruas'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-12">
                                            <label>Circunstancias</label>
                                            <textarea class="form-control" rows="10" id="circunstancias" name="circunstancias" required><?php echo $incidente['circunstancias'] ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-10"></div>
                                        <div class="col-lg-2">
                                            <br><input type="submit" value="Guardar" class="btn btn-primary">
                                            <input id="incidente_id" name="incidente_id" type="hidden" value="<?php echo $incidente['idincidente'] ?>" required>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Change this to a button or input when using this as a form -->
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">


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
    </script>

</body>

</html>