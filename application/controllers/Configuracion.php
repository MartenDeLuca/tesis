<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Configuracion extends CI_Controller {

	public function index(){
		if ($this->session->userdata('id_usuario')){
			$data["configuracion"] = $this->configuracionModel->getConfiguracion();
			$data["mails"] = $this->configuracionModel->getConfiguracionMail();
			$this->configuracionModel->getHeader();
			$this->load->view('configuracion/configuracion', $data);
			$this->load->view('menu/footer');
		}else{
			$this->session->set_userdata("id_usuario", "1");
			$this->session->set_flashdata('url',current_url());
			redirect(base_url('login'));
		}
	}

	public function cambiar_menu(){
		if ($this->input->is_ajax_request()) {
			if ($this->session->userdata('id_usuario')){
				$columna = $_POST['columna'];
				$valor = $_POST['valor'];
				echo $this->configuracionModel->cambiar_menu($columna, $valor);
			}
		}
	}

	public function cambiar_configuracion(){
		if ($this->input->is_ajax_request()) {
			if ($this->session->userdata('id_usuario')){
				$columna = $_POST['columna'];
				$valor = $_POST['valor'];	
				echo $this->configuracionModel->cambiar_configuracion($columna, $valor);
			}
		}
	}

	public function configuracion_mail(){
		if ($this->input->is_ajax_request()) {
			if ($this->session->userdata('id_usuario')){
				$columna = $_POST['columna'];
				$valor = $_POST['valor'];
				$esContrasena = $_POST['esContrasena'];
				if($esContrasena == "1"){
					$this->load->library('encrypt');
					$valor = $this->encrypt->encode($valor);
				}
				echo $this->configuracionModel->configuracion_mail($columna, $valor);
			}
		}
	}

	public function vaciarNotificaciones(){
		if ($this->input->is_ajax_request()) {
			if ($this->session->userdata('id_usuario')){
				echo $this->configuracionModel->vaciarNotificaciones();
			}
		}
	}

	public function validarDatosCorreo(){
		if ($this->input->is_ajax_request()) {
			if ($this->session->userdata('id_usuario')){
				$correo = $_POST['correo'];
				$contrasena = $_POST['contrasena'];
				$puerto = $_POST['puerto'];
				$host = $_POST['host'];
				$certificado_ssl = $_POST['certificado_ssl'];
				$respuesta = $this->reglaModel->validarDatosCorreo($correo, $contrasena, $puerto, $host, $certificado_ssl);
				echo $respuesta;
			}
		}
	}	
}
