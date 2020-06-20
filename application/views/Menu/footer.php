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
    <footer class="main-footer hidden-xs" style="background: #fafafa !important;">
		<b><a href="<?php echo base_url('plugin') ?>/Manual.pdf" title="Descarga el manual" download="Manual.pdf">Manual</a></b>
	</footer>
  </div>
</body>
</html>
<script type="text/javascript">
	var tipo_funcion = "";

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
		if(id != 0){	
			$.ajax({
				url: "<?php echo base_url() ?>plantilla/seleccionarPlantilla",
				type: "POST",
				data:{id, cliente:$("#id_cliente_mail").val()},
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
</script>