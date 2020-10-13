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
}
?>