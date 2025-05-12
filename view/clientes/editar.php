<?php
require('../session.php');
include('../../model/ModelVendedor.php');
$model_vendedor = new ModelVendedor();
include('../../model/ModelCliente.php');
$model_cliente = new ModelCliente();

$vendedores = $model_vendedor->get_vendedores();
$cliente_id = isset($_GET["cliente_id"])  ? $_GET['cliente_id'] : null;
$cliente = $model_cliente->get_cliente($cliente_id);
$tipos_cliente = $model_cliente->obtener_tipos_cliente();
include('../../model/ModelVehiculo.php');

$model_vehiculo = new ModelVehiculo();
$vehiculos = $model_vehiculo->getAll();
if (!$cliente) {
    echo "<script>
        alert('El cliente no existe.');
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
                    <h1 class="page-header">Modificar cliente</h1>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <!-- DataTables Advanced Tables -->
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="col-lg-12">
<form method="POST" name="contact" action="../../controller/clientes/modificar.php" id="formModificar">
    <div class="form-row">
        <div class="form-group col-lg-6">
            <label for="nombre">Nombre</label>
            <input class="form-control" id="nombre" name="nombre" type="text" value="<?php echo $cliente['nombre']; ?>" required>
        </div>
        <div class="form-group col-lg-6">
            <label for="fecha_nacimiento">Fecha de Nacimiento</label>
            <input class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" type="date"
                   value="<?php echo ($cliente['fecha_nacimiento'] != '' && $cliente['fecha_nacimiento'] != '0000-00-00') ? date('Y-m-d', strtotime($cliente['fecha_nacimiento'])) : ''; ?>">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-lg-6">
            <label for="calle">Calle</label>
            <input class="form-control" id="calle" name="calle" type="text" value="<?php echo $cliente['direccion_calle']; ?>" required>
        </div>
        <div class="form-group col-lg-6">
            <label for="numero">Número</label>
            <input class="form-control" id="numero" name="numero" type="text" value="<?php echo $cliente['direccion_numero']; ?>" required>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-lg-6">
            <label for="colonia">Colonia</label>
            <input class="form-control" id="colonia" name="colonia" type="text" value="<?php echo $cliente['direccion_colonia']; ?>" required>
        </div>
        <div class="form-group col-lg-6">
            <label for="ciudad">Ciudad</label>
            <input class="form-control" id="ciudad" name="ciudad" type="text" value="<?php echo $cliente['direccion_ciudad']; ?>" required>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-lg-6">
            <label for="codigo_postal">Código Postal</label>
            <input class="form-control" id="codigo_postal" name="codigo_postal" type="number" value="<?php echo $cliente['direccion_codigo_postal']; ?>">
        </div>
        <div class="form-group col-lg-6">
            <label for="estado">Estado</label>
            <input class="form-control" id="estado" name="estado" type="text" value="<?php echo $cliente['direccion_estado']; ?>" required>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-lg-6">
            <label for="telefono">Teléfono</label>
            <input class="form-control" id="telefono" name="telefono" type="tel" value="<?php echo $cliente['telefono']; ?>" required>
        </div>
        <div class="form-group col-lg-6">
            <label for="telefono_alternativo">Teléfono Alternativo</label>
            <input class="form-control" id="telefono_alternativo" name="telefono_alternativo" type="tel" value="<?php echo $cliente['telefono_alternativo']; ?>">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-lg-6">
            <label for="curp">CURP</label>
            <input type="text" class="form-control" id="curp" name="curp" value="<?php echo $cliente['curp']; ?>" required>
        </div>
        <div class="form-group col-lg-6">
            <label for="vendedor">Vendedor</label>
            <select class="form-control" name="vendedor" id="vendedor" onchange="click(), darclick();">
                <option value="">NO ASIGNADO</option>
                <?php foreach ($vendedores as $vendedor) : ?>
                    <option value="<?php echo $vendedor['idvendedor']; ?>" <?php echo ($cliente['vendedor_id'] == $vendedor['idvendedor']) ? 'selected' : ''; ?>>
                        <?php echo $vendedor['nombre']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <!-- Nuevos campos -->
    <div class="form-row">
        <div class="form-group col-lg-6">
            <label for="fecha_registro">Fecha de Registro</label>
            <input type="date" class="form-control" id="fecha_registro" name="fecha_registro"
                   value="<?php echo ($cliente['fecha_registro'] != '' && $cliente['fecha_registro'] != '0000-00-00') ? date('Y-m-d', strtotime($cliente['fecha_registro'])) : date('Y-m-d'); ?>"
                   readonly>
        </div>
        <div class="form-group col-lg-6">
            <label for="tipo_cliente_id">Tipo de Cliente</label>
            <select class="form-control" name="tipo_cliente" id="tipo_cliente" required>
                <option value="">-- Selecciona una opción --</option>
                <?php foreach ($tipos_cliente as $tipo) : ?>
                    <option value="<?php echo $tipo['id']; ?>" <?php echo ($cliente['tipo_cliente_id'] == $tipo['id']) ? 'selected' : ''; ?>>
                        <?php echo $tipo['nombre']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <!-- Campo oculto con el ID del cliente -->
    <input type="hidden" name="cliente_id" id="cliente_id" value="<?php echo $cliente['idcliente']; ?>">

</form>

<!-- Botón fuera del formulario -->
<div class="mt-3">
    <button form="formModificar" type="submit" class="btn btn-primary">Guardar</button>
</div>

                            </div>
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