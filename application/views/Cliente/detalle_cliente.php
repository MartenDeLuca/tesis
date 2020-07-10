<?php 
$seguimientoModel = new seguimientoModel;
?>
<style type="text/css">
	.form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
	    background-color: white !important;	 
	}
	.box_actividad{
		border-top: 3px solid #00a65a !important;
	}		
	.box_mail{
		border-top: 3px solid #3c8dbc !important;
	}
</style>

<script src="<?php echo base_url('plugin') ?>/amcharts/amcharts.js"></script>
<script src="<?php echo base_url('plugin') ?>/amcharts/plugins/dataloader/dataloader.min.js" type="text/javascript"></script>
<script src="<?php echo base_url('plugin') ?>/amcharts/serial.js"></script>
<script src="<?php echo base_url('plugin') ?>/amcharts/funnel.js"></script>
<script src="<?php echo base_url('plugin') ?>/amcharts/pie.js"></script>
<script src="<?php echo base_url('plugin') ?>/amcharts/plugins/export/export.min.js"></script>
<link href="<?php echo base_url('plugin') ?>/amcharts/plugins/export/export.css" type="text/css" media="all" rel="stylesheet"/>
<script src="<?php echo base_url('plugin') ?>/amcharts/themes/light.js"></script>

<div class="content-wrapper" style="background: white !important;">	
    <section class="content-header">
        <h1>
          Detalle del cliente
        </h1>
  	</section>
  	<section class="content">
  		<input type="hidden" id="detalle_cod_cliente" value="<?php echo $cliente["cod_client"]; ?>">
  		<input type="hidden" id="detalle_id_cliente" value="<?php echo $id; ?>">
  		<input type="hidden" id="detalle_nombre_cliente" value="<?php echo $cliente["cliente"]; ?>">
		<div class="row fijo">
			<div class="col-md-12">
				<h1 style="font-size:24px !important; margin-top:0px !important; cursor:pointer;" id="tituloSeleccion">
					<div style="display: inline;" id="tituloDiv"><?php echo $cliente["cliente"]; ?></div>

					<div class="pull-right">					
						<a title="Agregar actividad" onclick="agregarActividad()" class="btn btn-primary btn-form botones-derechos"><span class="glyphicon glyphicon-file"></span></a>
						
						<a title="Mandar mail" onclick="mandarMail()" class="btn btn-primary btn-form botones-derechos"><span class="glyphicon glyphicon-envelope"></span></a>

						<a title="Buscar Cliente" onclick="modal('Cliente')" class="btn btn-primary btn-form botones-derechos hidden-xs"><span class="glyphicon glyphicon-search"></span></a>
						
						<a title="Mostrar/Ocultar el contenido de las tareas" onclick="cambiarVistaInteraccion(this)" id="cambiarVistaInteraccion" class="btn btn-primary btn-form botones-derechos hidden-xs"><span class="glyphicon glyphicon-<?php echo $cambiarVistaInteraccion; ?>"></span></a>
						
						<a title="Ocultar/Mostrar Información" onclick="cambiarVista(this)" id="cambiarVista" class="btn btn-primary btn-form botones-derechos hidden-xs"><span class="glyphicon glyphicon-chevron-<?php echo $cambiarVista; ?>"></span></a>
					</div>
				</h1>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-md-<?php echo $div_actividades; ?>" id="div_actividades">
				<div class="box">
					<div class="box-header with-border">
						Comprobantes (<?php echo count($comprobantes); ?>)
						<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					</div>
					</div>
					<div class="box-body">
						<a class="btn btn-primary btn-form btn_anotacion" data-tipo="actividades" id="inicio_btn_anotacion" style="display: none;">Anotación</a>
						
						<table class="table dt-responsive" style="width: 100%" id="inicio_comprobantes">
							<thead>
								<tr>
									<th></th>
									<th>Tipo</th>
									<th>Comprobante</th>
									<th>Estado</th>
									<th>Fecha</th>
									<th>Vencimiento</th>
									<th>Días</th>
									<th>Importe</th>
									<th>Fecha de pago</th>
									<th>Forma de pago</th>
									<th>Observación</th>
								</tr>	
							</thead>
							<tbody>
								<?php
								$total = 0; 
								$contador = 0;
								foreach ($comprobantes as $fila) {
									if($fila["dias"] < 0){
									$estilo_color = 'style="color:red; font-weight: bold;"';
								}else{
									$estilo_color = '';
								}
									$tipo = $fila["tipo"];
									$comprobante = $fila["comprobante"];
			     				$acciones = $seguimientoModel->getActividadComprobante($tipo, $comprobante);
			     				$fecha_pago = $acciones["fecha_pago"];
			     				$forma_pago = $acciones["forma_pago"];
			     				$observaciones = $acciones["observaciones"];
			     				$comprobantes[$contador]["estado"] = 'Pendiente';
			     				$comprobantes[$contador]["fecha_pago"] = $fecha_pago;
			     				$comprobantes[$contador]["forma_pago"] = $forma_pago;
			     				$comprobantes[$contador]["observaciones"] = $observaciones; 
								?>
									<tr data-id='<?php echo $contador; ?>'>
										<td><input type="checkbox" data-comprobante="inicio" class="check_comprobantes"></td>
										<td><?php echo $tipo; ?></td>
										<td><a onclick="redirectComp('<?php echo $comprobante ?>', '<?php echo $tipo ?>')"><?php echo $comprobante ?></a></td>
										<td>Pendiente</td>
										<td><?php echo $fila["fecha"]; ?></td>
										<td><?php echo $fila["vencimiento"]; ?></td>
										<td <?php echo $estilo_color; ?>><?php echo $fila["dias"]; ?></td>
										<td style="text-align: right;"><?php echo number_format($fila["importe"],2,',','.'); ?></td>
										<td><?php echo $fecha_pago; ?></td>
										<td><?php echo $forma_pago; ?></td>
										<td><?php echo $observaciones; ?></td>
									</tr>
								<?php
									$contador++;
									$total = $total + (float) $fila["importe"];
								}
								?>	
								<script> var comprobantes_pendientes = <?php echo json_encode($comprobantes); ?>;</script>
							</tbody>
							<tfoot>
								<tr>
									<td></td>
									<td><b>Total<b></td>
									<td colspan="6" style="text-align: right;"><b><?php echo number_format($total,2,',','.'); ?></b></td>
									<td colspan="3"></td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
				<div id="div_actividades_pendientes">
					<?php echo $actividades_pendientes; ?>
				</div>
		  		<div class="nav-tabs-custom">
				  	<ul class="nav nav-tabs">
				    	<li class="active"><a data-step="comprobantes_tab" data-toggle="tab" href="#comprobantes_tab">Vigentes</a></li>
				    	<li><a data-step="historial_tab" data-toggle="tab" id="a_historial_tab" href="#historial_tab">Historial</a></li>
				    	<div class="text-right">
				    		<a title="Buscar Actividades" onclick="modal('Actividades', 'Seguimiento*Comprobante')" class="btn btn-primary btn-form botones-derechos"><span class="glyphicon glyphicon-search"></span></a>
				    	</div>
				  	</ul>
				  	<div class="tab-content">
			    		<div id="comprobantes_tab" class="tab-pane fade in active">
			    			<?php echo $actividades_realizadas; ?>
			    		</div>
			    		<div id="historial_tab" class="tab-pane fade in">
			    			<div id="div_historial_tab"></div>

			    			<center>
			    				<a class="btn btn-primary btn-form" onclick="a_historial_tab()">Ver Mas</a>
			    			</center>
			    		</div>
			    	</div>
			    </div>		
			</div>

			<div class="col-md-3" <?php echo $clase_div_datos_adicionales; ?> id="div_datos_adicionales">
       			<div class="box">
       				<div class="box-header with-border">
       					Información
       					<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
       				</div>
       				<div class="box-body">
		       			<div class="acordeon">
		       				<div class="acordeon__item">
								<input type="checkbox" checked name="acordeon" class="check-acordeon" id="item1">
								<label for="item1" class="acordeon__titulo">
									<div style="text-align:left;">
										<p style="display:inline">Datos</p>
										<span style="float:right;">
											<span id="icon1" class="glyphicon"></span>
										</span>
									</div>
								</label>

								<div class="acordeon__contenido" id="datos_acordeon_contenido">
									<?php
									if(!empty($cliente["telefono"])){
									?>
									<label class="lab">Telefono: <b><?php echo $cliente["telefono"]; ?></b></label><br>
									<?php 
									}
									if(!empty($cliente["horario_cobranza"])){
									?>
									<label class="lab">Horario Cobranza: <b><?php echo $cliente["horario_cobranza"]; ?></b></label><br>
									<?php 
									}
									$dias_cobranza = "";
									if($cliente["cobra_lunes"] == "S"){ $dias_cobranza .= "Lunes, "; }
									if($cliente["cobra_martes"] == "S"){ $dias_cobranza .= "Martes, "; }
									if($cliente["cobra_miercoles"] == "S"){ $dias_cobranza .= "Miercoles, "; }
									if($cliente["cobra_jueves"] == "S"){ $dias_cobranza .= "Jueves, "; }
									if($cliente["cobra_viernes"] == "S"){ $dias_cobranza .= "Viernes, "; }  
									if($cliente["cobra_sabado"] == "S"){ $dias_cobranza .= "Sabado, "; }
									if($cliente["cobra_domingo"] == "S"){ $dias_cobranza .= "Domingo, "; }
									$dias_cobranza = substr($dias_cobranza, 0, -2);
									if(!empty($dias_cobranza)){
									?>
									<label class="lab">Dias Cobranza: <b><?php echo $dias_cobranza; ?></b></label><br>
									<?php 
									}
									?>
									<label class="lab"><a onclick="verMasContactos()" title="Ver todos los contactos">Contacto: <b><?php echo $cliente["contacto"]; ?></b></a></label><br>
									<?php
									if(!empty($cliente["celular"])){
									?>
									<label class="lab">Celular: <b><?php echo $cliente["celular"]; ?></b></label><br>
									<?php 
									}
									?>
									<?php
									if(!empty($cliente["correo"])){
									?>
									<label class="lab">Correo: <b><?php echo $cliente["correo"]; ?></b></label><br>
									<?php 
									}
									?>
									<label class="lab">Cumplimiento: <b><?php echo $cumplimiento; ?> días</b></label><br>
									<label class="lab" <?php if($total > 0){ ?> style="color:red" <?php } ?>>Deuda: <?php echo number_format($total,2,',','.'); ?></label><br>
									<?php 
									if($no_vencida != 0 && $vencida != 0){
									?>
									<b>Vencido/No Vencido:</b>
									<div class="progress">
									    <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $vencida; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $vencida; ?>%">
									      <?php echo $vencida; ?>%
									    </div>
									    <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $no_vencida; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $no_vencida; ?>%">
									      <?php echo $no_vencida; ?>%
									    </div>
									</div>
									<?php } ?>									
								</div>
							</div>
						</div>
					</div>
				</div>
       		</div>
       	</div>
  	</section>
