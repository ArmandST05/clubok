<?php
require('../session.php');
include('../../model/ModelCobertura.php');
$model_cobertura = new ModelCobertura();
$nombre = isset($_GET["search"])  ? $_GET['search'] : null;
$coberturas = $model_cobertura->get_lista_coberturas();
?>
<!DOCTYPE html>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Coberturas</title>

    <!-- Bootstrap Core CSS -->
    <link href="../../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="../../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="../../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.js">

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
    function confirmar() {
        var flag = confirm("¿Estás seguro de eliminar la cobertura?");
        if (flag == true) {
            return true;
        } else {
            return false;
        }
    }

    function enviar_formulario() {
        document.formulario.submit()
    }
</script>

<body>
    <div class="row">
        <?php include("../menu_principal.php") ?>

        <div id="wrapper">

            <!-- Navigation -->

            <div id="page-wrapper">
                <div class="row">

                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Lista de coberturas</h1>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <!-- DataTables Advanced Tables -->
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="dataTable_wrapper">

                                    <div class="col-lg-12">

                                        <div class="form-group">

                                            <table class="table table-bordered table-hover" name="table" id="table">
                                                <tr>
                                                    <th>Nombre</th>
                                                    <th>Valor </th>
                                                    <th>Servicios</th>
                                                    <th>Acciones</th>
                                                </tr>
                                                <?php foreach ($coberturas as $cobertura) :
                                                    $servicios = $model_cobertura->get_servicios_cobertura($cobertura['idcobertura']);
                                                ?>
                                                    <tr>
                                                        <td><?php echo $cobertura["nombre"] ?></td>
                                                        <td align='center'>$<?php echo number_format($cobertura["cantidad_cubierta"],2) ?></td>
                                                        <td>
                                                            <?php foreach ($servicios as $servicio) {
                                                                echo $servicio['nombre'] . "<br>";
                                                            }
                                                            ?>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                <?php endforeach; ?>
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
    <script>
        $(document).ready(function() {
            $('#dataTables-example').DataTable({
                responsive: true
            });
        });
    </script>

    </div>
</body>

</html>