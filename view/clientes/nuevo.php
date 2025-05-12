<?php
require('../session.php');
include('../../model/ModelVendedor.php');
$model_vendedor = new ModelVendedor();
$vendedores = $model_vendedor->get_vendedores();
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
                <a href="index.php" class="btn btn-primary"><i class="fas fa-arrow-left fa-fw"></i> Regresar</a>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Agregar cliente</h1>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <!-- DataTables Advanced Tables -->
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="col-lg-12">
                                <form method="POST" name="form1" action="/controller/clientes/insertar.php" autocomplete="off" id="formInsertar">
                                    <div class="form-group">
                                        <div class="col-lg-5">
                                            <label>Nombre</label>
                                            <input class="form-control" placeholder="Nombre" id="nombre" name="nombre" type="text" required>
                                        </div>
                                        <div class="col-lg-5">
                                            <label>Fecha Nacimiento</label>
                                            <input class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" type="text" value="" placeholder="DD/MM/AAAA">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-5">
                                            <label>Calle</label>
                                            <input class="form-control" placeholder="Calle" id="calle" name="calle" type="text" required>
                                        </div>
                                        <div class="col-lg-5">
                                            <label>Número</label>
                                            <input class="form-control" placeholder="Número" id="numero" name="numero" type="text" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-5">
                                            <label>Colonia</label>
                                            <input class="form-control" placeholder="Colonia" id="colonia" name="colonia" type="text" required>
                                        </div>
                                        <div class="col-lg-5">
                                            <label>Ciudad</label>
                                            <input class="form-control" placeholder="Ciudad" id="ciudad" name="ciudad" type="text" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-lg-5">
                                            <label>Código Postal</label>
                                            <input class="form-control" placeholder="Código Postal" id="codigo_postal" name="codigo_postal" type="text">
                                        </div>
                                        <div class="col-lg-5">
                                            <label>Estado</label>
                                            <input class="form-control" placeholder="Estado" id="estado" name="estado" type="text" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-5">
                                            <label>Teléfono </label>
                                            <input class="form-control" placeholder="Teléfono" id="telefono" name="telefono" type="text" required>
                                        </div>
                                        <div class="col-lg-5">
                                            <label>Teléfono Alternativo </label>
                                            <input class="form-control" placeholder="Teléfono" id="telefono_alternativo" name="telefono_alternativo" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-5">
                                            <label>Vendedor:</label>
                                            <select class="form-control" name="vendedor" id="vendedor" onchange="click(),darclick();">
                                                <option selected>NO ASIGNADO</option>
                                                <?php foreach ($vendedores as $vendedor) : ?>
                                                    <option value='<?php echo $vendedor["idvendedor"] ?>'><?php echo $vendedor["nombre"] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-5">
                                            <br><input type="submit" value="Guardar" class="btn btn-primary">
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

        $("#formInsertar").submit(function() {
            var fecha_nacimiento = $("#fecha_nacimiento").val();
            if(fecha_nacimiento != ""){
                if(regExValidateDate.test(fecha_nacimiento)){
                    return true;
                }
                else{
                    alert("Ingresa una fecha válida DD/MM/AAAA");
                   return false;
                }
            }   
            else{
                return true;
            }
        });
    </script>

</body>

</html>