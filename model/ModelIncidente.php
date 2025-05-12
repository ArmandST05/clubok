<?php
include_once('Medoo.php');

use Medoo\Medoo;
/*Sintaxis de la Base de Datos
- Select : $this->base_datos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->base_datos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->base_datos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->base_datos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

class ModelIncidente
{

	var $base_datos; //Variable para hacer la conexion a la base de datos
	var $resultado; //Variable para traer resultados de una consulta a la BD

	function __construct()
	{ //Constructor de la conexion a la BD
		$this->base_datos = new Medoo();
	}

	function agregar_incidente($cliente_id, $vehiculo_id, $poliza_id, $fecha, $vehiculo_involucrado, $ajustador,$cantidad_gruas, $circunstancias)
	{
		return $this->base_datos->insert("incidentes", [
			"cliente_id" => $cliente_id,
			"vehiculo_id" => $vehiculo_id,
			"poliza_id" => $poliza_id,
			"fecha" => $fecha,
			"vehiculo_involucrado" => $vehiculo_involucrado,
			"ajustador" => $ajustador,
			"cantidad_gruas" => $cantidad_gruas,
			"circunstancias" => $circunstancias
		]);
	}

	function actualizar_incidente($incidente_id, $vehiculo_involucrado, $ajustador,$cantidad_gruas, $circunstancias)
	{
		return $this->base_datos->update("incidentes", [
			"vehiculo_involucrado" => $vehiculo_involucrado,
			"ajustador" => $ajustador,
			"cantidad_gruas" => $cantidad_gruas,
			"circunstancias" => $circunstancias
		], ["idincidente[=]" => $incidente_id]);
	}

	function get_incidentes_cliente($cliente_id)
	{
		$sql = $this->base_datos->query("SELECT i.*,DATE_FORMAT(i.fecha,'%d/%m/%Y') as fecha_formato,
			c.nombre AS cliente_nombre, v.marca AS vehiculo_marca,v.tipo AS vehiculo_tipo,
			prc.nombre AS poliza_respaldo_compania,p.numero_poliza_respaldo,p.archivo_poliza_respaldo
			FROM incidentes i
			INNER JOIN clientes c ON i.cliente_id = c.idcliente
			INNER JOIN vehiculos v ON i.vehiculo_id = v.idvehiculo
			INNER JOIN polizas p ON p.idpoliza = i.poliza_id
			LEFT JOIN poliza_respaldo_companias prc ON prc.idpolizarespaldocompania = p.compania_poliza_respaldo_id
			WHERE i.cliente_id = '$cliente_id' 
			ORDER BY i.fecha DESC")->fetchAll();
		return $sql;
	}

	function get_incidente($id)
	{
		$sql = $this->base_datos->query("SELECT i.*,DATE_FORMAT(i.fecha,'%d/%m/%Y') as fecha_formato,
			c.nombre AS cliente_nombre, v.marca AS vehiculo_marca,v.tipo AS vehiculo_tipo
			FROM incidentes i,clientes c, vehiculos v
			WHERE i.cliente_id = c.idcliente
			AND i.vehiculo_id = v.idvehiculo
			AND i.idincidente = '$id' 
			LIMIT 1")->fetchAll();
		return $sql;
	}
}
