<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Regla extends CI_Controller {

	/*REGLAS*/
	public function reglas(){
		if ($this->session->userdata('id_usuario')){
			$this->configuracionModel->getHeader();
			$data["reglas"] = $this->reglaModel->getReglas();
			$this->load->view('regla/reglas_negocio', $data);
			$this->load->view('menu/footer');
		}else{
			$this->session->set_flashdata('url',current_url());
			redirect(base_url('login'));
		}
	}

	public function agregar_regla(){
		if ($this->session->userdata('id_usuario')){
			$data = array("id_regla" => "", "asunto" => "", "intervalo" => "", "accion" => "Correo y Alerta", "estado" => "", "tipo_consulta" => "", "consulta" => "", 
			"correo" => "", "contrasena" => "", "puerto" => "", "host" => "", "destinatarios_columnas" => "",
			"destinatarios_fijos" => "", "atributos" => "", "asunto_mail" => "", "contenido_mail" => "", 
			"adjuntos" => array(), "tipo_alerta" => "", "descripcion_alerta" => "");
			$data["array_usuarios"] = $this->usuarioModel->getUsuariosSelectSoloCorreo();
			//$data["consulta_externa"] = $this->reglaModel->getConsultaExterna();
			$data["instancia"] = "Agregar";
			$this->configuracionModel->getHeader();
			$this->load->view('regla/formulario_regla', $data);
			$this->load->view('menu/footer');
		}else{
			$this->session->set_flashdata('url',current_url());
			redirect(base_url('login'));
		}
	}

	public function modificar_regla(){
		if ($this->session->userdata('id_usuario')){
			$id_regla = isset($_GET["id"])?$_GET["id"]:"";
			if(!empty($id_regla)){
				$data = $this->reglaModel->getReglaPorId($id_regla);
				if(count($data) > 0){
					$data["array_usuarios"] = $this->usuarioModel->getUsuariosSelectSoloCorreo();
					//$data["consulta_externa"] = $this->reglaModel->getConsultaExterna();
					$data["instancia"] = "Modificar";
					$this->configuracionModel->getHeader();					
					$this->load->view('regla/formulario_regla', $data);
					$this->load->view('menu/footer');
				}else{

				}
			}				
		}else{
			$this->session->set_flashdata('url',current_url());
			redirect(base_url('login'));
		}
	}

	public function detalle_regla(){
		if ($this->session->userdata('id_usuario')){			
			$id_regla = isset($_GET["id"])?$_GET["id"]:"";
			if(!empty($id_regla)){
				$data = $this->reglaModel->getReglasPorIdRegla($id_regla);
				if(count($data) > 0){
					$this->configuracionModel->getHeader();
					$this->load->view('regla/detalle_regla', $data);
					$this->load->view('menu/footer');
				}else{

				}
			}
		}else{
			$this->session->set_flashdata('url',current_url());
			redirect(base_url('login'));
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

	public function regla_bd_modificar(){
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

	public function regla_eliminar(){
		if ($this->input->is_ajax_request()) {
			if ($this->session->userdata('id_usuario')){
				$id_regla = $_POST['id_regla'];
				$respuesta = $this->reglaModel->regla_eliminar($id_regla);
				echo json_encode($respuesta);
			}
		}
	}

	public function regla_bd(){
		if ($this->input->is_ajax_request()) {
			if ($this->session->userdata('id_usuario')){
				//general
				$instancia = $_POST['instancia'];
				$id_regla = $_POST['id_regla'];

				//encabezado
				$asunto = $_POST['asunto'];
				$intervalo = $_POST['intervalo'];
				$accion = $_POST['accion'];
				$estado = $_POST['estado'];
				
				//consulta
				$consulta = $_POST['consulta'];

				//correo
				$correo = $_POST['correo'];
				$contrasena = $_POST['contrasena'];
				$this->load->library('encrypt');
				$contrasena = $this->encrypt->encode($contrasena);
				$puerto = $_POST['puerto'];
				$host = $_POST['host'];
				$certificado_ssl = $_POST['certificado_ssl'];
				$host = $_POST['host'];
				$destinatario_fijos = "";
				if(isset($_POST['destinatario_fijos'])){
					$destinatario_fijos_post = $_POST['destinatario_fijos'];
					foreach($destinatario_fijos_post as $selected) {
			            $destinatario_fijos .= $selected.";";
			        }
			        $destinatario_fijos = substr($destinatario_fijos, 0, -1);
			    }
		        
			    $destinatario_columnas = "";
			    if(isset($_POST['destinatario_columnas'])){
					$destinatario_columnas_post = $_POST['destinatario_columnas'];
					foreach($destinatario_columnas_post as $selected) {
			            $destinatario_columnas .= $selected.";";
			        }
			        $destinatario_columnas = substr($destinatario_columnas, 0, -1);
			    }
				$asunto_mail = $_POST['asunto_mail'];
				$contenido_mail = $_POST['contenido_mail'];

				//alerta
				$tipo_alerta = $_POST['tipo_alerta'];
				$descripcion_alerta = $_POST['descripcion_alerta'];					

				$respuesta = $this->reglaModel->regla_bd(
				$instancia,	$id_regla,
				$asunto, $intervalo, $accion, $estado, 
				$consulta, 
				$correo, $contrasena, $puerto, $host, $certificado_ssl, $destinatario_fijos, $destinatario_columnas, $asunto_mail, $contenido_mail, 
				$tipo_alerta, $descripcion_alerta
				);
				if($respuesta[0] == "ok"){
					if(isset($_POST['archivos_subidos'])){
						$archivos_subidos = $_POST['archivos_subidos'];	
					}else{
						$archivos_subidos = array();
					}
					if(isset($_POST['archivos_eliminados'])){
						$archivos_eliminados = $_POST['archivos_eliminados'];	
					}else{
						$archivos_eliminados = array();
					}

					if (isset($_FILES['Adjunto']['name'])){
						$archivos_nuevos = $_FILES;
					}else{
						$archivos_nuevos = array();
					}

					$id_regla = $respuesta[1];
					$respuesta = $this->regla_bd_archivo($archivos_nuevos, $archivos_subidos, $archivos_eliminados, $id_regla);
				}

				echo json_encode($respuesta);
			}
		}
	}

	public function regla_bd_archivo($archivos_nuevos, $archivos_subidos, $archivos_eliminados, $id_regla){	    
		$carpeta = $_SERVER['DOCUMENT_ROOT'].'/'.$this->config->item('carpeta_principal').'/Plugin/archivos/'.$id_regla;

		//archivos nuevos
		if(isset($archivos_nuevos['Adjunto']['name'])){
		    $cantidad = count($archivos_nuevos['Adjunto']['name']);
	       	if (!file_exists($carpeta)) {
			    mkdir($carpeta, 0777, true);
			}
			$config = array();
		    $config['upload_path'] = 'plugin/archivos/'.$id_regla;
		    $config['allowed_types'] = '*';
		    $config['max_size']      = '0';
		    $config['overwrite']     = FALSE;
		    $this->load->library('upload');

		    for($j = 0; $j < $cantidad; $j++){
		    	if(!empty($archivos_nuevos['Adjunto']['name'][$j])){
					$nombreArchivo = $archivos_nuevos['Adjunto']['name'][$j];
					$_FILES['userfile']['name'] = $nombreArchivo;
			        $_FILES['userfile']['type'] = $archivos_nuevos['Adjunto']['type'][$j];
			        $_FILES['userfile']['tmp_name'] = $archivos_nuevos['Adjunto']['tmp_name'][$j];
			        $_FILES['userfile']['error'] = $archivos_nuevos['Adjunto']['error'][$j];
			        $_FILES['userfile']['size'] = $archivos_nuevos['Adjunto']['size'][$j];    
			        $array_archivos[] = $nombreArchivo; 
			        $this->upload->initialize($config);
			        
			        if (!$this->upload->do_upload()) {		           		
						$data['uploadError'] = $this->upload->display_errors();
						echo $this->upload->display_errors();
						return;
					}
					array_push($archivos_subidos, $nombreArchivo);
				}
		    }
		}

		//archivos viejos
		foreach ($archivos_eliminados as $fila) {
			$archivo_eliminar = $carpeta."/".$fila;
			if (file_exists($archivo_eliminar)) {
			    unlink($archivo_eliminar);
			}
		}
		return $this->reglaModel->regla_bd_archivo($archivos_subidos, $id_regla);
	}

	public function reglas_a_ejecutar(){
	    $registros = $this->reglaModel->reglas_a_ejecutar();
	    log_message("error", json_encode($registros));
	    foreach($registros as $fila) {
	    	$id_regla = $fila["id_regla"];
	    	$this->reglaModel->ejecucion_regla_negocio($id_regla);
	    }
	    $datos = array();
	    $datos['mensaje'] = 'OK';
	    echo json_encode($datos);
	}

	/*ALERTAS*/
	public function alertas(){
		if ($this->session->userdata('id_usuario')){
			$this->configuracionModel->getHeader();
			$data["alertas"] = $this->reglaModel->getAlertas();
			$this->load->view('alertas/alertas', $data);
			$this->load->view('menu/footer');
		}else{
			$this->session->set_flashdata('url',current_url());
			redirect(base_url('login'));
		}
	}

	//VIENE DE AFUERA
	public function verificar_consulta(){
		if ($this->input->is_ajax_request()) {
			if ($this->session->userdata('id_usuario')){
				$consulta = $_POST['consulta'];
				echo $this->reglaModel->verificar_consulta($consulta, "1");
			}
		}
	}
}