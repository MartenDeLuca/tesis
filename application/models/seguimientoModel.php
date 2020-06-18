<?php
class SeguimientoModel extends CI_Model{
	function __construct(){
		parent::__construct();	
	}

	function getSeguimiento($tipo, $where){
		if($tipo == "mails"){			
			$sql = "select mails.asunto, mails.destinatarios, DATE_FORMAT(mails.fecha, '%d/%m/%Y %T') fecha, cliente, mails.id_mail, mails.id_cliente
			from mails
			$where
			order by mails.id_mail desc";
			$stmt = $this->db->query($sql);
			return $stmt->result_array();
		}else{
			$sql = "select asunto, DATE_FORMAT(fecha, '%d/%m/%Y %T') fecha, DATE_FORMAT(proximo_contacto, '%d/%m/%Y %T') proximo_contacto, estado, cliente, contacto, telefono, id_actividad, id_cliente
			from actividades
			$where
			order by id_actividad desc";
			$stmt = $this->db->query($sql);
			return $stmt->result_array();
		}
	}

	function manejoWhere($tipo, $columna, $busqueda, $tipo_busqueda, $where){
		/*OBTENGO LAS OPCIONES BUSQUEDA SEGUN EL TIPO DE DATO*/
		$opciones_float = $this->config->item("opciones_float");
		$opciones_date = $this->config->item("opciones_date");
		$opciones_string = $this->config->item("opciones_string");
		$array_columna = $this->config->item($tipo."_array_columna");
		$sql_columna = $this->config->item($tipo."_sql_columna");
		$tipo_columna = $this->config->item($tipo."_tipo_columna");
		$array = procesarWhere($columna, $busqueda, $tipo_busqueda, $opciones_float, $opciones_date, $opciones_string, $where, $array_columna, $sql_columna, $tipo_columna);
		return $array;
	}	

	function registros_guardados($tipo, $where_adicional){
		$id_usuario = $this->session->userdata('id_usuario');
		$stmt = $this->db->query(
			"select columna, tipo_busqueda, busqueda
			from busqueda 
			where tipo = ? and id_usuario = ? $where_adicional", array($tipo, $id_usuario));
		return $stmt->result_array();
	}

	function guardar_busqueda($columna, $tipo_busqueda, $busqueda, $tipo, $nombreBusqueda){
		$id_usuario = $this->session->userdata('id_usuario');

		$this->vaciar_busqueda($tipo);

		$tm = $this->db->query("insert into busqueda (columna, tipo_busqueda, busqueda, tipo, id_usuario) values (?,?,?,?,?)", array($columna, $tipo_busqueda, $busqueda, $tipo, $id_usuario));
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return $error['message'];
    	}

    	if(!empty($nombreBusqueda)){
			$tm = $this->db->query("insert into busqueda (columna, tipo_busqueda, busqueda, tipo, nombreBusqueda, id_usuario) values (?,?,?,?,?,?)", array($columna, $tipo_busqueda, $busqueda, $tipo, $nombreBusqueda, $id_usuario));
			if(!$tm){
	    		$error = $this->db->error();
	    		$this->db->trans_rollback();
	    		return $error['message'];
	    	}
	    }
	}

