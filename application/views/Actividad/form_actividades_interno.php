<div class="formulario">
		<div class="nav-tabs-custom">
	  	<ul class="nav nav-tabs">
	    	<li class="active"><a data-toggle="tab" href="#general">Formulario</a></li>
	    	<div class="pull-right">
				<a title="Guardar" onclick="guardar()" class="btn btn-primary btn-form">Guardar</a>
				<a title="Cancelar proceso" onclick="cancelar()" class="btn btn-danger btn-form">Cancelar</a>
	    	</div>
	  	</ul>
	  	<div class="tab-content">
    		<div id="general" class="tab-pane fade in active">
				<div class="acordeon">
					<div class="acordeon__item">
						<input type="checkbox" name="acordeon" class="check-acordeon" id="item10" onchange="cambiar_check(10)" checked>
						<label for="item10" class="acordeon__titulo">
							<div style="text-align:left;">General <span style="float:right;"><span id="icon10" class="glyphicon glyphicon glyphicon-chevron-down"></span></span></div>
						</label>
						<div class="acordeon__contenido">
							<div class="row">
								<div class="col-md-4">
									<label class="lab">Asunto</label>
									<input type="text" class="form-control" id="asunto" maxlength="500" value="<?php echo htmlspecialchars($asunto); ?>">
									<div class="error_color" id="error_asunto"></div>
								</div>
								<div class="col-md-4">
									<label class="lab">Fecha</label>
									<input type="datetime-local" class="form-control" id="fecha" value="<?php
			    					$fecha = str_replace (' ' , 'T' , $fecha);
			    					if(strpos($fecha, '1969-12-31') !== false){
			    						echo '';
			    					}else{
			    						echo $fecha;
			    					}?>">
									<div class="error_color" id="error_fecha"></div>
								</div>
								<div class="col-md-4">
									<label class="lab">Estado</label>
									<select class="form-control select2 input_select2" id="estado">
										<option <?php if($estado == "Pendiente"){ echo "selected"; } ?>>Pendiente</option>
										<option <?php if($estado == "Realizada"){ echo "selected"; } ?>>Realizada</option>
									</select>
									<div class="error_color" id="error_estado"></div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<label class="lab">Cliente</label>
									<input type="text" class="form-control" id="cliente" readonly onfocus="modal('Cliente')" value="<?php echo htmlspecialchars($cliente); ?>">
									<input type="hidden" id="id_cliente" value="<?php echo htmlspecialchars($id_cliente); ?>">
									<input type="hidden" id="cod_cliente" value="<?php echo htmlspecialchars($cod_cliente); ?>">
									<div class="error_color" id="error_cliente"></div>
								</div>
								<div class="col-md-4">
									<label class="lab">Asignado</label>
									<select multiple class="form-control select2 input_select2" id="asignado">
										<?php echo $array_asignados; ?>
									</select>
									<script type="text/javascript">
										$(document).ready(function(){
											var asignados = new Array();
											<?php 
											foreach ($asignados as $fila) {
											?>
												asignados.push(<?php echo $fila["id_usuario"]; ?>);
											<?php
											}
											?>
											$("#asignado, #estado").select2();
											$("#asignado").val(asignados).trigger("change");
										});
									</script>
									<div class="error_color" id="error_asignado"></div>
								</div>
								<div class="col-md-4">
									<label class="lab">Direccion de retiro</label>
									<div class="input-group">
										<input type="text" class="form-control" id="direccion" value="<?php echo htmlspecialchars($direccion); ?>">
										<span class="input-group-addon add-on" onclick="modal('Direccion')">
					                    	<span class="glyphicon glyphicon-search"></span>
					                  	</span>
									</div>	
									<div class="error_color" id="error_direccion"></div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<label class="lab">Proximo Contacto</label>
									<input type="datetime-local" class="form-control" id="proximo_contacto" value="<?php 
			    					$proximo_contacto = str_replace (' ' , 'T' , $proximo_contacto);
			    					if(strpos($proximo_contacto, '1969-12-31') !== false){
			    						echo '';
			    					}else{
			    						echo $proximo_contacto;
			    					}?>">
									<div class="error_color" id="error_proximo_contacto"></div>
								</div>
							</div>									
			    			<div class="row">
								<div class="col-md-12">
									<label class="lab">Descripción</label>
									<textarea class="form-control" name="descripcion" id="descripcion">
										<?php echo htmlspecialchars($descripcion) ?>
									</textarea>
									<div class="error_color" id="error_descripcion"></div>
								</div>
						 		<script>
					 				CKEDITOR.replace('descripcion');
					 				CKEDITOR.add

									if (CKEDITOR.instances['descripcion']) {						
										CKEDITOR.instances['descripcion'].on('blur', function(event) {
											validoCkeditor('descripcion');
										});
									}

									CKEDITOR.on('descripcion', function(e) {
										e.CKEDITOR.instances['descripcion'].addCss( 'body { background-color: red; }' );
									});

									CKEDITOR.instances['descripcion'].on('contentDom', function() {
										CKEDITOR.instances['descripcion'].document.on('keyup', function(event) {
									        $('#cke_descripcion').children(".cke_inner").children('.cke_top').css('border','1px solid #d2d6de');
				       						$('#cke_descripcion').children(".cke_inner").children('.cke_contents').css('border-left','1px solid #d2d6de');
				       						$('#cke_descripcion').children(".cke_inner").children('.cke_contents').css('border-right','1px solid #d2d6de');
				       						$('#cke_descripcion').children(".cke_inner").children('.cke_contents').css('border-bottom','1px solid #d2d6de');	
											document.getElementById("error_descripcion").innerHTML = '';
										});
									});
								</script>
							</div>
						</div>
					</div>
					<div class="acordeon__item">
						<input type="checkbox" name="acordeon" class="check-acordeon" id="item11" onchange="cambiar_check(11)" checked>
						<label for="item11" class="acordeon__titulo">
							<div style="text-align:left;">Comprobantes <span id="cantidad11">(<?php echo count($comprobantes);?>)</span> <span style="float:right;"><span id="icon11" class="glyphicon glyphicon glyphicon-chevron-down"></span></span></div>
						</label>
						<div class="acordeon__contenido">
							<div class="row">
								<div class="col-md-12">
									<div class="table-responsive">
										<table class="table" id="comprobantes">
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
			</div>
		</div>
	</div>
