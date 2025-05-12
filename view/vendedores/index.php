<?php
require('../session.php');
include('../../model/ModelVendedor.php');
$model_vendedor = new ModelVendedor();

$nombre = isset($_GET["search"])  ? $_GET['search'] : null;
$vendedores = $model_vendedor->get_vendedores_nombre($nombre);
?>
<!DOCTYPE html>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Vendedores</title>

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
    function confirmar_eliminar() {
        var flag = confirm("¿Estás seguro de eliminar el vendedor?");
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
                        <h1 class="page-header">Lista de vendedores</h1>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <!-- DataTables Advanced Tables -->
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="dataTable_wrapper">

                                    <div class="col-lg-12">

                                        <div class="form-group">
                                            <p>
                                            <table border="0" style="width:50%">
                                                <form action='index.php' method='GET'>
                                                    <div class="form-group">
                                                        <div class="col-lg-6">
                                                            <div class="input-group">
                                                                <span class='input-group-addon input-group-sm'><i class='fas fa-search'></i></span>
                                                                <input type='text' name='search' id='search' class='form-control' placeholder='Buscar por clave o nombre' autofocus />
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <button type='submit' class='btn btn-secondary btn-sm'> Buscar</button>
                                                        </div>
                                                </form>
                                            </table>
                                            </p>

                                            <table class="table table-bordered table-hover" name="table" id="table">
                                                <tr>
                                                    <th>Código</th>
                                                    <th>Nombre completo</th>
                                                    <th>Nombre usuario</th>
                                                    <th>Acciones</th>
                                                </tr>
                                                <?php foreach ($vendedores as $datos) : ?>
                                                    <tr align='center'>
                                                        <td><?php echo $datos["idvendedor"] ?></td>
                                                        <td><?php echo $datos["nombre"] ?></td>
                                                        <td><?php echo $datos["username"] ?></td>
                                                        <td>
                                                            <form action='/view/vendedores/editar.php' method='POST'>
                                                                <button type='submit' class='btn btn-default btn-sm'><i class="fas fa-pencil-alt fa-fw"></i> Modificar</button>
                                                                <input type='hidden' name='vendedor_id' value='<?php echo $datos["idvendedor"] ?>'>
                                                                <input type='hidden' name='nombre' value='<?php echo $datos["nombre"] ?>'>
                                                                <input type='hidden' name='nombre_usuario' value='<?php echo $datos["username"] ?>'>
                                                            </form>

                                                            <form action='/controller/vendedores/actualizar_estatus.php' method='post'>
                                                                <button type='submit' class='btn btn-default btn-sm' onclick='return confirmar_eliminar();'><i class="fas fa-trash fa-fw"></i> Eliminar</button>
                                                                <input type='hidden' name='id' value='<?php echo $datos["idvendedor"] ?>'>
                                                                <input type='hidden' name='estatus' value='0'>
                                                            </form>
                                                        </td>
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