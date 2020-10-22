	<div class="modal fade" id="modalContra" tabindex="-1" role="dialog"  aria-hidden="true">
		<div class="modal-dialog modal-xs">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="tituloContra">Cambiar Contraseña</h4>
				</div>
				<div class="modal-body">	
				 	<div class="text-right">
						<a class="btn btn-primary btn-form" onclick="cambiarContrasena()">Guardar</a>
						<a class="btn btn-danger btn-form" data-dismiss="modal">Cancelar</a>
					</div>			
					<div class="row">
						<div class="col-md-12">
							<label class="lab">Contraseña Actual</label>
							<input type="password" data-tipo="string" class="form-control" id="contraActual">
							<div class="error_color" id="error_contraActual"></div>
						</div>
						<div class="col-md-12">
							<label class="lab">Nueva Contraseña</label>
							<input type="password" data-tipo="string" class="form-control" id="nuevaContra">
							<div class="error_color" id="error_nuevaContra"></div>
						</div>
						<div class="col-md-12">
							<label class="lab">Confirmar Contraseña</label>
							<input type="password"  data-tipo="string" class="form-control" id="confirmarContra">
							<div class="error_color" id="error_confirmarContra"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
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
						<div class="col-md-12" id="div-modal-opcion">
							<label class="lab">Criterio</label>
							<select class="form-control" id="modal-opcion">
							</select>
						</div>
						<div class="col-md-12">
							<div id="modal-datos"></div>
						</div>	
					</div>		
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade in" id="modalComprobantes" tabindex="-1" role="dialog" aria-labelledby="formBuscar" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" id="modalComprobantes-title">Anotación en comprobantes</h4> 
				</div>
				<div class="modal-body">
					<div class="pull-right">
						<a title="Guardar" onclick="guardarAnotacion()" class="btn btn-primary btn-form">Guardar</a>
						<a title="Cancelar proceso" data-dismiss="modal" class="btn btn-danger btn-form">Cancelar</a>
			    	</div>
					<input type="hidden" id="comprobantes_objeto">
					<input type="hidden" id="comprobantes_tipo_actividad">
					<div class="row">
						<div class="col-md-12">
							<label class="lab">Fecha de pago</label>
							<input type="date" class="form-control" id="comprobantes_fecha_pago">
						</div>
						<div class="col-md-12">
							<label class="lab">Forma de pago</label>
							<select class="form-control" id="comprobantes_forma_pago">
								<option>Transfiere</option>
								<option>Pago con eCheq</option>
								<option>Retirar pago</option>
								<option>Trae pago</option>
							</select>
						</div>
						<div class="col-md-12">
							<label class="lab">Observación</label>
							<input type="text" class="form-control" maxlength="200" id="comprobantes_observaciones">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

    <footer class="main-footer hidden-xs" style="background: #fafafa !important;">
		<b><a href="<?php echo base_url('plugin') ?>/Manual.pdf" title="Descarga el manual" download="Manual.pdf">Manual</a></b>
	</footer>
  </div>