</div>

<div class="modal fade in" id="modalInformacion" tabindex="-1" role="dialog" aria-labelledby="formBuscar" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title" id="modalInformacion-title"></h4> 
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div id="modalInformacion-datos"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade in" id="modalActividad" tabindex="-1" role="dialog" aria-labelledby="formBuscar" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title" id="modalActividad-title">Actividad</h4> 
			</div>
			<div class="modal-body">
				<?php 
				$data_actividad_interno = array(
				"id_actividad" => "", 
				"asunto" => "", "fecha" => date("Y-m-d"), "estado" => "Pendiente", "descripcion" => "", 
				"cliente" => "", "id_cliente" => "", "cod_cliente" => "",			
				"comprobantes" => array(),
				"proximo_contacto" => "", "direccion" => "", "asignados" => array(), "instancia" => "Modificar", "id_actividad" => "", "array_asignados" => $array_asignados);
				$this->load->view('actividad/form_actividades_interno', $data_actividad_interno); ?>
			</div>
		</div>
	</div>
</div>

<div class="modal fade in" id="modalMail" tabindex="-1" role="dialog" aria-labelledby="formBuscar" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title" id="modalMail-title">Mail</h4> 
			</div>
			<div class="modal-body">
				<?php 
				$data_mail_interno = array("array_asignados" => $array_asignados, "adjuntos" => array(), "contenido_mail" => "", "asunto_mail" => "", "destinatario_fijos" => array(), "cod_cliente" => $cliente["cod_client"], "comprobantes" => $comprobantes, "id_cliente" => $id, "cliente" => $cliente["cliente"], "visible" => "style='display:none;'");
				$this->load->view('actividad/form_mails_interno', $data_mail_interno); ?>
			</div>
		</div>
	</div>
