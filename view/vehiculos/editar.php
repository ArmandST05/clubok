<?php
require('../session.php');
include('../../model/ModelVehiculo.php');
$model_vehiculo = new ModelVehiculo();

$vehiculo_id = isset($_GET["vehiculo_id"])  ? $_GET['vehiculo_id'] : null;
$vehiculo = $model_vehiculo->get_vehiculo($vehiculo_id);
$estados = $model_vehiculo->obtenerEstados();
$tipo_vehiculo = $model_vehiculo->obtenerTipos();
if (!$vehiculo) {
    echo "<script>
        alert('El vehículo no existe.');
        window.location.href = 'index.php'; 
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

    <title>Vehículos</title>
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
                <a href="../vehiculos/index.php?cliente_id=<?php echo $_GET['cliente_id'] ?>" class="btn btn-primary"><i class="fas fa-arrow-left fa-fw"></i> Regresar</a>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Modificar vehículo</h1>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <!-- DataTables Advanced Tables -->
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="col-lg-12">
<form method="post" name="form1" id="form1" action="../../controller/vehiculos/modificar.php" autocomplete="off">
    <div class="form-group">
        <div class="col-lg-5">
            <label>Marca</label>
            <input class="form-control" placeholder="Marca" id="marca" name="marca" type="text" value="<?php echo $vehiculo['marca']; ?>" required>
        </div>
        <div class="col-lg-5">
            <label>Submarca</label>
            <input class="form-control" placeholder="Submarca" id="submarca" name="submarca" type="text" value="<?php echo $vehiculo['submarca']; ?>" required>
        </div>
    </div>

    <div class="form-group">
        <div class="col-lg-5">
            <label>Tipo</label>
            <select class="form-control" name="tipo" id="tipo" required>
                <option value="">Seleccione un tipo</option>
                <?php foreach ($tipo_vehiculo as $tipo) : ?>
                    <option value="<?php echo $tipo['id']; ?>" <?php if ($vehiculo['tipo_id'] == $tipo['id']) echo 'selected'; ?>>
                        <?php echo $tipo['nombre']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-lg-5">
            <label>Estado</label>
            <select class="form-control" name="estado" id="estado" required>
                <option value="">Seleccione un estado</option>
                <?php foreach ($estados as $estado) : ?>
                    <option value="<?php echo $estado['id']; ?>" <?php if ($vehiculo['estado_id'] == $estado['id']) echo 'selected'; ?>>
                        <?php echo $estado['estado']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <div class="col-lg-5">
            <label>Color</label>
            <input class="form-control" placeholder="Color" id="color" name="color" type="text" value="<?php echo $vehiculo['color']; ?>" required>
        </div>
        <div class="col-lg-5">
            <label>Año</label>
            <input class="form-control" placeholder="Año" id="anio" name="anio" type="number" value="<?php echo $vehiculo['anio']; ?>" required>
        </div>
    </div>

    <div class="form-group">
        <div class="col-lg-5">
            <label>Placa</label>
            <input class="form-control" placeholder="Placa" id="placa" name="placa" type="text" value="<?php echo $vehiculo['placa']; ?>" required>
        </div>
        <div class="col-lg-5">
            <label>Número de Serie</label>
            <input class="form-control" placeholder="Número de Serie" id="numero_serie" name="numero_serie" type="text" value="<?php echo $vehiculo['numero_serie']; ?>" required>
        </div>
    </div>

    <div class="form-group">
        <div class="col-lg-5">
            <label>Valor</label>
            <input class="form-control" placeholder="Valor" id="valor" name="valor" type="number" step="0.01" value="<?php echo $vehiculo['valor']; ?>" required>
        </div>
    </div>

    <div class="form-group">
        <div class="col-lg-5">
            <br><input type="submit" value="Actualizar" class="btn btn-primary">
            <input type="hidden" name="vehiculo_id" value="<?php echo $vehiculo['idvehiculo']; ?>">
            <input type="hidden" name="cliente_id" value="<?php echo $vehiculo['cliente_id']; ?>">
        </div>
    </div>
</form>

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
    </script>

</body>

</html>