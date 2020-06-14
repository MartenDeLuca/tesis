<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Correo extends CI_Controller {

	public function enviar(){
		ini_set('memory_limit', '2048M');
		ini_set('max_execution_time', 3000);
		$this->load->library("phpmailer_library");
	    $mail = $this->phpmailer_library->load();
		if (isset($_POST['correo'])){
			$correo = $_POST['correo'];
			$contra = $_POST['contra'];	
			$nombre = $_POST['nombre'];	
			$host= $_POST['host'];	
			$puerto= $_POST['puerto'];	
		} else {
			$correo = "crmflow2017@gmail.com";
			$contra = "neestor1";
			$nombre = 'SIMPLAPP';
			$host="smtp.gmail.com";
			$puerto="25";
		}
		if(!isset($_POST['separador'])){
			$separador = '***';
		}else{
			$separador = $_POST['separador'];
		}
		$destinatarios = $_POST['destinatarios'];
		$destinatarios_plano = $_POST['destinatarios'];
		$destinatarios = explode($separador, $destinatarios);
		$adjuntos = $_POST['adjuntos'];
		$adjuntos = explode($separador, $adjuntos);
		$asunto = $_POST['asunto'];
		$contenido = $_POST['contenido'];
		$mail->SMTPDebug = 0;
		$mail->Debugoutput = 'html';
		if(!isset($_POST['certificado_ssl'])){
			$mail->SMTPOptions = array('ssl' => array('verify_peer' => false,'verify_peer_name' => false,'allow_self_signed' => true));
		}else{
			if($_POST['certificado_ssl'] == "Si"){
				$mail->SMTPOptions = array('ssl' => array('verify_peer' => false,'verify_peer_name' => false,'allow_self_signed' => true));
			}else{
				$mail->SMTPOptions = array();
			}
		}
		$mail->Host = $host;
		$mail->Port = $puerto;
		$mail->isSMTP();
		$mail->SMTPSecure = 'tls';
		$mail->SMTPAuth = true;
		$mail->Username = $correo; 
		$mail->Password = $contra;

		for($i = 0; $i < count($destinatarios); $i++) { 
			$correos = $destinatarios[$i];
			$result = true;//(false !== filter_var($correos, FILTER_VALIDATE_EMAIL));
			//if ($result){
				$mail->addAddress($correos, '');
				$mail->addBcc($correos);
			//}
		}

		if(isset($_POST['id_regla'])){
			$ruta = "";
		}else{
			$ruta = $_SERVER['DOCUMENT_ROOT'].'/'.$this->config->item('carpeta_principal').'/Plugin/modulos/correo/archivos/';
		}
		for($k=0; $k < count($adjuntos); $k++) { 
			if ($adjuntos[$k] != ''){
				$archivo = $ruta.$adjuntos[$k];
				if(file_exists($archivo)){
					$mail->addAttachment($archivo);		
				}
			}
		}

		$mail->setFrom($correo, $nombre);
		$mail->addReplyTo($correo, $nombre);
		$mail->AddBCC($correo, $name = $nombre);
		$mail->Sender = $correo;
		$mail->Subject = $asunto;
		$mail->msgHTML($contenido);
		$mail->AltBody = $contenido;
		$mail->CharSet = 'UTF-8';

		if (!$mail->send()) {
			echo 'error';			
		}else {
			echo 'OK';
		}
	}

	public function correoLeido($id_correo, $destinatario){
		$this->correoModel->marcarLeido($id_correo,urldecode($destinatario));
	}
}