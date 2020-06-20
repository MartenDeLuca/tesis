<div class="formulario">
	<div class="nav-tabs-custom">
	  	<ul class="nav nav-tabs">
	    	<li class="active"><a data-toggle="tab" href="#general_mail">Formulario</a></li>
	    	<div class="pull-right">
				<a title="Guardar" onclick="guardar_mandarMail()" class="btn btn-primary btn-form">Guardar</a>
				<a title="Cancelar proceso" onclick="cancelar_mandarMail()" class="btn btn-danger btn-form">Cancelar</a>
	    	</div>
	  	</ul>
	  	<div class="tab-content">
    		<div id="general_mail" class="tab-pane fade in active">
				<form method="post" enctype="multipart/form-data" id="formulario_mail">
					<div style="display:none">
						<textarea id="input_comprobantes" name="comprobantes"></textarea>
					</div>
					<div class="acordeon">
						<div class="acordeon__item">
							<input type="checkbox" name="acordeon" class="check-acordeon" id="item110" onchange="cambiar_check(110)" checked>
							<label for="item110" class="acordeon__titulo">
								<div style="text-align:left;">General <span style="float:right;"><span id="icon110" class="glyphicon glyphicon glyphicon-chevron-down"></span></span></div>
							</label>
							<div class="acordeon__contenido">
								<div class="col-md-12" <?php echo $visible; ?>>
									<label class="lab">Cliente</label>
									<input type="text" class="form-control" name="cliente" id="cliente_mail" readonly onfocus="modal('Cliente')" value="<?php echo htmlspecialchars($cliente); ?>">
									<input type="hidden" id="id_cliente_mail" name="id_cliente" value="<?php echo htmlspecialchars($id_cliente); ?>">
									<input type="hidden" id="cod_cliente_mail" name="cod_cliente" value="<?php echo htmlspecialchars($cod_cliente); ?>">
									<div class="error_color" id="error_cliente_mail"></div>
								</div>		
								<div class="row">
									<div class="col-md-12">
										<label class="lab">Destinatarios</label>
										<select class="form-control select2" multiple="multiple" name="destinatario_fijos[]" id="destinatario_fijos">
											<?php 
											echo str_replace("value=", "data-id=", $array_asignados); 
											if(isset($cod_cliente)){					
												$seguimientoModel = new seguimientoModel;
												echo $seguimientoModel->mailsDeContactos($cod_cliente);
											}
											?>
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
												    placeholder: " Destinatarios",
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
									</script>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div id="adjuntos">
											<?php 
											$cont_adjunto = 0;
											foreach ($adjuntos as $fila) {
											?>
												<div class="row" id='fila_<?php echo $cont_adjunto ?>'>
													<div class="col-md-12">
														<div class="input-group">
															<input type="text" readonly class="form-control archivos_subidos" value="<?php echo $fila["adjunto"]; ?>" style="margin: 10px 0 0 0;" placeholder="Adjunto" name="archivos_subidos[]" id='archivo_<?php echo $cont_adjunto ?>' >
															<span class="input-group-btn">
																<a onclick="eliminarAdjunto(this)" class="btn btn-primary btn-sel" style="cursor: pointer; margin: 10px 0 0 0;">
																	<span class="glyphicon glyphicon-trash"></span>
																</a>
															</span>
														</div>
														<div class="error_color" id="error_adjunto_<?php echo $cont_adjunto ?>"></div>
													</div>
												</div>
											<?php
												$cont_adjunto ++;
											}
											?>
										</div>
										<br>
										<input type="hidden" id="tabla_adjunto_id" value="<?php echo $cont_adjunto; ?>">
										<a class="btn btn-primary btn-form" onclick="agregarAdjunto()"><i class="fa fa-paperclip"></i> Adjunto</a>
									</div>
								</div>
							</div>
						</div>
						<div class="acordeon__item">
							<input type="checkbox" name="acordeon" class="check-acordeon" id="item111" onchange="cambiar_check(111)" checked>
							<label for="item111" class="acordeon__titulo">
								<div style="text-align:left;">Comprobantes <span id="cantidad111">(<?php echo count($comprobantes);?>)</span> <span style="float:right;"><span id="icon11" class="glyphicon glyphicon glyphicon-chevron-down"></span></span></div>
							</label>
							<div class="acordeon__contenido">
								<div class="row">
									<div class="col-md-12">
										<div class="table-responsive">
											<table class="table" id="comprobantes_mails">
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
														<th>Fecha de pago</th>
					       								<th>Forma de pago</th>
					       								<th>Observación</th>
													</tr>
												</thead>
												<tbody>
													<?php 
													foreach ($comprobantes as $fila) {
													?>	
													<tr>
														<td><input type="checkbox" class="check_comprobantes"></td>
														<td><?php echo $fila["tipo"]; ?></td>
														<td><?php echo $fila["comprobante"]; ?></td>
														<td><?php echo $fila["estado"]; ?></td>
														<td><?php echo $fila["fecha"]; ?></td>
														<td><?php echo $fila["vencimiento"]; ?></td>
														<td><?php echo $fila["importe"]; ?></td>
														<td><?php echo $fila["dias"]; ?></td>
														<td><?php echo $fila["fecha_pago"]; ?></td>
														<td><?php echo $fila["forma_pago"]; ?></td>
														<td><?php echo $fila["observaciones"]; ?></td>
													</tr>
													<?php
													}
													?>
												</tbody>
											</table>
										</div>
									</div>
								</div>			
							</div>
						</div>		
					</div>			
				</form>	
			</div>
		</div>
	</div>	
