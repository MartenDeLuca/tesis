<?php
class CorreoModel extends CI_Model{
	function __construct(){
		parent::__construct();
	}

	function enviarCorreo($asunto, $contenido, $destinatarios, $adjuntos, $correo = '', $contra = '', $nombre = '', $host = '',$puerto = ''){
		$url = base_url()."correo/enviar";
	    $curl = curl_init();    
	    $post = array();            
		if ($correo != ''){
			$post['correo'] = $correo;
			$post['contra'] = $contra;
			$post['nombre'] = $nombre;
			$post['host'] = $host;
			$post['puerto'] = $puerto;
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

	function marcarLeido($id_correo, $destinatario){
		$sql = "select id_correo from mails_leidos where id_correo = ? and destinatario = ?";
		$stmt = $this->db->query($sql, array($id_correo, $destinatario));
		$registros = $stmt->result_array();
		if (count($registros) == 0){
			$tm = $this->db->query("INSERT INTO mails_leidos (id_correo, destinatario) VALUES (?, ?)", array($id_correo, $destinatario));
		}
	}

	function conectar(){
		$puerto_lectura = "993";
		$host_lectura = "imap.gmail.com";
		$username = "martin@grupotesys.com.ar";
		$password = "Martin#1988";
		$host = "{".$host_lectura.":".$puerto_lectura."/imap/ssl}INBOX";
		
		if(!$imap = imap_open($host, $username, $password)){
			$imap = "Ha fallado la conexion: Verifique su correo y contraseña o apruebe los permisos accesos de su casilla.";
		}
		
		return $imap;
	}

	function downloadAttachment($uid, $partNum, $encoding, $path, $imap, $tipo, $body) {
	    //$partStruct = imap_bodystruct($imap, imap_msgno($imap, $uid), $partNum);
		$filename = $path;
	    $message = imap_fetchbody($imap, $uid, $partNum, FT_UID);
	    switch ($encoding) {
	        case 0:
	        case 1:
	            $message = imap_8bit($message);
	            break;
	        case 2:
	            $message = imap_binary($message);
	            break;
	        case 3:
	            $message = imap_base64($message);
	            break;
	        case 4:
	            $message = quoted_printable_decode($message);
	            break;
	    }
	    $fecha = date('dmYHisu');	
	    $unique_filename = $fecha.strtolower($filename);
	    if ($tipo == "INLINE"){
	    	preg_match_all('/src="cid:(.*)"/Uims', $body, $matches);
	    	if(count($matches)) {
	            $search = array();
	            $replace = array();
	            $contador=count($matches[1])-1;
	            if ($contador != '-1'){
	            	$match=$matches[1][$contador];
                	$search = "src=\"cid:$match\"";
                	$replace = "src='http://190.210.127.181:2052/archivosticket/$unique_filename'";
                	$body = str_replace($search, $replace, $body);	
	            }
	            
	        }
	    }
	    $carpeta ="\\\\192.168.0.211\\htdocs\\archivosticket";
		chmod($carpeta, 0777);
		$file_location = $carpeta."\\".$unique_filename;
		//$file_location = "\\\\192.168.0.148\\htdocs\\tickets\\archivos\\".$unique_filename;
		//chmod($file_location, 0777);
		file_put_contents($file_location,$message);
		$datos= array("body" => $body, "nombreArchivo" => $unique_filename);
		return $datos;
	}

	function getBody($uid, $imap) {
		$body = $this->get_part($imap, $uid, "TEXT/HTML");
	    if ($body == "") {
		    $body = $this->get_part($imap, $uid, "TEXT/PLAIN");
	    }
	    return $body;
	}

	function get_mime_type($structure) {
	    $primaryMimetype = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER");

	    if ($structure->subtype) {
	       return $primaryMimetype[(int)$structure->type] . "/" . $structure->subtype;
	    }
	    return "TEXT/PLAIN";
	}

	function get_part($imap, $uid, $mimetype, $structure = false, $partNumber = false) {
	    if (!$structure) {	    	
	        $structure = imap_fetchstructure($imap, $uid, FT_UID);
	    }
	    if ($structure) {
	        if ($mimetype == $this->get_mime_type($structure)) {
	            if (!$partNumber) {
	                $partNumber = 1;
	            }
	            $text = imap_fetchbody($imap, $uid, $partNumber, FT_UID);
	            
	            switch ($structure->encoding) {
	                case 3: return imap_base64($text);
	                case 4: return imap_qprint($text);
	                default: return $text;
	           }
	       	}

	        // multipart 
	        if ($structure->type == 1) {
	            foreach ($structure->parts as $index => $subStruct) {
	                $prefix = "";
	                if ($partNumber) {
	                    $prefix = $partNumber . ".";
	                }
	                $data = $this->get_part($imap, $uid, $mimetype, $subStruct, $prefix . ($index + 1));
	                if ($data) {
	                    return $data;
	                }
	            }
	        }
	    }
	    return false;
	}

	function getAttachments($imap, $mailNum, $part, $partNum) {
	    $attachments = array();	    
	    if (isset($part->parts)) {	    		    	
	        foreach ($part->parts as $key => $subpart) {
	            if($partNum != "") {
	                $newPartNum = $partNum . "." . ($key + 1);
	            }
	            else {
	                $newPartNum = ($key+1);
	            }	            
	            $result = $this->getAttachments($imap, $mailNum, $subpart,
	                $newPartNum);
	            if (count($result) != 0) {
	            	
	            	if(!isset($result[0])){
	            		array_push($attachments, $result);	
	            	}else{
	            		$attachments = $result;
	            	}
	            }
	        }
	    } else if (isset($part->disposition)) {
            $partStruct = imap_bodystruct($imap, $mailNum, $partNum);
            $nombreArchivo = "";
            if(isset($part->dparameters)){
	            for ($i=0;$i < count($part->dparameters);$i++){
	            	if ($part->dparameters[$i]->attribute == "FILENAME"){
	            		$nombreArchivo = $part->dparameters[$i]->value;
	            		break;
	            	}
	            }
	            $idArchivoCorreo="";
	            if (isset($part->id)){
	            	$idArchivoCorreo=$part->id;
	            }
	            
	            $encod = '3';
	            if (isset($partStruct->encoding)){
	            	$encod = $partStruct->encoding;
	            }
	            
				$attachmentDetails = array(
	                "name"    => $nombreArchivo,
	                "partNum" => $partNum,
	                "enc"     => $encod,
	                "bytes"		=>$part->bytes,
	                "tipo"		=>$part->disposition, 
	                "id"		=>$idArchivoCorreo
	            );
	            return $attachmentDetails;
	        }
	    }else if(isset($part->ifid)){
	    	if($part->ifid == "1"){
	    		$partStruct = imap_bodystruct($imap, $mailNum, $partNum);
	            $nombreArchivo = "";	            
	            if(isset($part->parameters)){
		            for ($i=0;$i < count($part->parameters);$i++){
		            	if ($part->parameters[$i]->attribute == "FILENAME" || $part->parameters[$i]->attribute == "NAME"){
		            		$nombreArchivo = $part->parameters[$i]->value;
		            		break;
		            	}
		            }
		            $idArchivoCorreo="";
		            if (isset($part->id)){
		            	$idArchivoCorreo=$part->id;
		            }
		            
		            $encod = '3';
		            if (isset($partStruct->encoding)){
		            	$encod = $partStruct->encoding;
		            }
		            
					$attachmentDetails = array(
		                "name"    => $nombreArchivo,
		                "partNum" => $partNum,
		                "enc"     => $encod,
		                "bytes"	  => $part->bytes,
		                "tipo"	  => "INLINE", 
		                "id"	  => $idArchivoCorreo
		            );
		            return $attachmentDetails;
		        }
	    	}
	    }
	    return $attachments;
	}
}
?>