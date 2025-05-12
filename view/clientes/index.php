<?php
require('../session.php');
include('../../model/ModelCliente.php');
$model_cliente = new ModelCliente();
include('../../model/ModelVendedor.php');
$model_vendedor = new ModelVendedor();
$vendedores = $model_vendedor->get_vendedores();
$vendedorId = (isset($_GET["vendedorId"])) ? $_GET["vendedorId"] : 0;
if ($vendedorId == 0) {
    $clientes = $model_cliente->get_lista_clientes();
} else {
    $clientes = $model_cliente->get_lista_clientes_vendedor($vendedorId);
}
?>
<!DOCTYPE html>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Clientes</title>

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
    function confirmar() {
        var flag = confirm("¿Estás seguro de eliminar el cliente?");
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
            td = tr[i].getElementsByTagName("td")[1];
            if (td) {
                if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
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
                        <h1 class="page-header">Lista de clientes</h1>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <!-- DataTables Advanced Tables -->
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="dataTable_wrapper">

                                    <div class="col-lg-12">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Vendedores:</label>
                                                <select class="form-control" name="cobertura" id="cobertura" onchange="seleccionarVendedor(this.value)" required>
                                                    <option value='0' <?php echo ($vendedorId == 0) ? "selected" : "" ?>>TODOS</option>
                                                    <?php foreach ($vendedores as $vendedor) : ?>
                                                        <option value="<?php echo $vendedor['idvendedor'] ?>" <?php echo ($vendedor["idvendedor"] == $vendedorId) ? "selected" : "" ?>><?php echo $vendedor["nombre"] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-lg-6">
                                                <label>Búsqueda:</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fas fa-search"></i></span>
                                                    <input type="text" name="search" id="search" class="form-control" onkeyup="myFunction()" placeholder="Buscar clientes" autofocus autocomplete="off">
                                                </div>
                                            </div>
                                            <br>
                                        </div>
                                        <div class="form-group">
                                            <table class="table table-bordered table-hover" name="table" id="table">
                                                <tr>
                                                    <th>Clave</th>
                                                    <th>Nombre</th>
                                                    <th>Dirección</th>
                                                    <th>Teléfonos </th>
                                                    <th>Vendedor</th>
                                                    <th>Acciones</th>
                                                </tr>
                                                <?php foreach ($clientes as $cliente) :
                                                ?>
                                                    <tr align='center'>
                                                        <td><?php echo $cliente["idcliente"] ?></td>
                                                        <td><?php echo $cliente["nombre"] ?></td>
                                                        <td><?php echo $cliente["direccion_calle"] . " #" . $cliente["direccion_numero"] . ", " . $cliente["direccion_colonia"] . ", " . $cliente["direccion_ciudad"] . ", " . $cliente["direccion_estado"] . ", CP: " . $cliente["direccion_codigo_postal"]; ?></td>
                                                        <td>Teléfono: <?php echo ($cliente["telefono"]) ?><br>
                                                            Teléfono Alternativo: <?php echo $cliente["telefono_alternativo"] ?>
                                                        </td>
                                                        <td><?php echo ($cliente["vendedor_id"]) ? $cliente["vendedor_nombre"] : "NO ASIGNADO" ?></td>
                                                        <td>
                                                            <a href="../vehiculos/index.php?cliente_id=<?php echo $cliente['idcliente'] ?>" class='btn btn-default btn-sm'><i class="fas fa-folder-open fa-fw"></i> Expediente vehículos</a>
                                                            <a href="../incidentes/index.php?cliente_id=<?php echo $cliente['idcliente'] ?>" class='btn btn-default btn-sm'><i class="fas fa-history fa-fw"></i> Historial Incidentes</a>
                                                            <?php if ($_SESSION["tipo_usuario"] == 1) : ?>
                                                                <a href="editar.php?cliente_id=<?php echo $cliente['idcliente'] ?>" class='btn btn-default btn-sm'><i class="fas fa-pencil-alt fa-fw"></i> Modificar</a>
                                                                <form action='/controller/clientes/modificar_estatus.php' method='POST'>
                                                                    <button type='submit' class='btn btn-default btn-sm' onclick='return confirmar();'><i class="fas fa-trash fa-fw"></i> Eliminar</button>
                                                                    <input type='hidden' name='cliente_id' value='<?php echo $cliente["idcliente"] ?>'>
                                                                    <input type='hidden' name='estatus' value='0'>
                                                                </form>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                            <h4 style='text-align:right;font-style:bold;'>Total clientes: <?php echo count($clientes) ?></h4>
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

        function seleccionarVendedor(vendedorId) {
            window.location.assign("index.php?vendedorId=" + vendedorId);
        }
    </script>

    </div>
</body>

</html>