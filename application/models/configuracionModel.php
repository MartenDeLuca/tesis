<?php
class ConfiguracionModel extends CI_Model{
	function __construct(){
		parent::__construct();
	}

	/* SQL:
	create table licencias(id_licencia int not null AUTO_INCREMENT PRIMARY KEY, licencia varchar(8), dominio varchar(1000), puerto int, usuario varchar(300), contrasena varchar(300), diccionario varchar(400));

	create table usuarios (id_usuario int not null AUTO_INCREMENT PRIMARY KEY, correo varchar(300), contrasena varchar(1000), menu_fijo varchar(2), menu_color varchar(25), id_carpeta int, id_licencia int);

	create table usuarios_config(
	id_configuracion int not null AUTO_INCREMENT PRIMARY KEY, id_usuario int, cantidad_decimales int, separador_decimales varchar(1), separador_miles varchar(1), formato_negativo bit, ubicacion_unidad varchar(1), unidad varchar(5), tamano_texto varchar(1), alineacion_texto varchar(1), formato_fecha varchar(15), separador_fecha varchar(1), formato_hora varchar(10), tamano_fecha varchar(1), alineacion_fecha varchar(1)
	);

	create table carpetas (id_carpeta int not null AUTO_INCREMENT PRIMARY KEY, nombre varchar(100), id_padre int, es_padre bit, id_usuario int, id_licencia int);

	create table reglas(id_regla int not null AUTO_INCREMENT PRIMARY KEY, asunto varchar(100), intervalo int, fecha datetime, accion varchar(100), estado varchar(15), id_usuario int, id_licencia int);

	create table reglas_consulta(id_regla_consulta int not null AUTO_INCREMENT PRIMARY KEY, id_regla int, consulta varchar(8000));

	create table reglas_accion(id_regla_accion int not null AUTO_INCREMENT PRIMARY KEY, id_regla int, correo varchar(1000), nombre varchar(1000), contra varchar(1000), puerto int, host varchar(200), destinatario_consulta varchar(1000), destanitario_fijo varchar(1000), asunto_mail varchar(1000), tipo_alerta varchar(50));

	create table reglas_adjunto(id_regla_adjunto int not null AUTO_INCREMENT PRIMARY KEY, id_regla int, adjunto varchar(8000));
	
	create table reglas_contenido(id_regla_contenido int not null AUTO_INCREMENT PRIMARY KEY, id_regla int, relacionado varchar(20), consulta varchar(8000));

	create table alertas(id_alerta int not null AUTO_INCREMENT PRIMARY KEY, descripcion varchar(8000), tipo varchar(50), fecha datetime, leido bit, id_regla int);

	create table mails(id_mail int not null AUTO_INCREMENT PRIMARY KEY, id_regla int, uid_mail varchar(1000), fecha_leido datetime);
	*/
	
	function getHeader($id_carpeta_sel = '0'){
		$data["id_carpeta"] = $this->session->userdata('id_carpeta');
		$data["id_carpeta_sel"] = $id_carpeta_sel;
		$data["alertas"] = array();
		$data["carpetas"] = $this->tableroModel->getCarpetas();
		$this->load->view('Menu/header', $data);
	}

	function cambiar_menu($columna, $valor){
		$id_usuario = $this->session->userdata('id_usuario');		
		$tm = $this->db->query("update usuarios set $columna = ? where id_usuario = ?", array($valor, $id_usuario));
		$this->session->set_userdata($columna, $valor);		
	}

	function cambiar_configuracion($columna, $valor){
		$id_usuario = $this->session->userdata('id_usuario');
		$sql_select = "select id_configuracion from usuarios_config where id_usuario = ?";
		$stmt = $this->db->query($sql_select, array($id_usuario));
		$registros = $stmt->result_array();
		if(count($registros) > 0){
			$tm = $this->db->query("update usuarios_config set $columna = ? where id_usuario = ?", array($valor, $id_usuario));
		}else{
			$objeto["cantidad_decimales"] = "2";
			$objeto["separador_decimales"] = ",";
			$objeto["formato_negativo"] = "1";
			$objeto["ubicacion_unidad"] = "D";
			$objeto["unidad"] = "$";
			$objeto["tamano_texto"] = "G";
			$objeto["alineacion_texto"] = "I";
			$objeto["tamano_fecha"] = "G";
			$objeto["alineacion_fecha"] = "I";
			$objeto["formato_fecha"] = "d m Y";
			$objeto["separador_fecha"] = "/";
			$objeto["formato_hora"] = "H:i:s";
			$objeto["id_usuario"] = $id_usuario;
			$objeto[$columna] = $valor;
			$tm = $this->db->insert("usuarios_config", $objeto);
		}
	}

	function getConfiguracion(){
		$id_usuario = $this->session->userdata('id_usuario');
		$sql_select = 
		"select *
		from usuarios_config 
		where id_usuario = ?";
		$stmt = $this->db->query($sql_select, array($id_usuario));
		$configuracion = $stmt->result_array();
		return $configuracion;
	}

	/*FUNCIONES GENERICAS DE CONFIGURACION*/
	function formato_fecha($fecha){
		$fecha = date_create($fecha);
		
		$formato_fecha = "d m Y";
		$separador_fecha = "/";
		$formato_fecha = str_replace(" ", $separador_fecha, $formato_fecha);
		$formato_hora = "H:i:s";	
		$formato = trim($formato_fecha." ".$formato_hora);	
		
		return date_format($fecha, $formato);
	}

	function formato_decimal($decimal){
		$cantidad_decimales = "2";
		$separador_decimales = ",";
		$separador_miles = ".";
		
		return number_format($decimal, $cantidad_decimales, $separador_decimales, $separador_miles);
	}
}
?>