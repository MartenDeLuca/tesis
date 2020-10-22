<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plantilla extends CI_Controller {

	/*REGLAS*/
	public function index(){
		if ($this->session->userdata('id_usuario')){
			$this->configuracionModel->getHeader();
			$data["plantillas"] = $this->plantillaModel->getPlantillas("", "");
			$this->load->view('plantilla/plantillas', $data);
			$this->load->view('menu/footer');
		}else{
			$this->session->set_flashdata('url',current_url());
			redirect(base_url('login'));
		}
	}

	public function buscarPlantilla(){
		if ($this->session->userdata('id_usuario')){
			$consulta = isset($_POST["consulta"])?$_POST["consulta"]:"";
			echo json_encode($this->plantillaModel->getPlantillas("1", " and asunto like '%$consulta%'"));
		}
	}

	public function seleccionarPlantilla(){
		if ($this->session->userdata('id_usuario')){
			$id = isset($_POST["id"])?$_POST["id"]:"";
			$cliente = isset($_POST["cliente"])?$_POST["cliente"]:"";
			$tipo = isset($_POST["tipo"])?$_POST["tipo"]:"";
			echo json_encode($this->plantillaModel->seleccionarPlantilla($id, $cliente, $tipo));
		}
	}	

	public function agregar_plantilla(){
		if ($this->session->userdata('id_usuario')){
			$data = array("id_plantilla" => "", "asunto" => "", "asunto_mail" => "", "contenido_mail" => "");
			$data["instancia"] = "Agregar";
			$this->configuracionModel->getHeader();
			$this->load->view('plantilla/formulario_plantilla', $data);
			$this->load->view('menu/footer');
		}else{
			$this->session->set_flashdata('url',current_url());
			redirect(base_url('login'));
		}
	}

	public function modificar_plantilla(){
		if ($this->session->userdata('id_usuario')){
			$id_plantilla = isset($_GET["id"])?$_GET["id"]:"";
			if(!empty($id_plantilla)){
				$data = $this->plantillaModel->getPlantillaPorId($id_plantilla);
				if(count($data) > 0){
					$data["instancia"] = "Modificar";
					$this->configuracionModel->getHeader();
					$this->load->view('plantilla/formulario_plantilla', $data);
					$this->load->view('menu/footer');
				}else{

				}
			}
		}else{
			$this->session->set_flashdata('url',current_url());
			redirect(base_url('login'));
		}
	}

	public function plantilla_eliminar(){
		if ($this->input->is_ajax_request()) {
			if ($this->session->userdata('id_usuario')){
				$id_plantilla = $_POST['id_plantilla'];
				$respuesta = $this->plantillaModel->plantilla_eliminar($id_plantilla);
				echo json_encode($respuesta);
			}
		}
	}

	public function plantilla_bd(){
		if ($this->input->is_ajax_request()) {
			if ($this->session->userdata('id_usuario')){
				$asunto = $_POST['asunto'];
				$asunto_mail = $_POST['asunto_mail'];
				$contenido_mail = $_POST['contenido_mail'];
				$instancia = $_POST['instancia'];
				$id_plantilla = $_POST['id_plantilla'];

				$respuesta = $this->plantillaModel->plantilla_bd($instancia, $id_plantilla, $asunto, $asunto_mail, $contenido_mail);
				echo json_encode($respuesta);
			}
		}
	}	
}