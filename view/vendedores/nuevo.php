<?php
require('../session.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Vendedores</title>

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

    <?php include("../menu_principal.php") ?>

    <div id="wrapper">

        <!-- Navigation -->
        <div id="page-wrapper">
            <div class="row">
                <a href="index.php" class="btn btn-primary"><i class="fas fa-arrow-left fa-fw"></i> Regresar</a>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-8">
                    <h1 class="page-header">Agregar vendedor</h1>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <!-- DataTables Advanced Tables -->
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="col-lg-12">

                                <form method="POST" name="form1" id="form1" action="/controller/vendedores/insertar.php" autocomplete="off">
                                    <fieldset>
                                        <div class="form-group">
                                            <div class="col-lg-5">
                                                <label>Nombre completo</label>
                                                <input class="form-control" placeholder="Nombre del vendedor" id="nombre" name="nombre" type="text" required>
                                            </div>
                                            <div class="col-lg-5">
                                                <label>Nombre de usuario</label>
                                                <input class="form-control" placeholder="Nombre de Usuario" id="nombre_usuario" name="nombre_usuario" type="text" value="" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-lg-5">
                                                <label>Contraseña</label>
                                                <input class="form-control" placeholder="Contraseña" id="contrasena" name="contrasena" type="password" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-lg-5">
                                                <br><button type="submit" class="btn btn-primary">Guardar</button>
                                            </div>
                                        </div>
                                </form>
                                <!-- Change this to a button or input when using this as a form -->
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
    </script>

</body>

</html>