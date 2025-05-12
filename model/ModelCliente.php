<?php
include_once('Medoo.php');

use Medoo\Medoo;
/*Sintaxis de la Base de Datos
- Select : $this->base_datos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->base_datos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->base_datos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->base_datos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

class ModelCliente
{

	var $base_datos; //Variable para hacer la conexion a la base de datos
	var $resultado; //Variable para traer resultados de una consulta a la BD

	function __construct()
	{ //Constructor de la conexion a la BD
		$this->base_datos = new Medoo();
	}

	function get_lista_clientes()
	{
		$sql = $this->base_datos->query("SELECT c.*,v.nombre as vendedor_nombre
			FROM clientes c
			LEFT JOIN vendedores v ON c.vendedor_id = v.idvendedor
			WHERE c.estatus = '1'
			ORDER BY c.idcliente DESC")->fetchAll();
		return $sql;
	}

	function get_lista_clientes_vendedor($vendedorId)
	{
		$sql = $this->base_datos->query("SELECT c.*,v.nombre as vendedor_nombre
			FROM clientes c
			LEFT JOIN vendedores v ON c.vendedor_id = v.idvendedor
			WHERE c.estatus = '1'
			AND c.vendedor_id = '$vendedorId'
			ORDER BY c.idcliente DESC")->fetchAll();
		return $sql;
	}

	function get_cliente($id)
	{
		return $this->base_datos->get("clientes", "*", [
			"idcliente" => $id
		]);
	}

	function validar_cliente($nombre)
	{
		//Validar la existencia de un cliente para que no se repitan
		$sql = $this->base_datos->query("SELECT c.*
			FROM clientes c
			WHERE c.nombre like '%$nombre%'
			LIMIT 1")->fetchAll();
		return $sql;
	}


	function agregar_cliente($nombre, $fecha_nacimiento, $calle, $numero, $colonia, $ciudad, $codigo_postal, $estado, $telefono, $telefono_alternativo, $vendedor_id = NULL, $curp, $tipo_cliente, $fecha_registro)
{
    return $this->base_datos->insert("clientes", [
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
        "vendedor_id" => $vendedor_id,
        "curp" => $curp,
        "tipo_cliente_id" => $tipo_cliente,
        "fecha_registro" => $fecha_registro
    ]);
}

	function actualizar_cliente($cliente_id, $nombre, $fecha_nacimiento, $calle, $numero, $colonia, $ciudad, $codigo_postal, $estado, $telefono, $telefono_alternativo, $vendedor_id = NULL, $curp, $tipo_cliente, $fecha_registro)
	{
		return $this->base_datos->update("clientes", [
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
			"vendedor_id" => $vendedor_id,
			"curp" => $curp,
			"tipo_cliente_id" => $tipo_cliente,
        "fecha_registro" => $fecha_registro
		], ["idcliente[=]" => $cliente_id]);
	}

	function actualizar_estatus($cliente_id, $estatus)
	{
		return $this->base_datos->update("clientes", [
			"estatus" => $estatus,
		], ["idcliente[=]" => $cliente_id]);
	}

	function delete_cliente($id)
	{
		$sql = $this->base_datos->query("DELETE FROM clientes WHERE idcliente = '$id'")->fetchAll();
		return $sql;
	}
	public function obtener_tipos_cliente()
{
    return $this->base_datos->select("tipos_cliente", "*");
}

}
