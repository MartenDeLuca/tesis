<div class="content-wrapper" style="background: white !important;">
  	<section class="content-header">
      <h1 id="h1_tablero">
        <?php echo $instancia; ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url() ?>reglas">Reglas de negocio</a></li>
        <li class="active"><?php echo $instancia; ?> regla de negocio</li>
      </ol>
	</section>
	<section class="content">
		<form method="post" enctype="multipart/form-data" id="formulario">
			<input type="hidden" name="instancia" value="<?php echo $instancia; ?>">
			<input type="hidden" name="id_regla" value="<?php echo $id_regla; ?>">
	  		<div class="nav-tabs-custom">
			  	<ul class="nav nav-tabs">
			    	<li class="active"><a data-step="encabezado_tab" data-toggle="tab" href="#encabezado_tab">Encabezado</a></li>
			    	<li><a data-step="consulta_tab" data-toggle="tab" href="#consulta_tab">Consulta</a></li>
			    	<li id="li_correo_tab" <?php if(strpos($accion, "Correo") === false){ echo 'style="display:none"'; }?>><a data-step="correo_tab" data-toggle="tab" href="#correo_tab">Correo</a></li>
			    	<li id="li_alerta_tab" <?php if(strpos($accion, "Alerta") === false){ echo 'style="display:none"'; }?>><a data-step="alerta_tab" data-toggle="tab" href="#alerta_tab">Alerta</a></li>
			    	<div class="pull-right">
			    		<div class="text-right">
							<a title="Guardar regla de negocio" onclick="guardar()" class="btn btn-primary btn-form">Guardar</a>
							<a title="Cancelar proceso" onclick="cancelar()" class="btn btn-danger btn-form">Cancelar</a>
						</div>
			    	</div>
			  	</ul>
			  	<div class="tab-content">
		    		<div id="encabezado_tab" class="tab-pane fade in active">
		    			<div class="row">
		    				<div class="col-md-6">
		    					<label class="lab">Asunto</label>
		    					<input type="text" class="form-control" placeholder="Asunto" name="asunto" id="asunto" value="<?php echo $asunto; ?>" maxlength="1000">
		    					<div class="error_color" id="error_asunto"></div>
		    				</div>
		    				<div class="col-md-6">
		    					<label class="lab">Estado</label>
		    					<select class="form-control select2 input_select2" name="estado" id="estado">
		    						<option <?php if($estado == "Activa"){ echo 'selected'; }?>>Activa</option>
		    						<option <?php if($estado == "Pausada"){ echo 'selected'; }?>>Pausada</option>
		    					</select>
		    					<div class="error_color" id="error_estado"></div>
		    				</div>
		    			</div>
		    			<div class="row">	
		    				<div class="col-md-6">
		    					<label class="lab">Fecha de Inicio</label>
		    					<input type="datetime-local" class="form-control" placeholder="Fecha de Inicio" name="fechaInicio" id="fechaInicio" value="<?php 
		    					$fechaInicio = str_replace (' ' , 'T' , $fechaInicio );
		    					echo $fechaInicio; ?>">
		    					<div class="error_color" id="error_fechaInicio"></div>
		    				</div>
		    				<div class="col-md-6">
		    					<label class="lab">Fecha de Expiracion</label>
		    					<input type="datetime-local" class="form-control" placeholder="Fecha de Expiracion" name="fechaExpiracion" id="fechaExpiracion" value="<?php 
		    					$fechaExpiracion = str_replace (' ' , 'T' , $fechaExpiracion );
		    					echo $fechaExpiracion; ?>">
		    					<div class="error_color" id="error_fechaExpiracion"></div>
		    				</div>
		    			</div>
		    			<div class="row">	
		    				<div class="col-md-6">
		    					<label class="lab">Tipo de Intervalo</label>
		    					<select class="form-control" name="tipoIntervalo" id="tipoIntervalo">
		    						<option value="Minutos">Minutos</option>
		    						<option value="Horas">Horas</option>
		    						<option value="Dias">Dias</option>
		    						<option value="Semanas">Semanas</option>
		    						<option value="Meses">Meses</option>
		    					</select>
		    					<div class="error_color" id="error_tipoIntervalo"></div>
		    				</div>
		    				<div class="col-md-6">
		    					<label class="lab">Intervalo</label>
		    					<input type="text" class="form-control int" placeholder="Intervalo" name="intervalo" id="intervalo" value="<?php echo $intervalo; ?>">
		    					<div class="error_color" id="error_intervalo"></div>
		    				</div>
		    			</div>
		    			<div class="row">	
		    				<div class="col-md-6">
		    					<label class="lab">Accion</label>
		    					<select class="form-control select2 input_select2" name="accion" id="accion" placeholder="Accion">
		    						<option <?php if($accion == "Correo y Alerta"){ echo 'selected'; }?>>Correo y Alerta</option>
		    						<option <?php if($accion == "Correo"){ echo 'selected'; }?>>Correo</option>
		    						<option <?php if($accion == "Alerta"){ echo 'selected'; }?>>Alerta</option>
		    					</select>
		    					<div class="error_color" id="error_accion"></div>
		    				</div>
		    			</div>
					</div>
					<div id="consulta_tab" class="tab-pane fade in">
						<div class="row">
							<div class="col-md-12">
								<label class="lab">Consulta</label>
								<textarea class="form-control" placeholder="Recuerde que esta conectado a la base master" rows="17" style="resize: inherit;" name="consulta" id="consulta"><?php echo $consulta; ?></textarea>
								<div class="error_color" id="error_consulta"></div>
							</div>							
						</div>	
					</div>
					<div id="correo_tab" class="tab-pane fade in">
						<div class="acordeon">
							<div class="acordeon__item">
								<input type="checkbox" name="acordeon" class="check-acordeon" id="item0" onchange="cambiar_check(0)" checked>
								<label for="item0" class="acordeon__titulo">
									<div style="text-align:left;">Mail <span style="float:right;"><span id="icon0" class="glyphicon glyphicon glyphicon-chevron-down"></span></span></div>
								</label>
								<div class="acordeon__contenido">
									<div class="row">
										<div class="col-md-6">
					    					<label class="lab">Correo</label>
					    					<input type="text" class="form-control" placeholder="Correo" name="correo" id="correo" value="<?php echo $correo; ?>">
					    					<div class="error_color" id="error_correo"></div>
					    				</div>
					    				<div class="col-md-6">
					    					<label class="lab">Contraseña</label>
					    					<input type="password" class="form-control" placeholder="Contraseña" name="contrasena" id="contrasena" value="">
					    					<div class="error_color" id="error_contrasena"></div>
					    				</div>
					    			</div>
					    			<div class="row">
					    				<div class="col-md-6">
					    					<label class="lab">Puerto</label>
					    					<input type="text" class="form-control int" placeholder="Puerto" name="puerto" id="puerto" value="<?php echo $puerto; ?>">
					    					<div class="error_color" id="error_puerto"></div>
					    				</div>
					    				<div class="col-md-6">
					    					<label class="lab">Host</label>
					    					<input type="text" class="form-control" placeholder="Host" name="host" id="host" value="<?php echo $host; ?>">
					    					<div class="error_color" id="error_host"></div>
					    				</div>
					    			</div>
					    			<div class="row">
					    				<div class="col-md-6">
					    					<label class="lab">Certificado SSL</label>
					    					<select class="form-control select2 input_select2" name="certificado_ssl" id="certificado_ssl">
					    						<option value="1">Si</option>
					    						<option value="0">No</option>
					    					</select>
					    					<div class="error_color" id="error_certificado_ssl"></div>
					    				</div>
					    				<div class="col-md-6">
						    				<div class="pull-right" style="padding-top:20px;">
						    					<a class="btn btn-primary btn-form" onclick="validarDatosCorreo(this)">Validar Correo</a>
						    				</div>
					    				</div>
					    			</div>
					    		</div>
					    	</div>	
					    	<div class="acordeon__item">
								<input type="checkbox" name="acordeon" class="check-acordeon" id="item1" onchange="cambiar_check(1)" checked>
								<label for="item1" class="acordeon__titulo">
									<div style="text-align:left;">Destinatarios <span style="float:right;"><span id="icon1" class="glyphicon glyphicon glyphicon-chevron-down"></span></span></div>
								</label>
								<div class="acordeon__contenido">
					    			<div class="row">
					    				<div class="col-md-12">
					    					<label class="lab">Destinatarios por columnas</label>
					    					<select multiple="multiple" class="form-control input_select2 select2" name="destinatario_columnas[]" id="destinatario_columnas">
					    						<option></option>
					    						<?php echo $atributos; ?>
					    					</select>
					    					<script type="text/javascript">
					    						$(document).ready(function(){
												  	<?php 
													if(!empty($destinatario_columnas)){
													  	$destinatario_columnas = explode(";", $destinatario_columnas);
													  	foreach ($destinatario_columnas as $fila) {
													  	?>
														$("#destinatario_columnas option[value='<?php echo $fila; ?>']").attr('selected',true);
													  	<?php
													  	}
													}
												  	?>
												});
					    					</script>
					    					<div class="error_color" id="error_destinatario_columnas"></div>
					    				</div>
					    			</div>
					    			<div class="row">
					    				<div class="col-md-12">
					    					<label class="lab">Destinatarios fijos</label>
					    					<select class="form-control select2" multiple="multiple" name="destinatario_fijos[]" id="destinatario_fijos">
					    					<?php echo $array_usuarios; ?>
		                  					</select>
					    					<div class="error_color" id="error_destinatario_fijos"></div>
					    					<script type="text/javascript">
											  	$(document).ready(function(){
												  	<?php 
													if(!empty($destinatario_fijos)){
													  	$destinatario_fijos = explode(";", $destinatario_fijos);
													  	foreach ($destinatario_fijos as $fila) {
													  	?>
													  	var newState = new Option('<?php echo $fila; ?>', '<?php echo $fila; ?>', true, true);
														$("#destinatario_fijos").append(newState).trigger('change');
													  	<?php
													  	}
													}
												  	?>
												  	var opciones = [];
												  	var array = $('#destinatario_fijos option');
												  	var tamano = array.length;
												  	for(var i = tamano-1; i >= 0; i--){
													   	if(opciones.indexOf($(array[i]).val()) > -1){
													    	$(array[i]).remove();
													   	}else{
													      	opciones.push($(array[i]).val());
													   	}
													}
												  	$('#destinatario_fijos').select2({
												      placeholder:" Destinatarios",
												      tags: true,
												      tokenSeparators: [",", " "],
												      createTag: function (tag) {      
												        if(validoEmail(tag.term)) {
												            return {
												              id: tag.term,
												              text: tag.term
												            };      
												        }
												        return false;
												      },
												  	});
											  	});
					    					</script>
					    				</div>
					    			</div>
					    		</div>
					    	</div>		
					    	<div class="acordeon__item">
								<input type="checkbox" name="acordeon" class="check-acordeon" id="item2" onchange="cambiar_check(2)" checked>
								<label for="item2" class="acordeon__titulo">
									<div style="text-align:left;">Contenido del mail <span style="float:right;"><span id="icon2" class="glyphicon glyphicon glyphicon-chevron-down"></span></span></div>
								</label>
								<div class="acordeon__contenido">
					    			<div class="row">	
					    				<div class="col-md-12">
					    					<label class="lab">Atributos</label>
					    					<select class="form-control input_select2 select2" name="atributos" id="atributos">
					    						<option></option>
					    						<?php echo $atributos; ?>
					    					</select>
					    					<div class="error_color" id="error_atributos"></div>
					    				</div>
					    			</div>
					    			<div class="row">
					    				<div class="col-md-12">
					    					<label class="lab">Asunto Mail</label>
					    					<input type="text" class="form-control" placeholder="Asunto Mail" name="asunto_mail" id="asunto_mail" value="<?php echo $asunto_mail; ?>">
					    					<div class="error_color" id="error_asunto_mail"></div>
					    				</div>
					    			</div>	
					    			<div class="row">
					    				<div class="col-md-12">
					    					<label class="lab">Contenido Mail</label>
					    					<textarea class="form-control" placeholder="Contenido Mail" name="contenido_mail" id="contenido_mail">
					    						<?php echo htmlspecialchars($contenido_mail) ?>
					    					</textarea>
					    					<div class="error_color" id="error_contenido_mail"></div>
					    				</div>
								 		<script>
							 				CKEDITOR.replace('contenido_mail');
							 				CKEDITOR.add

											if (CKEDITOR.instances['contenido_mail']) {
												CKEDITOR.instances['contenido_mail'].on('blur', function(event) {
													validoCkeditor('contenido_mail');
												});
											}

											CKEDITOR.on('contenido_mail', function(e) {
												e.CKEDITOR.instances['contenido_mail'].addCss( 'body { background-color: red; }' );
											});

											CKEDITOR.instances['contenido_mail'].on('contentDom', function() {
												CKEDITOR.instances['contenido_mail'].document.on('keyup', function(event) {
											        $('#cke_contenido_mail').children(".cke_inner").children('.cke_top').css('border','1px solid #d2d6de');
						       						$('#cke_contenido_mail').children(".cke_inner").children('.cke_contents').css('border-left','1px solid #d2d6de');
						       						$('#cke_contenido_mail').children(".cke_inner").children('.cke_contents').css('border-right','1px solid #d2d6de');
						       						$('#cke_contenido_mail').children(".cke_inner").children('.cke_contents').css('border-bottom','1px solid #d2d6de');	
													document.getElementById("error_contenido_mail").innerHTML = '';
												});
											});
										</script>
									</div>
									<div class="row">
					    				<div class="col-md-12">
					    					<div id="adjuntos">
						    					<?php 
						    					$contador = 0;
						    					foreach ($adjuntos as $fila) {
						    					?>
													<div class="row" id='fila_<?php echo $contador ?>'>
														<div class="col-md-12">
															<div class="input-group">
																<input type="text" readonly class="form-control archivos_subidos" value="<?php echo $fila["adjunto"]; ?>" style="margin: 10px 0 0 0;" placeholder="Adjunto" name="archivos_subidos[]" id='archivo_<?php echo $contador ?>' >
																<span class="input-group-btn">
																	<a onclick="eliminarAdjunto(this)" class="btn btn-primary btn-sel" style="cursor: pointer; margin: 10px 0 0 0;">
																		<span class="glyphicon glyphicon-trash"></span>
																	</a>
																</span>
															</div>
															<div class="error_color" id="error_adjunto_<?php echo $contador ?>"></div>
														</div>
													</div>
						    					<?php
						    						$contador ++;
						    					}
						    					?>
					    					</div>
					    					<br>
					    					<input type="hidden" id="tabla_adjunto_id" value="<?php echo $contador; ?>">
					    					<a class="btn btn-primary btn-form" onclick="agregarAdjunto()"><i class="fa fa-paperclip"></i> Adjunto</a>
					    				</div>
					    			</div>
					    		</div>
					    	</div>
					    </div>			
					</div>
					<div id="alerta_tab" class="tab-pane fade in">
						<div class="row">
							<div class="col-md-6">
								<label class="lab">Tipo</label>
								<select class="form-control" name="tipo_alerta" id="tipo_alerta">
									<option <?php if($tipo_alerta == "Notificación"){ echo 'selected'; } ?>>Notificación</option>
									<option <?php if($tipo_alerta == "Comprobante"){ echo 'selected'; } ?>>Comprobante</option>
								</select>
							</div>
							<div class="col-md-6">
		    					<label class="lab">Atributos</label>
		    					<select class="form-control input_select2 select2" name="atributos_alerta" id="atributos_alerta"><option></option><?php echo $atributos; ?></select>
		    					<div class="error_color" id="error_atributos_alerta"></div>
		    				</div>
		    			</div>
		    			<div class="row">	
							<div class="col-md-12">
								<label class="lab">Descripción</label>
								<textarea class="form-control" name="descripcion_alerta" id="descripcion_alerta">
									<?php echo htmlspecialchars($descripcion_alerta) ?>
								</textarea>
								<div class="error_color" id="error_descripcion_alerta"></div>
							</div>
					 		<script>
				 				CKEDITOR.replace('descripcion_alerta');
				 				CKEDITOR.add

								if (CKEDITOR.instances['descripcion_alerta']) {						
									CKEDITOR.instances['descripcion_alerta'].on('blur', function(event) {
										validoCkeditor('descripcion_alerta');
									});
								}

								CKEDITOR.on('descripcion_alerta', function(e) {
									e.CKEDITOR.instances['descripcion_alerta'].addCss( 'body { background-color: red; }' );
								});

								CKEDITOR.instances['descripcion_alerta'].on('contentDom', function() {
									CKEDITOR.instances['descripcion_alerta'].document.on('keyup', function(event) {
								        $('#cke_descripcion_alerta').children(".cke_inner").children('.cke_top').css('border','1px solid #d2d6de');
			       						$('#cke_descripcion_alerta').children(".cke_inner").children('.cke_contents').css('border-left','1px solid #d2d6de');
			       						$('#cke_descripcion_alerta').children(".cke_inner").children('.cke_contents').css('border-right','1px solid #d2d6de');
			       						$('#cke_descripcion_alerta').children(".cke_inner").children('.cke_contents').css('border-bottom','1px solid #d2d6de');	
										document.getElementById("error_descripcion_alerta").innerHTML = '';
									});
								});
							</script>
						</div>
					</div>
				</div>
			</div>
		</form>	
	</section>
