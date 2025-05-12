<?php
include_once('Medoo.php');

use Medoo\Medoo;
/*Sintaxis de la Base de Datos
- Select : $this->base_datos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->base_datos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->base_datos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->base_datos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

class ModelVehiculo
{

	var $base_datos; //Variable para hacer la conexion a la base de datos
	var $resultado; //Variable para traer resultados de una consulta a la BD

	function __construct()
	{ //Constructor de la conexion a la BD
		$this->base_datos = new Medoo();
	}


	function agregar_vehiculo($cliente_id,$marca,$tipo,$color,$anio,$placa,$numero_serie)
	{
		return $this->base_datos->insert("vehiculos", [
			"cliente_id" => $cliente_id,
			"marca" => $marca,
			"tipo" => $tipo,
			"color" => $color,
			"anio" => $anio,
			"placa" => $placa,
			"numero_serie" => $numero_serie
		]);
	}

	function actualizar_vehiculo($vehiculo_id,$marca,$tipo,$color,$anio,$placa,$numero_serie)
	{
		return $this->base_datos->update("vehiculos", [
			"marca" => $marca,
			"tipo" => $tipo,
			"color" => $color,
			"anio" => $anio,
			"placa" => $placa,
			"numero_serie" => $numero_serie
		],["idvehiculo[=]" => $vehiculo_id]);
	}

	function delete_vehiculo($id)
	{
		$sql = $this->base_datos->query("DELETE FROM vehiculos WHERE idvehiculo = '$id'")->fetchAll();
		return $sql;
	}

	function actualizar_estatus($vehiculo_id,$estatus)
	{
		return $this->base_datos->update("vehiculos", [
			"estatus" => $estatus,
		],["idvehiculo[=]" => $vehiculo_id]);
	}

	function get_vehiculos_cliente($cliente_id)
	{
		return $this->base_datos->select("vehiculos", "*", [
			"cliente_id" => $cliente_id,
			"estatus" => 1
		],["ORDER" => ["idvehiculo" => "DESC"]]);
	}

	function get_vehiculo($id)
	{
		return $this->base_datos->get("vehiculos", "*", [
			"idvehiculo" => $id
		]);
	}
}