</div>

<input type="hidden" id="cont" value="<?php echo $cont; ?>">
<input type="hidden" id="cont_activo" value="">
<input type="hidden" id="id_actividad_no_mirar">
<input type="hidden" id="inicio" value="0">


<script type="text/javascript">
	var empresa = "<?php echo $this->session->userdata('empresa'); ?>";
	$(document).ready(function(){
	  	$(window).scroll(function(){
	    	if($(this).scrollTop() > 0){
	        	$('.fijo').addClass('fijo_scroll');
	      	}else{
	        	$('.fijo').removeClass('fijo_scroll');
	      	}
	  	});
	});

	function verMasContactos(){
		var id_cliente = $("#cod_cliente").val();
		$("#modalInformacion").modal("show");
		$("#modalInformacion-title").html("Contactos Asociados");		
		$.ajax({
			url: "<?php echo $this->session->userdata('dominio') ?>/api/buscarContactos",
			type: "POST",
			data:{consulta:"", id_cliente, empresa},
			dataType: "json",
			success: function(respuesta){
				var html = `<table class='table'><tr><td>Contacto</td><td>Celular</td><td>Correo</td></tr>`;
				var tamano = respuesta.length;
				for(var i = 0; i < tamano; i++){
					html += `<tr><td>${respuesta[i]["contacto"]}</td><td>${respuesta[i]["celular"]}</td><td>${respuesta[i]["correo"]}</td></tr>`;
				}
				html += `</tbody></table>`;
				$("#modalInformacion-datos").html(html);
			}
		});
	}

	function cambiarVista(objeto){
		var icono = $(objeto).children();
		var opcion = icono.attr("class");
		var cambio_ancho = 3;
		var valor_configuracion = "";
		if(opcion.indexOf("glyphicon-chevron-left") > -1){
			icono.removeClass("glyphicon-chevron-left");
			icono.addClass("glyphicon-chevron-right");
			$("#div_datos_adicionales").hide();
			valor_configuracion = "right";
		}else{
			icono.addClass("glyphicon-chevron-left");
			icono.removeClass("glyphicon-chevron-right");
			cambio_ancho = cambio_ancho * -1;
			$("#div_datos_adicionales").show();
			valor_configuracion = "left";
		}
		var div_actividades = $("#div_actividades").attr("class");
		cambio_ancho = parseInt(div_actividades.substr(div_actividades.lastIndexOf("-")+1))+cambio_ancho;
		$("#div_actividades").removeClass(div_actividades);
		$("#div_actividades").addClass("col-md-"+cambio_ancho);

		//guardar acciones
		cambiar_menu('vista_amplia', valor_configuracion);
	}

	function cambiarVistaInteraccion(objeto){
		var icono = $(objeto).children();
		var opcion = icono.attr("class");
		var valor_configuracion = "";
		if(opcion.indexOf("glyphicon-plus") > -1){
			icono.removeClass("glyphicon-plus");
			icono.addClass("glyphicon-minus");
			$("#div_actividades .box").removeClass("collapsed-box");
			$("#div_actividades .box .box-header .btn-visual button i").removeClass("fa-plus");
			$("#div_actividades .box .box-header .btn-visual button i").addClass("fa-minus");
			valor_configuracion = "minus";
		}else{
			icono.addClass("glyphicon-plus");
			icono.removeClass("glyphicon-minus");
			$("#div_actividades .box").addClass("collapsed-box");
			$("#div_actividades .box .box-header .btn-visual button i").addClass("fa-plus");
			$("#div_actividades .box .box-header .btn-visual button i").removeClass("fa-minus");
			valor_configuracion = "plus";
		}
		//guardar acciones
		//guardarConfiguracion('interaccion', valor_configuracion);
	}

	var banderaHistorial = 0;
	$("#a_historial_tab").click(function(){
		if(banderaHistorial == 0){
			banderaHistorial = 1;
			a_historial_tab();
		}
	});

	function a_historial_tab(){
		var id = $("#detalle_id_cliente").val();
		var cont = $("#cont").val();
		var inicio = $("#inicio").val();
		var id_actividad_no_mirar = $("#id_actividad_no_mirar").val();
		if (!Number.isInteger(parseInt(inicio))){
			inicio = 0;
		}
		$.ajax({
			url: "<?php echo base_url() ?>seguimiento/actividadRealizadaHistorial",
			type: "POST",
			data:{id, cont, inicio, id_actividad_no_mirar},
			dataType: "json",
			success: function(respuesta){
				$("#div_historial_tab").append(respuesta["html"]);
				$("#cont").val(respuesta["cont"]);
				$("#inicio").val(parseInt(inicio)+10);
			}
		});
	}

	function eliminarActividad(id, cont){
		if(confirm("Deseas eliminar la actividad?")){
			$.ajax({
				url: "<?php echo base_url() ?>seguimiento/eliminarActividad",
				type: "POST",
				data:{id},
				success: function(respuesta){
					if(respuesta == "OK"){
						$("#"+cont+"_box").remove();
					}else{
						alert(respuesta);
					}
				}
			});
		}
	}

	function editarActividad(cont){
		$("#cont_activo").val(cont);
		$("#formulario_btn_anotacion").hide();
		$("#modalActividad").modal('show');
		var array = window[cont+"_datos_actividad"];
		$("#instancia").val("Modificar");
		$("#id_actividad").val(array["id_actividad"]);
		$("#asunto").val(array["asunto"]);
		var fecha = (array["fecha"]).replace(" ", "T");
		if(fecha.indexOf("1969-12-31") > -1){
			fecha = "";
		}
		$("#fecha").val(fecha);
		$("#estado").val(array["estado"]).trigger('change');	
		$("#id_cliente").val($("#detalle_id_cliente").val());
		$("#cliente").val($("#detalle_nombre_cliente").val());
		$("#cod_cliente").val($("#detalle_cod_cliente").val());
		$("#cliente").prop("disabled", true);
		$("#direccion").val(array["direccion"]);
		var proximo_contacto = (array["proximo_contacto"]).replace(" ", "T");
		if(proximo_contacto.indexOf("1969-12-31") > -1){
			proximo_contacto = "";
		}
		$("#proximo_contacto").val(proximo_contacto);
		var array_asignados = array["asignados"];
		var tamano_array_asignados = array_asignados.length;
		var asignados = new Array();
		for(var i = 0; i < tamano_array_asignados; i++){
			asignados.push(array_asignados[i]["id_usuario"]);
		}
		$('#asignado').val(asignados).change();

		setearComprobantes(array["comprobantes"], 'cantidad11', 'comprobantes');
	}

	function agregarActividad(){
		$("#modalActividad").modal('show');
		$("#formulario_btn_anotacion").hide();
		$("#instancia").val("Agregar");
		var d = new Date();
       	localDateTime = 
	       	[d.getFullYear(),
	        (d.getMonth()+1).AddZero(),
	        (d.getDate()).AddZero()].join('-') 
	        +'T'+
	        [d.getHours().AddZero(),
	        d.getMinutes().AddZero()].join(':');
       	$("#fecha").val(localDateTime);
		$("#asunto, #direccion, #id_actividad, #proximo_contacto").val("");
		$("#estado").val("Pendiente").trigger('change');
		$("#id_cliente").val($("#detalle_id_cliente").val());
		$("#cliente").val($("#detalle_nombre_cliente").val());
		$("#cod_cliente").val($("#detalle_cod_cliente").val());
		$("#cliente").prop("disabled", true);
		var asignados = new Array();
		$('#asignado').val(asignados).change();	
		setearComprobantes(comprobantes_pendientes, 'cantidad11', 'comprobantes');
	}

	function setearComprobantes(array_comprobantes, id_cantidad, id_tabla){
		var tamano_array_comprobantes = array_comprobantes.length;
		var html_comp = "";
		if(tamano_array_comprobantes > 0){
			html_comp = htmlComprobantes(array_comprobantes, tamano_array_comprobantes, 'formulario');
		}
		$("#"+id_cantidad).html("("+tamano_array_comprobantes+")");
		$("#"+id_tabla+" tbody").html(html_comp);
	}

	function mandarMail(){
		modal("Plantilla");
	}

	function accion_actividad_bd(array){
		if($("#instancia").val() == "Modificar"){
			var cont = $("#cont_activo").val();

			var array_viejo = window[cont+"_datos_actividad"];
			var estado_viejo = array_viejo["estado"];
			var estado_nuevo = array["estado"];

			$("#"+cont+"_asunto").html(array["asunto"]);
			var fecha = array["fecha"];
			var fecha = fecha.replace("T", " ");
			if(fecha.indexOf("1969-12-31") > -1){
				fecha = "";
			}else{
				fecha = fecha.substr(8,2)+'/'+fecha.substr(5,2)+'/'+fecha.substr(0,4)+fecha.substr(10);
			}
			$("#"+cont+"_fecha").html(fecha);
			$("#"+cont+"_label_estado").html(estado_nuevo);		
			var removeClass = "";
			var addClass = "";
			if(estado_nuevo == "Pendiente"){
				addClass = "bg-red";
				removeClass = "bg-green";
			}else{
				addClass = "bg-green";
				removeClass = "bg-red";
			}

			$("#"+cont+"_label_estado").removeClass(removeClass);	
			$("#"+cont+"_label_estado").addClass(addClass);

			$("#"+cont+"_direccion").val(array["direccion"]);
			var proximo_contacto = (array["proximo_contacto"]).replace(" ", "T");
			if(proximo_contacto.indexOf("1969-12-31") > -1){
				proximo_contacto = "";
			}
			$("#"+cont+"_proximo_contacto").val(proximo_contacto);
			
			var array_asignados = array["asignados"];
			var tamano_array_asignados = array_asignados.length;
			var asignados = new Array();		
			for(var i = 0; i < tamano_array_asignados; i++){
				asignados.push(array_asignados[i]["id_usuario"]);
			}
			$("#"+cont+"_asignado").val(asignados).change();

			var array_comprobantes = array["comprobantes"];
			var tamano_array_comprobantes = array_comprobantes.length;
			var html_comp = "";
			if(tamano_array_comprobantes > 0){
				html_comp = htmlComprobantes(array_comprobantes, tamano_array_comprobantes, cont);
			}
			$("#"+cont+"_comprobantes tbody").html(html_comp);

			window[cont+"_datos_actividad"] = array;
			if(estado_nuevo == "Pendiente" && estado_viejo == "Realizada"){
				var html = ajusto_html(cont);			
				$("#div_actividades_pendientes").prepend(html);
				$('.box').boxWidget({
				  animationSpeed: 500,
				  collapseIcon: 'fa-minus',
				  expandIcon: 'fa-plus',
				  removeIcon: 'fa-times'
				})
			
				$("#"+cont+"_check_estado").show();		
			}else if(estado_viejo == "Pendiente" && estado_nuevo == "Realizada"){
				acciones_adicionales(cont, array["id_actividad"]);			
			}
		}else{
			var cont = $("#cont").val();
			$("#cont").val(parseInt(cont)+1);
			var id = array["id_actividad"];
			ajustarAdicionalesComprobante(array["comprobantes"]);
			$.ajax({
				url: "<?php echo base_url() ?>seguimiento/actividadesAgregar",
				type: "POST",
				data:{id, cont, array_asignados:"<?php echo $array_asignados; ?>"},
				success: function(respuesta){
					if(array["estado"] == "Pendiente"){
						var div_id = "div_actividades_pendientes";
					}else{
						var div_id = "comprobantes_tab";
					}
					$("#"+div_id).prepend(respuesta);
				}
			});
		}
		$("#"+cont+"_btn_anotacion").hide();
	}

	function accion_mail_bd(array){
		var cont = $("#cont").val();
		$("#cont").val(parseInt(cont)+1);
		var id = array["id_actividad"];
		ajustarAdicionalesComprobante(array["comprobantes"]);
		$.ajax({
			url: "<?php echo base_url() ?>seguimiento/mailAgregar",
			type: "POST",
			data:{id, cont, array_asignados:"<?php echo $array_asignados; ?>"},
			success: function(respuesta){
				$("#comprobantes_tab").prepend(respuesta);
			}
		});
	}

	function ajustarAdicionalesComprobante(array){
		var tamano = array.length;
		var fecha_pago, forma_pago, observacion;
		for(var i = 0; i < tamano; i++){
			fecha_pago = array[i]["fecha_pago"];
			fecha_pago = fecha_pago.substr(8,2)+'/'+fecha_pago.substr(5,2)+'/'+fecha_pago.substr(0,4);
			forma_pago = array[i]["forma_pago"];
			observacion = array[i]["observaciones"];
			$($($("#inicio_comprobantes tbody tr")[i]).children("td")[8]).html(fecha_pago);
			comprobantes_pendientes[i]["fecha_pago"] = array[i]["fecha_pago"];
			$($($("#inicio_comprobantes tbody tr")[i]).children("td")[9]).html(forma_pago);
			comprobantes_pendientes[i]["forma_pago"] = forma_pago;
			$($($("#inicio_comprobantes tbody tr")[i]).children("td")[10]).html(observacion);
			comprobantes_pendientes[i]["observaciones"] = observacion;
		}
	}

	function descargarArchivos(id_adjunto, id_mail){
		location.href = '<?php echo base_url() ?>seguimiento/descargarArchivos?id_adjunto='+id_adjunto+'&id_mail='+id_mail;
	}

	$(document).on("change", ".realizo_actividad", function(){
		if(confirm("Deseas pasar estado realizado la actividad?")){
			var id = $(this).data("id");
			var cont = $(this).data("cont");
			var valor = 'Realizada';
			var campo = 'estado';
			$.ajax({
				url: "<?php echo base_url() ?>seguimiento/modificarActividad",
				type: "POST",
				data:{id, valor, campo},
				success: function(respuesta){
					if(respuesta == "OK"){
						var array = window[cont+"_datos_actividad"];
						array["estado"] = valor;
						window[cont+"_datos_actividad"] = array;
						$("#"+cont+"_check_estado").prop("checked", false);
						$("#"+cont+"_check_estado").hide();
						$("#"+cont+"_label_estado").html(valor);
						$("#"+cont+"_label_estado").removeClass("bg-red");	
						$("#"+cont+"_label_estado").addClass("bg-green");
						acciones_adicionales(cont, id);
					}else{
						alert(respuesta);
					}
				}
			});
		}else{
			$(this).prop("checked", false);
		}
	});

	function acciones_adicionales(cont, id){
		var comprobantesPendientes = "";
		var tr_comprobantes = $("#"+cont+"_comprobantes tbody tr");
		for(var i = 0; i < tr_comprobantes.length; i++){
			if($($(tr_comprobantes[i]).children("td")[3]).html() == "Pendiente"){
				comprobantesPendientes = "1";
				i = tr_comprobantes.length+1;
				break;		
			}
		}

		if(comprobantesPendientes == "1"){
			var id_prepend = "comprobantes_tab";
		}else{
			var id_prepend = "div_historial_tab";
		}
		var html = ajusto_html(cont);
		var id_actividad_no_mirar = $("#id_actividad_no_mirar").val();
		id_actividad_no_mirar += " and id_actividad <> '"+id+"' ";
		$("#id_actividad_no_mirar").val(id_actividad_no_mirar);
		$("#"+id_prepend).prepend(html);
		$('.box').boxWidget({
		  animationSpeed: 500,
		  collapseIcon: 'fa-minus',
		  expandIcon: 'fa-plus',
		  removeIcon: 'fa-times'
		})
	}

	function ajusto_html(cont){
		var html = $("#"+cont+"_box").html();
		var clases = $("#"+cont+"_box").attr("class");
		var html_aux = html;
		var html_final = "";
		while(html_aux != ""){			
			if(html_aux.indexOf("<script>") > -1){
				html_final += html_aux.substr(0, html_aux.indexOf("<script>"));
				html_aux = html_aux.substr(html_aux.indexOf(`/script>`)+8);
			}else{
				html_final += html_aux;
				html_aux = "";
			}
		}
		html = "<div class='"+clases+"' id='"+cont+"_box'>"+html_final+"</div>";		
		$("#"+cont+"_box").remove();
		return html;
	}

  	function resizeIframe(obj) {
  		var tamano = obj.contentWindow.document.documentElement.scrollHeight;
    	obj.style.height = tamano+ 'px';
	}	

	function abrirIframe(cont, id_mail){
		if($("#"+cont+"_box").attr("class").indexOf("collapsed-box") > -1){
			$("#"+cont+"_idFrame").attr('src', "<?php echo base_url(); ?>seguimiento/getContenidoMail/"+id_mail);
		}
	}

	function buscarActividades(){
		var consulta = $("#modal-buscar").val();
		var opcion = $("#modal-opcion").val();
		var cliente = $("#detalle_id_cliente").val();
		$.ajax({
			url: "<?php echo base_url() ?>/seguimiento/buscarActividades",
			type: "POST",
			data:{consulta, opcion, cliente},
			dataType: "json",
			success: function(respuesta){
				if(opcion == "Seguimiento"){
					var html = `<table class='table'><tr><td>Asunto</td><td>Fecha</td><td>Tipo</td></tr>`;
					var tamano = respuesta.length;
					for(var i = 0; i < tamano; i++){
						html += 
						`<tr style="cursor:pointer" onclick="seleccionarActividades(${respuesta[i]["id"]}, '${respuesta[i]["tipo"]}')">
							<td>${respuesta[i]["asunto"]}</td>
							<td>${respuesta[i]["fecha"]}</td>
							<td>${respuesta[i]["tipo"]}</td>
						</tr>`;
					}
				}else{
					var html = `<table class='table'><tr><td>Asunto</td><td>Fecha</td><td>Tipo</td><td>Comprobante</td></tr>`;
					var tamano = respuesta.length;
					for(var i = 0; i < tamano; i++){
						html += 
						`<tr style="cursor:pointer" onclick="seleccionarActividades(${respuesta[i]["id"]}, '${respuesta[i]["tipo_actividad"]}')">
							<td>${respuesta[i]["asunto"]}</td>
							<td>${respuesta[i]["fecha"]}</td>
							<td>${respuesta[i]["tipo_actividad"]}</td>
							<td>${respuesta[i]["tipo"]} - ${respuesta[i]["comprobante"]}</td>
						</tr>`;
					}
				}
				html += `</tbody></table>`;
				$("#modal-datos").html(html);				
			}
		});		
	}

	function seleccionarActividades(id, tipo){
		window.open("<?php echo base_url() ?>detalle-"+tipo+"?id="+id, '_blank');
	}

	function redirectComp(n_comp, t_comp){
		window.open("<?php echo base_url() ?>detalle-comprobante?n_comp="+n_comp+"&t_comp="+t_comp, '_blank');
	}

	function verComprobantes(cont, tipoActividad){
		$("#modalInformacion-title").html($("#"+cont+"_asunto").html());
		var html = $("#"+cont+"_comprobantes").html();
		var reg = new RegExp('data-comprobante="'+cont+'"', "g");
		html = html.replace(reg, 'data-contori="si" data-comprobante="modal'+cont+'"');
		html = 
		`<a class="btn btn-primary btn-form btn_anotacion" data-tipo="${tipoActividad}" id="modal${cont}_btn_anotacion" style="display: none;">Anotación</a>
		<div class='table-responsive'>
			<table class='table' id="modal_${cont}_comprobantes">${html}</table>
		</div>`;
		$("#modalInformacion-datos").html(html);
		$("#modalInformacion").modal("show");
	}

	$(document).on('change','[data-contori="si"]', function(){
		var cont = $(this).data("comprobante");		
		cont = cont.substr(5);
		var id = $(this).closest("tr").data("id");
		var tr = $('#'+cont+'_comprobantes').children("tbody").children("tr");
		for(var i = 0; i < tr.length; i++){
			if(id == $(tr[i]).data("id")){
				$(tr[i]).children("td").children(".check_comprobantes").prop("checked", $(this).is(':checked'))
				break;
			}
		}
	});
</script>