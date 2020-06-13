<?php
class ReglaModel extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->sqlserver = $this->load->database('sqlsrv',TRUE);
	}

	function getReglas(){
		$id_usuario = $this->session->userdata('id_usuario');
		$id_licencia = $this->session->userdata('id_licencia');
		$sql = "select *, DATE_FORMAT(fecha, '%d/%m/%Y %T') fecha2 from reglas where id_licencia = ? and id_usuario = ?";
		$stmt = $this->db->query($sql, array($id_licencia, $id_usuario));
		$reglas = $stmt->result_array();
		return $reglas;
	}

	function getReglaPorId($id_regla, $ejecucion = ''){
		$sql_select = 
		"select * 
		from reglas
		inner join reglas_accion on reglas.id_regla = reglas_accion.id_regla 
		where reglas.id_regla = ?";
		$stmt = $this->db->query($sql_select, array($id_regla));
		$data = $stmt->result_array();		
		if(count($data) > 0){
			$data = $data[0];
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

			$descripcion_alerta = "";
			$sql_select = 
			"select consulta 
			from reglas_contenido
			where id_regla = ? and relacionado = 'Alerta'";
			$stmt = $this->db->query($sql_select, array($id_regla));
			$alertas = $stmt->result_array();
			foreach ($alertas as $fila) {
				$descripcion_alerta .= $fila["consulta"];
			}		
			$data["descripcion_alerta"] = $descripcion_alerta;

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
		$id_usuario = $this->session->userdata('id_usuario');

		$sql = "select reglas.asunto
		from reglas
		where reglas.id_regla = ? and id_usuario = ?";
		$stmt = $this->db->query($sql, array($id_regla, $id_usuario));		
		$reglas = $stmt->result_array();
		$data = array();
		if(count($reglas) > 0){
			$data["asunto"] = $reglas[0]["asunto"];

			$sql = "select alertas.descripcion, DATE_FORMAT(alertas.fecha, '%d/%m/%Y') fecha, DATE_FORMAT(alertas.fecha, '%T') hora, alertas.leido
			from reglas 
			inner join alertas on reglas.id_regla = alertas.id_regla 
			where reglas.id_regla = ? and id_usuario = ?
			order by alertas.fecha desc";
			$stmt = $this->db->query($sql, array($id_regla, $id_usuario));
			$data["alertas"] = $stmt->result_array();

			$sql = "select mails.*
			from reglas 
			inner join mails on reglas.id_regla = mails.id_regla 
			where reglas.id_regla = ? and id_usuario = ?
			order by mails.id_mail desc";
			$stmt = $this->db->query($sql, array($id_regla, $id_usuario));
			$data["mails"] = $stmt->result_array();
		}
		return $data;
	}

	function getCorreoPorIdCorreo($id_correo){
		$data =array();
		//faltan los adjuntos
		$sql = "select mails.*, reglas.asunto as reglaAsunto 
			from mails 
			inner join reglas on reglas.id_regla = mails.id_regla 
			where mails.id_mail = $id_correo";
			$stmt = $this->db->query($sql);
		$data['correo']= $stmt->result_array()[0];

		$sql = "select * from mails_leidos
			where id_correo = $id_correo";
		$stmt = $this->db->query($sql);
		$leidos =$stmt->result_array();
		$data['leidos'] = array();
		if (count($leidos)){
			$data['leidos']= $leidos; 	
		}
		

		$sql_select = "select contenido from mails_contenido where id_mail = $id_correo";
		$stmt = $this->db->query($sql_select);
		$consultas_externas = $stmt->result_array();
		$contenido_mail = '';
		foreach ($consultas_externas as $fila) {
			$contenido_mail .= $fila["contenido"];
		}
		$data["contenido_mail"] = $contenido_mail;

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
		$asunto, $intervalo, $accion, $estado, 
		$consulta, 
		$correo, $contrasena, $puerto, $host, $certificado_ssl, $destinatario_fijos, $destinatario_columnas, $asunto_mail, $contenido_mail,
		$tipo_alerta, $descripcion_alerta
	){
		$this->db->trans_begin();
		$id_usuario = $this->session->userdata('id_usuario');
		$id_licencia = $this->session->userdata('id_licencia');

		$fecha = strtotime(date('Y-m-d H:i:s')); 
		$fecha = strtotime('+'.$intervalo.' minute', $fecha); 
		$fecha = date('Y-d-m H:i:s' , $fecha); 

		//encabezado
		$objeto = array("asunto" => $asunto, "intervalo" => $intervalo, "fecha" => $fecha, "accion" => $accion, "estado" => $estado, "id_usuario" => $id_usuario, "id_licencia" => $id_licencia);
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
		(id_regla, correo, contra, puerto, host, destinatario_columnas, destinatario_fijos, asunto_mail, tipo_alerta) 
		VALUES 
		(?, ?, ?, ?, ?, ?, ?, ?, ?)", array($id_regla, $correo, $contrasena, $puerto, $host, $destinatario_columnas, $destinatario_fijos, $asunto_mail, $tipo_alerta));
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

    	//alerta
    	while(!empty($descripcion_alerta)) {
    		$descripcion_aux = substr($descripcion_alerta, 0, 8000);
    		$tm = $this->db->query(
			"INSERT INTO reglas_contenido 
			(id_regla, consulta, relacionado) 
			VALUES 
			(?, ?, 'Alerta')", array($id_regla, $descripcion_aux));
			if(!$tm){
	    		$error = $this->db->error();
	    		$this->db->trans_rollback();
	    		return array($error['message']);
	    	}
	    	$descripcion_alerta = substr($descripcion_alerta, 8000);
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
		$carpeta = $_SERVER['DOCUMENT_ROOT'].'/'.$this->config->item('carpeta_principal').'/Plugin/archivos/'.$id_regla;
		$this->deleteDir($carpeta);
	}

	function deleteDir($dirPath) {
	   	if (!is_dir($dirPath)) {
	        throw new InvalidArgumentException("$dirPath must be a directory");
	    }
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
	    if (is_dir($dirPath)) {
	    	rmdir($dirPath);
		}
	}

	function reglas_a_ejecutar(){
		$sql = 
	    "SELECT id_regla
        FROM reglas
        WHERE
	    (TIMESTAMPDIFF(MINUTE, fecha, NOW()) = 0 or TIMESTAMPDIFF(MINUTE, fecha, NOW()) > 60) 
	    AND IFNULL(estado, '') <> 'Pausada'";
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
		$tipo_alerta = ""; 
		$descripcion_alerta = ""; 

		/* TOMO LAS REGLAS DE NEGOCIO CON SUS RESPECTIVOS DATOS*/
		$data = $this->getReglaPorId($id_regla, '1');		
		if(count($data) > 0){
			$id_licencia = $data["id_licencia"];
			$asunto = $data["asunto"];
			$intervalo = $data["intervalo"];
			$accion = $data["accion"]; 
			$estado = $data["estado"];
			$consulta = $data["consulta"];
			$correo = $data["correo"];
			$contrasena = $data["contra"];
			$this->load->library('encrypt');
			$contrasena = $this->encrypt->decode($contrasena);
			$puerto = $data["puerto"];
			$host = $data["host"];
			$destinatarios_columnas = $data["destinatario_columnas"];
			$destinatarios_fijos = $data["destinatario_fijos"];
			$asunto_mail = $data["asunto_mail"];
			$contenido_mail = $data["contenido_mail"];
			$adjuntos = $data["adjuntos"];		
			$tipo_alerta = $data["tipo_alerta"];
			$descripcion_alerta = $data["descripcion_alerta"];

			$asunto_mail_fijo = $asunto_mail;
			$contenido_mail_fijo = $contenido_mail;
			$descripcion_alerta_fijo = $descripcion_alerta;
			$destinatarios_columnas_fijo = $destinatarios_columnas;
			if(!empty($consulta)){
				$dominio = "";
				$sql = 
			    "SELECT dominio
		        FROM licencias
		        WHERE id_licencia = ?";
				$stmt = $this->db->query($sql, array($id_licencia));
				$licencias = $stmt->result_array();
				foreach ($licencias as $licencia) {
					$dominio = $licencia["dominio"];
				}

				$sql_columnas = json_decode($this->verificar_consulta($consulta, "0", $dominio),true);
				if(count($sql_columnas) > 0){
					$keys = array_keys($sql_columnas[0]);
					$cantidad_key = count($keys);
					foreach($sql_columnas as $sql_columna){
						$asunto_mail = $asunto_mail_fijo;
						$contenido_mail = $contenido_mail_fijo;
						$descripcion_alerta = $descripcion_alerta_fijo;
						$destinatarios_columnas = $destinatarios_columnas_fijo;

						/*modifico el email_contenido y email_asunto para obtener los valores de verdad */
						for($i = 0; $i < $cantidad_key; $i++){
							$asunto_mail = str_replace("[^*COLUMNA_".$keys[$i]."^*]", $sql_columna[$keys[$i]], $asunto_mail);
							$contenido_mail = str_replace("[^*COLUMNA_".$keys[$i]."^*]", $sql_columna[$keys[$i]], $contenido_mail);
							$descripcion_alerta = str_replace("[^*COLUMNA_".$keys[$i]."^*]", $sql_columna[$keys[$i]], $descripcion_alerta);
							$destinatarios_columnas = str_replace("[^*COLUMNA_".$keys[$i]."^*]", $sql_columna[$keys[$i]], $destinatarios_columnas);
						}

						if(strpos($accion, "Alerta") !== false){
							if(!empty($descripcion_alerta)){
								$tm = $this->db->query(
								"INSERT INTO alertas 
								(id_regla, descripcion, tipo, fecha) 
								VALUES 
								(?, ?, ?, NOW())", array($id_regla, $descripcion_alerta, $tipo_alerta));
								if(!$tm){
						    		$error = $this->db->error();
						    		return $error['message'];
						    	}
							}
						}
						if(strpos($accion, "Correo") !== false){
							if(!empty($puerto) && !empty($host) && !empty($correo) && !empty($contrasena)){
								$adjunto_procesados = "";
								$carpeta = $_SERVER['DOCUMENT_ROOT'].'/'.$this->config->item('carpeta_principal').'/Plugin/archivos/'.$id_regla.'/';
								foreach ($adjuntos as $fila) {
									$adjunto_procesados = $carpeta.$fila["adjunto"].";";
								}
								$adjunto_procesados = substr($adjunto_procesados, 0, -1);

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
								$id_correo_bd =$this->reglaModel->guardarMail($id_regla, $destinatarios, $asunto_mail,$contenido_mail);
								if(!empty($destinatarios)){
									$post['id_regla'] = $id_regla;
									$post['puerto'] = $puerto;
									$post['host'] = $host;
									$post['correo'] = $correo;
									$post['contra'] = $contrasena;
									$post['separador'] = ";";
									$post['adjuntos'] = $adjunto_procesados;
									$post['nombre'] = "";
									$post['asunto'] = $asunto_mail;
									//src="http://190.210.127.181:2052/tesis/correo/correoLeido/[^*DEST_ID^*]/[^*DEST_DESTINATARIO^*]"
									$contenido_mail = str_replace ('[^*DEST_ID^*]', $id_correo_bd, $contenido_mail);
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

	function guardarMail($id_regla, $destinatarios, $asunto,  $contenido,$mail =''){
		$uid_mail ='';
		if ($uid_mail != ''){
			$uid_mail = $mail->getMensajeId();	
		}
		$destinatarios = str_replace(";", "; ", $destinatarios);
		$tm = $this->db->query(
		"INSERT INTO mails 
		(id_regla, destinatarios, asunto, uid_mail) 
		VALUES 
		(?, ?, ?, ?)", array($id_regla, $destinatarios, $asunto, $uid_mail));
		if(!$tm){
    		$error = $this->db->error();
    		return $error['message'];
    	}
		$id_mail = $this->db->insert_id();

    	while(!empty($contenido)) {
    		$descripcion_aux = substr($contenido, 0, 8000);
    		$tm = $this->db->query(
			"INSERT INTO mails_contenido 
			(id_mail, contenido) 
			VALUES 
			(?, ?)", array($id_mail, $descripcion_aux));
			if(!$tm){
	    		$error = $this->db->error();
	    		$this->db->trans_rollback();
	    		return array($error['message']);
	    	}
	    	$contenido = substr($contenido, 8000);
    	} 
    	return $id_mail;
	}

	function getAlertas(){
		$id_usuario = $this->session->userdata('id_usuario');

		$sql = "select alertas.descripcion, DATE_FORMAT(alertas.fecha, '%d/%m/%Y') fecha, DATE_FORMAT(alertas.fecha, '%T') hora, alertas.leido
		from reglas 
		inner join alertas on reglas.id_regla = alertas.id_regla 
		where id_usuario = ? and tipo = 'Notificación'
		order by alertas.fecha desc";
		$stmt = $this->db->query($sql, array($id_usuario));
		
		//marco a todos como leido
		$tm = $this->db->query(
		"UPDATE alertas
		INNER JOIN reglas ON reglas.id_regla = alertas.id_regla 
		SET leido = '1'
		WHERE id_usuario = ?", array($id_usuario));
		
		return $stmt->result_array();
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