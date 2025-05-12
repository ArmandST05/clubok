<?php
include_once('Medoo.php');
use Medoo\Medoo;
/*Sintaxis de la Base de Datos
- Select : $this->base_datos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->base_datos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->base_datos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->base_datos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

class ModelFormula
{

	var $base_datos; //Variable para hacer la conexion a la base de datos
	var $resultado; //Variable para traer resultados de una consulta a la BD

	function __construct()
	{ //Constructor de la conexion a la BD
		$this->base_datos = new Medoo();
	}
	/*-------------- Datos de la empresa ------------------ */
	function get_configuracion_empresa()
	{
		$sql = $this->base_datos->select("empresa_configuracion", "*");
		$sql = array_column($sql, "valor", "clave");
		return $sql;
	}
	/*-------------- Datos de la empresa ------------------ */

	/*-------------- Corte de Caja ------------------ */
	function get_caja_vendedor($vendedor_id)
	{
		$sql = $this->base_datos->query("SELECT SUM(caja)caja 
			FROM caja_detalles 
			WHERE tipo_concepto='caja' 
			AND incluido_corte = '0'
			AND vendedor_id='$vendedor_id'")->fetchAll();
		return $sql;
	}

	function get_gastos_vendedor($vendedor_id)
	{
		$sql = $this->base_datos->query("SELECT SUM(caja)gastos 
			FROM caja_detalles 
			WHERE tipo_concepto='gasto' 
			AND incluido_corte='0'
			AND vendedor_id='$vendedor_id'")->fetchAll();
		return $sql;
	}

	function add_caja_vendedor($vendedor_id, $cantidad)
	{
		$sql = $this->base_datos->query("INSERT INTO caja_detalles(caja,tipo_concepto,vendedor_id) VALUES ('$cantidad','caja','$vendedor_id')")->fetchAll();
		return $sql;
	}

	function actualizar_corte_caja_vendedor($vendedor_id)
	{
		$fecha_actual = date("Y-m-d");
		//Actualiza los detalles en caja incluyéndolos en el corte
		$sql = $this->base_datos->query("UPDATE caja_detalles SET incluido_corte = 1,fecha_corte='$fecha_actual' WHERE incluido_corte = 0 AND vendedor_id='$vendedor_id'")->fetchAll();
		return $sql;
	}

	function add_gasto_vendedor($vendedor_id, $gasto, $concepto)
	{
		$sql = $this->base_datos->query("INSERT INTO caja_detalles(caja,tipo_concepto,concepto,vendedor_id) VALUES ('$gasto','gasto','$concepto','$vendedor_id')")->fetchAll();
		return $sql;
	}

	/*-------------- Corte de Caja ------------------ */

}
