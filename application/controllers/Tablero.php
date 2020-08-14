<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tablero extends CI_Controller {

	public function guardar_configuracion_usuario(){
		$idGva14 = $_POST['idGva14'];
		$codigoCliente = $_POST['codigoCliente'];
		$razonSocial = $_POST['razonSocial'];
		$fecha_hasta = $_POST['fecha_hasta'];
		$fecha_desde = $_POST['fecha_desde'];
		$id_usuario = $this->session->userdata('id_usuario');
		$params = array(
			'id_usuario' =>  $id_usuario,
			'id_gva14' => $idGva14,
			'codigo_cliente' => $codigoCliente,
			'razon_social' => $razonSocial, 
			'fecha_desde' => $fecha_desde,
			'fecha_hasta' => $fecha_hasta
		);
		$this->tableroModel->guardar_configuracion_usuario($params, $id_usuario);
	}

	public function index(){
		if ($this->session->userdata('id_usuario')){
			$id_carpeta = isset($_GET["id"])?$_GET["id"]:$this->session->userdata('id_carpeta');
			if($id_carpeta){
				$carpeta = $this->tableroModel->getCarpetaPorId($id_carpeta);
				$graficos = $this->tableroModel->getGraficosPorCarpeta($id_carpeta);
				if(count($carpeta) == 0){
					$carpeta = $this->tableroModel->getCarpetaPorId($this->session->userdata('id_carpeta'));
				}
				if(count($carpeta) > 0){
					$data["carpeta"] = $carpeta[0];
					$data["graficos"] = $graficos;
					$data["conf"] = $this->tableroModel->getConfiguracionUsuario();
					$data["clientes"] = $this->getClientes();
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

	public function getClientes(){
		$id_empresa = $this->session->userdata('id_empresa');

			//tipo de grilla
			$tipo = "clientes";
			$where = "where gva12.estado = 'PEN'";

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

			$array = $this->seguimientoModel->manejoWhere($tipo, $columna, $busqueda, $tipo_busqueda, $where, 'isnull');
			
			$data["array_valores"] = $array;
			
			$data["tamano"] = count($this->config->item($tipo.'_array_columna'));
			$data["columna"] = $this->config->item($tipo.'_array_columna');
			$data["sql_columna"] = $this->config->item($tipo.'_sql_columna');
			$data["key"] = $this->config->item($tipo.'_key');
			$data["tipo_columna"] = $this->config->item($tipo.'_tipo_columna');


			$where_ajuste = $array["where"];
			$where_general = "";
			$having_general = "";
			while (!empty($where_ajuste)) {
				$count = strpos($where_ajuste, " count(");
				$sum = strpos($where_ajuste, " sum(");
				$count2 = strpos($where_ajuste, " isnull(count(");
				$sum2 = strpos($where_ajuste, " isnull(sum(");
				if($sum !== false || $count !== false || $sum2 !== false || $count2 !== false){
					$array_min = array($sum, $count, $sum2, $count2);
					$posicion = "";
					foreach ($array_min as $value) {
						if($value !== false){
							if($posicion == ""){
								$posicion = $value;
							}else if($posicion > $value){
								$posicion = $value;
							}
						}	
					}
					$where_general .= substr($where_ajuste, 0, $posicion-4);
					$and = strpos($where_ajuste, " and", $posicion);
					if($and !== false){
						$final = $and;
					}else{
						$final = strlen($where_ajuste);
					}
					$having_general .= substr($where_ajuste, $posicion-4, $final-($posicion)+4);					
					$where_ajuste = substr($where_ajuste, $final);
				}else{
					$where_general = substr($where_ajuste, 0);
					$where_ajuste = "";
				}
			}
			$array["where"] = $where_general;
			if(!empty($having_general)){
				$having_general = " having ".substr($having_general, 4);
			}
			$this->session->set_userdata($tipo.'_where_sql', $array["where"]);
			$this->session->set_userdata($tipo.'_having_sql', $having_general);			
			$clientes = $this->seguimientoModel->getClientes($array["where"], $having_general);
			return $clientes;
	}


	public function modificarGrafico(){
		$id_grafico = $_POST['id_grafico'];
		$data = json_decode(isset($_POST["data"])?$_POST["data"]:"", true);
		$this->tableroModel->modificarGrafico($id_grafico, $data);
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

	public function get_objetivos_graficos(){
		$id_grafico = $_POST['id_grafico'];
		echo json_encode($this->tableroModel->get_objetivos_graficos($id_grafico));
	}

	public function guardar_objetivos_graficos(){
		$datos = json_decode($_POST['datos'], true);
		$id_grafico = $_POST['id_grafico'];
		echo $this->tableroModel->guardar_objetivos_graficos($datos, $id_grafico);
	}

	public function sel_objetivos_graficos(){
		$id_grafico = $_POST['id_grafico'];
		$cantidad_grafico = $_POST['cantidad_grafico'];
		echo $this->tableroModel->sel_objetivos_graficos($id_grafico, $cantidad_grafico);	
	}
}