</div>
<script type="text/javascript">
	$('document').ready(function(){
		$(".input_select2").select2();
		$(".show-hide").parent().parent().hide();
	});

	$(".form-control").keypress(function(){
		quitarError(this.id);
	})

	function guardar(){
		var tab = "";
		var ok = false;
		var asunto = $("#asunto").val();
		if(asunto == ""){
			ok = false;
			if(tab == "") {tab = "encabezado_tab";}
			marcarError("asunto", "Campo obligatorio");
		}
		var fechaInicio = $("#fechaInicio").val();
		if(fechaInicio == ""){
			ok = false;
			if(tab == "") {tab = "encabezado_tab";}
			marcarError("fechaInicio", "Campo obligatorio");
		}
		var fechaExpiracion = $("#fechaExpiracion").val();
		if(fechaExpiracion == ""){
			ok = false;
			if(tab == "") {tab = "encabezado_tab";}
			marcarError("fechaExpiracion", "Campo obligatorio");
		}
		var intervalo = $("#intervalo").val();
		if(intervalo == ""){
			ok = false;
			if(tab == "") {tab = "encabezado_tab";}
			marcarError("intervalo", "Campo obligatorio");
		}/*else if((parseInt(intervalo)/5) - Math.trunc(parseInt(intervalo)/5) > 0){
			ok = false;
			if(tab == "") {tab = "encabezado_tab";}
			marcarError("intervalo", "Debe ser multiplo de 5");
		}*/
		var consulta = $("#consulta").val();
		if(consulta == ""){
			ok = false;
			if(tab == "") {tab = "consulta_tab";}
			marcarError("consulta", "Campo obligatorio");
		}		

		var tipo = $("#accion").val();
		if(tipo.indexOf("Correo") > -1){
			var correo = $("#correo").val();
			var contrasena = $("#contrasena").val();
			var host = $("#host").val();
			var puerto = $("#puerto").val();
			if(correo != ""){
				if(!validoEmail(correo)){
					ok = false;
					if(tab == "") {tab = "correo_tab";}
					marcarError("correo", "El correo esta escrito incorrectamente");
				}
			}else{
				ok = false;
				if(tab == "") {tab = "correo_tab";}
				marcarError("correo", "El correo esta escrito incorrectamente");
			}
			if(contrasena == ""){
				ok = false;
				if(tab == "") {tab = "correo_tab";}
				marcarError("contrasena", "Campo obligatorio");
			}
			if(host == ""){
				ok = false;
				if(tab == "") {tab = "correo_tab";}
				marcarError("host", "Campo obligatorio");
			}
			if(puerto == ""){
				ok = false;
				if(tab == "") {tab = "correo_tab";}
				marcarError("puerto", "Campo obligatorio");
			}else if (!Number.isInteger(parseInt(puerto))){
				ok = false;
				if(tab == "") {tab = "correo_tab";}
				marcarError("puerto", "Debe ser un numero");
			}
			var contenido_mail = CKEDITOR.instances["contenido_mail"].getData();
			if(contenido_mail == ""){
				ok = false;
				if(tab == "") {tab = "correo_tab";}
				marcarError("contenido_mail", "Campo obligatorio");
			}
			var asunto_mail = $("#asunto_mail").val();
			if(asunto_mail == ""){
				ok = false;
				if(tab == "") {tab = "correo_tab";}
				marcarError("asunto_mail", "Campo obligatorio");
			}
		}
		if(tipo.indexOf("Alerta") > -1){
			var descripcion_alerta = CKEDITOR.instances["descripcion_alerta"].getData();
			if(descripcion_alerta == ""){
				ok = false;
				if(tab == "") {tab = "alerta_tab";}
				marcarError("descripcion_alerta", "Campo obligatorio");
			}
		}	

		for(instance in CKEDITOR.instances) {
	        CKEDITOR.instances[instance].updateElement();
	    }
		if(ok){
			var formData = new FormData(document.getElementById("formulario"));
			$.ajax({
				type: "POST",
				url: document.getElementById("base_url").value+"Regla/regla_bd",
				data:formData,
				contentType: false,
				processData: false,
				dataType: "json",
				success: function (respuesta) {
					if(respuesta[0] == "ok"){
						location.href = document.getElementById("base_url").value+"reglas";
					}else{
						alert(respuesta[0]);
					}
				}
			});
		}else{
			$("[data-step='"+tab+"']").tab('show');
		}
	}

	function cancelar(){
		if(confirm("Desea cancelar el ingreso de la regla de negocio?")){
			history.go(-1);
		}
	}

	/*Acciones de Adjunto*/
	function eliminarAdjunto(objeto){
		if(confirm("Desea eliminar el adjunto?")){
			var row = $(objeto).closest(".row");			
			var input = $(row).children().children().children(".form-control");
			if(input.attr("name").indexOf("archivos_subidos[]") > -1){
				$(row).hide();
				input.prop("name", "archivos_eliminados[]");
			}else{
				$(row).remove();
			}
		}
	}

	function agregarAdjunto(){
		var contador = parseInt($("#tabla_adjunto_id").val())+1;
		var fila =`
	 	<div class="row" id='fila_${contador}'>
	 		<div class="col-md-12">
			 	<div class="input-group">
			 		<input type="file" class="form-control" style="margin: 10px 0 0 0;" placeholder="Adjunto" name="Adjunto[]" id='archivo_${contador}'>
			 		<span class="input-group-btn">
			 			<a onclick="eliminarAdjunto(this)" class="btn btn-primary btn-sel" style="cursor: pointer; margin: 10px 0 0 0;">
			 				<span class="glyphicon glyphicon-trash"></span>
			 			</a>
			 		</span>
			 	</div>
		 		<div class="error_color" id="error_adjunto_${contador}"></div>
		 	</div>
		</div>`;
	 	$("#tabla_adjunto_id").val(contador);
	 	$('#adjuntos').append(fila);
	}

	/*Manejos de int*/
	$(".int").keypress(function (e) {
		if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
			return false;
		}
	});

	$(".int").blur(function(){
		var valor = this.value;
		if (!Number.isInteger(parseInt(valor))){
			$(this).val("0");
		}
	});

	/*Manejo de intervalo*/
	var intervalo_focus;
	$("#intervalo").focus(function (e) {
		intervalo_focus = this.value;
	});

	/*$("#intervalo").blur(function (e) {
		if($(this).val() % 5 > 0){
			$(this).val(intervalo_focus);
			marcarError("intervalo", "Debe ser multiplo de 5");
		}
	});*/

	/*Ocultar solapas dependiendo la accion elegida*/
	$("#accion").change(function (e) {
		var accion = this.value;
		if (accion.indexOf('Correo') == -1){
			$("li [data-step='correo_tab']").hide();
		}else{
			$("#li_correo_tab").show();
			$("[data-step='correo_tab']").show();
		}
		if (accion.indexOf('Alerta') == -1){
			$("[data-step='alerta_tab']").hide();
		}else{
			$("#li_alerta_tab").show();
			$("[data-step='alerta_tab']").show();
		}	
	});

	/*Verifica el correo mientras escribe*/
	$(document).on("keyup", "#correo", function(e){
		var id = this.id;
		var value = this.value;
		if (value != ''){
			if (validoEmail(value)){
				quitarError(id);
			}else {
				marcarError(id, 'El correo esta escrito incorrectamente');
			}
		}
	});

	function validoEmail(email) {
	  var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	  return re.test(email);
	}

	/*Funcion del boton Validar Correo, automanda un mail a los datos puestos*/
	function validarDatosCorreo(objeto){
		var correo = $("#correo").val();
		var contrasena = $("#contrasena").val();
		var host = $("#host").val();
		var puerto = $("#puerto").val();
		var certificado_ssl = $("#certificado_ssl").val();
		if(correo != "" && contrasena != "" && host != "" && puerto != "" && certificado_ssl != ""){
			if(validoEmail(correo)){
				$.ajax({
					url:document.getElementById("base_url").value+"Regla/validarDatosCorreo",
					type: "POST",
					data:{correo, contrasena, host, puerto, certificado_ssl},
					dataType: "text",
					beforeSend: function(){
						$(objeto).prop("disabled", "true");
						$(objeto).html("Cargando...");
					},
					success: function(respuesta){
						if(respuesta.trim() == "OK") {
							alert("Los datos son correctos");
						}else{
							alert("Los datos son incorrectos, por favor revisarlos.");
						}
						$(objeto).html("Validar Correo");
						$(objeto).prop("disabled", "false");
					}
				});
			}else{
				marcarError("correo", 'El correo esta escrito incorrectamente');
			}
		}
	}

	$("#tipo_consulta").change(function(){
		var valor = this.value;
		if(valor == "Consulta Externa"){
			quitarError("consulta_externa");
			$("#consulta_externa").closest("div").show();
			$("#consulta").closest("div").hide();
			validarConsulta();
		}else{
			quitarError("consulta_externa");
			$("#consulta_externa").closest("div").hide();
			$("#consulta").closest("div").show();
			$("#consulta").val("");
		}
		$("#atributos option, #atributos_alerta option, #destinatario_columnas option").remove();
	})

	$("#consulta").blur(function(){
		validarConsulta();
	})

	function validarConsulta(){
		var ok = true;
		var consulta = $("#consulta").val();
		if(consulta != ""){
			var consultaAux = consulta.trim();
			consultaAux = consultaAux.toLowerCase();
			var posicionSelect = consultaAux.indexOf('select'); 
			var posUltParentesisFinal = consultaAux.lastIndexOf(')'); 
			var consultaAux_2 = consultaAux.replace(/\r?\n/g, ' ');
			if(posicionSelect != "0"){
				marcarError("consulta", "La consulta solo permite SELECT");
				ok = false;	
			}
		}
		if(ok){
			$.ajax({
				url: "<?php echo $this->session->userdata('dominio') ?>/api/verificar_consulta",
				type: "POST",
				async: false, 
				data:{consulta, columna:'1'},
				dataType: "json",
				success: function(respuesta){
					console.log(respuesta);
					if(respuesta[0] == "OK") {
						$("#atributos option, #atributos_alerta option, #destinatario_columnas option").remove();
						$("#atributos, #atributos_alerta, #destinatario_columnas").append("<option></option>"+respuesta[1]);
					}else{
						marcarError("consulta", respuesta[1]);
						marcarError("consulta_externa", respuesta[1]);
					}
				}
			});
		}
	}

	var foco = "";
	$("#asunto_mail").focus(function() {
		foco = this.id;
	});

	if (CKEDITOR.instances['contenido_mail']) {
		CKEDITOR.instances['contenido_mail'].on('focus', function(event) {
			foco = 'contenido_mail';
		});
	}

	$("#atributos").change(function(){
		var atributos = this.value;
		if(atributos != ""){
			if (foco == "asunto_mail"){
				var con = $("#"+foco).val()+atributos;
				$("#"+foco).val(con);
				quitarError(foco);
			} else {
				CKEDITOR.instances['contenido_mail'].insertText(atributos);
			}
			$(this).val("");
			$(this).trigger('change');
		}
	});

	$("#atributos_alerta").change(function(){
		var atributos = this.value;
		if(atributos != ""){
			CKEDITOR.instances['descripcion_alerta'].insertText(atributos);
			$(this).val("");
			$(this).trigger('change');
		}
	});

	function validoCkeditor(){

	}
</script>