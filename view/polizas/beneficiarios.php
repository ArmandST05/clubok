<?php
require('../session.php');
include('../../model/ModelVendedor.php');
include('../../model/ModelVehiculo.php');
include('../../model/ModelCliente.php');

$model_vendedor = new ModelVendedor();
$vendedores = $model_vendedor->get_vendedores();
$model_vehiculo = new ModelVehiculo();
$vehiculos = $model_vehiculo->getAll();
$model_cliente = new ModelCliente();
$tipos_cliente = $model_cliente->obtener_tipos_cliente();

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Beneficiarios</title>

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
                <a href="index.php" class="btn btn-primary"><i class="fas fa-arrow-left fa-fw"></i> Regresar</a>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Agregar beneficiario</h1>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <!-- DataTables Advanced Tables -->
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
  <div class="col-lg-12">
<form method="POST" action="../../controller/clientes/insertar.php" id="formInsertar" autocomplete="off">
    <div class="form-row">
        <div class="form-group col-lg-6">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required>
        </div>
        <div class="form-group col-lg-6">
            <label for="fecha_nacimiento">Fecha de Nacimiento</label>
            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-lg-6">
            <label for="calle">Calle</label>
            <input type="text" class="form-control" id="calle" name="calle" placeholder="Calle" required>
        </div>
        <div class="form-group col-lg-6">
            <label for="numero">Número</label>
            <input type="number" class="form-control" id="numero" name="numero" placeholder="Número" required>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-lg-6">
            <label for="colonia">Colonia</label>
            <input type="text" class="form-control" id="colonia" name="colonia" placeholder="Colonia" required>
        </div>
        <div class="form-group col-lg-6">
            <label for="ciudad">Ciudad</label>
            <input type="text" class="form-control" id="ciudad" name="ciudad" placeholder="Ciudad" required>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-lg-6">
            <label for="codigo_postal">Código Postal</label>
            <input type="text" pattern="\d{5}" class="form-control" id="codigo_postal" name="codigo_postal" placeholder="Código Postal" title="Debe contener 5 dígitos">
        </div>
        <div class="form-group col-lg-6">
            <label for="estado">Estado</label>
            <input type="text" class="form-control" id="estado" name="estado" placeholder="Estado" required>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-lg-6">
            <label for="telefono">Teléfono</label>
            <input type="tel" class="form-control" id="telefono" name="telefono" placeholder="Teléfono" required>
        </div>
        <div class="form-group col-lg-6">
            <label for="telefono_alternativo">Teléfono Alternativo</label>
            <input type="tel" class="form-control" id="telefono_alternativo" name="telefono_alternativo" placeholder="Teléfono Alternativo">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-lg-6">
            <label for="curp">CURP</label>
            <input type="text" class="form-control" id="curp" name="curp" placeholder="CURP" required>
        </div>
        <div class="form-group col-lg-6">
            <label for="fecha_registro">Fecha de Registro</label>
            <input type="date" class="form-control" id="fecha_registro" name="fecha_registro" value="<?php echo date('Y-m-d'); ?>">
        </div>
    </div>

    <div class="form-group mt-4">
        <button type="submit" class="btn btn-primary btn-block">Guardar</button>
    </div>
</form>
</div>


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