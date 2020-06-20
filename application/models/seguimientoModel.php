<?php
class SeguimientoModel extends CI_Model{
	function __construct(){
		parent::__construct();	
	}

	function mailsDeContactos($id_cliente){
		$dominio = $this->session->userdata('dominio');
		$empresa = $this->session->userdata('empresa');

		$curl = curl_init();		
		$url = $dominio."/api/buscarContactos";
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, TRUE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		curl_setopt($curl, CURLOPT_POSTFIELDS, 'id_cliente='.$id_cliente.'&consulta=&empresa='.$empresa);
	    curl_setopt($curl, CURLOPT_USERAGENT, 'api');
	    curl_setopt($curl, CURLOPT_TIMEOUT, 2); 
	    curl_setopt($curl, CURLOPT_HEADER, 0);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
	    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
	    curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 10); 
	    curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
	    $result = curl_exec($curl);	    
	    $result = json_decode($result, true);
	    $opciones_contactos = "";
	    foreach ($result as $fila) {
	    	if($fila["correo"] != ""){
	    		$opciones_contactos .= "<option>".$fila["correo"]."</option>";
	    	}
	    }
	    return $opciones_contactos;
	}

	function getActividadComprobante($tipo, $comprobante){
		$sql_select = 
		"select *
		from (
			select forma_pago, DATE_FORMAT(fecha_pago, '%d/%m/%Y') fecha_pago, observaciones, actividades.fecha 
			from actividades_comprobantes 
			inner join actividades on actividades.id_actividad = actividades_comprobantes.id_actividad
			where actividades_comprobantes.comprobante = ? and actividades_comprobantes.tipo = ? and (actividades_comprobantes.forma_pago <> '' or actividades_comprobantes.fecha_pago <> '' or actividades_comprobantes.observaciones <> '')
			union 
			select forma_pago, DATE_FORMAT(fecha_pago, '%d/%m/%Y %T') fecha_pago, observaciones, mails.fecha
			from mails_comprobantes 
			inner join mails on mails.id_mail = mails_comprobantes.id_mail
			where mails_comprobantes.comprobante = ? and mails_comprobantes.tipo = ? and (mails_comprobantes.forma_pago <> '' or mails_comprobantes.fecha_pago <> '' or mails_comprobantes.observaciones <> '')
		) tabla
		order by fecha desc
		limit 0, 1		
		";
		$stmt = $this->db->query($sql_select, array($comprobante, $tipo, $comprobante, $tipo));
		$datos = $stmt->result_array();
		if(count($datos) > 0){
			return $datos[0];
		}else{
			return array("forma_pago" => "", "fecha_pago" => "", "observaciones" => "");
		}
	}

	function actividadesAgregar($id, $cont, $array_asignados){
		$sql_select = "select *, DATE_FORMAT(fecha, '%d/%m/%Y %T') fecha2 from actividades where id_actividad = ?";
		$stmt = $this->db->query($sql_select, array($id));
		$actividades_pendientes = $stmt->result_array();
		$html = "";		
		foreach ($actividades_pendientes as $fila) {
			$html .= $this->boxActividad($fila, $cont, $array_asignados);
		 	$cont++;
		}
		$html .= "
		<script>
		$(document).ready(function(){
			$('.box').boxWidget({
			  animationSpeed: 500,
			  collapseIcon: 'fa-minus',
			  expandIcon: 'fa-plus',
			  removeIcon: 'fa-times'
			})
		})
		</script>";
		return array("html" => $html, "cont" => $cont);
	}

	function mailAgregar($id, $cont, $array_asignados){
		$sql_select = 
		"SELECT fecha, DATE_FORMAT(fecha, '%d/%m/%Y %T') fecha2, asunto, '' estado, destinatarios proximo_contacto, '' direccion, '' descripcion, 'Mails' tipo, id_mail id_actividad, cliente, id_cliente
		FROM mails
		WHERE id_mail = ?";
		$stmt = $this->db->query($sql_select, array($id));
		$result = $stmt->result_array();
		$html = "";		
		foreach ($result as $fila) {
			$html .= $this->boxMail($fila, $cont, $array_asignados);
		 	$cont++;
		}
		$html .= "
		<script>
		$(document).ready(function(){
			$('.box').boxWidget({
			  animationSpeed: 500,
			  collapseIcon: 'fa-minus',
			  expandIcon: 'fa-plus',
			  removeIcon: 'fa-times'
			})
		})
		</script>";
		return array("html" => $html, "cont" => $cont);
	}	

	function descargarArchivos($id_adjunto, $id_mail){
		$sql_select = "select adjunto from mails_adjunto where id_adjunto = ?";
		$stmt = $this->db->query($sql_select, array($id_adjunto));
		$archivos = $stmt->result_array();
		if(count($archivos) > 0){
			foreach ($archivos as $fila) {
				$archivo = $fila["adjunto"];
			}

			$folder = $_SERVER['DOCUMENT_ROOT'].$this->config->item('carpeta_principal')."Plugin/mail/".$id_mail."/".$archivo;			
			header ("Content-Disposition: attachment; filename=$archivo");
			header ("Content-Type: application/force-download");
			header ("Content-Length: ".filesize($folder));
			readfile($folder);
		}
	}

	function actividadesPendientes($id, $array_asignados){
		$sql_select = "select *, DATE_FORMAT(fecha, '%d/%m/%Y %T') fecha2 from actividades where id_cliente = ? and estado = 'Pendiente'";
		$stmt = $this->db->query($sql_select, array($id));
		$actividades_pendientes = $stmt->result_array();
		$cont = 0;
		$html = "";		
		foreach ($actividades_pendientes as $fila) {
			$html .= $this->boxActividad($fila, $cont, $array_asignados);
		 	$cont++;
		}
		$html .= "
		<script>
		$(document).ready(function(){
			$('.box').boxWidget({
			  animationSpeed: 500,
			  collapseIcon: 'fa-minus',
			  expandIcon: 'fa-plus',
			  removeIcon: 'fa-times'
			})
		})
		</script>";		
		return array("html" => $html, "cont" => $cont);
	}

	function actividadRealizada($id, $array_asignados, $cont, $existeComp, $where_adicional, $id_actividad_no_mirar){
		$sql_select = 
		"SELECT * 
		FROM (
			SELECT 
				fecha, DATE_FORMAT(fecha, '%d/%m/%Y %T') fecha2, asunto, estado, proximo_contacto, direccion, descripcion, 'Actividad' tipo, id_actividad, cliente, id_cliente
			FROM actividades
			WHERE 
				estado <> 'Pendiente' 
				and id_cliente = ? 
				and $existeComp(SELECT id FROM actividades_comprobantes WHERE id_actividad = actividades.id_actividad and actividades_comprobantes.estado = 'Pendiente')
				$id_actividad_no_mirar				
			union
			SELECT 
				fecha, DATE_FORMAT(fecha, '%d/%m/%Y %T') fecha2, asunto, '' estado, destinatarios proximo_contacto, '' direccion, '' descripcion, 'Mails' tipo, id_mail id_actividad, cliente, id_cliente
			FROM mails
			WHERE 
				id_cliente = ? 
				and $existeComp(SELECT id FROM mails_comprobantes WHERE id_mail = mails.id_mail and mails_comprobantes.estado = 'Pendiente')
		) tabla
		order by fecha desc
		$where_adicional
		";
		$stmt = $this->db->query($sql_select, array($id, $id));
		$actividades_pendientes = $stmt->result_array();
		$html = "";
		foreach ($actividades_pendientes as $fila) {			
			if($fila["tipo"] == "Actividad"){
				$html .= $this->boxActividad($fila, $cont, $array_asignados);
			}else{
				$html .= $this->boxMail($fila, $cont, $array_asignados);
			}
		 	$cont++;
		}
		$html .= "
		<script>
		$(document).ready(function(){
			$('.box').boxWidget({
			  animationSpeed: 500,
			  collapseIcon: 'fa-minus',
			  expandIcon: 'fa-plus',
			  removeIcon: 'fa-times'
			})
		})
		</script>";
		return array("html" => $html, "cont" => $cont);
	}

	function boxActividad($fila, $cont, $array_asignados){
		$id_actividad = $fila["id_actividad"];		
		$asignados = $this->getAsignadosPorActividad($id_actividad);
		$comprobantes_2 = $this->getComprobantesPorActividad($id_actividad, 'actividad', 'actividades');
		$datos_actividad = $fila;
		$datos_actividad["asignados"] = $asignados;
		$datos_actividad["comprobantes"] = $comprobantes_2;
		$input_realizo_actividad = "<input type='checkbox' id='".$cont."_check_estado' data-cont='".$cont."' data-id='".$id_actividad."' class='realizo_actividad' ";
		if($fila["estado"] == "Realizada"){ 
			$color = "green"; 
			$box_collapse = "collapsed-box";
			$boton_collapse = "plus";
			$visual_box = " style='display:none;' ";
			$input_realizo_actividad .= " style='display:none;' >"; 
		}else{ 
			$color = "red"; 
			$box_collapse = "";
			$boton_collapse = "minus";
			$visual_box = "";
			$input_realizo_actividad .= ">"; 
		}
		$proximo_contacto = $fila["proximo_contacto"]; 
		$proximo_contacto = str_replace (" " , "T", $proximo_contacto);
		if(strpos($proximo_contacto, "1969-12-31") !== false){
			$proximo_contacto = "";
		}		
		$cantidad_comprobantes = count($comprobantes_2);
		$html = 
		'<script>window["'.$cont.'_datos_actividad"] = '.json_encode($datos_actividad).';</script>
		<div class="box '.$box_collapse.'" id="'.$cont.'_box">
			<div class="box-header with-border">
				<div class="user-block">
			       	<span style="font-size: 16px; font-weight: 600;">
		                '.$input_realizo_actividad.' <b id="'.$cont.'_asunto">'.$fila["asunto"].'</b> ('.$cantidad_comprobantes.' comprobantes)
		            </span>
					<span style="margin-left: 0px;" class="description"><div style="display:inline;" id="'.$cont.'_fecha">'.$fila["fecha2"].'</div> <small id="'.$cont.'_label_estado" class="label bg-'.$color.'">'.$fila["estado"].'</small></span>
		        </div>
				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" onclick="eliminarActividad('.$id_actividad.', '.$cont.')"><i class="fa fa-trash"></i></button> 
					<button type="button" class="btn btn-box-tool" onclick="editarActividad('.$cont.')"><i class="fa fa-pencil"></i></button>
					<button type="button" class="btn btn-box-tool btn-visual" data-widget="collapse"><i class="fa fa-'.$boton_collapse.'"></i></button>
				</div>
			</div>
			<div class="box-body" '.$visual_box.'>
				<div class="acordeon">	
					<div class="acordeon__item">
						<input type="checkbox" name="acordeon" class="check-acordeon" id="'.$cont.'_item1" onchange="cambiar_check(\''.$cont.'_1\')" checked>
						<label for="'.$cont.'_item1" class="acordeon__titulo">
							<div style="text-align:left;">General <span style="float:right;"><span id="'.$cont.'_icon1" class="glyphicon glyphicon glyphicon-chevron-down"></span></span></div>
						</label>
						<div class="acordeon__contenido">
	       					<div class="row">
								<div class="col-md-4">
									<label class="lab">Asignado</label>
									<select disabled multiple class="form-control select2 input_select2" id="'.$cont.'_asignado">
										'.$array_asignados.'
									</select>
									<script>
										var asignados = new Array();
										$(document).ready(function(){';
											foreach ($asignados as $fila_asig) {
												$html .= 'asignados.push('.$fila_asig["id_usuario"].');'; 
											}
									$html .= '
											if (!$("#'.$cont.'_asignado").data("select2")){ 
												$("#'.$cont.'_asignado").select2(); 
												$("#'.$cont.'_asignado").val(asignados).trigger("change");
											}
										});
									</script>
								</div>
								<div class="col-md-4">
									<label class="lab">Proximo Contacto</label>
									<input disabled type="datetime-local" class="form-control" id="'.$cont.'_proximo_contacto" value="'.$proximo_contacto.'">
								</div>
								<div class="col-md-4">
									<label class="lab">Direccion de retiro</label>
									<input disabled type="text" class="form-control" id="'.$cont.'_direccion" value="'.$fila["direccion"].'">
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<label class="lab">Descripción</label>
									<textarea class="form-control" name="'.$cont.'_descripcion" id="'.$cont.'_descripcion" disabled>
										'.$fila["descripcion"].'
									</textarea>
									<script>
						 				CKEDITOR.replace("'.$cont.'_descripcion");
						 				CKEDITOR.add
						 			</script>
								</div>
							</div>
						</div>
					</div>
					'.$this->boxComprobantes($comprobantes_2, $cont, $cantidad_comprobantes, 'actividades').'
				</div>	
			</div>
		</div>';
		return $html;
	}

	function boxComprobantes($comprobantes_2, $cont, $cantidad_comprobantes, $tipoActividad){
		$html = 
		'<div class="acordeon__item">
			<input type="checkbox" name="acordeon" class="check-acordeon" id="'.$cont.'_item2" onchange="cambiar_check(\''.$cont.'_2\')" checked>
			<label for="'.$cont.'_item2" class="acordeon__titulo">
				<div style="text-align:left;">Comprobantes ('.$cantidad_comprobantes.') <span style="float:right;"><span id="'.$cont.'_icon2" class="glyphicon glyphicon glyphicon-chevron-down"></span></span></div>
			</label>
			<div class="acordeon__contenido">
				<div class="col-md-12">
					<a class="btn btn-primary btn-form btn_anotacion" data-tipo="'.$tipoActividad.'" id="'.$cont.'_btn_anotacion" style="display: none;">Anotación</a>
					<div class="table-responsive">
						<table class="table" id="'.$cont.'_comprobantes">
							<thead>
								<tr>
									<th></th>
									<th>Tipo</th>
									<th>Comprobante</th>
									<th>Estado</th>
									<th>Fecha</th>
									<th>Vencimiento</th>
									<th>Importe</th>
									<th>Días</th>
									<th>Fecha de Pago</th>
									<th>Forma de Pago</th>									
									<th>Observaciones</th>
								</tr>
							</thead>
							<tbody>'; 
								foreach ($comprobantes_2 as $fila_comp) {
								$html .= 
									'<tr data-id='.$fila_comp["id"].'>
										<td><input type="checkbox" data-comprobante="'.$cont.'" class="check_comprobantes"></td>
										<td>'.$fila_comp["tipo"].'</td>
										<td>'.$fila_comp["comprobante"].'</td>
										<td>'.$fila_comp["estado"].'</td>
										<td>'.$fila_comp["fecha"].'</td>
										<td>'.$fila_comp["vencimiento"].'</td>
										<td>'.$fila_comp["importe"].'</td>
										<td>'.$fila_comp["dias"].'</td>		
										<td>';
										$fecha_pago = $fila_comp["fecha_pago"]; 
										if(strpos($fecha_pago, '31/12/1969') !== false){
											$fecha_pago = ''; 
										}
										$html .= $fecha_pago.'</td>
										<td>'.$fila_comp['forma_pago'].'</td>
										<td>'.$fila_comp['observaciones'].'</td>
									</tr>';
								}
								$html .= '
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>';
		return $html;
	}

	function boxMail($fila, $cont, $array_asignados){
		$id_mail = $fila["id_actividad"];
		$adjuntos = $this->getAdjuntosPorMail($id_mail);
		$fila["asociacion"] = $adjuntos;
		$comprobantes_2 = $this->getComprobantesPorActividad($id_mail, 'mail', 'mails');
		$cantidad_comprobantes = count($comprobantes_2);
		$enviados = 0;
        $cantLeidos = 0;
        $noLeidos = 0;
        $destinatarios = $fila["proximo_contacto"];        
        $destinatarios = explode(";", $destinatarios);
        $enviados = count($destinatarios);
		$sql = "select * from mails_leidos
			where id_correo = '$id_mail'";
		$stmt = $this->db->query($sql);
		$leidos = $stmt->result_array();
		$data['leidos'] = array();
		$cantLeidos = count($leidos);
		if ($cantLeidos > 0){
			$data['leidos'] = $leidos;
	        $noLeidos = $enviados - $cantLeidos;
		}
		
		$html = 
		'<div class="box collapsed-box" id="'.$cont.'_box">
			<div class="box-header with-border">
				<div class="user-block">
			       	<span style="font-size: 16px; font-weight: 600;">
		                <b>'.$fila["asunto"].'</b> ('.$cantidad_comprobantes.' comprobantes)
		            </span>
					<span style="margin-left: 0px;" class="description">'.$fila["fecha2"];
					foreach ($destinatarios as $fila_dest) {					
						$html .= '<span class="label label-primary label_correo">'.$fila_dest.'</span>';
					}
					$html .= 
					'</span>
		        </div>
				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse" onclick="abrirIframe(\''.$cont.'\', \''.$id_mail.'\')"><i class="fa fa-plus"></i></button>
				</div>
			</div>
			<div class="box-body">
				<div class="nav-tabs-custom">
				  	<ul class="nav nav-tabs">
				    	<li class="active"><a data-step="'.$cont.'_datos_mail_tab" data-toggle="tab" href="#'.$cont.'_datos_mail">Datos</a></li>
				    	<li><a data-step="'.$cont.'_metrica_mail_tab" data-toggle="tab" href="#'.$cont.'_metrica_mail">Metricas</a></li>
				  	</ul>
				  	<div class="tab-content">
			    		<div id="'.$cont.'_datos_mail" class="tab-pane fade in active">
							<div class="acordeon">
								<div class="acordeon__item">
									<input type="checkbox" name="acordeon" class="check-acordeon" id="'.$cont.'_item1" onchange="cambiar_check(\''.$cont.'_1\')" checked>
									<label for="'.$cont.'_item1" class="acordeon__titulo">
										<div style="text-align:left;">General <span style="float:right;"><span id="'.$cont.'_icon1" class="glyphicon glyphicon glyphicon-chevron-down"></span></span></div>
									</label>
									<div class="acordeon__contenido">
				       					<div class="row">
				       						<iframe class="iframe_body" style="overflow-x: auto !important; padding-left: 15px;" width="100%" src="'.base_url().'seguimiento/getContenidoMail/'.$id_mail.'" id="'.$cont.'_idFrame" frameborder="0" onload="resizeIframe(this)"></iframe>
				       					</div>
				       					<div class="row">';
				       					foreach ($adjuntos as $fila) {
				       						$html .= 
				       						'<div class="col-md-6">
				       							<div class="input-group" onclick="descargarArchivos('.$fila["id_adjunto"].', '.$id_mail.')">
				       								<input type="text" class="form-control" readonly style="cursor:pointer" value = "'.$fila["adjunto"].'">
				       								<span class="input-group-addon add-on">
				       									<span class="glyphicon glyphicon-paperclip"></span>
				       								</span>
				       							</div>
				       						</div>';
				       					}
				       				$html .= 
				       					'</div>
				       				</div>
				       			</div>
				       			'.$this->boxComprobantes($comprobantes_2, $cont, $cantidad_comprobantes, 'mails').'
				       		</div>
				       	</div>
				       	<div id="'.$cont.'_metrica_mail" class="tab-pane fade in">
				       		<div class="row">
			                    <div class="col-md-8">
			                      <table class="table table-bordered">
			                        <tr>
			                          <th>Enviados<br>'.$enviados.'</th>
			                          <th>Leidos <br>'.$cantLeidos.'</th>
			                          <th>No Leidos<br>'.$noLeidos.'</th>
			                        </tr>
			                      </table>
			                      <div id="'.$cont.'_chartdiv" style="width: 100%;height: 300px;"></div>
			                    </div>
			                    <div class="col-md-4">
			                      	<table class="table table-bordered">
				                        <thead>
				                        	<tr>
				                        		<th colspan="2">Leidos</th>
				                        	</tr>
				                          	<tr>
				                            	<th>Destinatario</th>
				                            	<th>Fecha</th>
				                          	</tr>
				                        </thead>
				                        <tbody>';
				                        foreach ($leidos as $filaLeidos) { 
				                            $html.= 
				                            '<tr>
					                            <td>'.$filaLeidos['destinatario'].'</td>
					                            <td>'.$filaLeidos['fechaCreacion'].'</td>
				                        	</tr>';
				                        }
				                        $html.= 
				                        '</tbody>
			                      	</table>
			                    </div>
			                </div>
				       		<script>				       		
							$(document).ready(function() {
								var total = '.$enviados.';
								var leidos = '.$cantLeidos.';
								var noLeidos = '.$noLeidos.';
					       		var chart = AmCharts.makeChart("'.$cont.'_chartdiv", {
									"type": "pie",
									"theme": "light",
									"dataProvider": [{"title": "Leidos","value": leidos}, {"title": "No Leidos","value": noLeidos}],
									"valueField": "value",
									"titleField": "title",
									"balloon":{
										"fixedPosition":true
									},
									"export": {
										"enabled": true
									},
									"responsive": {
										"enabled": true
									}
								});
							});
							</script>
				       	</div>
					</div>
				</div>					       		
	       	</div>
		</div>';
		return $html;
	}

	function getArrayAsignados(){
		$array_usuarios = $this->reglaModel->getUsuariosParaSelect();
		$opciones_usuario = "<option></option>";
		foreach ($array_usuarios as $fila_usuario) {
			$opciones_usuario .= "<option value='".$fila_usuario["id_usuario"]."'>".$fila_usuario["correo"]."</option>";
		}
		return $opciones_usuario;
	}

	function getContenidoMail($id_mail){		
		$sql_select = "select contenido from mails_contenido where id_mail = '$id_mail'";
		$stmt = $this->db->query($sql_select);
		$consultas_externas = $stmt->result_array();
		$contenido_mail = '';
		foreach ($consultas_externas as $fila) {
			$contenido_mail .= $fila["contenido"];
		}
		return $contenido_mail;
	}

	function anotacionesComprobantes($where_id, $tipo, $fecha_pago, $forma_pago, $observacion){
	 	$tm = $this->db->query(
		"UPDATE ".$tipo."_comprobantes
		SET fecha_pago = ?, forma_pago = ?, observaciones = ?
		$where_id", 
		array($fecha_pago, $forma_pago, $observacion));
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return $error['message'];
    	}
    	return "OK";
	}

	function getSeguimiento($tipo, $where){
		if($tipo == "mails"){			
			$sql = "select mails.asunto, mails.destinatarios, DATE_FORMAT(mails.fecha, '%d/%m/%Y %T') fecha, cliente, mails.id_mail, mails.id_cliente
			from mails
			$where
			order by mails.id_mail desc";
			$stmt = $this->db->query($sql);
			return $stmt->result_array();
		}else{
			$sql = "select asunto, DATE_FORMAT(fecha, '%d/%m/%Y %T') fecha, DATE_FORMAT(proximo_contacto, '%d/%m/%Y %T') proximo_contacto, estado, cliente, contacto, telefono, id_actividad, id_cliente
			from actividades
			$where
			order by id_actividad desc";
			$stmt = $this->db->query($sql);
			return $stmt->result_array();
		}
	}

	function manejoWhere($tipo, $columna, $busqueda, $tipo_busqueda, $where){
		/*OBTENGO LAS OPCIONES BUSQUEDA SEGUN EL TIPO DE DATO*/
		$opciones_float = $this->config->item("opciones_float");
		$opciones_date = $this->config->item("opciones_date");
		$opciones_string = $this->config->item("opciones_string");
		$array_columna = $this->config->item($tipo."_array_columna");
		$sql_columna = $this->config->item($tipo."_sql_columna");
		$tipo_columna = $this->config->item($tipo."_tipo_columna");
		$array = procesarWhere($columna, $busqueda, $tipo_busqueda, $opciones_float, $opciones_date, $opciones_string, $where, $array_columna, $sql_columna, $tipo_columna);
		return $array;
	}	

	function registros_guardados($tipo, $where_adicional){
		$id_usuario = $this->session->userdata('id_usuario');
		$stmt = $this->db->query(
			"select columna, tipo_busqueda, busqueda
			from busqueda 
			where tipo = ? and id_usuario = ? $where_adicional", array($tipo, $id_usuario));
		return $stmt->result_array();
	}

	function guardar_busqueda($columna, $tipo_busqueda, $busqueda, $tipo, $nombreBusqueda){
		$id_usuario = $this->session->userdata('id_usuario');

		$this->vaciar_busqueda($tipo);

		$tm = $this->db->query("insert into busqueda (columna, tipo_busqueda, busqueda, tipo, id_usuario) values (?,?,?,?,?)", array($columna, $tipo_busqueda, $busqueda, $tipo, $id_usuario));
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return $error['message'];
    	}

    	if(!empty($nombreBusqueda)){
			$tm = $this->db->query("insert into busqueda (columna, tipo_busqueda, busqueda, tipo, nombreBusqueda, id_usuario) values (?,?,?,?,?,?)", array($columna, $tipo_busqueda, $busqueda, $tipo, $nombreBusqueda, $id_usuario));
			if(!$tm){
	    		$error = $this->db->error();
	    		$this->db->trans_rollback();
	    		return $error['message'];
	    	}
	    }
	}

	function vaciar_busqueda($tipo){
		$id_usuario = $this->session->userdata('id_usuario');
		$tm = $this->db->query("delete from busqueda where tipo = ? and ifnull(nombreBusqueda,'') = '' and id_usuario = ?", array($tipo, $id_usuario));
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return $error['message'];
    	}
	}

	function getBusquedasGuardadas($tipo){
		$id_usuario = $this->session->userdata('id_usuario'); 
		$tabla = "<table class='table'><tr><th>Nombre</th><th>Favorito</th><th></th></tr>";
		$stmt = $this->db->query("select nombreBusqueda, id_busqueda, favoritoBusqueda
			from busqueda
			where (id_usuario = '$id_usuario' or id_usuario = '0') and tipo = '$tipo' and ifnull(nombreBusqueda,'') <> ''");
		$registrants = $stmt->result_array();
		foreach ($registrants as $fila) {
			$nombre = $fila["nombreBusqueda"];
			$id_busqueda = $fila["id_busqueda"];
			$favorito = $fila["favoritoBusqueda"];
			$clase_estrella = "";
			if($favorito == "0"){
				$clase_estrella = "-o";
			}
			$tabla .= 
			"<tr>
				<td><a style='cursor:pointer;' onclick='ejecutarBusquedaGuardada(".$id_busqueda.")'>".$nombre."</a></td>
				<td><a style='cursor:pointer;' onclick='favoritoBusquedaGuardada(".$id_busqueda.", this)'><i data-estado='".$favorito."' class='fa fa-star".$clase_estrella." text-yellow'></i></a></td>
				<td><a style='cursor:pointer;' onclick='eliminarBusquedaGuardada(".$id_busqueda.", this)'><span class='glyphicon glyphicon-trash'></span></a></td>
			</tr>";
		}
		$tabla .= "</table>";
		return $tabla;
	}

	function eliminarBusquedaGuardada($id_busqueda){
		$tm = $this->db->query("delete from busqueda where id_busqueda = ?", array($id_busqueda));
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return $error['message'];
    	}
	}	

	function favoritoBusquedaGuardada($id_busqueda, $favorito){
		if($favorito == "0"){
			$favorito = "false";
		}else{
			$favorito = "true";
		}
		$sql = "update busqueda set favoritoBusqueda = $favorito where id_busqueda = '$id_busqueda'";
		$tm = $this->db->query($sql);
		if($tm){
			return 'OK';
		}else{
			return $this->db->error()['message'];
		}
	}	

	function exportar($tipo){
		ini_set('memory_limit', '2048M');
		ini_set('max_execution_time', 300);
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=".$tipo.".xls");
		
		$tamano = count($this->config->item($tipo.'_array_columna'));
		$columna = $this->config->item($tipo.'_array_columna');
		$sql_columna = $this->config->item($tipo.'_sql_columna');
		$key = $this->config->item($tipo.'_key');
		$tipo_columna = $this->config->item($tipo.'_tipo_columna');

		$where = $this->session->userdata($tipo.'_where_sql');
		
		$datos = $this->getSeguimiento($tipo, $where);
		$html = "";
		if(count($datos) > 0) {
			$html =
			'<table>
				<thead>
					<tr>';			
	                for($i = 0; $i < $tamano; $i++){
	                    $html .= '<th>'.$columna[$i].'</th>';
	                }
					$html .=
					'</tr>
				</thead>
				<tbody>'; 
				foreach ($datos as $fila) {
					$html .= '<tr>';
				   	for($i = 0; $i < $tamano; $i++){
	                	$html .= '<th>'.$fila[$key[$i]].'</th>';
	                }
	                $html .= '</tr>';
	            }
	            $html .=         
				'</tbody>
			</table>';
		}
		return $html;
	}

	function getActividadPorId($id){
		$empresa = $this->session->userdata('empresa');
		$id_empresa = $this->session->userdata('id_empresa');

		$sql_select = 
		"select * 
		from actividades
		where id_actividad = ? and id_empresa = ?";
		$stmt = $this->db->query($sql_select, array($id, $id_empresa));
		$data = $stmt->result_array();
		if(count($data) > 0){
			$data = $data[0];
			$dominio = $this->session->userdata('dominio');
			$cliente = json_decode($this->seleccionarCliente($data["id_cliente"], $empresa, $dominio),true);			
		    $data["comprobantes"] = $this->obtenerComprobantesRealizado($id);

			if(count($cliente) > 0){
				$data["cliente"] = $cliente[0]["cliente"];
				$data["cod_cliente"] = $cliente[0]["cod_cliente"];
			}else{
				$data["id_cliente"] = "";
				$data["cliente"] = "";
				$data["cod_cliente"] = "";
			}			    
			$contacto = json_decode($this->seleccionarContacto($data["id_contacto"], $data["id_cliente"], $empresa, $dominio), true);
			if(count($contacto) > 0){
				$data["contacto"] = $contacto[0]["contacto"];
			}else{
				$data["id_contacto"] = "";
				$data["contacto"] = "";
			}
			$asignados = $this->getAsignadosPorActividad($id);
			$data["asignados"] = $asignados;
			
		}
		return $data;
	}

	function getAsignadosPorActividad($id){
		$sql_select = 
		"select id_usuario 
		from actividades_asociacion
		where id_actividad = ?";
		$stmt = $this->db->query($sql_select, array($id));
		$asignados = $stmt->result_array();
		return $asignados;
	}

	function getComprobantesPorActividad($id, $singular, $plural){
		$sql_select = 
		"select tipo, comprobante, DATE_FORMAT(fecha, '%d/%m/%Y') fecha, DATE_FORMAT(vencimiento, '%d/%m/%Y') vencimiento, case when estado = 'Pendiente' then datediff(vencimiento, now()) else '-' end dias, importe, DATE_FORMAT(fecha_pago, '%d/%m/%Y') fecha_pago, forma_pago, observaciones, id, estado  
		from ".$plural."_comprobantes
		where id_".$singular." = ?";
		$stmt = $this->db->query($sql_select, array($id));
		$comprobantes = $stmt->result_array();
		return $comprobantes;
	}

	function getAdjuntosPorMail($id_mail){
		$sql_select = 
		"select *
		from mails_adjunto
		where id_mail = ?";
		$stmt = $this->db->query($sql_select, array($id_mail));
		$adjuntos = $stmt->result_array();
		return $adjuntos;
	}

	function obtenerComprobantes($id, $empresa){
		$dominio = $this->session->userdata('dominio');
		$comprobantes = json_decode($this->seleccionarComprobante($id, $empresa, $dominio), true);
		$cont = 0;
		foreach ($comprobantes as $fila) {
			$sql_select = 
			"select fecha_retiro
			from actividades_comprobantes
			where comprobante = ? and tipo = ?";
			$stmt = $this->db->query($sql_select, array($fila["comprobante"], $fila["tipo"]));
			$datos = $stmt->result_array();
			foreach ($datos as $dato) {
				$comprobantes[$cont]["fecha_retiro"] = $dato["fecha_retiro"];
			}
			$cont++;
		}		
		return $comprobantes;
	}

	function obtenerComprobantesRealizado($id){
		$sql_select = 
		"select *, '-' dias
		from actividades_comprobantes
		where id_actividad = ?";
		$stmt = $this->db->query($sql_select, array($id));
		$datos = $stmt->result_array();
		return $datos;
	}

	function actividad_bd
	(
		$instancia,	$id_actividad,
		$asunto, $fecha, $estado, 
		$id_cliente, $cliente,
		$asociacion, $proximo_contacto, $direccion,
		$descripcion, 
		$comprobantes
	)
	{
		$this->db->trans_begin();
		$id_usuario = $this->session->userdata('id_usuario');
		$id_empresa = $this->session->userdata('id_empresa');
		//encabezado
		$objeto = array(
		"asunto" => $asunto, "fecha" => $fecha, "estado" => $estado, 
		"id_cliente" => $id_cliente, "cliente" => $cliente, 
		"descripcion" => $descripcion, 
		"proximo_contacto" => $proximo_contacto, "direccion" => $direccion,
		"id_empresa" => $id_empresa);
		
		if($instancia == "Agregar"){
			//inserto a la regla
			$tm = $this->db->insert("actividades", $objeto);
			if(!$tm){
	    		$error = $this->db->error();
	    		$this->db->trans_rollback();
	    		return $error['message'];
	    	}
	    	$id_actividad = $this->db->insert_id();
	    }else{
	    	//modifico a la regla
	    	$this->db->where('id_actividad', $id_actividad);
			$tm = $this->db->update("actividades", $objeto);
			if(!$tm){
	    		$error = $this->db->error();
	    		$this->db->trans_rollback();
	    		return array($error['message']);
	    	}
	    }

	 	$tm = $this->db->query(
		"DELETE FROM actividades_comprobantes
		WHERE id_actividad = ?", 
		array($id_actividad));
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return array($error['message']);
    	}

	    foreach ($comprobantes as $fila) {
	    	unset($fila["dias"]);
	    	$fila["id_actividad"] = $id_actividad;
	    	$tm = $this->db->insert("actividades_comprobantes", $fila);
			if(!$tm){
	    		$error = $this->db->error();
	    		$this->db->trans_rollback();
	    		return array($error['message']);
	    	}
	    }

	 	$tm = $this->db->query(
		"DELETE FROM actividades_asociacion
		WHERE id_actividad = ?", 
		array($id_actividad));
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return array($error['message']);
    	}

	    foreach ($asociacion as $fila) {
	    	$fila["id_actividad"] = $id_actividad;
	    	$tm = $this->db->insert("actividades_asociacion", $fila);
			if(!$tm){
	    		$error = $this->db->error();
	    		$this->db->trans_rollback();
	    		return array($error['message']);
	    	}
	    }	    

		if ($this->db->trans_status() === FALSE){
			$error = $this->db->error();
	        $this->db->trans_rollback();
	        return array($error['message']);
		}else{
		    $this->db->trans_commit();
		    return array("OK", $id_actividad);
		}
	}

	function eliminarActividad($id_actividad){
	 	$this->db->trans_begin();

	 	$tm = $this->db->query(
		"DELETE FROM actividades_comprobantes
		WHERE id_actividad = ?", 
		array($id_actividad));
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return $error['message'];
    	}


	 	$tm = $this->db->query(
		"DELETE FROM actividades_asociacion
		WHERE id_actividad = ?", 
		array($id_actividad));
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return $error['message'];
    	}

		$tm = $this->db->query(
		"DELETE FROM actividades
		WHERE id_actividad = ?", 
		array($id_actividad));
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return $error['message'];
    	}    	

		if ($this->db->trans_status() === FALSE){
			$error = $this->db->error();
	        $this->db->trans_rollback();
	        return $error['message'];
		}else{
		    $this->db->trans_commit();
		    return "OK";
		}
	}

	function modificarActividad($id, $valor, $campo){
	 	$tm = $this->db->query(
		"UPDATE actividades
		SET $campo = ?
		WHERE id_actividad = ?", 
		array($valor, $id));
		if(!$tm){
    		$error = $this->db->error();
    		$this->db->trans_rollback();
    		return $error['message'];
    	}
		return "OK";		
	}

	//FUNCIONES QUE VIENEN DE SQL SERVER	
	function seleccionarCliente($id_cliente, $empresa, $dominio){
		$curl = curl_init();
		$url = $dominio."/api/seleccionarCliente";
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, TRUE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		curl_setopt($curl, CURLOPT_POSTFIELDS, 'id='.$id_cliente.'&empresa='.$empresa);
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

	function seleccionarDetalleCliente($id_cliente, $empresa, $dominio){
		$curl = curl_init();
		$url = $dominio."/api/seleccionarDetalleCliente";
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, TRUE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		curl_setopt($curl, CURLOPT_POSTFIELDS, 'id='.$id_cliente.'&empresa='.$empresa);
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

	function seleccionarComprobante($id_cliente, $empresa, $dominio){
		$curl = curl_init();
		$url = $dominio."/api/seleccionarComprobante";
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, TRUE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		curl_setopt($curl, CURLOPT_POSTFIELDS, 'id='.$id_cliente.'&empresa='.$empresa);
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


	function seleccionarContacto($id_contacto, $id_cliente, $empresa, $dominio){
		$curl = curl_init();		
		$url = $dominio."/api/seleccionarContacto";
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, TRUE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		curl_setopt($curl, CURLOPT_POSTFIELDS, 'id='.$id_contacto.'&id_cliente='.$id_cliente.'&empresa='.$empresa);
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
}