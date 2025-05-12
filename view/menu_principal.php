<?php //Código utilizado la primera vez que se implementó la alerta con sesión iniciada.
if (!isset($_SESSION["alert_payment"])) {
$_SESSION["alert_payment"] = 0;
}
?>
<!-- Charts -->
<script src="../../bower_components/chart/chart.min.js"></script>
<!-- Charts -->
<!-- Font Awesome Icons -->
<link href="../../bower_components/font-awesome/css/all.min.css" rel="stylesheet" type="text/css" />

<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <span class="navbar-brand" style="background-size: 100px;width: 100px; height:80px; background-image:url('../../images/logo-menu.jpg');background-repeat: no-repeat; position:absolute;top:1px;left:80px;"></span>
    </div>
    <!-- /.navbar-header -->

    <ul class="nav navbar-top-links navbar-right">
        <!-- /.dropdown -->
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fas fa-user fa-fw"></i> <?php echo $_SESSION["user"] ?></a>
            <!-- /.dropdown-user -->
        </li>
        <li class="dropdown">
            <form action="../cerrarsesion.php" method="post" name="formulario"><a href="JavaScript:enviar_formulario()"><i class="fas fa-sign-out fa-fw"></i> Salir</a></form>
            <!-- /.dropdown-user -->
        </li>
        <!-- /.dropdown -->
    </ul>
    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li class="sidebar-search">
                    <div class="input-group custom-search-form">
                        <!-- <input type="text" class="form-control" placeholder="Search...">-->
                        <span class="input-group-btn">
                            <!--<button class="btn btn-default" type="button">-->
                            <i class="fas fa-search"></i>
                            </button>
                        </span>
                    </div>
                    <!-- /input-group -->
                </li>
                <?php if ($_SESSION["tipo_usuario"] == 1 || $_SESSION["tipo_usuario"] == 3) : ?>
                    <li>
                        <a href="#"><i class="fas fa-user fa-fw"></i> Clientes<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <?php if ($_SESSION["tipo_usuario"] == 1) : ?>
                                <li>
                                    <a href="../clientes/nuevo.php">Agregar Clientes</a>
                                </li>
                            <?php endif; ?>
                            <li>
                                <a href="../clientes/index.php">Consultar Clientes</a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if ($_SESSION["tipo_usuario"] == 1 || $_SESSION["tipo_usuario"] == 3) : ?>
                    <li>
                        <a href="#"><i class="fas fa-list fa-fw"></i> Coberturas<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="../coberturas/index.php">Consultar Coberturas</a>
                            </li>
                            <!--<li>
                                <a href="../coberturas/nuevo.php">Agregar Cobertura</a>
                            </li>
                            <li>
                                <a href="../servicios/nuevo.php">Agregar Servicio</a>
                            </li>-->
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if ($_SESSION["tipo_usuario"] == 1 || $_SESSION["tipo_usuario"] == 3) : ?>
                    <li>
                        <a href="#"><i class="fas fa-copy fa-fw"></i>Pólizas<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <?php if ($_SESSION["tipo_usuario"] == 1 || $_SESSION["tipo_usuario"] == 3) : ?>
                                <li>
                                    <a href="../polizas/index.php">Reporte de pólizas</a>
                                </li>
                            <?php endif; ?>
                            <?php if ($_SESSION["tipo_usuario"] == 1) : ?>
                                <li>
                                    <a href="../cortes/index.php">Realizar cortes</a>
                                </li>
                                <li>
                                    <a href="../polizas/reporte_cobranza.php">Cobranza</a>
                                </li>
                                <!--
                                <li>
                                    <a href="../ventas/reporte.php">Reporte Tablas</a>
                                </li>
                                <li>
                                    <a href="../ventas/reporte_ventas_grafico.php">Reporte Gráfico</a>
                                </li>-->
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if ($_SESSION["tipo_usuario"] == 1) : ?>
                    <li>
                        <a href="#"><i class="fas fa-users fa-fw"></i> Vendedores<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="../vendedores/nuevo.php">Agregar Vendedores</a>
                            </li>
                            <li>
                                <a href="../vendedores/index.php">Consultar Vendedores</a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
</nav>
<script>
    /*(function() {
        if ("<?php echo $_SESSION["alert_payment"] ?>" == 0) {
        alert("Estimado usuario, tu sistema presenta un saldo vencido. Te invitamos a regularizarlo a la brevedad posible.");
        let alertMessage = "<?php echo $_SESSION["alert_payment"] = 1 ?>"
      }
    })();*/
    var regExValidateDate = /^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[13-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/;
    document.addEventListener("DOMContentLoaded", function() {
        // Invocamos cada 10 minutos ;)
        const milisegundos = 600 * 1000;
        setInterval(function() {
            // No esperamos la respuesta de la petición porque no nos importa
            fetch("../refrescar.php");
        }, milisegundos);
    });
</script>