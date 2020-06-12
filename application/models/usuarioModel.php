<?php
class UsuarioModel extends CI_Model{
	function __construct(){
		parent::__construct();	
	}


	public function confirmar($data, $id, $fecha_confirmacion){
		$this->db->where('fechaConfirmacion', $fecha_confirmacion);
		$this->db->where('id_usuario', $id);
		$this->db->update('usuarios', $data); 
		$error = $this->db->error();
		if ($error['message']){
			return $error['message'];
		}
		return 'OK';

	}

	public function getUsuarioPorCorreo($correo){
		$this->load->library('encrypt');
		$query = "select * from usuarios  
		where correo = ?";
		$usuario =  $this->db->query($query, array($correo));
		if ($usuario->num_rows() > 0){
			$user = $usuario->row();
			return $user;
		} else {
			return null;
		} 			
	}

	public function getUsuarioPorIdYFecha($id, $fecha_confirmacion){
		$query = "select * from usuarios where id_usuario = ? and fechaConfirmacion = ?";
		$usuario =  $this->db->query($query, array($id, $fecha_confirmacion));
		return $usuario->result_array();
	}

	//devuelve un usuario si es que existe a partir de un usuario y una contraseña
	public function login($correo, $contra){
		$this->load->library('encrypt');
		$query = 
		"select us.*, li.dominio, li.puerto, li.empresa, li.diccionario 
		from tesis.usuarios us
		left join tesis.licencias li on li.id_licencia = us.id_licencia
		where correo = ?";
		$usuario =  $this->db->query($query, array($correo));
		if ($usuario->num_rows() > 0){
			$user = $usuario->row();
			$contraBd =$this->encrypt->decode($user->contrasena);
			if ($contraBd == $contra){
				$user->contra = $contraBd;
				return $user;
			}
		} else {
			return null;
		} 
	}

	//devuelve un usuario si es que existe a partir de un usuario y una contraseña
	public function usuario_bd_alta($data){
		$this->db->trans_begin();

		/* agrego el usuario */
		$tm =$this->db->insert('usuarios',$data);
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return 'error';
    	}
		$id_usuario = $this->db->insert_id();

		/* agrego la carpeta de inicio */
		$fila = array("id_carpeta" => "0", "id_usuario" => $id_usuario, "nombre" => "Inicio", "id_padre" => "0", "es_padre" => false, "id_licencia" => $data["id_licencia"]);
		$this->db->insert('carpetas', $fila);		
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return 'error';
    	}
    	$id_carpeta = $this->db->insert_id();

    	/* modifico para que tenga por defecto esa carpeta */
		$tm = $this->db->query("update usuarios set id_carpeta = ? where id_usuario = ?", array($id_carpeta, $id_usuario));
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return 'error';
    	}

		if ($this->db->trans_status() === FALSE){
			$error = $this->db->error();
	        $this->db->trans_rollback();
	        return 'error';
		}else{
		    $this->db->trans_commit();
		    return $id_usuario;
		}
	}

	function validarLicencia($licencia){
		/*$this->load->library('encrypt');
		$licencia =$this->encrypt->encode($licencia);*/
		$query = "select id_licencia from licencias where licencia = ? and estado='Habilitado'";
		$tm =  $this->db->query($query, array($licencia));
		$registros = $tm->result_array();
		if(count($registros) > 0){
			return $registros[0]["id_licencia"];
		}
		return 0;
	}

	function validarCorreo($correo){
		$query = "select id_usuario from usuarios where correo = ?";
		$tm =  $this->db->query($query, array($correo));
		$registros = $tm->result_array();
		if(count($registros) > 0){
			return 0;
		}
		return 1;
	}	

	function cambiarContrasena($id_usuario, $nuevaContra, $contraActual =''){
		$this->load->library('encrypt');
		$nuevaContra = $this->encrypt->encode($nuevaContra);
		$query = "select id_usuario, contrasena from usuarios where id_usuario = ? ";
		$tm =  $this->db->query($query, array($id_usuario));
		$registros = $tm->result_array();
		if(count($registros) > 0){
			if ($this->session->userdata('id_usuario')){
				$contraBd = $registros[0]['contrasena'];
				$contraBd = $this->encrypt->decode($contraBd);
				if ($contraActual == $contraBd){
					$tm = $this->db->query("update usuarios set contrasena = ? where id_usuario = ?", array($nuevaContra, $id_usuario));
					if(!$tm){
			    		$error = $this->db->error();
			    		$this->db->trans_rollback();
			    		return $error['message'];
			    	}
			    	return "OK";
		    	} else {
			    	return "La contraseña actual no es correcta";
			    }
			}else {
				$tm = $this->db->query("update usuarios set contrasena = ? where id_usuario = ?", array($nuevaContra, $id_usuario));
				if(!$tm){
		    		$error = $this->db->error();
		    		$this->db->trans_rollback();
		    		return $error['message'];
		    	}
		    	return "OK";
			}
		}	
		
		
	}

	function getUsuariosSelect(){
		$id_usuario = $this->session->userdata('id_usuario');
		$id_licencia = $this->session->userdata('id_licencia');
		$sql = "select id_usuario, correo from usuarios where id_licencia = ? and id_usuario <> ?";
		$stmt = $this->db->query($sql, array($id_licencia, $id_usuario));
		$array_usuarios = $stmt->result_array();
		$opciones_usuario = "<option></option>";
		foreach ($array_usuarios as $fila_usuario) {
			$opciones_usuario .= "<option value='".$fila_usuario["id_usuario"]."'>".$fila_usuario["correo"]."</option>";
		}
		return $opciones_usuario;
	}

	function getUsuariosSelectSoloCorreo(){
		$id_usuario = $this->session->userdata('id_usuario');
		$id_licencia = $this->session->userdata('id_licencia');
		$sql = "select id_usuario, correo from usuarios where id_licencia = ? and id_usuario <> ?";
		$stmt = $this->db->query($sql, array($id_licencia, $id_usuario));
		$array_usuarios = $stmt->result_array();
		$opciones_usuario = "<option></option>";
		foreach ($array_usuarios as $fila_usuario) {
			$opciones_usuario .= "<option value='".$fila_usuario["correo"]."'>".$fila_usuario["correo"]."</option>";
		}
		return $opciones_usuario;
	}		

	function getLicencias(){
		$query = "select * from licencias";
		$tm =  $this->db->query($query);
		$registros = $tm->result_array();
		return $registros;
	}	

	function getUsuarios(){
		$query = "select us.*, li.empresa from usuarios us left join licencias li on li.id_licencia = us.id_licencia ";
		$tm =  $this->db->query($query);
		$registros = $tm->result_array();
		return $registros;
	}	

	function licencia_bd_modificar($id_licencia,$data){
		$this->db->where('id_licencia', $id_licencia);
		$this->db->update('licencias', $data); 
		$error = $this->db->error();
		if ($error['message']){
			return $error['message'];
		}

		$this->db->where('id_licencia', $id_licencia);
		$this->db->update('usuarios', $data); 
		$error = $this->db->error();
		if ($error['message']){
			return $error['message'];
		}

		return 'OK';
	}

	function usuario_bd_modificar($id_usuario,$data){
		$this->db->where('id_usuario', $id_usuario);
		$this->db->update('usuarios', $data); 
		$error = $this->db->error();
		if ($error['message']){
			return $error['message'];
		}

		return 'OK';

	}

	function licencia_bd_alta($data){
		$tm =$this->db->insert('licencias',$data);
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return 'error';
    	}
    	return 'OK';
	}
}

