<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cobranza extends CI_Controller {

	public function detalles(){
		if ($this->session->userdata('id_usuario')){
			$t_comp = isset($_GET["t_comp"])?$_GET["t_comp"]:"";
			$n_comp = isset($_GET["n_comp"])?$_GET["n_comp"]:"";

			$cobranza = $this->obtenerDatosEncabezadoYPie(strtoupper($n_comp), strtoupper($t_comp));
			if($cobranza['return'] == "OK"){
				$this->configuracionModel->getHeader();
				$this->load->view('Cobranza/cabecera', $cobranza);
				$this->load->view('Cobranza/pie', $cobranza);		
				$this->load->view('menu/footer');	
			}else{
				
			}	
		}else{
			$this->session->set_flashdata('url',current_url());
			redirect(base_url('login'));
		}	
	}

	private function obtenerDatosEncabezadoYPie($n_comp, $t_comp){
		if ($this->session->userdata('id_usuario')){
			$registrants_datos = $this->encabezadoYPie($n_comp, $t_comp);

			if(count($registrants_datos) > 0){
				$cobranza = $registrants_datos[0];
				$importe_pesos = $cobranza["importe_pesos"];						
				$maximo = $this->obtenerMaximoPrecio($n_comp, $t_comp);
				$cobranza['saldo'] = $importe_pesos - $maximo;
				$cobranza['n_comp'] = $n_comp;
				$cobranza['t_comp'] = $t_comp;
				$cobranza['t_comp_aux'] = $this->obtenerTipoComprabanteAuxiliar($n_comp, $t_comp);
				$cobranza['return'] = "OK";
				return $cobranza;
			}else{
				$cobranza['return'] = "NO";
				return $cobranza;
			}
		}			
	}

	private function obtenerTipoComprabanteAuxiliar($n_comp, $t_comp){
		$t_comp_aux = '';
		if ($t_comp !=='FAC' && $t_comp !=='N/C' && $t_comp !=='N/D'){
			$dominio = $this->session->userdata('dominio');
			$empresa = $this->session->userdata('empresa');
			$curl = curl_init();
			$url = $dominio."/api/obtenerTipoComprabanteAuxiliar";
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_POST, TRUE);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
			curl_setopt($curl, CURLOPT_POSTFIELDS, 't_comp='.$t_comp.'&empresa='.$empresa);
		    curl_setopt($curl, CURLOPT_USERAGENT, 'api');
		    curl_setopt($curl, CURLOPT_TIMEOUT, 2); 
		    curl_setopt($curl, CURLOPT_HEADER, 0);
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
		    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
		    curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 10); 
		    curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
		    $registrants_tcomp = curl_exec($curl);
		    $registrants_tcomp = json_decode($registrants_tcomp, true);
			foreach($registrants_tcomp as $registrant_tcomp) {
				$t_comp_aux = trim($registrant_tcomp['clase_operacion']);
			}
		}else{
			$t_comp_aux = $t_comp;
		}
		return $t_comp_aux;
	}	

	private function encabezadoYPie($n_comp, $t_comp){
		$dominio = $this->session->userdata('dominio');
		$empresa = $this->session->userdata('empresa');
		$curl = curl_init();
		$url = $dominio."/api/encabezadoYPie";
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, TRUE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		curl_setopt($curl, CURLOPT_POSTFIELDS, 't_comp='.$t_comp.'&n_comp='.$n_comp.'&empresa='.$empresa);
		curl_setopt($curl, CURLOPT_USERAGENT, 'api');
		curl_setopt($curl, CURLOPT_TIMEOUT, 2); 
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
		curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 10); 
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
		$registrants_datos = curl_exec($curl);
		$registrants_datos = json_decode($registrants_datos, true);
		return $registrants_datos;
	}

	private function obtenerMaximoPrecio($n_comp, $t_comp){
		$maximo = "0";
		$dominio = $this->session->userdata('dominio');
		$empresa = $this->session->userdata('empresa');
		$curl = curl_init();
		$url = $dominio."/api/obtenerMaximoPrecio";
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, TRUE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		curl_setopt($curl, CURLOPT_POSTFIELDS, 't_comp='.$t_comp.'&n_comp='.$n_comp.'&empresa='.$empresa);
	    curl_setopt($curl, CURLOPT_USERAGENT, 'api');
	    curl_setopt($curl, CURLOPT_TIMEOUT, 2); 
	    curl_setopt($curl, CURLOPT_HEADER, 0);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
	    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
	    curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 10); 
	    curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
	    $registrants_datos = curl_exec($curl);
	    $registrants_datos = json_decode($registrants_datos, true);
		foreach ($registrants_datos as $registrant_datos){
			$maximo = $registrant_datos['MAXIMO'];
		}
		return $maximo;		
	}

	public function comprobante_actividades(){
		if ($this->session->userdata('id_usuario')){
			$t_comp = isset($_POST["t_comp"])?$_POST["t_comp"]:"";
			$n_comp = isset($_POST["n_comp"])?$_POST["n_comp"]:"";
			echo json_encode($this->seguimientoModel->comprobante_actividades($n_comp, $t_comp));
		}
	}
}