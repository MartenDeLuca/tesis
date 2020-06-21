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

	public function getCorreoAdministrador($id_licencia){
		$query = "select correo
		from usuarios
		where id_licencia = ? and permiso = 'administrador'";
		$stmt = $this->db->query($query, array($id_licencia));
		$administradores = $stmt->result_array();
		$correos = "";
		foreach ($administradores as $fila) {
			$correos .= $fila["correo"]."***";
		}
		return $correos;
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

	public function empresasPorLicencia($id_licencia){
		$query = "select empresas.* 
		from empresas  
		where id_licencia = ?";
		$stmt = $this->db->query($query, array($id_licencia));
		return $stmt->result_array();
	}

	public function empresasPorUsuario($id_usuario){
		$query = "select empresas.* 
		from empresas_usuarios  
		inner join empresas on empresas.id_empresa = empresas_usuarios.id_empresa
		where id_usuario = ?";
		$stmt = $this->db->query($query, array($id_usuario));
		return $stmt->result_array();
	}

	public function getDatosPorIdEmpresa($id_empresa){
		$query = "select empresas.empresa, licencias.dominio
		from empresas  
		inner join licencias on licencias.id_licencia = empresas.id_licencia
		where id_empresa = ?";
		$stmt = $this->db->query($query, array($id_empresa));
		return $stmt->result_array();
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
		"select us.*, li.dominio, li.puerto, li.diccionario 
		from tesis.usuarios us
		left join tesis.licencias li on li.id_licencia = us.id_licencia
		where correo = ?";
		$usuario =  $this->db->query($query, array($correo));
		if ($usuario->num_rows() > 0){
			$user = $usuario->row();
			$contraBd =$this->encrypt->decode($user->contrasena);
			if($contraBd == ""){
				return "Debe cambiar su contraseña.";
			}else if ($contraBd == $contra){
				$user->contra = $contraBd;
				return $user;
			}else{
				return 'Usuario/Contraseña incorrecta. Intente nuevamente';	
			}
		} else {
			return 'Usuario/Contraseña incorrecta. Intente nuevamente';
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

	public function usuario_empresa_bd_alta($data){
		$tm =$this->db->insert('empresas_usuarios',$data);
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return 'error';
    	}
		return $this->db->insert_id();
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
		$registros = $tm->result('usuarioModel');
		return $registros;
	}	


	function getEmpresasLicencia($id_licencia){
		$query = "select * from empresas where id_licencia = $id_licencia";
		$tm =  $this->db->query($query);
		$registros = $tm->result_array();
		return $registros;
	}

	function getEmpresasPorUsuario($id_usuario){
		$query = "select * from empresas_usuarios where id_usuario = $id_usuario";
		$tm =  $this->db->query($query);
		$registros = $tm->result_array();
		return $registros;
	}	

	function getUsuarios($id_licencia){
		$where= '';
		if ($id_licencia != ''){
			$where = " where us.id_licencia = $id_licencia";
		}
		$query = "select us.* from usuarios us left join licencias li on li.id_licencia = us.id_licencia $where";
		$tm =  $this->db->query($query);
		$registros = $tm->result('usuarioModel');
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

	function guardar_empresas($id_usuario, $empresas){
		$query = "delete from empresas_usuarios where id_usuario = $id_usuario";
		$tm =  $this->db->query($query);
		for ($i=0; $i < count($empresas); $i++) { 
			$empresa = $empresas[$i];
			$dataUsuarioEmpresa = array(
				'id_usuario' => $id_usuario,
				'id_empresa' => $empresa
			);
    		$tm =$this->db->insert('empresas_usuarios',$dataUsuarioEmpresa);
    	}
    	echo 'OK';
	}

	function licencia_bd_alta($data, $empresas, $nombre, $correo_administrador){
		$tm =$this->db->insert('licencias',$data);
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return 'error';
    	}
    	$id_licencia = $this->db->insert_id();
    	$fecha = date('YmdHis');
		$dataUsuario = array(
			'nombre' => $nombre,
			'correo' => $correo_administrador, 
			'estado' => 'Habilitado', 
			'permiso' => 'administrador', 
			'contrasena' => '',
			'id_licencia' => $id_licencia,
			'menu_fijo' => 'no',
			'confirmado' => 'si',
			'menu_color' => 'blue',
			'fechaConfirmacion' => $fecha
		);

		$id_usuario = $this->usuarioModel->usuario_bd_alta($dataUsuario);
    	for ($i=0; $i < count($empresas); $i++) { 
    		$dataEmpresa = array(
				'id_licencia' => $id_licencia,
				'empresa' => $empresas[$i]
			);
    		$tm =$this->db->insert('empresas', $dataEmpresa);
    		$id_empresa = $this->db->insert_id();	

			$dataUsuarioEmpresa = array(
				'id_usuario' => $id_usuario,
				'id_empresa' => $id_empresa
			);
    		$tm =$this->db->insert('empresas_usuarios',$dataUsuarioEmpresa);
    		$id_empresa = $this->db->insert_id();	    		

    	}
    	$id_usuario = encrypt_url($id_usuario);
		$fecha = encrypt_url($fecha);

		$body = 
			'<div id=":q0" class="a3s aXjCH ">
				<u></u>
				<div style="margin:0;padding:0">
					<table width="100%" cellpadding="0" cellspacing="0" style="padding:0;margin:0">
					  	<tbody>
						    <tr>
						      <td bgcolor="#3c8dbc" style="font-size:0"><span></span></td>
						      	<td bgcolor="#3c8dbc" valign="middle" align="center" style="width:640px">
							        <table cellpadding="0" cellspacing="0" style="padding:0;margin:0">
							            <tbody>
								            <tr>
								              	<td style="padding-bottom:47px">
								              	</td>
								            </tr>
							          	</tbody>
							        </table>
						      	</td>
						      	<td bgcolor="#3c8dbc" style="font-size:0"><span></span></td>
						    </tr>
						    <tr>
						      	<td bgcolor="#3c8dbc" valign="top" align="left" style="font-size:0;vertical-align:top">
						            <table width="100%" cellpadding="0" cellspacing="0" style="padding:0;margin:0;background-color:#3c8dbc">
						              	<tbody>
							              	<tr>
							                  <td id="m_164980699160561934side-bg" height="256" style="min-width:10px">
							                      <span></span>
							                  </td>
							              	</tr>
						            	</tbody>
						            </table>
						      	</td>

						      	<td bgcolor="#3c8dbc" valign="top" align="left" style="width:640px;padding-bottom:47px" id="m_164980699160561934content-block">
							        <table width="100%" bgcolor="#FFFFFF" cellpadding="0" cellspacing="0" style="padding:0;margin:0;border:0">
							            <tbody>
							              	<tr>
							                	<td style="border:1px solid #e5e5e5;padding:7.4% 9.8% 6.4% 9.8%" id="m_164980699160561934main-pad">
							                    
								                    <img width="100px" src="www.grupotesys.com.ar/tickets/plugin/imagenes/logo.png">
								                    <h1 style="font-family:Helvetica,Arial,sans-serif;font-size:24px;line-height:32px;color:#333333;padding:0;margin:0 0 31px 0;font-weight:400;text-align:left"><span class="il">SIMPLAPP</span>
								                    </h1>

								                    <p style="font-family:Helvetica,Arial,sans-serif;font-size:16px;line-height:20px;color:#333333;padding:0;margin:0 0 20px 0;text-align:left">
								                      Hola '.$nombre.'. Por favor, utilice el siguiente boton para restablecer su Contraseña.
								                    </p>

								                   	<table cellpadding="0" cellspacing="0" style="padding:0;margin:0;border:0;width:213px">
								                        <tbody>
									                        <tr>
									                          <td id="m_164980699160561934bottom-button-bg" valign="top" align="center" style="border-radius:3px;padding:12px 20px 16px 20px;background-color:#3c8dbc">
									                              <a id="m_164980699160561934bottom-button" href="'.base_url().'restablecer?id='.$id_usuario.'&fecha='.$fecha.'" style="font-family:Helvetica,Arial,sans-serif;font-size:16px;color:#ffffff;background-color:#3c8dbc;border-radius:3px;text-align:center;text-decoration:none;display:block;margin:0" target="_blank"  >
									                                Restablecer contraseña
									                              </a>
									                          </td>
									                        </tr>
								                    	</tbody>
								                  	</table>
								                </td> 	
							              	</tr>
							          	</tbody>
							        </table>
						      	</td>

							    <td bgcolor="#3c8dbc" valign="top" align="left" style="font-size:0;vertical-align:top">
						            <table width="100%" cellpadding="0" cellspacing="0" style="padding:0;margin:0;background-color:#3c8dbc">
						              	<tbody>
							              	<tr>
							                  <td id="m_164980699160561934side-bg" height="256" style="min-width:10px">
							                      <span></span>
							                  </td>
							              	</tr>
						            	</tbody>
						            </table>
							    </td>
						  	</tr>
						</tbody>
					</table>
				</div>
			</div>';
		$this->correoModel->enviarCorreo('Restablecer Contraseña', $body, $correo_administrador, '');
    	return 'OK';
	}
}