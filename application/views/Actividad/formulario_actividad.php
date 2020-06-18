<div class="content-wrapper" style="background: white !important;">	
    <section class="content-header">
        <h1>
          Actividad
        </h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url(); ?>seguimiento">Seguimiento</a></li>
          <li class="active">Formulario Actividad</li>
        </ol>
  	</section>
  	<section class="content">
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
								<input type="checkbox" name="acordeon" class="check-acordeon" id="item0" onchange="cambiar_check(0)" checked>
								<label for="item0" class="acordeon__titulo">
									<div style="text-align:left;">General <span style="float:right;"><span id="icon0" class="glyphicon glyphicon glyphicon-chevron-down"></span></span></div>
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
												<option <?php if($estado == "Realizado"){ echo "selected"; } ?>>Realizado</option>
											</select>
											<div class="error_color" id="error_estado"></div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-4">
											<label class="lab">Cliente</label>
											<input type="cliente" class="form-control" id="cliente" readonly onfocus="modal('Cliente')" value="<?php echo htmlspecialchars($cliente); ?>">
											<input type="hidden" id="id_cliente" value="<?php echo htmlspecialchars($id_cliente); ?>">
											<input type="hidden" id="cod_cliente" value="<?php echo htmlspecialchars($cod_cliente); ?>">
											<div class="error_color" id="error_cliente"></div>
										</div>
										<div class="col-md-4">
											<label class="lab">Teléfono</label>
											<input type="text" class="form-control" id="telefono" value="<?php echo htmlspecialchars($telefono); ?>">
											<div class="error_color" id="error_telefono"></div>
										</div>
										<div class="col-md-4">
											<label class="lab">Días de Reclamo</label>
											<select class="form-control select2 input_select2" id="dias_reclamo" multiple>
												<option>Lunes</option>
												<option>Martes</option>
												<option>Miercoles</option>
												<option>Jueves</option>
												<option>Viernes</option>
												<option>Sabado</option>
												<option>Domingo</option>
											</select>
											<script type="text/javascript">
												$(document).ready(function(){
													campo_dias_reclamo('<?php echo $dias_reclamo; ?>');
												})
											</script>
											<div class="error_color" id="error_dias_reclamo"></div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-4">
											<label class="lab">Contacto</label>
											<div class="input-group">
												<input type="contacto" class="form-control" id="contacto" readonly onfocus="modal('Contacto')" value="<?php echo htmlspecialchars($contacto); ?>">
												<input type="hidden" id="id_contacto" value="<?php echo htmlspecialchars($id_contacto); ?>">
												<span class="input-group-addon add-on" onclick="vaciar_modal('contacto')">
							                    	<span class="glyphicon glyphicon-remove"></span>
							                  	</span>
											</div>	
											<div class="error_color" id="error_contacto"></div>
										</div>
										<div class="col-md-4">
											<label class="lab">Celular</label>
											<input type="text" class="form-control" id="celular" value="<?php echo htmlspecialchars($celular); ?>">
											<div class="error_color" id="error_celular"></div>
										</div>
										<div class="col-md-4">
											<label class="lab">Correo</label>
											<input type="text" class="form-control" id="correo" value="<?php echo htmlspecialchars($correo); ?>">
											<div class="error_color" id="error_correo"></div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-4">
											<label class="lab">Asignado</label>
											<select multiple class="form-control select2 input_select2" id="asignado">
												<?php echo $array_asignados; ?>
											</select>
											<script type="text/javascript">
												$(document).ready(function(){
													<?php 
													foreach ($asignados as $fila) {
													?>
														$("#asignados_actividad option[value='<?php echo $fila["id_usuario"]; ?>']").attr("selected", true);
													<?php
													}
													?>
												});
											</script>
											<div class="error_color" id="error_asignado"></div>
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
								<input type="checkbox" name="acordeon" class="check-acordeon" id="item1" onchange="cambiar_check(1)" checked>
								<label for="item1" class="acordeon__titulo">
									<div style="text-align:left;">Comprobantes <span id="cantidad1">(<?php echo count($comprobantes);?>)</span> <span style="float:right;"><span id="icon1" class="glyphicon glyphicon glyphicon-chevron-down"></span></span></div>
								</label>
								<div class="acordeon__contenido">
									<div class="row">
										<div class="col-md-12">
											<div class="table-responsive">
												<table class="table" id="comprobantes">
													<thead>
														<tr>
															<th>Tipo</th>
															<th>Comprobante</th>
															<th>Fecha</th>
															<th>Vencimiento</th>
															<th>Importe</th>
															<th>Días</th>
															<th>Fecha de retiro</th>
														</tr>
													</thead>
													<tbody>
														<?php 
														foreach ($comprobantes as $fila) {
														?>	
														<tr>
															<td><?php echo $fila["tipo"]; ?></td>
															<td><?php echo $fila["comprobante"]; ?></td>
															<td><?php echo $fila["fecha"]; ?></td>
															<td><?php echo $fila["vencimiento"]; ?></td>
															<td><?php echo $fila["importe"]; ?></td>
															<td><?php echo $fila["dias"]; ?></td>
															<td><input type="date" class="fecha_retiro" value="<?php $fecha_retiro = str_replace (' ' , 'T' , $fila["fecha_retiro"]);
															if(strpos($fecha_retiro, '1969-12-31') !== false){echo '';}else{echo $fecha_retiro;}?>"></td>
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
							<div class="acordeon__item">
								<input type="checkbox" name="acordeon" class="check-acordeon" id="item2" onchange="cambiar_check(2)" checked>
								<label for="item2" class="acordeon__titulo">
									<div style="text-align:left;">Gestión de cobranza <span style="float:right;"><span id="icon2" class="glyphicon glyphicon glyphicon-chevron-down"></span></span></div>
								</label>
								<div class="acordeon__contenido">
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
										<div class="col-md-4">
											<label class="lab">Horario de retiro</label>
											<input type="text" class="form-control" id="horario_retiro" value="<?php echo htmlspecialchars($horario_retiro); ?>">
											<div class="error_color" id="error_horario_retiro"></div>
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
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<div class="modal fade in" id="modal" tabindex="-1" role="dialog" aria-labelledby="formBuscar" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title" id="modal-title"></h4> 
			</div>				
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<label class="lab">Buscar</label>
						<input type="text" class="form-control" id="modal-buscar">
					</div>
					<div class="col-md-12">
						<div id="modal-datos"></div>
					</div>	
				</div>				
			</div>
		</div>
	</div>
</div>
<input type="hidden" id="id_actividad" value ="<?php echo $id_actividad; ?>">
<input type="hidden" id="instancia" value ="<?php echo $instancia; ?>">
<script type="text/javascript">
	var empresa = "<?php echo $this->session->userdata('empresa'); ?>";
	var tipo_funcion = "";

	$('document').ready(function(){
		$(".input_select2").select2();
		$(".show-hide").parent().parent().hide();
	});
	
	$("#modal-buscar").keyup(function(){
		window["buscar"+tipo_funcion]();
	})

	function modal(tipo){
		$("#modal").modal("show");
		$("#modal-buscar").val("");
		tipo_funcion = tipo;
		$("#modal-title").html("Buscar "+tipo);
		window["buscar"+tipo]();
	}

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
							for(var j = 0; j < tamano; j++){
								html_comp += 
								`<tr>
									<td>${comprobantes[j]["tipo"]}</td>
									<td>${comprobantes[j]["comprobante"]}</td>
									<td>${comprobantes[j]["fecha"]}</td>
									<td>${comprobantes[j]["vencimiento"]}</td>
									<td>${comprobantes[j]["importe"]}</td>
									<td>${comprobantes[j]["dias"]}</td>
									<td><input type="date" class="fecha_retiro" value="${comprobantes[j]["fecha_retiro"]}"></td>
								</tr>`;
							}
						}
						$("#cantidad1").html("("+tamano+")");
						$("#comprobantes tbody").html(html_comp);
					}
				});						
				$("#modal").modal("hide");
			}
		});
	}

	function campo_dias_reclamo(dias_reclamo){		
		if(dias_reclamo != ""){
			var dias_reclamo = dias_reclamo.split(';');
			var tamano = dias_reclamo.length;
			var dias = new Array("Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado", "Domingo");
			for(var j = 0; j < tamano; j++){
				var seleccionada = dias_reclamo[j];
				if(seleccionada == "S"){
					var dia_reclamo = dias[j];
					$("#dias_reclamo option[value='"+dia_reclamo+"']").attr("selected", true);
				}
			}
		}
	}

	function buscarContacto(){
		var id_cliente = $("#cod_cliente").val();
		var consulta = $("#modal-buscar").val();		
		$.ajax({
			url: "<?php echo $this->session->userdata('dominio') ?>/api/buscarContactos",
			type: "POST",
			data:{consulta, id_cliente, empresa},
			dataType: "json",
			success: function(respuesta){
				var html = `<table class='table'><tr><td>Contacto</td><td>Celular</td><td>Correo</td></tr>`;
				var tamano = respuesta.length;
				for(var i = 0; i < tamano; i++){
					html += `<tr style="cursor:pointer" onclick="seleccionarContacto(${respuesta[i]["id"]})"><td>${respuesta[i]["contacto"]}</td><td>${respuesta[i]["celular"]}</td><td>${respuesta[i]["correo"]}</td></tr>`;
				}
				html += `</tbody></table>`;
				$("#modal-datos").html(html);
			}
		});
	}

	function seleccionarContacto(id){
		var id_cliente = $("#id_cliente").val();
		$.ajax({
			url: "<?php echo $this->session->userdata('dominio') ?>/api/seleccionarContacto",
			type: "POST",
			data:{id, id_cliente, empresa},
			dataType: "json",
			success: function(respuesta){
				$("#id_contacto").val(id);
				$("#contacto").val(respuesta[0]["contacto"]);
				$("#correo").val(respuesta[0]["correo"]);
				$("#celular").val(respuesta[0]["celular"]);
				$("#modal").modal("hide");
			}
		});
	}

	function vaciar_modal(id){
		$("#"+id).val("");
		$("#id_"+id).val("");
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
		var telefono = $("#telefono").val();
		var dias_reclamo = "";
		$('#dias_reclamo > option').each(function() {
		    if($(this).is(':selected')){
		    	dias_reclamo += "S;";
		    }else{
		    	dias_reclamo += "N;";
		    }
		});
		dias_reclamo = dias_reclamo.substr(0, dias_reclamo.length-1);
		var id_contacto = $("#id_contacto").val();
		var contacto = $("#contacto").val();
		var celular = $("#celular").val();
		var correo = $("#correo").val();
		var descripcion = CKEDITOR.instances["descripcion"].getData();
		var proximo_contacto = $("#proximo_contacto").val();
		var horario_retiro = $("#horario_retiro").val();
		var direccion = $("#direccion").val();
		var objeto = new Object();
		var asociacion = new Array();
		$("#asociacion option:selected").each(function(){
			objeto = new Object();
			objeto.id_usuario = $(this).val(); 
			asociacion.push(objeto);
		});
		var comprobantes = new Array();
		$("#comprobantes tbody tr").each(function(){
			objeto = new Object();
			objeto.tipo = $($(this).children("td")[0]).html();
			objeto.comprobante = $($(this).children("td")[1]).html();
			var fecha = $($(this).children("td")[2]).html();
			objeto.fecha = fecha.substr(6,4)+'-'+fecha.substr(3,2)+'-'+fecha.substr(0,2);
			var vencimiento = $($(this).children("td")[3]).html();
			objeto.vencimiento = vencimiento.substr(6,4)+'-'+vencimiento.substr(3,2)+'-'+vencimiento.substr(0,2);
			objeto.importe = $($(this).children("td")[4]).html();
			objeto.fecha_retiro = $($(this).children("td")[6]).children(".fecha_retiro").val();
			comprobantes.push(objeto);
		})
		if(ok){
			$.ajax({
				url: "<?php echo base_url() ?>seguimiento/actividad_bd",
				type: "POST",
				data:{instancia, id_actividad, 
					asunto, fecha, estado, 
					id_cliente, cliente, telefono, dias_reclamo,
					id_contacto, contacto, celular, correo, asociacion:JSON.stringify(asociacion),
					descripcion,					
					comprobantes:JSON.stringify(comprobantes),
					proximo_contacto, horario_retiro, direccion},
				success: function(respuesta){
					if(respuesta == "OK"){
						location.href = "<?php echo base_url() ?>seguimiento";
					}else{
						alert(respuesta);
					}
				}
			});
		}
	}

	function cancelar(){
		if(confirm("Desea cancelar el ingreso de la actividad?")){
			history.go(-1);
		}
	}

	var hoy = new Date();
	var dd = hoy.getDate();
	var mm = hoy.getMonth() + 1;
	var yyyy = hoy.getFullYear();
	if(dd < 10){
	  dd = '0' + dd;
	}
	if(mm < 10){
	  mm = '0' + mm;
	}
	hoy = yyyy + '-' + mm + '-' + dd;

	$(document).on('blur','.fecha_retiro', function(e) {
		validarFecha(this);
	});

	function validarFecha(objeto){
	  var date = $(objeto).val();
	  if(Date.parse(date)){
	    if(date < hoy){
	      $(objeto).val(hoy);
	    }
	  }
	}
</script>