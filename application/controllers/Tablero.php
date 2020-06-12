<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tablero extends CI_Controller {
	public function index(){
		if ($this->session->userdata('id_usuario')){
			$id_carpeta = isset($_GET["id"])?$_GET["id"]:$this->session->userdata('id_carpeta');
			if($id_carpeta){
				$carpeta = $this->tableroModel->getCarpetaPorId($id_carpeta);
				if(count($carpeta) == 0){
					$carpeta = $this->tableroModel->getCarpetaPorId($this->session->userdata('id_carpeta'));
				}
				if(count($carpeta) > 0){
					$data["carpeta"] = $carpeta[0];
					$data["array_usuarios"] = $this->usuarioModel->getUsuariosSelect();
					$this->configuracionModel->getHeader($id_carpeta);
					$this->load->view('tablero/tablero', $data);
					$this->load->view('menu/footer');
				}else{

				}
			}
		}else{
			$this->session->set_flashdata('url',current_url());
			redirect(base_url('login'));
		}	
	}

	public function crear_carpeta(){
		if ($this->input->is_ajax_request()) {
			if ($this->session->userdata('id_usuario')){
				$carpeta = $_POST['carpeta'];
				$id_padre = $_POST['id_padre'];
				echo $this->tableroModel->crear_carpeta($carpeta, $id_padre);
			}
		}
	}

	public function renombrar_carpeta(){
		if ($this->input->is_ajax_request()) {
			if ($this->session->userdata('id_usuario')){
				$carpeta = $_POST['carpeta'];
				$id_carpeta = $_POST['id_carpeta'];
				echo $this->tableroModel->renombrar_carpeta($carpeta, $id_carpeta);
			}
		}
	}

	public function eliminar_carpeta(){
		if ($this->input->is_ajax_request()) {
			if ($this->session->userdata('id_usuario')){				
				$id_carpeta = $_POST['id_carpeta'];
				$eliminar_hijos = $_POST['eliminar_hijos'];
				echo $this->tableroModel->eliminar_carpeta($id_carpeta, $eliminar_hijos);
			}
		}
	}	

	public function mover_carpeta(){
		if ($this->input->is_ajax_request()) {
			if ($this->session->userdata('id_usuario')){
				$id_padre = $_POST['id_padre'];
				$id_carpeta = $_POST['id_carpeta'];
				echo $this->tableroModel->mover_carpeta($id_padre, $id_carpeta);
			}
		}
	}

	public function compartir_carpeta(){
		if ($this->input->is_ajax_request()) {
			if ($this->session->userdata('id_usuario')){
				$array = json_decode(isset($_POST["array"])?$_POST["array"]:"", true);
				$id_carpeta = $_POST['id_carpeta'];
				echo $this->tableroModel->compartir_carpeta($array, $id_carpeta);
			}
		}
	}	
}