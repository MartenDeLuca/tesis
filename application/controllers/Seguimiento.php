<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Seguimiento extends CI_Controller {
	
	public function detalle_cliente(){
		if ($this->session->userdata('id_usuario')){
			$id = isset($_GET["id"])?$_GET["id"]:"";
			$empresa = $this->session->userdata('empresa');
			$dominio = $this->session->userdata('dominio');

			$cliente = json_decode($this->seguimientoModel->seleccionarDetalleCliente($id, $empresa, $dominio),true);
			if(count($cliente) > 0){
				if($this->session->userdata('vista_amplia') == "right"){
					$div_actividades = "12";
					$cambiarVista = "right";
					$clase_div_datos_adicionales = "style='display:none;'";	
				}else{
					$div_actividades = "9";
					$cambiarVista = "left";
					$clase_div_datos_adicionales = "";
				}

				$array_asignados = $this->seguimientoModel->getArrayAsignados();		
				$comprobantes = json_decode($this->seguimientoModel->seleccionarComprobante($id, $empresa, $dominio), true);
				$actividades_pendientes = $this->seguimientoModel->actividadesPendientes($id, $array_asignados);
				
				$actividades_realizadas = $this->seguimientoModel->actividadRealizada($id, $array_asignados, $actividades_pendientes["cont"], 'exists', '', '');

				$data["cliente"] = $cliente[0];
				$data["id"] = $id;
				$data["comprobantes"] = $comprobantes;				
				$data["array_asignados"] = $array_asignados;
				$data["actividades_pendientes"] = $actividades_pendientes["html"];
				$data["actividades_realizadas"] = $actividades_realizadas["html"];
				$data["cont"] = $actividades_realizadas["cont"];
				$data["div_actividades"] = $div_actividades;
				$data["cambiarVista"] = $cambiarVista;
				$data["clase_div_datos_adicionales"] = $clase_div_datos_adicionales;
				$data["cambiarVistaInteraccion"] = "minus";
				$this->configuracionModel->getHeader();
				$this->load->view('cliente/detalle_cliente', $data);
				$this->load->view('menu/footer');
			}else{

			}
		}else{
			$this->session->set_flashdata('url',current_url());
			redirect(base_url('login'));
		}
	}

	public function actividadRealizadaHistorial(){
		if ($this->session->userdata('id_usuario')){
			$array_asignados = $this->seguimientoModel->getArrayAsignados();
			$id = isset($_POST["id"])?$_POST["id"]:"";
			$cont = isset($_POST["cont"])?$_POST["cont"]:"";
			$inicio = isset($_POST["inicio"])?$_POST["inicio"]:"0";
			$id_actividad_no_mirar = isset($_POST["id_actividad_no_mirar"])?$_POST["id_actividad_no_mirar"]:"";

			$actividades_realizadas = $this->seguimientoModel->actividadRealizada($id, $array_asignados, $cont, 'not exists', "limit $inicio, 10", $id_actividad_no_mirar);
			echo json_encode($actividades_realizadas);
		}
	}

	public function actividadesAgregar(){
		if ($this->session->userdata('id_usuario')){
			$id = isset($_POST["id"])?$_POST["id"]:"";
			$cont = isset($_POST["cont"])?$_POST["cont"]:"";
			$array_asignados = isset($_POST["array_asignados"])?$_POST["array_asignados"]:"";
			$actividad = $this->seguimientoModel->actividadesAgregar($id, $cont, $array_asignados);
			echo $actividad["html"];
		}	
	}

	public function mailAgregar(){
		if ($this->session->userdata('id_usuario')){
			$id = isset($_POST["id"])?$_POST["id"]:"";
			$cont = isset($_POST["cont"])?$_POST["cont"]:"";
			$array_asignados = isset($_POST["array_asignados"])?$_POST["array_asignados"]:"";
			$actividad = $this->seguimientoModel->mailAgregar($id, $cont, $array_asignados);
			echo $actividad["html"];
		}	
	}	

	public function mandarMail(){
		if ($this->session->userdata('id_usuario')){	        
			$asunto_mail = $_POST['asunto_mail'];
			$contenido_mail = $_POST['contenido_mail'];
			$id_cliente = $_POST['id_cliente'];
			$cliente = $_POST['cliente'];
			
			$destinatario_fijos = "";
			if(isset($_POST['destinatario_fijos'])){
				$destinatario_fijos_post = $_POST['destinatario_fijos'];
				foreach($destinatario_fijos_post as $selected) {
		            $destinatario_fijos .= $selected.";";
		        }
		        $destinatario_fijos = substr($destinatario_fijos, 0, -1);
		    }			
			
			$comprobantes = json_decode($_POST['comprobantes'], true);			

			if (isset($_FILES['Adjunto']['name'])){
				$archivos_nuevos = $_FILES;
			}else{
				$archivos_nuevos = array();
			}
			$adjuntos = array();
			$archivoTemporal = date("Ymdhisu");
			$carpeta = $_SERVER['DOCUMENT_ROOT'].$this->config->item('carpeta_principal').'Plugin/mailtemporal/'.$archivoTemporal.'/';
			if(isset($archivos_nuevos['Adjunto']['name'])){
			    $cantidad = count($archivos_nuevos['Adjunto']['name']);
		       	if (!file_exists($carpeta)) {
				    mkdir($carpeta, 0777, true);
				}
				$config = array();
			    $config['upload_path'] = 'plugin/mailtemporal/'.$archivoTemporal;
			    $config['allowed_types'] = '*';
			    $config['max_size']      = '0';
			    $config['overwrite']     = FALSE;
			    $this->load->library('upload');

			    for($j = 0; $j < $cantidad; $j++){
			    	if(!empty($archivos_nuevos['Adjunto']['name'][$j])){
						$nombreArchivo = $archivos_nuevos['Adjunto']['name'][$j];
						$nombreArchivo = str_replace(" ", "", $nombreArchivo);
						$_FILES['userfile']['name'] = $nombreArchivo;
				        $_FILES['userfile']['type'] = $archivos_nuevos['Adjunto']['type'][$j];
				        $_FILES['userfile']['tmp_name'] = $archivos_nuevos['Adjunto']['tmp_name'][$j];
				        $_FILES['userfile']['error'] = $archivos_nuevos['Adjunto']['error'][$j];
				        $_FILES['userfile']['size'] = $archivos_nuevos['Adjunto']['size'][$j]; 
				        $this->upload->initialize($config);
				        
				        if (!$this->upload->do_upload()) {
							$data['uploadError'] = $this->upload->display_errors();
							echo $this->upload->display_errors();
							return;
						}
						$objeto = array();
						$objeto["adjunto"] = $nombreArchivo;
						array_push($adjuntos, $objeto);
					}
			    }
			}

			$id_empresa = $this->session->userdata('id_empresa');
			$empresa = $this->session->userdata('empresa');
			$dominio = $this->session->userdata('dominio');
			
			$parametrosMail = $this->reglaModel->parametrosMail($id_empresa);
			$correo = $parametrosMail["correo"];
			$contrasena = $parametrosMail["contrasena"];
			$puerto = $parametrosMail["puerto"];
			$host = $parametrosMail["host"];
			$certificado_ssl = $parametrosMail["certificado_ssl"];
			
			$respuesta = $this->reglaModel->mandarMail(
				'0', $id_empresa, $id_cliente, $cliente,
				$asunto_mail, $contenido_mail, 'Manual',
				$empresa, $dominio, $puerto, $host, $correo, $contrasena, $certificado_ssl,
				$carpeta, $adjuntos, '', $destinatario_fijos, '2', $comprobantes
			);
			$carpeta = str_replace('/', '\\', $carpeta);
			$this->reglaModel->deleteDir($carpeta);
			
			echo json_encode($respuesta);
		}
	}

	public function getContenidoMail($id_mail){
		if ($this->session->userdata('id_usuario')){
			$datos = array();
			$datos['body'] = $this->seguimientoModel->getContenidoMail($id_mail);
			$this->load->view('cliente/ifram_seleccion', $datos);
		}	
	}

	public function descargarArchivos(){
		if ($this->session->userdata('id_usuario')){
			$id_adjunto = isset($_GET["id_adjunto"])?$_GET["id_adjunto"]:"";
			$id_mail = isset($_GET["id_mail"])?$_GET["id_mail"]:"";
			$this->seguimientoModel->descargarArchivos($id_adjunto, $id_mail);
		}
	}

	public function anotacionesComprobantes(){
		if ($this->session->userdata('id_usuario')){
			$where_id = isset($_POST["where_id"])?$_POST["where_id"]:"";
			$tipo = isset($_POST["tipo"])?$_POST["tipo"]:"";
			$fecha_pago = isset($_POST["fecha_pago"])?$_POST["fecha_pago"]:"";
			$forma_pago = isset($_POST["forma_pago"])?$_POST["forma_pago"]:"";
			$observacion = isset($_POST["observacion"])?$_POST["observacion"]:"";
			echo $this->seguimientoModel->anotacionesComprobantes($where_id, $tipo, $fecha_pago, $forma_pago, $observacion);
		}			
	}


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
			"cliente" => "", "id_cliente" => "", "cod_cliente" => "",			
			"comprobantes" => array(),
			"proximo_contacto" => "", "direccion" => "", "asignados" => array());
			$data["array_asignados"] = $this->seguimientoModel->getArrayAsignados();
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
					$data["array_asignados"] = $this->seguimientoModel->getArrayAsignados();
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

				$descripcion = $_POST['descripcion'];

				$asociacion = json_decode($_POST['asociacion'], true);
				
				$comprobantes = json_decode($_POST['comprobantes'], true);

				$proximo_contacto = $_POST['proximo_contacto'];
				$direccion = $_POST['direccion'];


				$respuesta = $this->seguimientoModel->actividad_bd(
				$instancia,	$id_actividad,
				$asunto, $fecha, $estado, 
				$id_cliente, $cliente,
				$asociacion, $proximo_contacto, $direccion,
				$descripcion, 
				$comprobantes				
				);
				echo json_encode($respuesta);
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


	public function modificarActividad(){
		if ($this->input->is_ajax_request()) {
			if ($this->session->userdata('id_usuario')){		
				$id = $_POST['id'];
				$valor = $_POST['valor'];
				$campo = $_POST['campo'];
				$respuesta = $this->seguimientoModel->modificarActividad($id, $valor, $campo);
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