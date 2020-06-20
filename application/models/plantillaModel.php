<?php
class PlantillaModel extends CI_Model{
	function __construct(){
		parent::__construct();
	}

	function getPlantillas($bandera, $busqueda){
		$id_empresa = $this->session->userdata('id_empresa');
		$id_usuario = $this->session->userdata('id_usuario');
		$permiso = $this->session->userdata('permiso');
		$where_adicional = "";
		if($permiso != "administrador" && $bandera == ""){
			$where_adicional = " and id_usuario = '$id_usuario'";
		}

		$sql = "select asunto, asunto_mail, id_plantilla from plantillas where id_empresa = ? $where_adicional $busqueda";
		$stmt = $this->db->query($sql, array($id_empresa));
		$plantillas = $stmt->result_array();
		return $plantillas;
	}

	function getPlantillaPorId($id_plantilla){
		$id_empresa = $this->session->userdata('id_empresa');
		$id_usuario = $this->session->userdata('id_usuario');
		$permiso = $this->session->userdata('permiso');
		$where_adicional = "";
		if($permiso != "administrador"){
			$where_adicional = " and id_usuario = '$id_usuario'";
		}

		$sql_select = 
		"select * 
		from plantillas
		where id_plantilla = ? and id_empresa = ? $where_adicional";
		$stmt = $this->db->query($sql_select, array($id_plantilla, $id_empresa));
		$data = $stmt->result_array();		
		if(count($data) > 0){
			$data = $data[0];

			$contenido = "";
			$sql_select = 
			"select contenido 
			from plantillas_contenido
			where id_plantilla = ?";
			$stmt = $this->db->query($sql_select, array($id_plantilla));
			$contenidos = $stmt->result_array();
			foreach ($contenidos as $fila) {
				$contenido .= $fila["contenido"];
			}
			$data["contenido_mail"] = $contenido;
		}
		return $data;
	}

	function plantilla_bd($instancia, $id_plantilla, $asunto, $asunto_mail, $contenido){
		$this->db->trans_begin();
		$id_empresa = $this->session->userdata('id_empresa');
		$id_usuario = $this->session->userdata('id_usuario');
		
		$objeto = array("asunto" => $asunto, "asunto_mail" => $asunto_mail, "id_usuario" => $id_usuario, "id_empresa" => $id_empresa);
		if($instancia == "Agregar"){
			$tm = $this->db->insert("plantillas", $objeto);
			if(!$tm){
	    		$error = $this->db->error();
	    		$this->db->trans_rollback();
	    		return array($error['message']);
	    	}
	    	$id_plantilla = $this->db->insert_id();
	    }else{
	    	$this->db->where('id_plantilla', $id_plantilla);
			$tm = $this->db->update("plantillas", $objeto);
			if(!$tm){
	    		$error = $this->db->error();
	    		$this->db->trans_rollback();
	    		return $error['message'];
	    	}

	    	$tm = $this->db->query(
			"DELETE FROM plantillas_contenido WHERE id_plantilla = ?", array($id_plantilla));
			if(!$tm){
	    		$error = $this->db->error();
	    		$this->db->trans_rollback();
	    		return array($error['message']);
	    	}
	    }

    	while(!empty($contenido)) {
    		$contenido_aux = substr($contenido, 0, 8000);
    		$tm = $this->db->query(
			"INSERT INTO plantillas_contenido 
			(id_plantilla, contenido) 
			VALUES 
			(?, ?)", array($id_plantilla, $contenido_aux));
			if(!$tm){
	    		$error = $this->db->error();
	    		$this->db->trans_rollback();
	    		return array($error['message']);
	    	}
	    	$contenido = substr($contenido, 8000);
    	}

		if ($this->db->trans_status() === FALSE){
			$error = $this->db->error();
	        $this->db->trans_rollback();
	        return array('error');
		}else{
		    $this->db->trans_commit();
		    return array('ok', $id_plantilla);
		}
	}

	function plantilla_eliminar($id_plantilla){
		$this->db->trans_begin();

		$tm = $this->db->query(
		"DELETE FROM plantillas_contenido WHERE id_plantilla = ?", array($id_plantilla));
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return array($error['message']);
    	}

    	$tm = $this->db->query(
		"DELETE FROM plantillas WHERE id_plantilla = ?", array($id_plantilla));
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return array($error['message']);
    	}
    	

		if ($this->db->trans_status() === FALSE){
			$error = $this->db->error();
	        $this->db->trans_rollback();
	        return array('error');
		}else{
		    $this->db->trans_commit();
		    return array('ok', $id_plantilla);
		}    	
	}

	function seleccionarPlantilla($id, $cliente){
		$empresa = $this->session->userdata('empresa');
		$dominio = $this->session->userdata('dominio');

		$data = $this->getPlantillaPorId($id);
		
		$contenido_mail = $data["contenido_mail"]; 
		if(strpos($contenido_mail, "[^*COLUMNA_Cliente*^]") !== false){
			$array_cliente = json_decode($this->seguimientoModel->seleccionarCliente($cliente, $empresa, $dominio), true);
			$nombre_cliente = "";
			if(count($array_cliente) > 0){
				$nombre_cliente = $array_cliente[0]["cliente"];
			}
			$contenido_mail = str_replace("[^*COLUMNA_Cliente*^]", $nombre_cliente, $contenido_mail);
		}

		if(strpos($contenido_mail, "[^*TABLA_Comprobante*^]") !== false){
			$array_comprobantes = json_decode($this->seguimientoModel->seleccionarComprobante($cliente, $empresa, $dominio), true);
			$comprobantes = "";
			$html_tabla_comprobantes = 
			"<table>
			<tr>
				<th>Tipo</th><th>Comprobante</th><th>Fecha Emisión</th><th>Fecha Vencimiento</th><th>Importe</th><th>Días</th>
			</tr>";
			foreach ($array_comprobantes as $comp) {
				$html_tabla_comprobantes .= 
				"<tr>
					<td>".$comp["tipo"]."</td>
					<td>".$comp["comprobante"]."</td>
					<td>".$comp["fecha"]."</td>
					<td>".$comp["vencimiento"]."</td>
					<td>".$comp["importe"]."</td>
					<td>".$comp["dias"]."</td>
				</tr>";
			}
			$html_tabla_comprobantes .= "</table>";
			$contenido_mail = str_replace("[^*TABLA_Comprobante*^]", $html_tabla_comprobantes, $contenido_mail);
		}
		$data["contenido_mail"] = $contenido_mail;
		return $data;
	}
}