</div>

<input type="hidden" id="id_actividad" value = "<?php echo $id_actividad; ?>">
<input type="hidden" id="instancia" value = "<?php echo $instancia; ?>">
<script>
	function buscarDireccion(){
		var id_cliente = $("#cod_cliente").val();
		var consulta = $("#modal-buscar").val();
		$.ajax({
			url: "<?php echo $this->session->userdata('dominio') ?>/api/buscarDirecciones",
			type: "POST",
			data:{consulta, id_cliente, empresa},
			dataType: "json",
			success: function(respuesta){
				var html = `<table class='table'><tr><td>Direccion</td><td>Cod. Postal</td><td>Localidad</td><td>Provincia</td></tr>`;
				var tamano = respuesta.length;
				for(var i = 0; i < tamano; i++){
					html += `<tr style="cursor:pointer" onclick="seleccionarDireccion(${respuesta[i]["id"]})"><td>${respuesta[i]["direccion"]}</td><td>${respuesta[i]["codigo_postal"]}</td><td>${respuesta[i]["localidad"]}</td><td>${respuesta[i]["provincia"]}</td></tr>`;
				}
				html += `</tbody></table>`;
				$("#modal-datos").html(html);
			}
		});
	}

	function seleccionarDireccion(id){
		$.ajax({
			url: "<?php echo $this->session->userdata('dominio') ?>/api/seleccionarDireccion",
			type: "POST",
			data:{id, empresa},
			dataType: "json",
			success: function(respuesta){
				$("#direccion").val(respuesta[0]["direccion"]);
				$("#modal").modal("hide");
			}
		});
	}

	function buscarCliente(){
		var consulta = $("#modal-buscar").val();
		$.ajax({
			url: "<?php echo $this->session->userdata('dominio') ?>/api/buscarClientes",
			type: "POST",
			data:{consulta, empresa},
			dataType: "json",
			success: function(respuesta){
				var html = `<table class='table'><thead><tr><td>Cliente</td><td>Documento</td></tr></thead><tbody>`;
				var tamano = respuesta.length;
				for(var i = 0; i < tamano; i++){
					html += `<tr style="cursor:pointer" onclick="seleccionarCliente(${respuesta[i]["id"]})"><td>${respuesta[i]["cliente"]}</td><td>${respuesta[i]["documento"]}</td></tr>`;
				}
				html += `</tbody></table>`;
				$("#modal-datos").html(html);
			}
		});
	}

	function seleccionarCliente(id){
		$.ajax({
			url: "<?php echo $this->session->userdata('dominio') ?>/api/seleccionarCliente",
			type: "POST",
			data:{id, empresa},
			dataType: "json",
			success: function(respuesta){
				$("#id_cliente").val(respuesta[0]["id_cliente"]);
				$("#cod_cliente").val(respuesta[0]["cod_cliente"]);
				$("#cliente").val(respuesta[0]["cliente"]);
				$("#telefono").val(respuesta[0]["telefono"]);
				$("#horario_retiro").val(respuesta[0]["horario_retiro"]);
				$("#id_contacto").val("");
				$("#contacto").val("");
				campo_dias_reclamo(respuesta[0]["dias_reclamo"]);	
				$.ajax({
					url: "<?php echo base_url() ?>seguimiento/obtenerComprobantes",
					type: "POST",
					data:{id, empresa},
					dataType: "json",
					success: function(comprobantes){
						var tamano = comprobantes.length;
						var html_comp = "";
						if(tamano > 0){
							html_comp = htmlComprobantes(comprobantes, tamano);
						}
						$("#cantidad11").html("("+tamano+")");
						$("#comprobantes tbody").html(html_comp);
					}
				});						
				$("#modal").modal("hide");
			}
		});
	}

	function htmlComprobantes(comprobantes, tamano){
		var html_comp = "";
		for(var j = 0; j < tamano; j++){
			html_comp += 
			`<tr>
				<td><input type="checkbox" class="check_comprobantes"></td>
				<td>${comprobantes[j]["tipo"]}</td>
				<td>${comprobantes[j]["comprobante"]}</td>
				<td>${comprobantes[j]["estado"]}</td>
				<td>${comprobantes[j]["fecha"]}</td>
				<td>${comprobantes[j]["vencimiento"]}</td>
				<td>${comprobantes[j]["importe"]}</td>
				<td>${comprobantes[j]["dias"]}</td>
				<td>${comprobantes[j]["fecha_pago"]}</td>
				<td>${comprobantes[j]["forma_pago"]}</td>
				<td>${comprobantes[j]["observaciones"]}</td>
			</tr>`;
		}
		return html_comp;
	}

	function guardar(){
		var ok = true;
		var instancia = $("#instancia").val();
		var id_actividad = $("#id_actividad").val();
		var asunto = $("#asunto").val();
		if(asunto == ""){
			ok = false;
			marcarError("asunto", "Campo obligatorio");
		}		
		var fecha = $("#fecha").val();
		var estado = $("#estado").val();
		var id_cliente = $("#id_cliente").val();
		var cliente = $("#cliente").val();
		if(id_cliente == ""){
			ok = false;
			marcarError("cliente", "Campo obligatorio");
		}
		var descripcion = CKEDITOR.instances["descripcion"].getData();
		var proximo_contacto = $("#proximo_contacto").val();
		var direccion = $("#direccion").val();
		var objeto = new Object();
		var asociacion_2 = $("#asignado").val();
		var asociacion = new Array();
		for(var i = 0; i < asociacion_2.length; i++){
			objeto = new Object();
			objeto.id_usuario = asociacion_2[i];
			asociacion.push(objeto);
		}
		var comprobantes = new Array();
		$("#comprobantes tbody tr").each(function(){
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
			var fecha_pago = $($(this).children("td")[8]).html();
			objeto.fecha_pago = vencimiento.substr(6,4)+'-'+vencimiento.substr(3,2)+'-'+vencimiento.substr(0,2);
			objeto.forma_pago = $($(this).children("td")[9]).html();
			objeto.observaciones = $($(this).children("td")[10]).html();
			comprobantes.push(objeto);
		})

		if(ok){
			$.ajax({
				url: "<?php echo base_url() ?>seguimiento/actividad_bd",
				type: "POST",
				data:{instancia, id_actividad, 
					asunto, fecha, estado, 
					id_cliente, cliente,
					asociacion:JSON.stringify(asociacion), proximo_contacto, direccion,
					descripcion,
					comprobantes:JSON.stringify(comprobantes)},
				dataType: "json",
				success: function(respuesta){
					if(respuesta[0] == "OK"){
						if($("#current_url_hidden").val().indexOf("-actividad") > -1){
							location.href = "<?php echo base_url() ?>seguimiento";
						}else{
							$("#modalActividad").modal("hide");
							var objeto_devolver = new Object();
							objeto_devolver.id_actividad = respuesta[1];
							objeto_devolver.asunto = asunto;
							objeto_devolver.fecha = fecha;
							objeto_devolver.estado = estado;
							objeto_devolver.id_cliente = id_cliente;
							objeto_devolver.cliente = cliente;
							objeto_devolver.direccion = direccion;
							objeto_devolver.descripcion = descripcion;
							objeto_devolver.proximo_contacto = proximo_contacto;
							objeto_devolver.comprobantes = comprobantes;
							objeto_devolver.asignados = asociacion;
							accion_actividad_bd(objeto_devolver);
						}
					}else{
						alert(respuesta[0]);
					}
				}
			});
		}
	}

	function cancelar(){
		if(confirm("Desea cancelar el ingreso de la actividad?")){
			if($("#current_url_hidden").val().indexOf("-actividad") > -1){
				history.go(-1);
			}else{
				$("#modalActividad").modal("hide");
			}
		}
	}
</script>