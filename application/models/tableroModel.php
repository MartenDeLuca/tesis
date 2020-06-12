<?php
class TableroModel extends CI_Model{
	function __construct(){
		parent::__construct();
	}

	function getCarpetas(){
		$id_usuario = $this->session->userdata('id_usuario');
		$id_carpeta = $this->session->userdata('id_carpeta');
		$id_licencia = $this->session->userdata('id_licencia');
		$sql_select = "select * from carpetas where id_usuario = ? and id_licencia = ? order by IFNULL(id_padre,0), nombre";
		$stmt = $this->db->query($sql_select, array($id_usuario, $id_licencia));
		$carpetas = $stmt->result_array();
		$data = array();
		foreach($carpetas as $row){
			$subcarpeta["id"] = $row["id_carpeta"];
			$subcarpeta["name"] = $row["nombre"];
			$subcarpeta["text"] = $row["nombre"];	
			if($id_carpeta == $row["id_carpeta"]){
				$subcarpeta["state"] = array("checked" => "true");
			}else{
				$subcarpeta["state"] = array();
			}			
			$subcarpeta["parent_id"] = $row["id_padre"];
			
			$data[] = $subcarpeta;
		}

		foreach($data as $key => &$value){
			$output[$value["id"]] = &$value;
		}
		
		foreach($data as $key => &$value){
			if($value["parent_id"] && isset($output[$value["parent_id"]])){
				$output[$value["parent_id"]]["nodes"][] = &$value;
			}
		}
		
		foreach($data as $key => &$value){
			if($value["parent_id"] && isset($output[$value["parent_id"]])){
				unset($data[$key]);
			}
		}
		return $data;
	}

	function getCarpetaPorId($id_carpeta){
		$id_usuario = $this->session->userdata('id_usuario');
		$id_licencia = $this->session->userdata('id_licencia');
		$sql_select = 
		"select * 
		from carpetas 
		where id_carpeta = ? and (id_usuario = ? or id_usuario = '0') and id_licencia = ?";
		$stmt = $this->db->query($sql_select, array($id_carpeta, $id_usuario, $id_licencia));
		$registros = $stmt->result_array();
		if(count($registros) == 0){
			$sql_select = 
			"select *
			from carpetas 
			where id_carpeta = ?";
			$stmt = $this->db->query($sql_select, array($this->session->userdata("id_carpeta")));
			$registros = $stmt->result_array();
		}
		return $registros;
	}

	function crear_carpeta($carpeta, $id_padre){
		$id_usuario = $this->session->userdata('id_usuario');
		$id_licencia = $this->session->userdata('id_licencia');
		
		$tm = $this->db->query(
		"INSERT INTO carpetas 
		(nombre, id_padre, id_usuario, id_licencia) 
		VALUES 
		(?,?,?,?)", array($carpeta, $id_padre, $id_usuario, $id_licencia));
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return $error['message'];
    	}

    	$tm = $this->db->query(
		"UPDATE carpetas
		SET es_padre = '1'
		WHERE id_carpeta = ? and id_usuario = ? and id_licencia = ?", array($id_padre, $id_usuario, $id_licencia));
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return $error['message'];
    	}

    	return "OK";
	}

	function renombrar_carpeta($carpeta, $id_carpeta){
		$id_usuario = $this->session->userdata('id_usuario');
		$tm = $this->db->query(
		"UPDATE carpetas
		SET nombre = ? 
		WHERE id_carpeta = ? and id_usuario = ?", array($carpeta, $id_carpeta, $id_usuario));
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return $error['message'];
    	}
		return "OK";
	}

