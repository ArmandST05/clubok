<?php
include_once('Medoo.php');

use Medoo\Medoo;
/*Sintaxis de la Base de Datos
- Select : $this->base_datos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->base_datos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->base_datos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->base_datos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

class ModelPoliza
{
	var $base_datos; //Variable para hacer la conexion a la base de datos
	var $resultado; //Variable para traer resultados de una consulta a la BD

	function __construct()
	{ //Constructor de la conexion a la BD
		$this->base_datos = new Medoo();
	}

	/*--------------PÓLIZAS------------------ */
	function get_polizas_vehiculo($vehiculo_id)
	{
		$sql = $this->base_datos->query("SELECT polizas.*, DATE_FORMAT(polizas.fecha_inicio,'%d/%m/%Y') AS fecha_inicio_formato,
			DATE_FORMAT(polizas.fecha_fin,'%d/%m/%Y') AS fecha_fin_formato,prc.nombre AS poliza_respaldo_compania_nombre
			FROM polizas 
			LEFT JOIN poliza_respaldo_companias prc ON prc.idpolizarespaldocompania = polizas.compania_poliza_respaldo_id
			WHERE vehiculo_id = '$vehiculo_id' 
			AND (polizas.estatus = 1 OR polizas.estatus = 2)
			ORDER BY fecha_inicio DESC")->fetchAll();
		return $sql;
	}


	function eliminar_poliza($poliza_id)
	{
		return $this->base_datos->delete("polizas", ["idpoliza[=]" => $poliza_id]);
	}

	function get_poliza($id)
	{
		$sql = $this->base_datos->query("SELECT polizas.*, DATE_FORMAT(polizas.fecha_inicio,'%d/%m/%Y') AS fecha_inicio_formato,
			DATE_FORMAT(polizas.fecha_fin,'%d/%m/%Y') AS fecha_fin_formato, coberturas.nombre AS cobertura_nombre,
			clientes.nombre AS cliente_nombre,clientes.direccion_calle AS cliente_direccion_calle,
			clientes.direccion_colonia AS cliente_direccion_colonia,clientes.direccion_ciudad AS cliente_direccion_ciudad,
			clientes.direccion_estado AS cliente_direccion_estado,clientes.direccion_codigo_postal AS cliente_direccion_codigo_postal,
			clientes.direccion_numero AS cliente_direccion_numero,
			clientes.telefono AS cliente_telefono, clientes.telefono_alternativo AS cliente_telefono_alternativo,
			clientes.fecha_nacimiento AS cliente_fecha_nacimiento,
			DATE_FORMAT(clientes.fecha_nacimiento,'%d/%m/%Y') AS fecha_nacimiento_formato,
			vendedores.nombre AS vendedor_nombre,
			vehiculos.marca AS vehiculo_marca, vehiculos.tipo AS vehiculo_tipo,
			vehiculos.color AS vehiculo_color, vehiculos.placa AS vehiculo_placa,
			vehiculos.numero_serie AS vehiculo_numero_serie, vehiculos.anio AS vehiculo_anio,
			prc.nombre AS poliza_respaldo_compania_nombre
			FROM polizas			
			INNER JOIN coberturas ON polizas.cobertura_id = coberturas.idcobertura
			INNER JOIN clientes ON polizas.cliente_id = clientes.idcliente
			INNER JOIN vendedores ON polizas.vendedor_id = vendedores.idvendedor
			INNER JOIN vehiculos ON polizas.vehiculo_id = vehiculos.idvehiculo
			LEFT JOIN poliza_respaldo_companias prc ON prc.idpolizarespaldocompania = polizas.compania_poliza_respaldo_id
			WHERE (polizas.estatus = 1 OR polizas.estatus = 2)
			AND polizas.idpoliza = '$id'")->fetchAll();
		return $sql;
	}

	function get_polizas_cliente($cliente_id)
	{
		$sql = $this->base_datos->query("SELECT polizas.*, DATE_FORMAT(polizas.fecha_inicio,'%d/%m/%Y') AS fecha_inicio_formato,
			DATE_FORMAT(polizas.fecha_fin,'%d/%m/%Y') AS fecha_fin_formato, coberturas.nombre AS cobertura_nombre,
			clientes.nombre AS cliente_nombre,clientes.direccion_calle AS cliente_direccion_calle,
			clientes.direccion_colonia AS cliente_direccion_colonia,clientes.direccion_ciudad AS cliente_direccion_ciudad,
			clientes.direccion_estado AS cliente_direccion_estado,clientes.direccion_codigo_postal AS cliente_direccion_codigo_postal,
			clientes.direccion_numero AS cliente_direccion_numero,
			clientes.telefono AS cliente_telefono, clientes.telefono_alternativo AS cliente_telefono_alternativo,
			clientes.fecha_nacimiento AS cliente_fecha_nacimiento,
			DATE_FORMAT(clientes.fecha_nacimiento,'%d/%m/%Y') AS fecha_nacimiento_formato,
			vendedores.nombre AS vendedor_nombre,
			vehiculos.marca AS vehiculo_marca, vehiculos.tipo AS vehiculo_tipo,
			vehiculos.color AS vehiculo_color, vehiculos.placa AS vehiculo_placa,
			vehiculos.numero_serie AS vehiculo_numero_serie, vehiculos.anio AS vehiculo_anio
			FROM polizas, coberturas,clientes,vendedores,vehiculos
			WHERE polizas.cobertura_id = coberturas.idcobertura
			AND polizas.cliente_id = clientes.idcliente
			AND polizas.vendedor_id = vendedores.idvendedor
			AND polizas.vehiculo_id = vehiculos.idvehiculo
			AND polizas.cliente_id = '$cliente_id'
			AND polizas.estatus = 1
			ORDER BY fecha_inicio DESC")->fetchAll();
		return $sql;
	}

	function get_ultima_poliza_cliente($cliente_id, $vehiculo_id)
	{
		$sql = $this->base_datos->query("SELECT *
			FROM polizas
			WHERE polizas.cliente_id = '$cliente_id'
			AND polizas.vehiculo_id = '$vehiculo_id' 
			AND polizas.estatus = 1
			ORDER BY fecha_inicio DESC LIMIT 1")->fetchAll();
		return $sql;
	}

	function get_poliza_fecha($cliente_id, $vehiculo_id, $fecha)
	{
		return $this->base_datos->get("polizas", "*", [
			"AND" => [
				"cliente_id[=]" => $cliente_id,
				"vehiculo_id[=]" => $vehiculo_id,
				"fecha_inicio[<=]" => $fecha,
				"fecha_fin[>=]" => $fecha,
				"estatus[=]" => 1
			]
		]);
	}

	function validar_poliza_cliente_fecha($cliente_id, $vehiculo_id, $fecha)
	{
		//Validar si existe una póliza en esa fecha para ese vehículo del cliente.
		return $this->base_datos->get("polizas", "*", [
			"AND" => [
				"cliente_id[=]" => $cliente_id,
				"vehiculo_id[=]" => $vehiculo_id,
				"estatus[=]" => '1',
				"OR" => [
					"fecha_inicio[<=]" => $fecha,
					"fecha_fin[>=]" => $fecha
				],
				"OR" => [
					"fecha_inicio[>=]" => $fecha,
					"fecha_fin[>=]" => $fecha
				]
			]
		]);
	}

	function get_poliza_detalles($poliza_id)
	{
		//Se busca si tiene la configuración de editar si es un servicio predeterminado de la cobertura 
		//También se obtiene la configuración de editar si no pertenece por defecto a la cobertura.
		$sql = $this->base_datos->query("SELECT pd.*,s.nombre AS servicio_nombre,s.pagina_impresion,
			cat.nombre as cantidad_asegurada_tipo_nombre,
			s.editar_cantidad AS servicio_editar_cantidad,
			(SELECT cs.editar_cantidad FROM cobertura_servicios cs WHERE p.cobertura_id = cs.cobertura_id AND pd.servicio_id = cs.servicio_id) AS cobertura_servicio_editar_cantidad
			FROM poliza_detalles pd
			INNER JOIN polizas p ON p.idpoliza = pd.poliza_id
			INNER JOIN servicios s ON pd.servicio_id = s.idservicio
			INNER JOIN cantidad_asegurada_tipos cat ON pd.cantidad_asegurada_tipo_id = cat.idcantidadaseguradatipo
			AND pd.poliza_id = '$poliza_id'
			ORDER BY s.nombre")->fetchAll();

		return $sql;
	}

	//Obtiene todas las pólizas activas y canceladas para exportarlas al reporte
	function get_polizas_activas()
	{
		$fecha_actual = date("Y-m-d");

		//Se obtienen las pólizas que son de este año y las que son de años anteriores que no se terminaron de pagar.
		$sql = $this->base_datos->query("SELECT polizas.*, DATE_FORMAT(polizas.fecha_inicio,'%d/%m/%Y') AS fecha_inicio_formato,
			DATE_FORMAT(polizas.fecha_fin,'%d/%m/%Y') AS fecha_fin_formato, 
			DATE_FORMAT(polizas.fecha_estatus,'%d/%m/%Y') AS fecha_estatus_formato,coberturas.nombre AS cobertura_nombre,
			clientes.nombre AS cliente_nombre,clientes.direccion_calle AS cliente_direccion_calle,
			clientes.direccion_colonia AS cliente_direccion_colonia,clientes.direccion_ciudad AS cliente_direccion_ciudad,
			clientes.direccion_estado AS cliente_direccion_estado,clientes.direccion_codigo_postal AS cliente_direccion_codigo_postal,
			clientes.direccion_numero AS cliente_direccion_numero,
			clientes.telefono AS cliente_telefono, clientes.telefono_alternativo AS cliente_telefono_alternativo,
			clientes.fecha_nacimiento AS cliente_fecha_nacimiento,
			DATE_FORMAT(clientes.fecha_nacimiento,'%d/%m/%Y') AS fecha_nacimiento_formato,
			vendedores.nombre AS vendedor_nombre,
			vehiculos.marca AS vehiculo_marca, vehiculos.tipo_id AS vehiculo_tipo,
			vehiculos.color AS vehiculo_color, vehiculos.placa AS vehiculo_placa,
			vehiculos.numero_serie AS vehiculo_numero_serie, vehiculos.anio AS vehiculo_anio,
			(SELECT SUM(cantidad) FROM abonos WHERE abonos.poliza_id = polizas.idpoliza) AS total_abonos,
			(SELECT COUNT(idabono) FROM abonos WHERE abonos.poliza_id = polizas.idpoliza) AS cantidad_abonos,
			(SELECT DATE_FORMAT(fecha,'%d/%m/%Y') FROM abonos WHERE abonos.poliza_id = polizas.idpoliza ORDER BY fecha DESC LIMIT 1) AS fecha_ultimo_abono
			FROM polizas, coberturas,clientes,vendedores,vehiculos
			WHERE polizas.cobertura_id = coberturas.idcobertura
			AND polizas.cliente_id = clientes.idcliente
			AND polizas.vendedor_id = vendedores.idvendedor
			AND polizas.vehiculo_id = vehiculos.idvehiculo
			AND (polizas.estatus = 1 OR polizas.estatus = 2)
			AND ((polizas.fecha_inicio <= '$fecha_actual' AND polizas.fecha_fin >= '$fecha_actual') OR polizas.liquidado = 0)
			ORDER BY fecha_inicio")->fetchAll();

		return $sql;
	}
function get_polizas_estatus($estatusId)
{
    $fecha_actual = date("Y-m-d");

    // Se obtienen las pólizas que son de este año y las que son de años anteriores que no se terminaron de pagar.
    $query = "SELECT polizas.*, 
       DATE_FORMAT(polizas.fecha_inicio, '%d/%m/%Y') AS fecha_inicio_formato,
       DATE_FORMAT(polizas.fecha_fin, '%d/%m/%Y') AS fecha_fin_formato, 
       DATE_FORMAT(polizas.fecha_estatus, '%d/%m/%Y') AS fecha_estatus_formato,
       coberturas.nombre AS cobertura_nombre,
       clientes.nombre AS cliente_nombre,
       clientes.direccion_calle AS cliente_direccion_calle,
       clientes.direccion_colonia AS cliente_direccion_colonia,
       clientes.direccion_ciudad AS cliente_direccion_ciudad,
       clientes.direccion_estado AS cliente_direccion_estado,
       clientes.direccion_codigo_postal AS cliente_direccion_codigo_postal,
       clientes.direccion_numero AS cliente_direccion_numero,
       clientes.telefono AS cliente_telefono,
       clientes.telefono_alternativo AS cliente_telefono_alternativo,
       clientes.fecha_nacimiento AS cliente_fecha_nacimiento,
       DATE_FORMAT(clientes.fecha_nacimiento, '%d/%m/%Y') AS fecha_nacimiento_formato,
       vendedores.nombre AS vendedor_nombre,
       vehiculos.marca AS vehiculo_marca,
       vehiculos.tipo_id AS vehiculo_tipo,
       vehiculos.color AS vehiculo_color,
       vehiculos.placa AS vehiculo_placa,
       vehiculos.numero_serie AS vehiculo_numero_serie,
       vehiculos.anio AS vehiculo_anio,
       tipos_vehiculos.id,  -- Verifica si esta columna está correcta
       (SELECT SUM(cantidad) FROM abonos WHERE abonos.poliza_id = polizas.idpoliza) AS total_abonos,
       (SELECT COUNT(idabono) FROM abonos WHERE abonos.poliza_id = polizas.idpoliza) AS cantidad_abonos,
       (SELECT DATE_FORMAT(fecha, '%d/%m/%Y') FROM abonos WHERE abonos.poliza_id = polizas.idpoliza ORDER BY fecha DESC LIMIT 1) AS fecha_ultimo_abono,
       prc.nombre AS poliza_respaldo_compania_nombre 
FROM polizas
INNER JOIN coberturas ON polizas.cobertura_id = coberturas.idcobertura
INNER JOIN clientes ON polizas.cliente_id = clientes.idcliente
INNER JOIN vendedores ON polizas.vendedor_id = vendedores.idvendedor
INNER JOIN vehiculos ON polizas.vehiculo_id = vehiculos.idvehiculo
INNER JOIN tipos_vehiculos ON vehiculos.tipo_id = tipos_vehiculos.id  -- Verifica que este JOIN esté bien
LEFT JOIN poliza_respaldo_companias prc ON prc.idpolizarespaldocompania = polizas.compania_poliza_respaldo_id
";
    
    if ($estatusId == 1) { // Activas
        $query .= " WHERE polizas.estatus = '$estatusId' AND polizas.fecha_fin >= '$fecha_actual' AND polizas.liquidado = 0 ";
    } else if ($estatusId == 2) { // Canceladas
        $query .= " WHERE polizas.estatus = '$estatusId' ";
    } else if ($estatusId == "paid") {
        $query .= " WHERE polizas.liquidado = 1 ";
    } else if ($estatusId == "inactive") {
        // Mostrar pólizas de años anteriores que estén pendientes por pagar
        $query .= " WHERE polizas.estatus = 1 AND polizas.fecha_fin < '$fecha_actual' AND polizas.liquidado = 0 "; 
    } else if ($estatusId == "pendingToPay") {
        $query .= " WHERE (polizas.estatus != 2 AND polizas.liquidado = 0) "; // Mostrar pólizas pendientes por pagar que no estén canceladas
    }
    
    $query .= " ORDER BY fecha_inicio";
    $sql = $this->base_datos->query($query)->fetchAll();

    return $sql;
}



	function polizas_pendientes_cliente($cliente_id)
	{
		$sql = $this->base_datos->query("SELECT polizas.*, DATE_FORMAT(polizas.fecha_inicio,'%d/%m/%Y') AS fecha_inicio_formato,
			DATE_FORMAT(polizas.fecha_fin,'%d/%m/%Y') AS fecha_fin_formato, coberturas.nombre AS cobertura_nombre,
			clientes.nombre AS cliente_nombre,
			vendedores.nombre AS vendedor_nombre,
			vehiculos.marca AS vehiculo_marca, vehiculos.tipo AS vehiculo_tipo,
			vehiculos.color AS vehiculo_color, vehiculos.placa AS vehiculo_placa,
			vehiculos.numero_serie AS vehiculo_numero_serie, vehiculos.anio AS vehiculo_anio,
			(SELECT SUM(cantidad) FROM abonos WHERE abonos.poliza_id = polizas.idpoliza) AS total_abonos,
			(SELECT COUNT(idabono) FROM abonos WHERE abonos.poliza_id = polizas.idpoliza) AS cantidad_abonos,
			(SELECT DATE_FORMAT(fecha,'%d/%m/%Y') FROM abonos WHERE abonos.poliza_id = polizas.idpoliza ORDER BY fecha DESC LIMIT 1) AS fecha_ultimo_abono
			FROM polizas, coberturas,clientes,vendedores,vehiculos
			WHERE polizas.cobertura_id = coberturas.idcobertura
			AND polizas.cliente_id = clientes.idcliente
			AND polizas.vendedor_id = vendedores.idvendedor
			AND polizas.vehiculo_id = vehiculos.idvehiculo
			AND polizas.liquidado = 0
			AND polizas.cliente_id = '$cliente_id'
			AND polizas.estatus = 1
			ORDER BY fecha_inicio DESC")->fetchAll();

		return $sql;
	}

	function polizas_pendientes()
	{
		$sql = $this->base_datos->query("SELECT polizas.*, DATE_FORMAT(polizas.fecha_inicio,'%d/%m/%Y') AS fecha_inicio_formato,
			DATE_FORMAT(polizas.fecha_fin,'%d/%m/%Y') AS fecha_fin_formato, coberturas.nombre AS cobertura_nombre,
			clientes.nombre AS cliente_nombre,
			vendedores.nombre AS vendedor_nombre,
			vehiculos.marca AS vehiculo_marca, vehiculos.tipo AS vehiculo_tipo,
			vehiculos.color AS vehiculo_color, vehiculos.placa AS vehiculo_placa,
			vehiculos.numero_serie AS vehiculo_numero_serie, vehiculos.anio AS vehiculo_anio,
			(SELECT SUM(cantidad) FROM abonos WHERE abonos.poliza_id = polizas.idpoliza) AS total_abonos,
			(SELECT COUNT(idabono) FROM abonos WHERE abonos.poliza_id = polizas.idpoliza) AS cantidad_abonos,
			(SELECT DATE_FORMAT(fecha,'%d/%m/%Y') FROM abonos WHERE abonos.poliza_id = polizas.idpoliza ORDER BY fecha DESC LIMIT 1) AS fecha_ultimo_abono
			FROM polizas, coberturas,clientes,vendedores,vehiculos
			WHERE polizas.cobertura_id = coberturas.idcobertura
			AND polizas.cliente_id = clientes.idcliente
			AND polizas.vendedor_id = vendedores.idvendedor
			AND polizas.vehiculo_id = vehiculos.idvehiculo
			AND polizas.liquidado = 0
			AND polizas.estatus = 1
			ORDER BY fecha_inicio DESC")->fetchAll();
		return $sql;
	}

	function agregar_poliza($poliza_id, $cobertura_id, $cliente_id, $usuario_id, $vehiculo_id, $fecha_inicio, $fecha_fin, $vendedor_id, $total, $costo_expedicion, $iva, $plazo, $beneficiarios_funeraria,$numero_poliza_respaldo,$compania_poliza_respaldo,$archivo_poliza_respaldo)
	{
		$sql = $this->base_datos->insert("polizas", [
			"idpoliza" => $poliza_id,
			"cobertura_id" => $cobertura_id,
			"cliente_id" => $cliente_id,
			"usuario_id" => $usuario_id,
			"vehiculo_id" => $vehiculo_id,
			"fecha_inicio" => $fecha_inicio,
			"fecha_fin" => $fecha_fin,
			"vendedor_id" => $vendedor_id,
			"costo_expedicion" => $costo_expedicion,
			"total" => $total,
			"iva" => $iva,
			"plazo" => $plazo,
			"beneficiarios_funeraria" => $beneficiarios_funeraria,
			"numero_poliza_respaldo" => $numero_poliza_respaldo,
			"compania_poliza_respaldo_id" => $compania_poliza_respaldo,
			"archivo_poliza_respaldo" => $archivo_poliza_respaldo
		]);
		return $this->base_datos->id();
	}

	function actualizar_poliza($poliza_id, $cobertura_id, $cliente_id, $usuario_id, $vehiculo_id, $fecha_inicio, $fecha_fin, $vendedor_id, $total, $costo_expedicion, $iva, $plazo, $beneficiarios_funeraria,$numero_poliza_respaldo,$compania_poliza_respaldo,$archivo_poliza_respaldo)
	{
		$sql = $this->base_datos->update("polizas", [
			"cobertura_id" => $cobertura_id,
			"cliente_id" => $cliente_id,
			"usuario_id" => $usuario_id,
			"vehiculo_id" => $vehiculo_id,
			"fecha_inicio" => $fecha_inicio,
			"fecha_fin" => $fecha_fin,
			"vendedor_id" => $vendedor_id,
			"costo_expedicion" => $costo_expedicion,
			"total" => $total,
			"iva" => $iva,
			"plazo" => $plazo,
			"beneficiarios_funeraria" => $beneficiarios_funeraria,
			"numero_poliza_respaldo" => $numero_poliza_respaldo,
			"compania_poliza_respaldo_id" => $compania_poliza_respaldo,
			"archivo_poliza_respaldo" => $archivo_poliza_respaldo
		], ["idpoliza[=]" => $poliza_id]);
		return $sql->rowCount();
	}

	function agregar_poliza_detalle($poliza_id, $servicio_id, $cantidad, $cantidad_deducible, $prima_neta, $cantidad_asegurada, $cantidad_asegurada_tipo_id)
	{
		$sql = $this->base_datos->insert("poliza_detalles", [
			"poliza_id" => $poliza_id,
			"servicio_id" => $servicio_id,
			"cantidad" => $cantidad,
			"cantidad_deducible" => $cantidad_deducible,
			"prima_neta" => $prima_neta,
			"cantidad_asegurada" => $cantidad_asegurada,
			"cantidad_asegurada_tipo_id" => $cantidad_asegurada_tipo_id
		]);
		return $this->base_datos->id();
	}

	function eliminar_poliza_detalles($poliza_id)
	{
		$sql = $this->base_datos->delete("poliza_detalles", ["poliza_id[=]" => $poliza_id]);
		return $sql->rowCount();
	}

	function liquidar_poliza($poliza_id)
	{
		return $this->base_datos->update("polizas", [
			"liquidado" => "1"
		], ["idpoliza[=]" => $poliza_id]);
	}

	function actualizar_estatus($poliza_id, $estatus, $fechaEstatus, $motivoEstatus)
	{
		return $this->base_datos->update("polizas", [
			"estatus" => $estatus,
			"fecha_estatus" => $fechaEstatus,
			"motivo_estatus" => $motivoEstatus
		], ["idpoliza[=]" => $poliza_id]);
	}

	/*--------------PÓLIZAS------------------ */

	/*--------------ABONOS-------------------- */
	function agregar_abono($poliza_id, $cantidad, $fecha, $vendedor_id)
	{
		$sql = $this->base_datos->insert("abonos", [
			"poliza_id" => $poliza_id,
			"cantidad" => $cantidad,
			"fecha" => $fecha,
			"vendedor_id" => $vendedor_id
		]);
		return $this->base_datos->id();
	}

	function get_abono($abono_id)
	{
		$sql = $this->base_datos->query("SELECT a.*, DATE_FORMAT(a.fecha,'%d/%m/%Y') AS fecha_formato,
			(SELECT COUNT(abonos.idabono) FROM abonos WHERE abonos.poliza_id = a.poliza_id AND abonos.fecha < a.fecha) AS total_abonos_anteriores,
			v.nombre AS vendedor_nombre
			FROM abonos a,vendedores v
			WHERE a.idabono = '$abono_id'
			AND a.vendedor_id = v.idvendedor
			ORDER BY a.fecha DESC")->fetchAll();
		return $sql;
	}

	function get_abonos_poliza($poliza_id)
	{
		$sql = $this->base_datos->query("SELECT a.*, DATE_FORMAT(a.fecha,'%d/%m/%Y') AS fecha_formato,
			v.nombre AS vendedor_nombre
			FROM abonos a,vendedores v
			WHERE a.poliza_id = '$poliza_id'
			AND a.vendedor_id = v.idvendedor
			ORDER BY a.fecha DESC")->fetchAll();
		return $sql;
	}

	function get_total_abonos_poliza($poliza_id)
	{
		return $this->base_datos->sum("abonos", "cantidad", ["poliza_id[=]" => $poliza_id]);
	}

	function get_total_corte_abonos_vendedor($vendedor_id)
	{
		return $this->base_datos->sum("abonos", "cantidad", [
			"AND" => [
				"vendedor_id[=]" => $vendedor_id,
				"incluido_corte[=]" => "0"
			]
		]);
	}

	function actualizar_corte_abonos_vendedor($vendedor_id)
	{
		$fecha_actual = date("Y-m-d");
		//Actualiza los abonos incluyéndolos en el corte

		return $this->base_datos->update("abonos", [
			"incluido_corte" => 1,
			"fecha_corte" => $fecha_actual
		], ["vendedor_id[=]" => $vendedor_id, "incluido_corte[=]" => 0]);
	}

	function get_corte_abonos_vendedor_detalle($vendedor_id)
	{
		$sql = $this->base_datos->query("SELECT a.*, DATE_FORMAT(a.fecha,'%d/%m/%Y') AS fecha_formato, 
			DATE_FORMAT(a.created_at,'%d/%m/%Y %H:%i:%s') AS created_at_formato,p.total AS poliza_total,
			ven.nombre AS vendedor_nombre,
			veh.marca AS vehiculo_marca, veh.tipo AS vehiculo_tipo,
			clientes.nombre AS cliente_nombre,clientes.direccion_calle AS cliente_direccion_calle,
			clientes.direccion_colonia AS cliente_direccion_colonia,clientes.direccion_ciudad AS cliente_direccion_ciudad,
			clientes.direccion_estado AS cliente_direccion_estado,clientes.direccion_codigo_postal AS cliente_direccion_codigo_postal,
			clientes.direccion_numero AS cliente_direccion_numero
			FROM abonos a,vendedores ven,polizas p,clientes, vehiculos veh
			WHERE a.vendedor_id = '$vendedor_id'
			AND a.vendedor_id = ven.idvendedor
			AND a.poliza_id = p.idpoliza
			AND p.cliente_id = clientes.idcliente
			AND p.vehiculo_id = veh.idvehiculo
			AND a.incluido_corte = 0
			ORDER BY a.fecha ASC")->fetchAll();
		return $sql;
	}

	function abonos_vendedor($vendedor_id)
	{
		$sql = $this->base_datos->query("SELECT a.*,DATE_FORMAT(a.fecha,'%d/%m/%Y') AS fecha_formato,
				c.nombre AS cliente_nombre,
				ven.nombre AS vendedor_nombre,
				veh.marca AS vehiculo_marca, veh.tipo AS vehiculo_tipo,
				veh.color AS vehiculo_color, veh.placa AS vehiculo_placa,
				veh.numero_serie AS vehiculo_numero_serie, veh.anio AS vehiculo_anio
				FROM abonos a, clientes c, vendedores ven,vehiculos veh,polizas p
				WHERE a.poliza_id = p.idpoliza
				AND a.vendedor_id = ven.idvendedor
				AND p.cliente_id = c.idcliente
				AND p.vehiculo_id = veh.idvehiculo
				AND a.vendedor_id = '$vendedor_id' 
				ORDER BY a.fecha DESC")->fetchAll();
		return $sql;
	}

	function abonos_vendedor_fecha($vendedor_id, $fecha_inicial, $fecha_final)
	{
		$sql = $this->base_datos->query("SELECT a.*,DATE_FORMAT(a.fecha,'%d/%m/%Y') AS fecha_formato,
				c.nombre AS cliente_nombre,
				ven.nombre AS vendedor_nombre,
				veh.marca AS vehiculo_marca, veh.tipo AS vehiculo_tipo,
				veh.color AS vehiculo_color, veh.placa AS vehiculo_placa,
				veh.numero_serie AS vehiculo_numero_serie, veh.anio AS vehiculo_anio
				FROM abonos a, clientes c, vendedores ven,vehiculos veh,polizas p
				WHERE a.poliza_id = p.idpoliza
				AND a.vendedor_id = ven.idvendedor
				AND p.cliente_id = c.idcliente
				AND p.vehiculo_id = veh.idvehiculo
				AND a.vendedor_id = '$vendedor_id' 
				AND a.fecha >= '$fecha_inicial'
				AND a.fecha <= '$fecha_final'
				ORDER BY a.fecha DESC")->fetchAll();
		return $sql;
	}

	function abonos_cliente($cliente_id)
	{
		$sql = $this->base_datos->query("SELECT a.*,DATE_FORMAT(a.fecha,'%d/%m/%Y') AS fecha_formato,
				c.nombre AS cliente_nombre,
				ven.nombre AS vendedor_nombre,
				veh.marca AS vehiculo_marca, veh.tipo AS vehiculo_tipo,
				veh.color AS vehiculo_color, veh.placa AS vehiculo_placa,
				veh.numero_serie AS vehiculo_numero_serie, veh.anio AS vehiculo_anio
				FROM  abonos a, clientes c, vendedores ven,vehiculos veh,polizas p
				WHERE a.poliza_id = p.idpoliza
				AND a.vendedor_id = ven.idvendedor
				AND p.cliente_id = c.idcliente
				AND p.vehiculo_id = veh.idvehiculo
				AND p.cliente_id = '$cliente_id' 
				ORDER BY a.fecha DESC")->fetchAll();
		return $sql;
	}

	function abonos_cliente_poliza($poliza_id)
	{
		$sql = $this->base_datos->query("SELECT a.*,DATE_FORMAT(a.fecha,'%d/%m/%Y') AS fecha_formato,
				c.nombre AS cliente_nombre,
				ven.nombre AS vendedor_nombre,
				veh.marca AS vehiculo_marca, veh.tipo AS vehiculo_tipo,
				veh.color AS vehiculo_color, veh.placa AS vehiculo_placa,
				veh.numero_serie AS vehiculo_numero_serie, veh.anio AS vehiculo_anio
				FROM  abonos a, clientes c, vendedores ven,vehiculos veh,polizas p
				WHERE a.poliza_id = p.idpoliza
				AND a.vendedor_id = ven.idvendedor
				AND p.cliente_id = c.idcliente
				AND p.vehiculo_id = veh.idvehiculo
				AND p.idpoliza = '$poliza_id' 
				ORDER BY a.fecha DESC")->fetchAll();
		return $sql;
	}

	function eliminar_abono($abono_id)
	{
		$this->base_datos->delete("abonos", ["idabono[=]" => $abono_id]);
	}
	/*--------------ABONOS-------------------- */
	function agregar_beneficiario($nombre, $fecha_nacimiento, $calle, $numero, $colonia, $ciudad, $codigo_postal, $estado, $telefono, $telefono_alternativo, $curp, $fecha_registro)
{
    return $this->base_datos->insert("beneficiarios", [
        "nombre" => $nombre,
        "fecha_nacimiento" => $fecha_nacimiento,
        "direccion_calle" => $calle,
        "direccion_numero" => $numero,
        "direccion_colonia" => $colonia,
        "direccion_ciudad" => $ciudad,
        "direccion_codigo_postal" => $codigo_postal,
        "direccion_estado" => $estado,
        "telefono" => $telefono,
        "telefono_alternativo" => $telefono_alternativo,
        "curp" => $curp,
        "fecha_registro" => $fecha_registro
    ]);
}
}
