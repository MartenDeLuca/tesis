<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Seguimiento extends CI_Controller {
	public function index(){
		if ($this->session->userdata('id_usuario')){
			$id_empresa = $this->session->userdata('id_empresa');

			//tipo de grilla
			$tipo = isset($_GET["tipo"])?$_GET["tipo"]:"actividades";
			if($tipo != "mails"){				
				$tipo = "actividades";
				$where = "where id_empresa = '$id_empresa'";
			}else{				
				$where = "where id_empresa = '$id_empresa'";
			}

			//busqueda guardada
			$busqueda_guardada = isset($_GET["guar"])?$_GET["guar"]:"";
			if(is_numeric($busqueda_guardada)){	
				$registros_guardados = $this->seguimientoModel->registros_guardados($tipo, " and id_busqueda = '$busqueda_guardada'");
				$columna = "";
				$busqueda = "";
				$tipo_busqueda = "";
				foreach ($registros_guardados as $fila) {
					$columna = $fila["columna"];
					$busqueda = $fila["busqueda"];
					$tipo_busqueda = $fila["tipo_busqueda"];
				}
				if(!empty($columna) && !empty($busqueda) && !empty($tipo_busqueda)){
					$this->seguimientoModel->guardar_busqueda($columna, $tipo_busqueda, $busqueda, $tipo, '');
				}
			}

			//vaciar
			$vaciar = isset($_GET["vaciar"])?$_GET["vaciar"]:"";
			if($vaciar == "1"){
				$columna = "";
				$busqueda = "";
				$tipo_busqueda = "";
				$this->seguimientoModel->vaciar_busqueda($tipo);
			}else{
				//busqueda generada en el momento
				$columna = isset($_GET["col"])?$_GET["col"]:"";
				$busqueda = isset($_GET["bus"])?$_GET["bus"]:"";
				$tipo_busqueda = isset($_GET["ti_bu"])?$_GET["ti_bu"]:"";
				//busqueda guardada
				$nombreBusqueda = isset($_GET["no_bu"])?$_GET["no_bu"]:"";
				if(!empty($columna) && !empty($busqueda) && !empty($tipo_busqueda)){
					$this->seguimientoModel->guardar_busqueda($columna, $tipo_busqueda, $busqueda, $tipo, $nombreBusqueda);
				}

				$registros_guardados = $this->seguimientoModel->registros_guardados($tipo, '');
				foreach ($registros_guardados as $fila) {
					$columna = $fila["columna"];
					$busqueda = $fila["busqueda"];
					$tipo_busqueda = $fila["tipo_busqueda"];
				}
			}

			$array = $this->seguimientoModel->manejoWhere($tipo, $columna, $busqueda, $tipo_busqueda, $where);
			
			$data["array_valores"] = $array;
			
			$data["tamano"] = count($this->config->item($tipo.'_array_columna'));
			$data["columna"] = $this->config->item($tipo.'_array_columna');
			$data["sql_columna"] = $this->config->item($tipo.'_sql_columna');
			$data["key"] = $this->config->item($tipo.'_key');
			$data["tipo_columna"] = $this->config->item($tipo.'_tipo_columna');
			
			$this->session->set_userdata($tipo.'_where_sql', $array["where"]);

			$data["datos"] = $this->seguimientoModel->getSeguimiento($tipo, $array["where"]);			
			$data["instancia"] = $tipo;

			//VISTA
			$this->configuracionModel->getHeader();
			$this->load->view('seguimiento/seguimiento', $data);
			$this->load->view('menu/footer');
		}else{
			$this->session->set_flashdata('url',current_url());
			redirect(base_url('login'));
		}
	}

	public function getBusquedasGuardadas(){
		$tipo = isset($_POST["tipo"])?$_POST["tipo"]:"actividades";
		$result = $this->seguimientoModel->getBusquedasGuardadas($tipo);
		echo $result;
	}

	public function eliminarBusquedaGuardada(){
		$id = isset($_POST["id"])?$_POST["id"]:"";
		$result = $this->seguimientoModel->eliminarBusquedaGuardada($id);
		echo $result;
	}

	public function favoritoBusquedaGuardada(){
		$id = isset($_POST["id"])?$_POST["id"]:"";
		$favorito = isset($_POST["favorito"])?$_POST["favorito"]:"";
		$result = $this->seguimientoModel->favoritoBusquedaGuardada($id, $favorito);
		echo $result;
	}

	public function exportar(){
		$tipo = isset($_GET["tipo"])?$_GET["tipo"]:"";
		$result = $this->seguimientoModel->exportar($tipo);
		echo $result;
	}

	public function agregar_actividad(){
		if ($this->session->userdata('id_usuario')){
			
			$data = array(
			"id_actividad" => "", 
			"asunto" => "", "fecha" => date("Y-m-d"), "estado" => "Pendiente", "descripcion" => "", 
			"cliente" => "", "id_cliente" => "", "cod_cliente" => "", "telefono" => "", "dias_reclamo" => "",
			"contacto" => "", "id_contacto" => "", "celular" => "", "correo" => "", 
			"comprobantes" => array(),
			"proximo_contacto" => "", "horario_retiro" => "", "direccion" => "", "asignados" => array());
			$array_usuarios = $this->reglaModel->getUsuariosParaSelect();
			$opciones_usuario = "<option></option>";
			foreach ($array_usuarios as $fila_usuario) {
				$opciones_usuario .= "<option value='".$fila_usuario["id_usuario"]."'>".$fila_usuario["correo"]."</option>";
			}
			$data["array_asignados"] = $opciones_usuario;
			$data["instancia"] = "Agregar";
			$this->configuracionModel->getHeader();
			$this->load->view('actividad/formulario_actividad', $data);
			$this->load->view('menu/footer');
		}else{
			$this->session->set_flashdata('url',current_url());
			redirect(base_url('login'));
		}
	}

	public function modificar_actividad(){
		if ($this->session->userdata('id_usuario')){
			$id = isset($_GET["id"])?$_GET["id"]:"";
			if(!empty($id)){
				$data = $this->seguimientoModel->getActividadPorId($id);
				if(count($data) > 0){
					$array_usuarios = $this->reglaModel->getUsuariosParaSelect();
					$opciones_usuario = "<option></option>";
					foreach ($array_usuarios as $fila_usuario) {
						$opciones_usuario .= "<option value='".$fila_usuario["id_usuario"]."'>".$fila_usuario["correo"]."</option>";
					}
					$data["array_asignados"] = $opciones_usuario;					
					$data["instancia"] = "Modificar";
					$this->configuracionModel->getHeader();					
					$this->load->view('actividad/formulario_actividad', $data);
					$this->load->view('menu/footer');
				}else{

				}
			}				
		}else{
			$this->session->set_flashdata('url',current_url());
			redirect(base_url('login'));
		}
	}

	public function actividad_bd(){
		if ($this->input->is_ajax_request()) {
			if ($this->session->userdata('id_usuario')){
				//general
				$instancia = $_POST['instancia'];
				$id_actividad = $_POST['id_actividad'];
				//encabezado
				$asunto = $_POST['asunto'];
				$fecha = $_POST['fecha'];
				$estado = $_POST['estado'];

				$id_cliente = $_POST['id_cliente'];
				$cliente = $_POST['cliente'];
				$telefono = $_POST['telefono'];
				$dias_reclamo = $_POST['dias_reclamo'];

				$id_contacto = $_POST['id_contacto'];
				$contacto = $_POST['contacto'];
				$celular = $_POST['celular'];
				$correo = $_POST['correo'];				

				$descripcion = $_POST['descripcion'];

				$asociacion = json_decode($_POST['asociacion'], true);
				
				$comprobantes = json_decode($_POST['comprobantes'], true);

				$proximo_contacto = $_POST['proximo_contacto'];
				$horario_retiro = $_POST['horario_retiro'];
				$direccion = $_POST['direccion'];

				$respuesta = $this->seguimientoModel->actividad_bd(
				$instancia,	$id_actividad,
				$asunto, $fecha, $estado, 
				$id_cliente, $cliente, $telefono, $dias_reclamo,
				$id_contacto, $contacto, $celular, $correo, $asociacion,
				$descripcion, 
				$comprobantes,
				$proximo_contacto, $horario_retiro, $direccion 
				);
				echo $respuesta;
			}
		}
	}

	public function eliminarActividad(){
		if ($this->input->is_ajax_request()) {
			if ($this->session->userdata('id_usuario')){		
				$id = $_POST['id'];
				$respuesta = $this->seguimientoModel->eliminarActividad($id);
				echo $respuesta;
			}
		}
	}

	public function obtenerComprobantes(){
		if ($this->input->is_ajax_request()) {
			if ($this->session->userdata('id_usuario')){				
				$id = $_POST['id'];
				$empresa = $_POST['empresa'];
				$respuesta = $this->seguimientoModel->obtenerComprobantes($id, $empresa);
				echo json_encode($respuesta);
			}
		}
	}
}