	function eliminar_carpeta($id_carpeta, $eliminar_hijos){
		$id_usuario = $this->session->userdata('id_usuario');
		$id_licencia = $this->session->userdata('id_licencia');
		
		//SI ES 0 LE TENGO QUE SACAR EL ID_PADRE Y PONERLE SI TENIA UNA PADRE LA CARPETA A ELIMINAR, SINO LE PONGO EL 1
		if($eliminar_hijos == "0"){			
    		$stmt = $this->db->query(
			"SELECT id_padre
			FROM carpetas 
			WHERE id_usuario = ? AND id_carpeta = ? AND id_licencia = ?", array($id_usuario, $id_carpeta, $id_licencia));
			$carpetas = $stmt->result_array();
			foreach($carpetas as $row){
				$id_padre = $row["id_padre"];
			}			

			$where = "";
			$stmt = $this->db->query(
			"SELECT id_carpeta 
			FROM carpetas 
			WHERE id_usuario = ? AND id_padre = ? AND id_licencia = ?", array($id_usuario, $id_carpeta, $id_licencia));
			$carpetas = $stmt->result_array();
			if(count($carpetas) > 0){
				$where = ' AND id_carpeta IN (';
				foreach($carpetas as $row){
					$where .= $row["id_carpeta"].",";
				}
				$where = substr($where, 0, -1).")";
			}
			$update = "UPDATE carpetas
			SET id_padre = '$id_padre'
			WHERE id_usuario = '$id_usuario' AND id_licencia = '$id_licencia' $where";			
			$tm1 = $this->db->query($update);
			if(!$tm1){
	    		$error = $this->db->error();
	    		$this->db->trans_rollback();
	    		return $error['message'];
	    	}
	    //SI ES 1 TENGO ELIMINAR TODAS LAS HIJOS, NIETOS Y BISNIETOS QUE TENGA ESA CARPETA
	    }else if($eliminar_hijos == "1"){	    	
			$this->eliminar_carpeta_hijos($id_carpeta);
	    }

	    //SI LA CARPETA QUE ESTA POR DEFECTO ES LA QUE SE ELIMINA, LE SETEO LA PRINCIPAL PARA QUE LE QUEDE UNA POR DEFECTO
    	if($id_carpeta == $this->session->userdata("id_carpeta")){    		
    		$id_carpeta_session = 0;
    		$stmt = $this->db->query(
			"SELECT id_carpeta
			FROM carpetas
			WHERE id_licencia = ? AND id_usuario = ? AND id_padre = '0'", array($id_licencia, $id_usuario));
			$carpetas = $stmt->result_array();
			foreach($carpetas as $row){
				$id_carpeta_session = $row["id_carpeta"];
			}
    		$this->session->set_userdata("id_carpeta", $id_carpeta_session);
    	}

    	//VERIFICO SI SU PADRE, SE QUEDO SIN HIJOS Y LO PONGO COMO SI NO FUERA PADRE
    	$stmt = $this->db->query(
		"SELECT padre.id_carpeta
		FROM carpetas
		INNER JOIN carpetas padre on carpetas.id_padre = padre.id_carpeta
		WHERE carpetas.id_carpeta = ? and carpetas.id_usuario = ? and carpetas.id_licencia = ?", array($id_carpeta, $id_usuario, $id_licencia));
		$padres = $stmt->result_array();
		if(count($padres) == 1){
			foreach ($padres as $fila) {
				$id_padre = $fila["id_carpeta"];
				$update_padre = "UPDATE carpetas
				SET es_padre = false
				WHERE id_carpeta = '$id_padre' and id_usuario = '$id_usuario' and id_licencia = '$id_licencia'";
				$tm = $this->db->query($update_padre);
				if(!$tm){
		    		$error = $this->db->error();
		    		$this->db->trans_rollback();
		    		return $error['message'];
		    	}
			}
		}

    	//ELIMINO LA CARPETA 
    	$tm = $this->db->query(
		"DELETE FROM carpetas
		WHERE id_carpeta = ? AND id_usuario = ? AND id_licencia = ?", 
		array($id_carpeta, $id_usuario, $id_licencia));
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return $error['message'];
    	}

		return "OK";
	}

	function eliminar_carpeta_hijos($id_carpeta){
		$id_usuario = $this->session->userdata('id_usuario');
		$id_licencia = $this->session->userdata('id_licencia');

		$sql_select = 
		"SELECT id_carpeta 
		FROM carpetas 
		WHERE id_padre = ? AND id_usuario = ? AND id_licencia = ?";
		$stmt = $this->db->query($sql_select, array($id_carpeta, $id_usuario, $id_licencia));
		$carpetas = $stmt->result_array();
		foreach($carpetas as $row){
			$id_carpeta = $row["id_carpeta"];

			$tm = $this->db->query(
			"DELETE FROM carpetas
			WHERE id_carpeta = ? AND id_usuario = ? AND id_licencia = ?", 
			array($id_carpeta, $id_usuario, $id_licencia));
			if(!$tm){
	    		$error = $this->db->error();
	    		$this->db->trans_rollback();
	    		return $error['message'];
	    	}
	    	$this->eliminar_carpeta_hijos($id_carpeta);
		}
	}

	function mover_carpeta($id_padre, $id_carpeta){
		$id_usuario = $this->session->userdata('id_usuario');
		$id_licencia = $this->session->userdata('id_licencia');
		$tm = $this->db->query(
		"UPDATE carpetas
		SET id_padre = ? 
		WHERE id_carpeta = ? and id_usuario = ? and id_licencia = ?", 
		array($id_padre, $id_carpeta, $id_usuario, $id_licencia));
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return $error['message'];
    	}
		return "OK";
	}

	function compartir_carpeta($array, $id_carpeta){
		$id_usuario = $this->session->userdata('id_usuario');
		
		//RECORRER LOS ASOCIADOS
		foreach ($array as $fila) {
			
		}

		return "OK";
	}	
}