</body>
</html>
<script type="text/javascript">
	var empresa = "<?php echo $this->session->userdata('empresa'); ?>";
	Number.prototype.AddZero = function(b,c){
        var l = (String(b|| 10).length - String(this).length)+1;
        return l > 0 ? new Array(l).join(c|| '0')+this : this;
    }

	var tipo_funcion = "";

	$("#modal-buscar").keyup(function(){
		window["buscar"+tipo_funcion]();
	})

	$("#modal-opcion").change(function(){
		window["buscar"+tipo_funcion]();
	});

	function modal(tipo, opciones){
		if(opciones == undefined){
			$("#div-modal-opcion").hide();
		}else{
			$("#div-modal-opcion").show();
			var opciones = opciones.split("*");
			$("#modal-opcion option").remove();
			for(var i = 0; i < opciones.length; i++){
				$("#modal-opcion").append("<option>"+opciones[i]+"</option>");
			}
		}
		$("#modal").modal("show");
		$("#modal-buscar").val("");
		tipo_funcion = tipo;
		$("#modal-title").html("Buscar "+tipo);
		window["buscar"+tipo]();
	}

	function vaciar_modal(id){
		$("#"+id).val("");
		$("#id_"+id).val("");
	}	

	function buscarPlantilla(){
		var consulta = $("#modal-buscar").val();
		$.ajax({
			url: "<?php echo base_url() ?>plantilla/buscarPlantilla",
			type: "POST",
			data:{consulta},
			dataType: "json",
			success: function(respuesta){
				var html = `<table class='table'><thead><tr><td>Asunto</td></tr></thead><tbody><tr style="cursor:pointer" onclick="seleccionarPlantilla(0)"><td>[SIN PLANTILLA]</td></tr>`;
				var tamano = respuesta.length;
				for(var i = 0; i < tamano; i++){
					html += `<tr style="cursor:pointer" onclick="seleccionarPlantilla(${respuesta[i]["id_plantilla"]})"><td>${respuesta[i]["asunto"]}</td></tr>`;
				}
				html += `</tbody></table>`;
				$("#modal-datos").html(html);
			}
		});
	}

	function seleccionarPlantilla(id){
		var tipo = "";
		if($("#current_url_hidden").val().indexOf("-mail") > -1){
			tipo = "sin_setear";
		}

		if(id != 0){	
			$.ajax({
				url: "<?php echo base_url() ?>plantilla/seleccionarPlantilla",
				type: "POST",
				data:{id, cliente:$("#id_cliente_mail").val(), tipo},
				dataType: "json",
				success: function(respuesta){
					if(respuesta["asunto_mail"]){
						$("#asunto_mail").val(respuesta["asunto_mail"]);
						CKEDITOR.instances['contenido_mail'].setData(respuesta["contenido_mail"]);
					}
					cerrarModalPlantilla();
				}
			});
		}else{
			cerrarModalPlantilla();
		}
	}	
	function cerrarModalPlantilla(){
		$("#modal").modal("hide");
		if($("#current_url_hidden").val().indexOf("-mail") == -1){
			$("#modalMail").modal('show');
		}
	}

	$(document).on("change", ".check_comprobantes", function(){
		if($(this).is(':checked')) {
			var id = $(this).data("comprobante");
			$("#"+id+"_btn_anotacion").show();
		}else{
			var id = $(this).data("comprobante");
			var fila_comprobantes = $('[data-comprobante="'+id+'"]');
			var tamano = fila_comprobantes.length;
			var where_id = "";
			var hayCheck = false;
			for(var i = 0; i < tamano; i++){
				if($(fila_comprobantes[i]).is(':checked')) {
					hayCheck = true;
					break;
				}
			}
			if(!hayCheck){
				$("#"+id+"_btn_anotacion").hide();
			}
		}
	});	

	$(document).on("click", ".btn_anotacion", function(){	
		var id = this.id;
		var objeto = id.substr(0,id.indexOf("_"));
		$("#comprobantes_objeto").val(objeto);
		$("#comprobantes_tipo_actividad").val($(this).data("tipo"));
		$("#comprobantes_fecha_pago, #comprobantes_forma_pago, #comprobantes_observaciones").val("");
		$("#modalComprobantes").modal("show");
	});

	function guardarAnotacion(){
		var tipo = $("#comprobantes_tipo_actividad").val();
		var objeto = $("#comprobantes_objeto").val();
		var esModal = 0;
		if(objeto.indexOf("modal") > -1){
			objeto = objeto.substr(5);
			esModal = 1;
		}
		var fecha_pago = $("#comprobantes_fecha_pago").val();
		var fecha_pago_2 = fecha_pago.substr(8,2)+'/'+fecha_pago.substr(5,2)+'/'+fecha_pago.substr(0,4);
		var forma_pago = $("#comprobantes_forma_pago").val();
		var observacion = $("#comprobantes_observaciones").val();
		var fila_comprobantes = $('[data-comprobante="'+objeto+'"]');
		var tamano = fila_comprobantes.length;
		var where_id = "";		
		if(objeto != "inicio"){
			for(var i = 0; i < tamano; i++){
				if($(fila_comprobantes[i]).is(':checked')) {
					var tr = $(fila_comprobantes[i]).closest("tr");
					where_id = " id = '"+$(tr).data("id")+"' and ";
					$($(tr).children("td")[8]).html(fecha_pago_2);
					$($(tr).children("td")[9]).html(forma_pago);
					$($(tr).children("td")[10]).html(observacion);
				}
			}
			if(where_id != ""){
				where_id = " where "+where_id.substr(0, where_id.length-4);
			}
		}
		if(objeto.indexOf("formulario") == -1 && objeto != "inicio" && where_id != ""){
			$.ajax({
				url: "<?php echo base_url() ?>seguimiento/anotacionesComprobantes",
				type: "POST",
				data:{where_id, tipo, fecha_pago, forma_pago, observacion},
				success: function(respuesta){					
					if(window[objeto+"_datos_actividad"]){
						var fila;
						var comprobantes = new Array();
						$("#"+objeto+"_comprobantes tbody tr").each(function(){
							fila = new Object();
							fila.tipo = $($(this).children("td")[1]).html();
							fila.comprobante = $($(this).children("td")[2]).html();
							fila.estado = $($(this).children("td")[3]).html();
							var fecha = $($(this).children("td")[4]).html();
							fila.fecha = fecha.substr(6,4)+'-'+fecha.substr(3,2)+'-'+fecha.substr(0,2);
							var vencimiento = $($(this).children("td")[5]).html();
							fila.vencimiento = vencimiento.substr(6,4)+'-'+vencimiento.substr(3,2)+'-'+vencimiento.substr(0,2);
							fila.importe = $($(this).children("td")[6]).html();
							fila.dias = $($(this).children("td")[7]).html();
							var fecha_pago = $($(this).children("td")[8]).html();
							fila.fecha_pago = fecha_pago.substr(6,4)+'-'+fecha_pago.substr(3,2)+'-'+fecha_pago.substr(0,2);
							fila.forma_pago = $($(this).children("td")[9]).html();
							fila.observaciones = $($(this).children("td")[10]).html();
							comprobantes.push(fila);
						})
						var array = window[objeto+"_datos_actividad"];
						array["comprobantes"] = comprobantes;
						window[objeto+"_datos_actividad"] = array;
					}
					if(respuesta == "OK"){
						$("#modalComprobantes").modal("hide");
					}else{
						alert(respuesta);
					}
					if(esModal == 1){
						$("#modalInformacion").modal("hide");
					}
				}
			});
		}else if(objeto.indexOf("formulario") > -1){
			$("#modalComprobantes").modal("hide");			
		}else if(objeto == "inicio"){
			$("#modalComprobantes").modal("hide");
			for(var i = 0; i < tamano; i++){
				if($(fila_comprobantes[i]).is(':checked')) {
					var tr = $(fila_comprobantes[i]).closest("tr");
					var contador = $(tr).data("id");
					comprobantes_pendientes[contador]["fecha_pago"] = fecha_pago;
					comprobantes_pendientes[contador]["forma_pago"] = forma_pago;
					comprobantes_pendientes[contador]["observaciones"] = observacion;
				}
			}
			agregarActividad();
		}
	}

	function buscarEnTabla(input, tabla){
	    if(input != ""){
	    	$("#"+tabla+" tbody>tr").hide();
	        $("#"+tabla+" td:contiene-palabra('" + input + "')").parent("tr").show();
	    }
	    else{
	        $("#"+tabla+" tbody>tr").show();
	    }
	}

	jQuery.extend(jQuery.expr[":"], 
	{
	    "contiene-palabra": function(elem, i, match, array) {
	        return (elem.textContent || elem.innerText || $(elem).text() || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
	    }
	});	

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

	function buscarCondicionDeVenta(){
		var consulta = $("#modal-buscar").val();
		$.ajax({
			url: "<?php echo $this->session->userdata('dominio') ?>/api/buscarCondicionDeVenta",
			type: "POST",
			data:{consulta, empresa},
			dataType: "json",
			success: function(respuesta){
				var html = `<table class='table'><thead><tr><td>Codigo</td><td>Descripcion</td></tr></thead><tbody>`;
				var tamano = respuesta.length;
				for(var i = 0; i < tamano; i++){
					html += `<tr style="cursor:pointer" onclick="seleccionarCondicionDeVenta(${respuesta[i]["COND_VTA"]})"><td>${respuesta[i]["COND_VTA"]}</td><td>${respuesta[i]["DESC_COND"]}</td></tr>`;
				}
				html += `</tbody></table>`;
				$("#modal-datos").html(html);
			}
		});
	}	

	function htmlComprobantes(comprobantes, tamano, cont){
		var html_comp = "";
		for(var j = 0; j < tamano; j++){
			var fecha = comprobantes[j]["fecha"];
			if(fecha.indexOf("-") > -1){
				fecha = fecha.substr(8,2)+'/'+fecha.substr(5,2)+'/'+fecha.substr(0,4);
			}			
			var vencimiento = comprobantes[j]["vencimiento"];
			if(vencimiento.indexOf("-") > -1){
				vencimiento = vencimiento.substr(8,2)+'/'+vencimiento.substr(5,2)+'/'+vencimiento.substr(0,4);
			}
			var fecha_pago = comprobantes[j]["fecha_pago"];
			if(fecha_pago.indexOf("-") > -1){
				fecha_pago = fecha_pago.substr(8,2)+'/'+fecha_pago.substr(5,2)+'/'+fecha_pago.substr(0,4);
			}
			html_comp += 
			`<tr>
				<td><input type="checkbox" data-comprobante="${cont}" class="check_comprobantes"></td>
				<td>${comprobantes[j]["tipo"]}</td>
				<td>${comprobantes[j]["comprobante"]}</td>
				<td>${comprobantes[j]["estado"]}</td>
				<td>${fecha}</td>
				<td>${vencimiento}</td>
				<td>${comprobantes[j]["importe"]}</td>
				<td>${comprobantes[j]["dias"]}</td>
				<td>${fecha_pago}</td>
				<td>${comprobantes[j]["forma_pago"]}</td>
				<td>${comprobantes[j]["observaciones"]}</td>
			</tr>`;
		}
		return html_comp;
	}		
</script>