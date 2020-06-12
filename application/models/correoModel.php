<?php
class CorreoModel extends CI_Model{
	function __construct(){
		parent::__construct();
	}

	public function enviarCorreo($asunto, $contenido,  $destinatarios, $adjuntos , $correo = '', $contra = '', $nombre = '', $host = '',$puerto =''){
		$url = base_url()."correo/enviar";
	    $curl = curl_init();    
	    $post = array();            
		if ($correo != ''){
			$post['correo']= $correo;
			$post['contra']= $contra;
			$post['nombre']= $nombre;
			$post['host']= $host;
			$post['puerto']= $puerto;
		}
		$post['contenido'] = $contenido;
		$post['asunto'] = $asunto;
	    $post['adjuntos'] = $adjuntos;
	    $post['destinatarios'] = $destinatarios;
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
?>