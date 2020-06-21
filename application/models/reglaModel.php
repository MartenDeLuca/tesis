<?php
class ReglaModel extends CI_Model{
	function __construct(){
		parent::__construct();
	}

	function getUsuariosParaSelect(){
		$id_licencia = $this->session->userdata('id_licencia');
		$sql = "select id_usuario, nombre, correo from usuarios where id_licencia = ?";
		$stmt = $this->db->query($sql, array($id_licencia));
		$array_usuarios = $stmt->result_array();
		return $array_usuarios;
	}	

	function getReglas(){
		$id_empresa = $this->session->userdata('id_empresa');
		$sql = "select *, DATE_FORMAT(fecha, '%d/%m/%Y %T') fecha2 from reglas where id_empresa = ?";
		$stmt = $this->db->query($sql, array($id_empresa));
		$reglas = $stmt->result_array();
		return $reglas;
	}

	function getReglaPorId($id_regla, $where_empresa, $ejecucion){
		$sql_select = 
		"select * 
		from reglas
		inner join reglas_accion on reglas.id_regla = reglas_accion.id_regla 
		where reglas.id_regla = ? $where_empresa";
		$stmt = $this->db->query($sql_select, array($id_regla));
		$data = $stmt->result_array();		
		if(count($data) > 0){
			$data = $data[0];
			$intervalo = $data["intervalo"];
			$tipoIntervalo = $data["tipoIntervalo"];
			if ($tipoIntervalo == "Horas"){
				$intervalo = $intervalo / 60;
			} else if ($tipoIntervalo == "Dias"){
				$intervalo = $intervalo / 60 / 24;
			} else if ($tipoIntervalo == "Semanas"){
				$intervalo = $intervalo / 60 / 24 / 7;
			} else if ($tipoIntervalo == "Meses"){
				$intervalo = $intervalo / 60 / 24 / 30;
			}
			$data["intervalo"] = $intervalo;
			$data["id_regla"] = $id_regla;

			$consulta = "";
			$sql_select = 
			"select consulta 
			from reglas_consulta
			where id_regla = ?";
			$stmt = $this->db->query($sql_select, array($id_regla));
			$consultas = $stmt->result_array();
			foreach ($consultas as $fila) {
				$consulta .= $fila["consulta"];
			}		
			$data["consulta"] = $consulta;			
			if(empty($ejecucion)){
				$atributos = json_decode($this->verificar_consulta($consulta, "1", $this->session->userdata('dominio')), true);				
				$data["atributos"] = $atributos[1];
			}else{
				$data["atributos"] = "";
			}
			
			$sql_select = 
			"select * 
			from reglas_adjunto
			where id_regla = ?";
			$stmt = $this->db->query($sql_select, array($id_regla));
			$adjuntos = $stmt->result_array();
			$data["adjuntos"] = $adjuntos;

			$descripcion_actividad = "";
			$sql_select = 
			"select consulta 
			from reglas_contenido
			where id_regla = ? and relacionado = 'Actividad'";
			$stmt = $this->db->query($sql_select, array($id_regla));
			$actividades = $stmt->result_array();
			foreach ($actividades as $fila) {
				$descripcion_actividad .= $fila["consulta"];
			}		
			$data["descripcion_actividad"] = $descripcion_actividad;

			$contenido_mail = "";
			$sql_select = 
			"select consulta 
			from reglas_contenido
			where id_regla = ? and relacionado = 'Consulta'";
			$stmt = $this->db->query($sql_select, array($id_regla));
			$consultas_externas = $stmt->result_array();
			foreach ($consultas_externas as $fila) {
				$contenido_mail .= $fila["consulta"];
			}
			$data["contenido_mail"] = $contenido_mail;
		}
		return $data;
	}

	function getReglasPorIdRegla($id_regla){
		$id_empresa = $this->session->userdata('id_empresa');
		$id_usuario = $this->session->userdata('id_usuario');

		$sql = "select reglas.asunto
		from reglas
		where reglas.id_regla = ? and id_empresa = ?";
		$stmt = $this->db->query($sql, array($id_regla, $id_empresa));
		$reglas = $stmt->result_array();
		$data = array();
		if(count($reglas) > 0){
			$data["asunto"] = $reglas[0]["asunto"];

			$sql = "select DATE_FORMAT(actividades.fecha, '%d/%m/%Y') fecha, DATE_FORMAT(actividades.fecha, '%T') hora, actividades.asunto, actividades.estado, actividades.id_actividad, ifnull(actividades_asociacion.leido, 0) leido
			from reglas 
			inner join actividades on reglas.id_regla = actividades.id_regla 
			inner join actividades_asociacion on actividades.id_actividad = actividades_asociacion.id_actividad
			where reglas.id_regla = ?
			order by actividades.fecha desc";
			$stmt = $this->db->query($sql, array($id_regla));
			$data["actividades"] = $stmt->result_array();

			$sql = "select mails.*, DATE_FORMAT(mails.fecha, '%d/%m/%Y %T') fecha2
			from reglas 
			inner join mails on reglas.id_regla = mails.id_regla 
			where reglas.id_regla = ?
			order by mails.id_mail desc";
			$stmt = $this->db->query($sql, array($id_regla));
			$data["mails"] = $stmt->result_array();
		}
		return $data;
	}

