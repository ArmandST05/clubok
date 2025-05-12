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


function agregar_vehiculo($cliente_id, $marca, $submarca, $tipo, $color, $anio, $placa, $numero_serie, $estado, $valor)
{
    return $this->base_datos->insert("vehiculos", [
        "cliente_id"    => $cliente_id,
        "marca"         => $marca,
        "submarca"      => $submarca,
        "tipo_id"          => $tipo,
        "color"         => $color,
        "anio"          => $anio,
        "placa"         => $placa,
        "numero_serie"  => $numero_serie,
        "estado_id"        => $estado,
        "valor"         => $valor
    ]);
}


function actualizar_vehiculo($vehiculo_id, $marca, $submarca, $tipo, $estado, $color, $anio, $placa, $numero_serie, $valor)
{
    return $this->base_datos->update("vehiculos", [
        "marca"         => $marca,
        "submarca"      => $submarca,
        "tipo_id"          => $tipo,
        "estado_id"        => $estado,
        "color"         => $color,
        "anio"          => $anio,
        "placa"         => $placa,
        "numero_serie"  => $numero_serie,
        "valor"         => $valor
    ], [
        "idvehiculo[=]" => $vehiculo_id
    ]);
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
    $sql = "
        SELECT 
            v.idvehiculo,
            v.placa,
            v.marca,
            v.color,
			v.anio,
            v.numero_serie,
            v.estado_id,
            v.tipo_id,
            v.estatus,
            e.estado AS estadonombre,
            t.nombre AS tiponombre
        FROM vehiculos v
        INNER JOIN estados e ON v.estado_id = e.id
        INNER JOIN tipos_vehiculos t ON v.tipo_id = t.id
        WHERE v.cliente_id = :cliente_id AND v.estatus = 1
        ORDER BY v.idvehiculo DESC
    ";

    $stmt = $this->base_datos->pdo->prepare($sql);
    $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


	function get_vehiculo($id)
	{
		return $this->base_datos->get("vehiculos", "*", [
			"idvehiculo" => $id
		]);
	}
function getAll(){
    $stmt = $this->base_datos->query("SELECT * FROM vehiculos");
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Esto es lo que necesitas
}
function obtenerEstados(){
	$stmt = $this->base_datos->query("SELECT * FROM estados");
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Esto es lo que necesitas
}
function obtenerTipos(){
	$stmt = $this->base_datos->query("SELECT * FROM tipos_vehiculos");
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Esto es lo que necesitas
}
}
