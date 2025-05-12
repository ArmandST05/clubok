<?php
include_once('Medoo.php');
use Medoo\Medoo;
/*Sintaxis de la Base de Datos
- Select : $this->base_datos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->base_datos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->base_datos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->base_datos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/
class ModelLogin{
	var $base_datos; //Variable para hacer la conexion a la base de datos
	var $resultado_datos; //Variable para traer resultados de una consulta a la BD

	function __construct() { //Constructor de la conexion a la BD
		$this->base_datos = new Medoo();
	}

	function valida_login($user,$pass){
	    $sql= $this->base_datos->query("SELECT * FROM usuarios WHERE username='$user' AND password='$pass' AND estatus = '1'")->fetchAll();
		return $sql;
	}

	function verifica_usuario($user){
		$sql= $this->base_datos->query("SELECT * FROM usuarios WHERE username='$user' AND estatus = '1'")->fetchAll();
		return $sql;
	}

	function agregar_usuario($tipo_usuario,$vendedor_id = NULL,$nombre_usuario,$contrasena,$nombre = NULL){
		$sql =  $this->base_datos->insert("usuarios",[
			"tipo_usuario_id" => $tipo_usuario,
			"vendedor_id" => $vendedor_id,
			"username" => $nombre_usuario,
			"password" => $contrasena,
			"nombre" => $nombre,
		]);
		return $this->base_datos->id();
	}

	function desactivar_usuario($usuario_id){
		return $this->base_datos->update("usuarios",[
			"estatus" => '0',
		],["iduser[=]" => $usuario_id]);
	}

	function actualizar_estatus_usuario_vendedor($vendedor_id,$estatus){
		return $this->base_datos->update("usuarios",[
			"estatus" => $estatus,
		],["vendedor_id[=]" => $vendedor_id]);
	}

	function editar_nombre_usuario_vendedor($vendedor_id,$nombre_usuario,$nombre){
		$sql= $this->base_datos->query("UPDATE usuarios SET username = '$nombre_usuario',nombre='$nombre' WHERE vendedor_id = '$vendedor_id'")->fetchAll();
	    return $sql;
	}

	function editar_vendedor_contrasena($vendedor_id,$nombre_usuario,$nombre,$contrasena){
		$sql= $this->base_datos->query("UPDATE usuarios SET username = '$nombre_usuario',nombre='$nombre',password = '$contrasena' WHERE vendedor_id = '$vendedor_id'")->fetchAll();
	    return $sql;
	}

	function editar_usuario($tipo_usuario,$vendedor_id = NULL,$nombre_usuario,$contrasena,$nombre){
		$sql= $this->base_datos->query("UPDATE usuarios SET tipo_usuario_id='$tipo_usuario',vendedor_id='$vendedor_id',username='$nombre_usuario',password='$contrasena',nombre='$nombre' WHERE vendedor_id='$vendedor_id'")->fetchAll();
	    return $sql;
	}
}

?>