	function validarDatosCorreo($correo, $contrasena, $puerto, $host, $certificado_ssl){
		date_default_timezone_set('Etc/UTC');
		$this->load->library("phpmailer_library");
	    $mail = $this->phpmailer_library->load();

		$asunto = 'Prueba';
		$body = "Prueba";
		$mail->SMTPDebug = 0;
		$mail->Debugoutput = 'html';
		if($certificado_ssl == "1"){
			$mail->SMTPOptions = 
			array('ssl' => 
				array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
				)
			);
		}else{
			$mail->SMTPOptions = array();
		}
		$mail->Host = $host;
		$mail->Port = $puerto;
		$mail->isSMTP();
		$mail->SMTPSecure = 'tls';
		$mail->SMTPAuth = true;
		$mail->Username = $correo; 
		$mail->Password = $contrasena;
		$mail->setFrom($correo, "");
		$mail->addReplyTo($correo, "");
		$mail->AddBCC($correo, $name = "");	
		$mail->Sender = $correo;
		$mail->Subject = $asunto;
		$mail->msgHTML($body);
		$mail->AltBody = $body;
		$mail->CharSet = 'UTF-8';
		if($mail->send()){
			return "OK";
		}else{
			return $mail->ErrorInfo;
		}
	}

	function regla_bd(
		$instancia, $id_regla, 
		$asunto, $intervalo, $accion, $estado, $fechaInicio, $fechaExpiracion , $tipoIntervalo,
		$consulta, 
		$destinatario_fijos, $destinatario_columnas, $asunto_mail, $contenido_mail, $cliente_mail, $comprobantes_mail,
		$asunto_actividad, $descripcion_actividad, $asignados_actividad, $cliente, $comprobantes
	){
		$this->db->trans_begin();
		$id_empresa = $this->session->userdata('id_empresa');
		//encabezado
		$objeto = array("asunto" => $asunto, "intervalo" => $intervalo, "fecha" => $fechaInicio, "accion" => $accion, "estado" => $estado, "id_empresa" => $id_empresa,"fechaExpiracion" => $fechaExpiracion,"fechaInicio" => $fechaInicio, "tipoIntervalo" => $tipoIntervalo);

		if($instancia == "Agregar"){
			//inserto a la regla
			$tm = $this->db->insert("reglas", $objeto);
			if(!$tm){
	    		$error = $this->db->error();
	    		$this->db->trans_rollback();
	    		return array($error['message']);
	    	}
	    	$id_regla = $this->db->insert_id();
	    }else{
	    	unset($objeto['fecha']);
	    	unset($objeto['fechaInicio']);
	    	//modifico a la regla
	    	$this->db->where('id_regla', $id_regla);
			$tm = $this->db->update("reglas", $objeto);
			if(!$tm){
	    		$error = $this->db->error();
	    		$this->db->trans_rollback();
	    		return $error['message'];
	    	}

	    	//elimino toda asociacion para volverla a asignar
	    	$tm = $this->db->query(
			"DELETE FROM reglas_contenido WHERE id_regla = ?", array($id_regla));
			if(!$tm){
	    		$error = $this->db->error();
	    		$this->db->trans_rollback();
	    		return array($error['message']);
	    	}
			$tm = $this->db->query(
			"DELETE FROM reglas_accion WHERE id_regla = ?", array($id_regla));
			if(!$tm){
	    		$error = $this->db->error();
	    		$this->db->trans_rollback();
	    		return array($error['message']);
	    	}
	    	$tm = $this->db->query(
			"DELETE FROM reglas_consulta WHERE id_regla = ?", array($id_regla));
			if(!$tm){
	    		$error = $this->db->error();
	    		$this->db->trans_rollback();
	    		return array($error['message']);
	    	}
	    }

    	//consulta
    	while(!empty($consulta)) {
    		$consulta_aux = substr($consulta, 0, 8000);
    		$tm = $this->db->query(
			"INSERT INTO reglas_consulta 
			(id_regla, consulta) 
			VALUES 
			(?, ?)", array($id_regla, $consulta_aux));
			if(!$tm){
	    		$error = $this->db->error();
	    		$this->db->trans_rollback();
	    		return array($error['message']);
	    	}
	    	$consulta = substr($consulta, 8000);
    	}

		//correo
		$tm = $this->db->query(
		"INSERT INTO reglas_accion 
		(id_regla, destinatario_columnas, destinatario_fijos, asunto_mail, asunto_actividad, cliente, comprobantes, asignados_actividad, cliente_mail, comprobantes_mail) 
		VALUES 
		(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array($id_regla, $destinatario_columnas, $destinatario_fijos, $asunto_mail, $asunto_actividad, $cliente, $comprobantes, $asignados_actividad, $cliente_mail, $comprobantes_mail));
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return array($error['message']);
    	}

    	while(!empty($contenido_mail)) {
    		$descripcion_aux = substr($contenido_mail, 0, 8000);
    		$tm = $this->db->query(
			"INSERT INTO reglas_contenido 
			(id_regla, consulta, relacionado) 
			VALUES 
			(?, ?, 'Consulta')", array($id_regla, $descripcion_aux));
			if(!$tm){
	    		$error = $this->db->error();
	    		$this->db->trans_rollback();
	    		return array($error['message']);
	    	}
	    	$contenido_mail = substr($contenido_mail, 8000);
    	} 	

    	//actividad
    	while(!empty($descripcion_actividad)) {
    		$descripcion_aux = substr($descripcion_actividad, 0, 8000);
    		$tm = $this->db->query(
			"INSERT INTO reglas_contenido 
			(id_regla, consulta, relacionado) 
			VALUES 
			(?, ?, 'Actividad')", array($id_regla, $descripcion_aux));
			if(!$tm){
	    		$error = $this->db->error();
	    		$this->db->trans_rollback();
	    		return array($error['message']);
	    	}
	    	$descripcion_actividad = substr($descripcion_actividad, 8000);
    	}

		if ($this->db->trans_status() === FALSE){
			$error = $this->db->error();
	        $this->db->trans_rollback();
	        return array('error');
		}else{
		    $this->db->trans_commit();
		    return array('ok', $id_regla);
		}
	}

	function regla_bd_archivo($archivos, $id_regla){
		$this->db->trans_begin();

		$tm = $this->db->query(
		"DELETE FROM reglas_adjunto 
		WHERE id_regla = ?", array($id_regla));
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return array($error['message']);
    	}

		foreach ($archivos as $fila) {
			$tm = $this->db->query(
			"INSERT INTO reglas_adjunto 
			(id_regla, adjunto) 
			VALUES 
			(?,?)", array($id_regla, $fila));
			if(!$tm){
	    		$error = $this->db->error();
	    		$this->db->trans_rollback();
	    		return array($error['message']);
	    	}			
		}

		if ($this->db->trans_status() === FALSE){
			$error = $this->db->error();
	        $this->db->trans_rollback();
	        return array('error');
		}else{
		    $this->db->trans_commit();
		    return array('ok', $id_regla);
		}
	}

	function regla_eliminar($id_regla){
		$this->db->trans_begin();
    	$tm = $this->db->query(
		"DELETE FROM reglas_contenido WHERE id_regla = ?", array($id_regla));
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return array($error['message']);
    	}
    	$tm = $this->db->query(
		"DELETE FROM reglas_adjunto WHERE id_regla = ?", array($id_regla));
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return array($error['message']);
    	}
    	$tm = $this->db->query(
		"DELETE FROM reglas_accion WHERE id_regla = ?", array($id_regla));
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return array($error['message']);
    	}
    	$tm = $this->db->query(
		"DELETE FROM reglas_consulta WHERE id_regla = ?", array($id_regla));
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return array($error['message']);
    	}
    	$tm = $this->db->query(
		"DELETE FROM reglas WHERE id_regla = ?", array($id_regla));
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
		    return array('ok');
		}
		$carpeta = $_SERVER['DOCUMENT_ROOT'].$this->config->item('carpeta_principal').'Plugin/archivos/'.$id_regla;
		$this->deleteDir($carpeta);
	}

	function deleteDir($dirPath) {
	   	if (is_dir($dirPath)) {	   		
		    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
		        $dirPath .= '/';
		    }
		    $files = glob($dirPath . '*', GLOB_MARK);
		    foreach ($files as $file) {
		        if (is_dir($file)) {
		            self::deleteDir($file);
		        } else {
		            unlink($file);
		        }
		    }
		    rmdir($dirPath);
	    }
	}

	function reglas_a_ejecutar(){
		$sql = 
	    "SELECT id_regla
        FROM reglas
        WHERE
	    (TIMESTAMPDIFF(MINUTE, fecha, NOW()) = 0 or TIMESTAMPDIFF(MINUTE, fecha, NOW()) > 60) 
	    AND IFNULL(estado, '') <> 'Pausada' 
	    AND
		    DATEDIFF(
		    (CASE WHEN IFNULL(fechaInicio, NOW()) = '1969-12-31 09:00:00' THEN NOW() ELSE fechaInicio END), NOW()
		    ) <= 0
	    AND 
		    DATEDIFF(
		    (CASE WHEN IFNULL(fechaExpiracion, NOW()) = '1969-12-31 09:00:00' THEN NOW() ELSE fechaExpiracion END), NOW()
		    ) >= 0";
		$stmt = $this->db->query($sql);
		return $stmt->result_array();
	}

	function ejecucion_regla_negocio($id_regla){
		$consulta = "";
		$asunto = ""; 
		$intervalo = "1000"; 
		$accion = ""; 
		$estado = "";
		$consulta = ""; 
		$correo = ""; 
		$contrasena = ""; 
		$puerto = ""; 
		$host = ""; 
		$destinatarios_columnas = ""; 
		$destinatarios_fijos = ""; 
		$asunto_mail = ""; 
		$contenido_mail = ""; 
		$adjuntos = ""; 
		$asunto_actividad = ""; 
		$descripcion_actividad = "";
		$asignados_actividad = "";

		/* TOMO LAS REGLAS DE NEGOCIO CON SUS RESPECTIVOS DATOS*/
		$data = $this->getReglaPorId($id_regla, '', '1');		
		if(count($data) > 0){
			$id_empresa = $data["id_empresa"];
			$cliente = $data["cliente"];
			$comprobantes = $data["comprobantes"];
			$cliente_mail = $data["cliente_mail"];
			$comprobantes_mail = $data["comprobantes_mail"];
			$asunto = $data["asunto"];
			$intervalo = $data["intervalo"];
			$accion = $data["accion"]; 
			$estado = $data["estado"];
			$consulta = $data["consulta"];
			$destinatarios_columnas = $data["destinatario_columnas"];
			$destinatarios_fijos = $data["destinatario_fijos"];
			$asunto_mail = $data["asunto_mail"];
			$contenido_mail = $data["contenido_mail"];
			$adjuntos = $data["adjuntos"];		
			$asunto_actividad = $data["asunto_actividad"];
			$descripcion_actividad = $data["descripcion_actividad"];
			$asignados_actividad = $data["asignados_actividad"];

			$asunto_mail_fijo = $asunto_mail;
			$contenido_mail_fijo = $contenido_mail;
			$descripcion_actividad_fijo = $descripcion_actividad;
			$asignados_actividad_fijo = $asignados_actividad;
			$destinatarios_columnas_fijo = $destinatarios_columnas;
			if(!empty($consulta)){
				$dominio = "";
				$empresa = "";
				$sql = 
			    "SELECT licencias.dominio, empresas.empresa
		        FROM licencias
		        INNER JOIN empresas on licencias.id_licencia = empresas.id_licencia
		        WHERE empresas.id_empresa = ?";
				$stmt = $this->db->query($sql, array($id_empresa));
				$licencias = $stmt->result_array();
				foreach ($licencias as $licencia) {
					$dominio = $licencia["dominio"];
					$empresa = $licencia["empresa"];
				}

				$parametrosMail = $this->parametrosMail($id_empresa);
				$correo = $parametrosMail["correo"];
				$contrasena = $parametrosMail["contrasena"];
				$puerto = $parametrosMail["puerto"];
				$host = $parametrosMail["host"];
				$certificado_ssl = $parametrosMail["certificado_ssl"];

				$sql_columnas = json_decode($this->verificar_consulta($consulta, "0", $dominio),true);
				if(count($sql_columnas) > 0){
					$keys = array_keys($sql_columnas[0]);
					$cantidad_key = count($keys);
					foreach($sql_columnas as $sql_columna){
						$asunto_mail = $asunto_mail_fijo;
						$contenido_mail = $contenido_mail_fijo;
						$descripcion_actividad = $descripcion_actividad_fijo;
						$asignados_actividad = $asignados_actividad_fijo;
						$destinatarios_columnas = $destinatarios_columnas_fijo;

						/*modifico el email_contenido y email_asunto para obtener los valores de verdad */
						for($i = 0; $i < $cantidad_key; $i++){
							$asunto_mail = str_replace("[^*COLUMNA_".$keys[$i]."^*]", $sql_columna[$keys[$i]], $asunto_mail);
							$contenido_mail = str_replace("[^*COLUMNA_".$keys[$i]."^*]", $sql_columna[$keys[$i]], $contenido_mail);
							$descripcion_actividad = str_replace("[^*COLUMNA_".$keys[$i]."^*]", $sql_columna[$keys[$i]], $descripcion_actividad);
							$asunto_actividad = str_replace("[^*COLUMNA_".$keys[$i]."^*]", $sql_columna[$keys[$i]], $asunto_actividad);
							$cliente = str_replace("[^*COLUMNA_".$keys[$i]."^*]", $sql_columna[$keys[$i]], $cliente);
							$contacto = str_replace("[^*COLUMNA_".$keys[$i]."^*]", $sql_columna[$keys[$i]], $contacto);
							$destinatarios_columnas = str_replace("[^*COLUMNA_".$keys[$i]."^*]", $sql_columna[$keys[$i]], $destinatarios_columnas);
						}

						if(strpos($accion, "Actividad") !== false){
							if(!empty($descripcion_actividad)){
								$nombre_cliente = "";
								$nombre_contacto = "";
								$array_cliente = json_decode($this->seguimientoModel->seleccionarCliente($cliente, $empresa, $dominio), true);
								if(count($array_cliente) > 0){
									$nombre_cliente = $array_cliente[0]["cliente"];
																	
									$tm = $this->db->query(
									"INSERT INTO actividades 
									(id_regla, descripcion, asunto, estado, fecha, id_cliente, cliente, id_empresa, origen) 
									VALUES 
									(?, ?, ?, 'Pendiente', NOW(), ?, ?, ?, ?, ?, 'Automatico')", array($id_regla, $descripcion_actividad, $asunto_actividad, $cliente, $nombre_cliente, $id_empresa));
									if(!$tm){
							    		$error = $this->db->error();
							    		return $error['message'];
							    	}
							    	$id_actividad = $this->db->insert_id();

									$html_tabla_comprobantes = "";
									if($comprobantes_mail == "1"){
										$array_comprobantes = json_decode($this->seguimientoModel->seleccionarComprobante($cliente, $empresa, $dominio), true);

										$html_tabla_comprobantes = $this->reglaModel->guardarComprobantes('actividades', 'actividad', $id_actividad, $array_comprobantes);
									}

									if(strpos($descripcion_actividad, '[^*TABLA_COMPROBANTES^*]') !== false){
										$descripcion_actividad = str_replace('[^*TABLA_COMPROBANTES^*]', $html_tabla_comprobantes, $descripcion_actividad);

										$tm = $this->db->query("UPDATE actividades SET descripcion = ? WHERE id_actividad = ?", array($descripcion_actividad, $id_actividad));
										if(!$tm){
								    		$error = $this->db->error();
								    		return $error['message'];
								    	}
									}

							    	if(!empty($asignados_actividad)){
							    		$asignados_actividad = explode("***", $asignados_actividad);
							    		foreach ($asignados_actividad as $fila) {
								    		$this->asociacion_actividad($id_actividad, $fila);
								    	}
							    	}else{
							    		$asignados_actividad = $this->getUsuariosParaSelect();
							    		foreach ($asignados_actividad as $fila) {
								    		$this->asociacion_actividad($id_actividad, $fila["id_usuario"]);
								    	}
							    	}
								}
							}
						}

						if(strpos($accion, "Correo") !== false){
							if(!empty($puerto) && !empty($host) && !empty($correo) && !empty($contrasena)){
								$carpeta = $_SERVER['DOCUMENT_ROOT'].$this->config->item('carpeta_principal').'Plugin/archivos/'.$id_regla.'/';
								
								$this->mandarMail(
									$id_regla, $id_empresa, $cliente_mail, $nombre_cliente_mail,
									$asunto_mail, $contenido_mail, 'Automatico',
									$empresa, $dominio, $puerto, $host, $correo, $contrasena, $certificado_ssl,
									$carpeta, $adjuntos, $destinatarios_columnas, $destinatarios_fijos, $comprobantes_mail, array()
								);
							}
						}
					}
				}
			}

			$tm = $this->db->query("UPDATE REGLAS
				SET fecha = DATE_ADD(NOW(), INTERVAL $intervalo MINUTE)
				WHERE id_regla = '$id_regla'");
			if(!$tm){
	    		$error = $this->db->error();
	    		$this->db->trans_rollback();
	    		return $error['message'];
	    	}

	    	$tm = $this->db->query("update reglas set estado='Pausada' where fecha > fechaExpiracion and estado <>'Pausada'");
			if(!$tm){
	    		$error = $this->db->error();
	    		$this->db->trans_rollback();
	    		return $error['message'];
	    	}			

	    	
		}else{			
			$tm = $this->db->query("UPDATE REGLAS
					SET estado = 'Pausada'
					WHERE id_regla = '$id_regla'");
			if(!$tm){
	    		$error = $this->db->error();
	    		$this->db->trans_rollback();
	    		return $error['message'];
	    	}	
		}
	}

	function mandarMail(
	$id_regla, $id_empresa, $cliente_mail, $nombre_cliente_mail, $asunto_mail, $contenido_mail, $origen,
	$empresa, $dominio, $puerto, $host, $correo, $contrasena, $certificado_ssl,
	$carpeta, $adjuntos, $destinatarios_columnas, $destinatarios_fijos, $comprobantes_mail, $array_comprobantes
	){
		$destinatarios = "";
		if(!empty($destinatarios_columnas)){
			$destinatarios .= $destinatarios_columnas;
		}
		if(!empty($destinatarios_fijos)){
			if(!empty($destinatarios_columnas)){
				$destinatarios .= ";";
			}
			$destinatarios .= $destinatarios_fijos;
		}
		$id_correo_bd = "";

		if(!empty($destinatarios)){
			$nombre_cliente_mail = "";
			
			$array_cliente = json_decode($this->seguimientoModel->seleccionarCliente($cliente_mail, $empresa, $dominio), true);
			if(count($array_cliente) > 0){
				$nombre_cliente_mail = $array_cliente[0]["cliente"];

				if(strpos($contenido_mail, "[^*COLUMNA_Link*^]") !== false){
					$id_cliente_url = encrypt_url($cliente_mail);
					$id_empresa_url = encrypt_url($id_empresa);
					$contenido_mail = str_replace("[^*COLUMNA_Link*^]", '<a href="'.base_url().'vista_cliente?id='.$id_cliente_url.'&id_em='.$id_empresa_url.'">Ver Comprobantes</a>', $contenido_mail);	
				}
			}

			$id_correo_bd = $this->reglaModel->guardarMail($id_regla, $id_empresa, $cliente_mail, $nombre_cliente_mail, $destinatarios, $asunto_mail, $origen);

			$html_tabla_comprobantes = "";
			if($comprobantes_mail == "1" || $comprobantes_mail == "2"){
				if($comprobantes_mail == "1"){
					$array_comprobantes = json_decode($this->seguimientoModel->seleccionarComprobante($cliente_mail, $empresa, $dominio), true);
				}
				$html_tabla_comprobantes = $this->reglaModel->guardarComprobantes('mails', 'mail', $id_correo_bd, $array_comprobantes);
			}

			$contenido_mail = str_replace('[^*TABLA_COMPROBANTES^*]', $html_tabla_comprobantes, $contenido_mail);
			$contenido_mail_2 = $contenido_mail;
	    	while(!empty($contenido_mail_2)) {
	    		$descripcion_aux = substr($contenido_mail_2, 0, 8000);
	    		$tm = $this->db->query(
				"INSERT INTO mails_contenido 
				(id_mail, contenido) 
				VALUES 
				(?, ?)", array($id_correo_bd, $descripcion_aux));
				if(!$tm){
		    		$error = $this->db->error();
		    		$this->db->trans_rollback();
		    		return array($error['message']);
		    	}
		    	$contenido_mail_2 = substr($contenido_mail_2, 8000);
	    	}	    	
	    	$carpeta_destino = $_SERVER['DOCUMENT_ROOT'].$this->config->item('carpeta_principal').'Plugin/mail/'.$id_correo_bd.'/';
	    	if (!file_exists($carpeta_destino)) {
			    mkdir($carpeta_destino, 0777, true);
			}
			$carpeta_destino = str_replace('/', '\\', $carpeta_destino);
			$carpeta = str_replace('/', '\\', $carpeta);
	    	$adjunto_procesados = "";
			foreach ($adjuntos as $fila) {
				$tm = $this->db->query(
				"INSERT INTO mails_adjunto
				(id_mail, adjunto) 
				VALUES 
				(?, ?)", array($id_correo_bd, $fila["adjunto"]));
				if(!$tm){
		    		$error = $this->db->error();
		    		$this->db->trans_rollback();
		    		return array($error['message']);
		    	}
				$archivo_origen = $carpeta.$fila["adjunto"];
				$adjunto_procesados = $archivo_origen.";";
				
				$archivo_destino = $carpeta_destino.$fila["adjunto"];
				if (file_exists($archivo_origen)) {
					copy($archivo_origen, $archivo_destino);
				}
			}
			$adjunto_procesados = substr($adjunto_procesados, 0, -1);

			$post['id_regla'] = $id_regla;
			$post['id_correo_bd'] = $id_correo_bd;
			$post['puerto'] = $puerto;
			$post['host'] = $host;
			$post['correo'] = $correo;
			$post['contra'] = $contrasena;
			$post['certificado_ssl'] = $certificado_ssl;
			$post['separador'] = ";";
			$post['adjuntos'] = $adjunto_procesados;
			$post['nombre'] = "";
			$post['asunto'] = $asunto_mail;
			$contenido_mail .= '<img src="http://190.210.127.181:2052/tesis/correo/correoLeido/[^*DEST_ID^*]/[^*DEST_DESTINATARIO^*]" onerror=\'this.style.display = "none"\' alt="imagen" />';
			$contenido_mail = str_replace('[^*DEST_ID^*]', $id_correo_bd, $contenido_mail);
			$destinatarios = explode('***', $destinatarios);
			for($x = 0; $x < count($destinatarios); $x++) { 
				$contenido_mail_particular = $contenido_mail; 
				$dest = $destinatarios[$x];
				$contenido_mail_particular = str_replace ('[^*DEST_DESTINATARIO^*]', urlencode($dest), $contenido_mail_particular);
				$post['contenido'] = $contenido_mail_particular;
				$post['destinatarios'] = $dest;
			    $base_url = base_url();
				$url = $base_url."correo/enviar";
			    $curl = curl_init();
			    curl_setopt($curl, CURLOPT_URL, $url);
			    curl_setopt($curl, CURLOPT_POST, TRUE);
			    curl_setopt($curl, CURLOPT_POSTFIELDS, $post); 
			    curl_setopt($curl, CURLOPT_USERAGENT, 'api');
			    curl_setopt($curl, CURLOPT_TIMEOUT, 1); 
			    curl_setopt($curl, CURLOPT_HEADER, 0);
			    curl_setopt($curl, CURLOPT_RETURNTRANSFER, false);
			    curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
			    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
			    curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 10); 
			    curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
			    curl_exec($curl);
			    curl_close($curl);
			}
		}
		
		return array("OK", $id_correo_bd);
	}

	function guardarMail($id_regla, $id_empresa, $id_cliente, $cliente, $destinatarios, $asunto, $origen){
		$destinatarios = str_replace(";", "; ", $destinatarios);
		$tm = $this->db->query(
		"INSERT INTO mails 
		(id_regla, id_empresa, destinatarios, asunto, id_cliente, cliente, fecha, origen) 
		VALUES 
		(?, ?, ?, ?, ?, ?, NOW(), ?)", array($id_regla, $id_empresa, $destinatarios, $asunto, $id_cliente, $cliente, $origen));
		if(!$tm){
    		$error = $this->db->error();
    		return $error['message'];
    	}
		$id_mail = $this->db->insert_id();
 
    	return $id_mail;
	}

	function setearIdMensaje($uid_mail, $id_mail){
		$tm = $this->db->query(
		"UPDATE mails 
		SET uid_mail = ?
		WHERE id_mail = ?", array($uid_mail, $id_mail));
		if(!$tm){
    		$error = $this->db->error();
    		return $error['message'];
    	}
	}

	function getActividades(){
		$id_usuario = $this->session->userdata('id_usuario');
		$id_empresa = $this->session->userdata('id_empresa');

		$sql = "select DATE_FORMAT(actividades.fecha, '%d/%m/%Y') fecha, DATE_FORMAT(actividades.fecha, '%T') hora, actividades.asunto, actividades.estado, actividades.id_actividad, ifnull(actividades_asociacion.leido, 0) leido
		from actividades
		inner join actividades_asociacion on actividades.id_actividad = actividades_asociacion.id_actividad
		where actividades_asociacion.id_usuario = ? and actividades.id_empresa = ?
		order by actividades.fecha desc";
		$stmt = $this->db->query($sql, array($id_usuario, $id_empresa));
		
		return $stmt->result_array();
	}

	function asociacion_actividad($id_actividad, $id_usuario){
		$tm = $this->db->query(
		"INSERT INTO actividades_asociacion 
		(id_actividad, id_usuario) 
		VALUES 
		(?, ?)", array($id_actividad, $id_usuario));
		return $tm;
	}

	function guardarComprobantes($tipo, $tipo_singular, $id, $array_comprobantes){
		$html_tabla_comprobantes = "";		
		if(count($array_comprobantes) > 0){
			$html_tabla_comprobantes = 
			"<table>
			<tr>
				<th>Tipo</th><th>Comprobante</th><th>Emisión</th><th>Vencimiento</th><th>Importe</th><th>Días</th>
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
				$comp["id_".$tipo_singular] = $id;
				$comp["estado"] = "Pendiente";
				if(isset($comp["dias"])){
					unset($comp["dias"]);
				}
				$tm = $this->db->insert($tipo."_comprobantes", $comp);				
			}
			$html_tabla_comprobantes .= "</table>";
		}
		return $html_tabla_comprobantes;
	}

	function sincronizar_comprobantes(){
		$sql = 
	    "SELECT licencias.dominio, empresas.empresa, empresas.id_empresa
        FROM licencias
        INNER JOIN empresas on licencias.id_licencia = empresas.id_licencia";
		$stmt = $this->db->query($sql);
		$licencias = $stmt->result_array();
		foreach ($licencias as $licencia) {
			$dominio = $licencia["dominio"];
			$empresa = $licencia["empresa"];
			$id_empresa = $licencia["id_empresa"];
			log_message("error", $dominio. " ".$empresa);
			$curl = curl_init();
			$url = $dominio."/api/comprobantesTodos";
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_POST, TRUE);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
			curl_setopt($curl, CURLOPT_POSTFIELDS, 'empresa='.$empresa);
		    curl_setopt($curl, CURLOPT_USERAGENT, 'api');
		    curl_setopt($curl, CURLOPT_TIMEOUT, 2); 
		    curl_setopt($curl, CURLOPT_HEADER, 0);
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
		    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
		    curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 10); 
		    curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
		    $comprobantes = curl_exec($curl);
		    $comprobantes = json_decode($comprobantes, true);		    
		    if(isset($comprobantes[0]["comprobante"])){
				foreach ($comprobantes as $fila) {
			    	$comprobante = $fila["comprobante"];
			    	$tipo = $fila["tipo"];
			    	$estado = $fila["estado"];

			    	$tm = $this->db->query("
			    	UPDATE actividades_comprobantes 
			    	INNER JOIN actividades on actividades_comprobantes.id_actividad = actividades.id_actividad
			    	SET actividades_comprobantes.estado = ? 
			    	WHERE actividades_comprobantes.comprobante = ? AND actividades_comprobantes.tipo = ? AND actividades.id_empresa = ?", array($estado, $comprobante, $tipo, $id_empresa));

			    	$tm = $this->db->query("
			    	UPDATE mails_comprobantes 
			    	INNER JOIN mails on mails_comprobantes.id_mail = mails.id_mail
			    	SET mails_comprobantes.estado = ? 
			    	WHERE mails_comprobantes.comprobante = ? AND mails_comprobantes.tipo = ? AND mails.id_empresa = ?", array($estado, $comprobante, $tipo, $id_empresa));
			    }
			}
		}
	}

	function parametrosMail($id_empresa){
		$sql = 
	    "SELECT *
        FROM mails_config
        WHERE id_empresa = ?";
		$stmt = $this->db->query($sql, array($id_empresa));
		$mails_config = $stmt->result_array();
		foreach ($mails_config as $fila_mail) {
			$correo = $fila_mail["correo"];
			$contrasena = $fila_mail["contrasena"];
			$this->load->library('encrypt');
			$contrasena = $this->encrypt->decode($contrasena);
			$puerto = $fila_mail["puerto"];
			$host = $fila_mail["host"];
			$certificado_ssl = $fila_mail["certificado_ssl"];
		}
		if(empty($puerto) || empty($host) || empty($correo) || empty($contrasena)){
			$correo = "crmflow2017@gmail.com";
			$contrasena = "neestor1";
			$host = "smtp.gmail.com";
			$puerto = "25";
			$certificado_ssl = "1";
		}
		return array("correo" => $correo, "contrasena" => $contrasena, "host" => $host, "puerto" => $puerto, "certificado_ssl" => $certificado_ssl);
	}

	//FUNCIONES QUE VIENEN DE SQL SERVER	
	function verificar_consulta($consulta, $columnas, $dominio){
		$curl = curl_init();
		$url = $dominio."/api/verificar_consulta";
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, TRUE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		curl_setopt($curl, CURLOPT_POSTFIELDS, 'consulta='.$consulta.'&columna='.$columnas);
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
	
	function getConsultaExterna(){
		$diccionario = $this->session->userdata('diccionario');
		$sql = "select id_consultaexterna, titulo_consultaexterna from $diccionario..tlconsultaexterna";
		$stmt = $this->sqlserver->query($sql);
		return $stmt->result_array();
	}
}