</div>			
<script>
	function cancelar_mandarMail(){
		if(confirm("Desea cancelar el envio del mail?")){
			if($("#current_url_hidden").val().indexOf("-mail") > -1){
				history.go(-1);
			}else{
				$("#modalMail").modal("hide");
			}
		}
	}

	function guardar_mandarMail(){
		var ok = true;
		if($("#asunto_mail").val() == ""){
			ok = false;
			marcarError("asunto_mail", "Campo obligatorio");
		}
		var descripcion = CKEDITOR.instances["contenido_mail"].getData();
		if(descripcion == ""){
			ok = false;
			marcarError("contenido_mail", "Campo obligatorio");
		}
		var destinatario_fijos = $("#destinatario_fijos option:selected");
		if(destinatario_fijos.length == 0){
			ok = false;
			marcarError("destinatario_fijos", "Campo obligatorio");	
		}
		var destinatarios = "";
		destinatario_fijos.each(function(){
			if(!validoEmail(this.value)){
				ok = false;
				marcarError("destinatario_fijos", "Todos deben ser correos");
			}else{
				destinatarios += (this.value)+";";
			}
		});
		if(destinatarios != ""){
			destinatarios.substr(0, destinatarios.length-1);
		}
	    var objeto = new Object();
	    var comprobantes = new Array();
		$("#comprobantes_mails tbody tr").each(function(){
			objeto = new Object();
			objeto.tipo = $($(this).children("td")[1]).html();
			objeto.comprobante = $($(this).children("td")[2]).html();
			objeto.estado = $($(this).children("td")[3]).html();
			var fecha = $($(this).children("td")[4]).html();
			objeto.fecha = fecha.substr(6,4)+'-'+fecha.substr(3,2)+'-'+fecha.substr(0,2);
			var vencimiento = $($(this).children("td")[5]).html();
			objeto.vencimiento = vencimiento.substr(6,4)+'-'+vencimiento.substr(3,2)+'-'+vencimiento.substr(0,2);
			objeto.importe = $($(this).children("td")[6]).html();
			objeto.dias = $($(this).children("td")[7]).html();
			objeto.fecha_pago = $($(this).children("td")[8]).html();
			var fecha_pago = $($(this).children("td")[9]).html();
			objeto.fecha_pago = vencimiento.substr(6,4)+'-'+vencimiento.substr(3,2)+'-'+vencimiento.substr(0,2);
			objeto.forma_pago = $($(this).children("td")[10]).html();
			objeto.observaciones = $($(this).children("td")[11]).html();
			comprobantes.push(objeto);
		})

		for(instance in CKEDITOR.instances) {
	        CKEDITOR.instances[instance].updateElement();
	    }

		$("#input_comprobantes").val(JSON.stringify(comprobantes));

		if(ok){
			var formData = new FormData(document.getElementById("formulario_mail"));
			$.ajax({
				type: "POST",
				url: document.getElementById("base_url").value+"Seguimiento/mandarMail",
				data:formData,
				contentType: false,
				processData: false,
				dataType: "json",
				success: function (respuesta) {
					if(respuesta[0] == "OK"){
						if($("#current_url_hidden").val().indexOf("-mail") > -1){
							location.href = "<?php echo base_url() ?>seguimiento";
						}else{
							var d = new Date();
					       	var fecha = 
						       	[d.getFullYear(),
						        (d.getMonth()+1).AddZero(),
						        (d.getDate()).AddZero()].join('-') 
						        +'T'+
						        [d.getHours().AddZero(),
						        d.getMinutes().AddZero()].join(':');
							
							$("#modalMail").modal("hide");
							var objeto_devolver = new Object();
							objeto_devolver.id_actividad = respuesta[1];
							objeto_devolver.asunto = asunto;
							objeto_devolver.fecha = fecha;
							objeto_devolver.estado = '';
							objeto_devolver.id_cliente = $("#id_cliente_mail").val();
							objeto_devolver.cliente = $("#cliente_mail").val();
							objeto_devolver.direccion = '';
							objeto_devolver.descripcion = descripcion;
							objeto_devolver.proximo_contacto = destinatarios;
							objeto_devolver.comprobantes = comprobantes;
							objeto_devolver.asignados = '';
							accion_mail_bd(objeto_devolver);
						}
					}else{
						alert(respuesta[0]);
					}
				}
			});
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
</script>