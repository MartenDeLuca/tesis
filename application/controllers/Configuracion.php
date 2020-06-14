<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Configuracion extends CI_Controller {

	public function index(){
		if ($this->session->userdata('id_usuario')){
			$data["configuracion"] = $this->configuracionModel->getConfiguracion();
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
}
