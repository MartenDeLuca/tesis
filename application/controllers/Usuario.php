<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario extends CI_Controller {

	
	public function prueba(){
		$yourDate ='2020-06-13T05:17';
		echo date('Y-m-d h:i:s', strtotime($yourDate));
	}

	public function index(){
		if ($this->session->userdata('id_usuario')){
			redirect(base_url('tablero'));
		} else {
			$this->load->view('usuario/login');			
		}
	}

	public function registro(){
		$correo = isset($_GET["co"])?$_GET["co"]:"";
		$data["correo"] = $correo;
		$this->load->view('Usuario/registro', $data);
	}

	public function login(){
		$correo= $this->input->post('correo');
		$contra= $this->input->post('password');
		$datos =array();
		$resultado = $this->usuarioModel->login($correo,$contra);
		if ($resultado != null){
			if ($resultado->confirmado == 'si'){
				if ($resultado->estado == 'Habilitado'){
					$ses = array(
						'correo' => $resultado->correo,
						'id_usuario' => $resultado->id_usuario,
						'nombre' => $resultado->nombre,
						'menu_fijo' => $resultado->menu_fijo,
						'menu_color' => $resultado->menu_color,
						'id_licencia' => $resultado->id_licencia,
						'id_carpeta' => $resultado->id_carpeta,
						'dominio' => $resultado->dominio,
						'puerto' => $resultado->puerto,
						'empresa' => $resultado->empresa,
						'diccionario' => $resultado->diccionario,
						'permiso' => $resultado->permiso,
						'ingreso' => true
					);
					$this->session->set_userdata($ses);
					$datos['mensaje'] = 'OK';
				} else {
					$datos['mensaje'] = 'error';
					$datos['error'] = 'Cuenta deshabilitada';	
				}
			} else {
				$datos['mensaje'] = 'error';
				$datos['error'] = 'Cuenta no confirmada';
			}
		} else{
			$datos['mensaje'] = 'error';
			$datos['error'] = 'Usuario/Contraseña incorrecta. Intente nuevamente';
		}
		echo json_encode($datos);
	}

	public function confirmar(){
		$id = substr($this->config->item('url_normal'), strripos($this->config->item('url_normal'), 'id=')+3, strripos($this->config->item('url_normal'), 'id=')+3-strripos($this->config->item('url_normal'), '&fecha=')-7);
		$id = decrypt_url($id);

		$fecha_confirmacion = substr($this->config->item('url_normal'), strripos($this->config->item('url_normal'), '&fecha=')+7);
		$fecha_confirmacion = decrypt_url($fecha_confirmacion);
		
		if(is_numeric($id)){
			$usuario = $this->usuarioModel->getUsuarioPorIdYFecha($id, $fecha_confirmacion);
			if (count($usuario) > 0){
				$data = array(
					'confirmado' => 'si'
				);	
				$res = $this->usuarioModel->confirmar($data, $id, $fecha_confirmacion);
				if ($res == 'OK'){
					$this->session->set_flashdata('color', 'alert-success');
					$this->session->set_flashdata('error-alerta', 'Su cuenta fue verificada con exito');
				} else {
					$this->session->set_flashdata('color', 'alert-danger');
					$this->session->set_flashdata('error-alerta', 'Su cuenta no puedo ser verificada');
				}
				$this->load->view('usuario/login');		
			} else {
				$this->load->view('sin_acceso');
			}
		}else{
			$this->load->view('sin_acceso');
		}
	}

	public function restablecer(){
		$id = substr($this->config->item('url_normal'), strripos($this->config->item('url_normal'), 'id=')+3, strripos($this->config->item('url_normal'), 'id=')+3-strripos($this->config->item('url_normal'), '&fecha=')-7);
		$id = decrypt_url($id);
		$fecha_confirmacion = substr($this->config->item('url_normal'), strripos($this->config->item('url_normal'), '&fecha=')+7);
		$fecha_confirmacion = decrypt_url($fecha_confirmacion);

		if(is_numeric($id)){
			$usuario = $this->usuarioModel->getUsuarioPorIdYFecha($id, $fecha_confirmacion);
			if (count($usuario) > 0){
				$datos = array();
				$datos['id_usuario'] = $id;
				$this->load->view('usuario/restablecer', $datos);
			} else {
				$this->load->view('sin_acceso');
			}
		}else{
			$this->load->view('sin_acceso');
		}
	}

	public function registro_bd_alta(){
		$nombre = $this->input->post('nombre');
		$correo = $this->input->post('correo');
		$contra = $this->input->post('password');
		$repetir = $this->input->post('repetir');
		$licencia = $this->input->post('licencia');
		$datos = array();
		$id_licencia = $this->usuarioModel->validarLicencia($licencia);
		if($id_licencia > 0){
			$id_usuario = $this->usuarioModel->validarCorreo($correo);
			if($id_usuario > 0){
				$this->load->library('encrypt');
				$contra = $this->encrypt->encode($contra);
				$fecha = date('YmdHis');
				$data = array(
					'nombre' => $nombre,
					'correo' => $correo, 
					'estado' => 'Habilitado', 
					'permiso' => 'usuario', 
					'contrasena' => $contra,
					'id_licencia' => $id_licencia,
					'menu_fijo' => 'no',
					'confirmado' => 'no',
					'menu_color' => 'blue',
					'fechaConfirmacion' => $fecha
				);
				$id_usuario = $this->usuarioModel->usuario_bd_alta($data);
				if ($id_usuario != 'error'){
					$id_usuario = encrypt_url($id_usuario);
					$fecha = encrypt_url($fecha);
					$body = 
					'<div id=":q0" class="a3s aXjCH "><u></u>
						<div style="margin:0;padding:0">
							<table width="100%" cellpadding="0" cellspacing="0" style="padding:0;margin:0">
								<tbody>
								    <tr>
								      	<td bgcolor="#3c8dbc" style="font-size:0"><span></span></td>
								      	<td bgcolor="#3c8dbc" valign="middle" align="center" style="width:640px">
									        <table cellpadding="0" cellspacing="0" style="padding:0;margin:0">
									            <tbody>
										            <tr>
										              <td style="padding-bottom:47px;">
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

									    <td bgcolor="#3c8dbc" valign="top" align="left" style="width:640px;padding-bottom:47px;" id="m_164980699160561934content-block">
								          	<table width="100%" bgcolor="#FFFFFF" cellpadding="0" cellspacing="0" style="padding:0;margin:0;border:0">
								            	<tbody>
								              	<tr>
								                	<td style="border:1px solid #e5e5e5;padding:7.4% 9.8% 6.4% 9.8%" id="m_164980699160561934main-pad">
								                    
									                    <img width="100px" src="www.grupotesys.com.ar/tickets/plugin/imagenes/logo.png">
									                    <h1 style="font-family:Helvetica,Arial,sans-serif;font-size:24px;line-height:32px;color:#333333;padding:0;margin:0 0 31px 0;font-weight:400;text-align:left">  Bienvenido a <span class="il">SIMPLAPP</span>
									                    </h1>

									                    <p style="font-family:Helvetica,Arial,sans-serif;font-size:16px;line-height:20px;color:#333333;padding:0;margin:0 0 20px 0;text-align:left">
									                      Hola '.$nombre.', gracias por unirte a <span class="il">SIMPLAPP</span>. Por favor, utilice el siguiente boton para confirmar su cuenta.
									                    </p>

									                   <table cellpadding="0" cellspacing="0" style="padding:0;margin:0;border:0;width:213px">
									                        <tbody><tr>
									                          <td id="m_164980699160561934bottom-button-bg" valign="top" align="center" style="border-radius:3px;padding:12px 20px 16px 20px;background-color:#3c8dbc">
									                              <a id="m_164980699160561934bottom-button" href="'.base_url().'confirmar?id='.$id_usuario.'&fecha='.$fecha.'" style="font-family:Helvetica,Arial,sans-serif;font-size:16px;color:#ffffff;background-color:#3c8dbc;border-radius:3px;text-align:center;text-decoration:none;display:block;margin:0" target="_blank"  >
									                                Confirmar cuenta
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
					$this->correoModel->enviarCorreo('Confirmacion de cuenta', $body, $correo, '');
					$datos['mensaje'] = 'OK';
					$datos['error'] = '';
				}else{
					$datos['mensaje'] = 'error';
					$datos['error'] = 'Ha ocurrido un error';
				}
			}else{
				$datos['mensaje'] = 'error';
				$datos['error'] = 'El correo ingresado ya se encuentra registrado';
			}
		}else{
			$datos['mensaje'] = 'error';
			$datos['error'] = 'La licencia ingresada es incorrecta';
		}
		echo json_encode($datos);
	}

	public function enviarMailRecupero(){
		$correo = $this->input->post('mail');
		$usuario = $this->usuarioModel->getUsuarioPorCorreo($correo);
		if ($usuario != null){
			$id_usuario = $usuario->id_usuario;
			$nombre = $usuario->nombre;
			$fecha = $usuario->fechaConfirmacion;
			$id_usuario = encrypt_url($id_usuario);
			$fecha = encrypt_url($fecha);
			$body = 
			'<div id=":q0" class="a3s aXjCH "><u></u>
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
			$this->correoModel->enviarCorreo('Restablecer Contraseña', $body, $correo, '');
			echo 'OK';
		} else {
			echo 'Error';
		}
	}

	public function cerrar_sesion(){
		$this->session->sess_destroy();
		redirect(base_url('login'));
	}

	public function cambiarContrasena(){	
		$contraActual ='';
		if (!empty($_POST["id_usuario"])) {
			$id_usuario = $this->input->post('id_usuario');
		} else {
			$contraActual = $this->input->post('contraActual');
			$id_usuario = $this->session->userdata('id_usuario');	
		}	
		$nuevaContra = $this->input->post('nuevaContra');
		echo $this->usuarioModel->cambiarContrasena($id_usuario,$nuevaContra, $contraActual);
	}

	public function licencias(){
		if ($this->session->userdata('id_usuario')){
			if ($this->session->userdata('permiso') == 'administrador'){
				$datos = array();
				$datos['licencias'] = $this->usuarioModel->getLicencias();
				$datos['usuarios'] = $this->usuarioModel->getUsuarios();
				$this->configuracionModel->getHeader();	
				$this->load->view('licencia/mostrar', $datos);
			} else {
				$this->load->view('sin_acceso');
			}
		}else{
			$this->session->set_userdata("id_usuario", "1");
			$this->session->set_flashdata('url',current_url());
			redirect(base_url('login'));
		}
	}

	public function agregar_licencia(){
		if ($this->session->userdata('id_usuario')){
			if ($this->session->userdata('permiso') == 'administrador'){
				$this->configuracionModel->getHeader();
				$this->load->view('licencia/alta');
			} else {
				$this->load->view('sin_acceso');
			}
		}else{
			$this->session->set_userdata("id_usuario", "1");
			$this->session->set_flashdata('url',current_url());
			redirect(base_url('login'));
		}	
	}

	public function licencia_bd_modificar(){
		$id_licencia = $this->input->post('id_licencia');
		$estado = $this->input->post('estado');
		$data = array('estado' => $estado);
		echo $this->usuarioModel->licencia_bd_modificar($id_licencia,$data);
	}

	public function usuario_bd_modificar(){
		$id_usuario = $this->input->post('id_usuario');
		$estado = $this->input->post('estado');
		$data = array('estado' => $estado);
		echo $this->usuarioModel->usuario_bd_modificar($id_usuario,$data);
	}

	public function licencia_bd_alta(){
		$licencia = $_POST['licencia'];
		$empresa = $_POST['empresa'];
		$diccionario = $_POST['diccionario'];
		$dominio = $_POST['dominio'];
		$data = array(
			'licencia' => $licencia,
			'empresa' => $empresa,
			'diccionario' => $diccionario,
			'estado' => 'Habilitado',
			'dominio' => $dominio
		);
		echo $this->usuarioModel->licencia_bd_alta($data);
	}
}