	function vaciar_busqueda($tipo){
		$id_usuario = $this->session->userdata('id_usuario');
		$tm = $this->db->query("delete from busqueda where tipo = ? and ifnull(nombreBusqueda,'') = '' and id_usuario = ?", array($tipo, $id_usuario));
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return $error['message'];
    	}
	}

	function getBusquedasGuardadas($tipo){
		$id_usuario = $this->session->userdata('id_usuario'); 
		$tabla = "<table class='table'><tr><th>Nombre</th><th>Favorito</th><th></th></tr>";
		$stmt = $this->db->query("select nombreBusqueda, id_busqueda, favoritoBusqueda
			from busqueda
			where (id_usuario = '$id_usuario' or id_usuario = '0') and tipo = '$tipo' and ifnull(nombreBusqueda,'') <> ''");
		$registrants = $stmt->result_array();
		foreach ($registrants as $fila) {
			$nombre = $fila["nombreBusqueda"];
			$id_busqueda = $fila["id_busqueda"];
			$favorito = $fila["favoritoBusqueda"];
			$clase_estrella = "";
			if($favorito == "0"){
				$clase_estrella = "-o";
			}
			$tabla .= 
			"<tr>
				<td><a style='cursor:pointer;' onclick='ejecutarBusquedaGuardada(".$id_busqueda.")'>".$nombre."</a></td>
				<td><a style='cursor:pointer;' onclick='favoritoBusquedaGuardada(".$id_busqueda.", this)'><i data-estado='".$favorito."' class='fa fa-star".$clase_estrella." text-yellow'></i></a></td>
				<td><a style='cursor:pointer;' onclick='eliminarBusquedaGuardada(".$id_busqueda.", this)'><span class='glyphicon glyphicon-trash'></span></a></td>
			</tr>";
		}
		$tabla .= "</table>";
		return $tabla;
	}

	function eliminarBusquedaGuardada($id_busqueda){
		$tm = $this->db->query("delete from busqueda where id_busqueda = ?", array($id_busqueda));
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return $error['message'];
    	}
	}	

	function favoritoBusquedaGuardada($id_busqueda, $favorito){
		if($favorito == "0"){
			$favorito = "false";
		}else{
			$favorito = "true";
		}
		$sql = "update busqueda set favoritoBusqueda = $favorito where id_busqueda = '$id_busqueda'";
		$tm = $this->db->query($sql);
		if($tm){
			return 'OK';
		}else{
			return $this->db->error()['message'];
		}
	}	

	function exportar($tipo){
		ini_set('memory_limit', '2048M');
		ini_set('max_execution_time', 300);
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=".$tipo.".xls");
		
		$tamano = count($this->config->item($tipo.'_array_columna'));
		$columna = $this->config->item($tipo.'_array_columna');
		$sql_columna = $this->config->item($tipo.'_sql_columna');
		$key = $this->config->item($tipo.'_key');
		$tipo_columna = $this->config->item($tipo.'_tipo_columna');

		$where = $this->session->userdata($tipo.'_where_sql');
		
		$datos = $this->getSeguimiento($tipo, $where);
		$html = "";
		if(count($datos) > 0) {
			$html =
			'<table>
				<thead>
					<tr>';			
	                for($i = 0; $i < $tamano; $i++){
	                    $html .= '<th>'.$columna[$i].'</th>';
	                }
					$html .=
					'</tr>
				</thead>
				<tbody>'; 
				foreach ($datos as $fila) {
					$html .= '<tr>';
				   	for($i = 0; $i < $tamano; $i++){
	                	$html .= '<th>'.$fila[$key[$i]].'</th>';
	                }
	                $html .= '</tr>';
	            }
	            $html .=         
				'</tbody>
			</table>';
		}
		return $html;
	}

	function getActividadPorId($id){
		$empresa = $this->session->userdata('empresa');
		$id_empresa = $this->session->userdata('id_empresa');

		$sql_select = 
		"select * 
		from actividades
		where id_actividad = ? and id_empresa = ?";
		$stmt = $this->db->query($sql_select, array($id, $id_empresa));
		$data = $stmt->result_array();
		if(count($data) > 0){
			$data = $data[0];
			$dominio = $this->session->userdata('dominio');
			$cliente = json_decode($this->seleccionarCliente($data["id_cliente"], $empresa, $dominio),true);			
		    if($data["estado"] == "Pendiente"){
		    	$data["comprobantes"] = $this->obtenerComprobantes($data["id_cliente"], $empresa, $dominio);	
		    }else{
		    	$data["comprobantes"] = $this->obtenerComprobantesRealizado($id);	
		    }
			if(count($cliente) > 0){
				$data["cliente"] = $cliente[0]["cliente"];
				$data["cod_cliente"] = $cliente[0]["cod_cliente"];
			}else{
				$data["id_cliente"] = "";
				$data["cliente"] = "";
				$data["cod_cliente"] = "";
			}			    
			$contacto = json_decode($this->seleccionarContacto($data["id_contacto"], $data["id_cliente"], $empresa, $dominio), true);
			if(count($contacto) > 0){
				$data["contacto"] = $contacto[0]["contacto"];
			}else{
				$data["id_contacto"] = "";
				$data["contacto"] = "";
			}
			$sql_select = 
			"select id_usuario 
			from actividades_asociacion
			where id_actividad = ?";
			$stmt = $this->db->query($sql_select, array($id));
			$asignados = $stmt->result_array();
			$data["asignados"] = $asignados;
			
		}
		return $data;
	}

	function obtenerComprobantes($id, $empresa){
		$dominio = $this->session->userdata('dominio');
		$comprobantes = json_decode($this->seleccionarComprobante($id, $empresa, $dominio), true);
		$cont = 0;
		foreach ($comprobantes as $fila) {
			$sql_select = 
			"select fecha_retiro
			from actividades_comprobantes
			where comprobante = ? and tipo = ?";
			$stmt = $this->db->query($sql_select, array($fila["comprobante"], $fila["tipo"]));
			$datos = $stmt->result_array();
			foreach ($datos as $dato) {
				$comprobantes[$cont]["fecha_retiro"] = $dato["fecha_retiro"];
			}
			$cont++;
		}		
		return $comprobantes;
	}

	function obtenerComprobantesRealizado($id){
		$sql_select = 
		"select *, '-' dias
		from actividades_comprobantes
		where id_actividad = ?";
		$stmt = $this->db->query($sql_select, array($id));
		$datos = $stmt->result_array();
		return $datos;
	}

	function actividad_bd
	(
		$instancia,	$id_actividad,
		$asunto, $fecha, $estado, 
		$id_cliente, $cliente, $telefono, $dias_reclamo,
		$id_contacto, $contacto, $celular, $correo, $asociacion,
		$descripcion, 
		$comprobantes,
		$proximo_contacto, $horario_retiro, $direccion 
	)
	{
		$this->db->trans_begin();
		$id_usuario = $this->session->userdata('id_usuario');
		$id_empresa = $this->session->userdata('id_empresa');
		//encabezado
		$objeto = array(
		"asunto" => $asunto, "fecha" => $fecha, "estado" => $estado, 
		"id_cliente" => $id_cliente, "cliente" => $cliente, "telefono" => $telefono, "dias_reclamo" => $dias_reclamo, 
		"id_contacto" => $id_contacto, "contacto" => $contacto, "celular" => $celular, "correo" => $correo, 
		"descripcion" => $descripcion, 
		"proximo_contacto" => $proximo_contacto, "horario_retiro" => $horario_retiro, "direccion" => $direccion,
		"id_empresa" => $id_empresa);
		
		if($instancia == "Agregar"){
			//inserto a la regla
			$tm = $this->db->insert("actividades", $objeto);
			if(!$tm){
	    		$error = $this->db->error();
	    		$this->db->trans_rollback();
	    		return $error['message'];
	    	}
	    	$id_actividad = $this->db->insert_id();
	    }else{
	    	//modifico a la regla
	    	$this->db->where('id_actividad', $id_actividad);
			$tm = $this->db->update("actividades", $objeto);
			if(!$tm){
	    		$error = $this->db->error();
	    		$this->db->trans_rollback();
	    		return $error['message'];
	    	}
	    }

	 	$tm = $this->db->query(
		"DELETE FROM actividades_comprobantes
		WHERE id_actividad = ?", 
		array($id_actividad));
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return $error['message'];
    	}

	    foreach ($comprobantes as $fila) {
	    	$fila["id_actividad"] = $id_actividad;
	    	$tm = $this->db->insert("actividades_comprobantes", $fila);
			if(!$tm){
	    		$error = $this->db->error();
	    		$this->db->trans_rollback();
	    		return $error['message'];
	    	}
	    }

	 	$tm = $this->db->query(
		"DELETE FROM actividades_asociacion
		WHERE id_actividad = ?", 
		array($id_actividad));
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return $error['message'];
    	}

	    foreach ($asociacion as $fila) {
	    	$fila["id_actividad"] = $id_actividad;
	    	$tm = $this->db->insert("actividades_asociacion", $fila);
			if(!$tm){
	    		$error = $this->db->error();
	    		$this->db->trans_rollback();
	    		return $error['message'];
	    	}
	    }	    

		if ($this->db->trans_status() === FALSE){
			$error = $this->db->error();
	        $this->db->trans_rollback();
	        return $error['message'];
		}else{
		    $this->db->trans_commit();
		    return "OK";
		}
	}

	function eliminarActividad($id_actividad){
	 	$this->db->trans_begin();

	 	$tm = $this->db->query(
		"DELETE FROM actividades_asociacion
		WHERE id_actividad = ?", 
		array($id_actividad));
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return $error['message'];
    	}

	 	$tm = $this->db->query(
		"DELETE FROM actividades_asociacion
		WHERE id_actividad = ?", 
		array($id_actividad));
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return $error['message'];
    	}

		$tm = $this->db->query(
		"DELETE FROM actividades
		WHERE id_actividad = ?", 
		array($id_actividad));
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return $error['message'];
    	}    	

		if ($this->db->trans_status() === FALSE){
			$error = $this->db->error();
	        $this->db->trans_rollback();
	        return $error['message'];
		}else{
		    $this->db->trans_commit();
		    return "OK";
		}    	
	}

	//FUNCIONES QUE VIENEN DE SQL SERVER	
	function seleccionarCliente($id_cliente, $empresa, $dominio){		
		$curl = curl_init();
		$url = $dominio."/api/seleccionarCliente";
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, TRUE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		curl_setopt($curl, CURLOPT_POSTFIELDS, 'id='.$id_cliente.'&empresa='.$empresa);
	    curl_setopt($curl, CURLOPT_USERAGENT, 'api');
	    curl_setopt($curl, CURLOPT_TIMEOUT, 2); 
	    curl_setopt($curl, CURLOPT_HEADER, 0);
	    //tuve que poner true aca porque sino hacia echo y no lo guardaba en la variable result
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
	    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
	    curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 10); 
	    curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
	    $result = curl_exec($curl);
	    return $result;
	}

	function seleccionarComprobante($id_cliente, $empresa, $dominio){
		$curl = curl_init();
		$url = $dominio."/api/seleccionarComprobante";
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, TRUE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		curl_setopt($curl, CURLOPT_POSTFIELDS, 'id='.$id_cliente.'&empresa='.$empresa);
	    curl_setopt($curl, CURLOPT_USERAGENT, 'api');
	    curl_setopt($curl, CURLOPT_TIMEOUT, 2); 
	    curl_setopt($curl, CURLOPT_HEADER, 0);
	    //tuve que poner true aca porque sino hacia echo y no lo guardaba en la variable result
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
	    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
	    curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 10); 
	    curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
	    $result = curl_exec($curl);
	    return $result;
	}


	function seleccionarContacto($id_contacto, $id_cliente, $empresa, $dominio){
		$curl = curl_init();		
		$url = $dominio."/api/seleccionarContacto";
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, TRUE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		curl_setopt($curl, CURLOPT_POSTFIELDS, 'id='.$id_contacto.'&id_cliente='.$id_cliente.'&empresa='.$empresa);
	    curl_setopt($curl, CURLOPT_USERAGENT, 'api');
	    curl_setopt($curl, CURLOPT_TIMEOUT, 2); 
	    curl_setopt($curl, CURLOPT_HEADER, 0);
	    //tuve que poner true aca porque sino hacia echo y no lo guardaba en la variable result
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
	    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
	    curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 10); 
	    curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
	    $result = curl_exec($curl);
	    return $result;
	}	
}