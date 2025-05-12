<?php
include_once('Medoo.php');

use Medoo\Medoo;
/*Sintaxis de la Base de Datos
- Select : $this->base_datos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->base_datos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->base_datos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->base_datos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

class ModelVendedor
{

	var $base_datos; //Variable para hacer la conexion a la base de datos
	var $resultado; //Variable para traer resultados de una consulta a la BD

	function __construct()
	{ //Constructor de la conexion a la BD
		$this->base_datos = new Medoo();
	}

	function get_vendedores($vendedor_id = null)
	{
		$sql = $this->base_datos->query("SELECT * FROM vendedores 
		WHERE estatus = '1'
		OR (vendedores.idvendedor = '$vendedor_id' 
		AND vendedores.estatus = '0') 
		ORDER BY nombre DESC")->fetchAll();
		return $sql;
	}

	function get_vendedores_no_asignados($vendedor_id = null)
	{
		$sql = $this->base_datos->query("SELECT * FROM vendedores WHERE estatus = '1' OR (vendedores.idvendedor = '$vendedor_id' AND vendedores.estatus = '0') ")->fetchAll();
		return $sql;
	}

	function get_vendedores_nombre($nombre)
	{
		$sql = $this->base_datos->query("SELECT vendedores.*,usuarios.username
			FROM vendedores,usuarios
			WHERE usuarios.vendedor_id = vendedores.idvendedor
			AND vendedores.nombre like '%$nombre%' 
			AND vendedores.estatus ='1'")->fetchAll();
		return $sql;
	}

	function get_vendedor($id)
	{
		return $this->base_datos->get("vendedores", "*", [
			"idvendedor" => $id
		]);
	}

	function agregar_vendedor($nombre, $direccion = NULL, $telefono = NULL, $observaciones = NULL)
	{
		$this->base_datos->insert("vendedores", [
			"nombre" => $nombre,
			"direccion" => $direccion,
			"telefono" => $telefono,
			"observaciones" => $observaciones
		]);
		return $this->base_datos->id(); 
	}

	function editar_vendedor($vendedor_id, $nombre, $direccion = null, $telefono = null, $observaciones = null)
	{
		return $this->base_datos->update("vendedores", [
			"nombre" => $nombre,
			"direccion" => $direccion,
			"telefono" => $telefono,
			"observaciones" => $observaciones
		], ["idvendedor[=]" => $vendedor_id]);
	}

	function actualizar_estatus($vendedor_id, $estatus)
	{
		return $this->base_datos->update("vendedores", [
			"estatus" => $estatus,
		], ["idvendedor[=]" => $vendedor_id]);
	}
}
