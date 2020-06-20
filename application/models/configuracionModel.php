<?php
class ConfiguracionModel extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	function getHeader($id_carpeta_sel = '0'){
		$data["id_carpeta"] = $this->session->userdata('id_carpeta');
		$data["id_carpeta_sel"] = $id_carpeta_sel;
		$data["actividades_no_leidas"] = $this->actividades_no_leidas();
		$data["empresas"] = $this->usuarioModel->empresasPorUsuario($this->session->userdata('id_usuario'));
		$data["carpetas"] = $this->tableroModel->getCarpetas();
		$this->load->view('Menu/header', $data);
	}

	function actividades_no_leidas(){
		$id_usuario = $this->session->userdata('id_usuario');
		$id_empresa = $this->session->userdata('id_empresa');
		
		$sql_select = "select actividades.asunto, actividades.id_actividad
		from actividades
		inner join actividades_asociacion on actividades.id_actividad = actividades_asociacion.id_actividad
		where actividades_asociacion.id_usuario = '$id_usuario' and actividades.id_empresa = '$id_empresa' and ifnull(actividades_asociacion.leido, 0) = 0
		order by actividades.fecha desc";
		$stmt = $this->db->query($sql_select);
		$registros = $stmt->result_array();
		return $registros;
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

	function configuracion_mail($columna, $valor){
		$id_empresa = $this->session->userdata('id_empresa');
		$sql_select = "select id_configuracion from mails_config where id_empresa = ?";
		$stmt = $this->db->query($sql_select, array($id_empresa));
		$registros = $stmt->result_array();
		if(count($registros) > 0){
			$tm = $this->db->query("update mails_config set $columna = ? where id_empresa = ?", array($valor, $id_empresa));
		}else{
			$objeto["certificado_ssl"] = "1";
			$objeto["id_empresa"] = $id_empresa;
			$objeto[$columna] = $valor;
			$tm = $this->db->insert("mails_config", $objeto);
		}
	}

	function vaciarNotificaciones(){
		$id_usuario = $this->session->userdata('id_usuario');

		$tm = $this->db->query("update actividades_asociacion
		set leido = '1'
		where id_usuario = ?", array($id_usuario));
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return $error['message'];
    	}
    	return "OK";
	}

	function getConfiguracion(){
		$id_usuario = $this->session->userdata('id_usuario');
		$id_empresa = $this->session->userdata('id_empresa');
		$sql_select = 
		"select *
		from usuarios_config 
		where id_usuario = ?";
		$stmt = $this->db->query($sql_select, array($id_usuario));
		$configuracion = $stmt->result_array();
		return $configuracion;
	}

	function getConfiguracionMail(){
		$id_empresa = $this->session->userdata('id_empresa');
		$sql_select = 
		"select *
		from mails_config 
		where id_empresa = ?";
		$stmt = $this->db->query($sql_select, array($id_empresa));
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