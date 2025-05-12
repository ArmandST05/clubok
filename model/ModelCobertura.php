<?php
include_once('Medoo.php');
use Medoo\Medoo;
/*Sintaxis de la Base de Datos
- Select : $this->base_datos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->base_datos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->base_datos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->base_datos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

class ModelCobertura
{
	//INCLUYE LOS DATOS DE LAS TABLAS COBERTURAS/SERVICIOS
	var $base_datos; //Variable para hacer la conexion a la base de datos
	var $resultado; //Variable para traer resultados de una consulta a la BD

	function __construct()
	{ //Constructor de la conexion a la BD
		$this->base_datos = new Medoo();
	}

	function get_lista_coberturas()
	{
		return $this->base_datos->select("coberturas", "*");
	}

	function get_lista_servicios($cobertura_id)
	{
		return $this->base_datos->select("coberturas", "*");
	}

	function get_lista_servicios_cobertura($cobertura_id)
	{
		//Obtiene todos los servicios pero también verifica si están relacionados por defecto a las coberturas.
		$sql = $this->base_datos->query("SELECT s.*,cs.idcoberturaservicio AS cobertura_servicio_id,cs.cantidad_asegurada_tipo_id,cs.pagina_impresion,cs.cantidad,
			cs.editar_cantidad AS cobertura_servicio_editar_cantidad,
			cs.cantidad_asegurada,cs.cantidad_deducible
			FROM servicios s
			LEFT JOIN cobertura_servicios cs ON s.idservicio = cs.servicio_id 
			AND cs.cobertura_id = '$cobertura_id'
			ORDER BY cs.idcoberturaservicio DESC,s.nombre ASC")->fetchAll();
			return $sql;
	}

	
	function get_lista_servicios_cobertura_poliza($cobertura_id,$poliza_id)
	{
		//Obtiene todos los servicios pero también verifica si están relacionados por defecto a las coberturas.
		$sql = $this->base_datos->query("SELECT s.*,cs.pagina_impresion,
			cs.editar_cantidad AS cobertura_servicio_editar_cantidad,
			pd.cantidad,pd.cantidad_asegurada_tipo_id,
			pd.cantidad_asegurada,pd.cantidad_deducible,
			pd.prima_neta,
			pd.idpolizadetalle
			FROM servicios s
			LEFT JOIN cobertura_servicios cs ON s.idservicio = cs.servicio_id 
			AND cs.cobertura_id = '$cobertura_id'
			LEFT JOIN poliza_detalles pd ON s.idservicio = pd.servicio_id 
			AND pd.poliza_id = '$poliza_id'
			LEFT JOIN polizas p ON p.idpoliza = pd.poliza_id
			AND pd.poliza_id = '$poliza_id'
			ORDER BY cs.idcoberturaservicio DESC,s.nombre ASC")->fetchAll();
			return $sql;
	}


	function get_servicios_cobertura($cobertura_id)
	{
		//Obtiene los servicios predeterminados para una cobertura en específica.
		$sql = $this->base_datos->query("SELECT s.nombre FROM servicios s,cobertura_servicios cs
			WHERE cs.cobertura_id = '$cobertura_id' AND s.idservicio = cs.servicio_id
			ORDER BY s.nombre")->fetchAll();
			return $sql;
	}

	function get_porcentajes_total_cobertura($cobertura_id)
	{
		/*Obtiene el porcentaje para calcular el total por página al imprimir la póliza.
		Esto se hace porque se requiere imprimir una póliza en 2 páginas, la segunda página muestra solamente el servicio de asistencia total legal
		Pero deben de tener un diferente total, del total que se establezca para la póliza en general, cierto porcentaje se muestra en la primera página, y otro porcentaje en la segunda.
		El porcentaje para cada página se establece en esta tabla*/
		$sql = $this->base_datos->query("SELECT cstp.pagina,cstp.porcentaje 
			FROM cobertura_servicio_total_paginas cstp
			WHERE cstp.cobertura_id = '$cobertura_id'
			GROUP BY cstp.pagina
			ORDER BY cstp.pagina")->fetchAll();
			return $sql;
	}

	function get_lista_cantidad_asegurada_tipos()
	{
		$sql = $this->base_datos->query("SELECT * FROM cantidad_asegurada_tipos WHERE estatus='1' ORDER BY nombre")->fetchAll();
		return $sql;
	}
}
