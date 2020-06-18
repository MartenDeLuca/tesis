<div class="content-wrapper" style="background: white !important;">
  	<section class="content-header">
      <h1 id="h1_tablero">
        Licencia <small>Alta</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url() ?>licencias">Licencias</a></li>
        <li class="active">Agregar Licencia</li>
      </ol>
	</section>
	<section class="content">
  		<div class="nav-tabs-custom">
		  	<ul class="nav nav-tabs">
		    	<li class="active"><a data-step="licencia_tab" data-toggle="tab" href="#licencia">Licencia</a></li>
		    	<div class="pull-right">
		    		<div class="text-right">
						<a title="Guardar Licencia" onclick="guardar()" class="btn btn-primary btn-form">Guardar</a>
						<a title="Cancelar proceso" onclick="cancelar()" class="btn btn-danger btn-form">Cancelar</a>
					</div>
		    	</div>
		  	</ul>
		  	<div class="tab-content">
	    		<div id="licencia_tab" class="tab-pane fade in active">
	    			<div class="row">
	    				<div class="col-md-6">
	    					<label class="lab">Licencia</label>
	    					<input type="text" class="form-control int" placeholder="Licencia" name="licencia" id="licencia"  maxlength="8">
	    					<div class="error_color" id="error_licencia"></div>
	    				</div>
	    				<div class="col-md-6">
	    					<label class="lab">Dominio</label>
	    					<input type="text" onblur="getBases()" class="form-control" placeholder="Dominio" name="dominio" id="dominio">
	    					<div class="error_color" id="error_dominio"></div>
	    				</div>
	    			</div>
	    			<div class="row">
	    				<div class="col-md-6">
	    					<label class="lab">Diccionario</label>
	    					<input type="text" onblur="getBases()" class="form-control" placeholder="Diccionario" name="diccionario" id="diccionario">
	    					<div class="error_color" id="error_diccionario"></div>
	    				</div>
	    				<div class="col-md-6">
	    					<label class="lab">Empresa</label>
	    					<select type="text" class="form-control select2" multiple="multiple" placeholder="Empresa" name="empresa" id="empresa">
	    					</select>
	    					<div class="error_color" id="error_empresa"></div>
	    				</div>
	    			</div>
	    			<div class="row">
	    				<div class="col-md-6">
	    					<label class="lab">Nombre Administrador</label>
	    					<input type="text" class="form-control" placeholder="Administrador" name="usuario_administrador" id="usuario_administrador">
	    					<div class="error_color" id="error_usuario_administrador"></div>
	    				</div>
	    				<div class="col-md-6">
	    					<label class="lab">Correo Administrador</label>
	    					<input type="text" class="form-control" placeholder="Correo administrador" name="correo_administrador" id="correo_administrador">
	    					<div class="error_color" id="error_correo_administrador"></div>
	    				</div>
	    			</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script type="text/javascript">
	$('document').ready(function(){
		$(".select2").select2();
	});

	function getBases(){
		let dominio=  $('#dominio').val();
		let diccionario=  $('#diccionario').val();
		if (diccionario !='' && dominio!=''){
			$.ajax({
				url: dominio+"/api/get_bases",
				type: "POST",
				async: false, 
				data:{diccionario:diccionario},
				dataType: "json",
				success: function(respuesta){
					if(respuesta.length > 0) {
						let opciones = '';
						$("#empresa option").remove();
						for (var i = 0; i < respuesta.length; i++) {
							opciones +=`<option class="columna_consulta" value="${respuesta[i]['NombreBD']}">${respuesta[i]['NombreBD']}</option>`;	
						}
						$("#empresa").append("<option></option>"+opciones);
					}
				}
			});		
		}
	}
	

	function guardar(){
		var ok = true;
		var licencia = $("#licencia").val();
		if(licencia == ""){
			ok = false;
			marcarError("licencia", "Campo obligatorio");
		}
		var empresa = $("#empresa").val();
		if(empresa == ""){
			ok = false;
			marcarError("empresa", "Campo obligatorio");
		}
		var diccionario = $("#diccionario").val();
		if(diccionario == ""){
			ok = false;
			marcarError("diccionario", "Campo obligatorio");
		}	
		var dominio = $("#dominio").val();
		if(dominio == ""){
			ok = false;
			marcarError("dominio", "Campo obligatorio");
		}
		var usuario_administrador = $("#usuario_administrador").val();
		if(usuario_administrador == ""){
			ok = false;
			marcarError("usuario_administrador", "Campo obligatorio");
		}		
		var correo_administrador = $("#correo_administrador").val();
		if(correo_administrador == ""){
			ok = false;
			marcarError("correo_administrador", "Campo obligatorio");
		}		
		if(ok){
			$.ajax({
				type: "POST",
				url: document.getElementById("base_url").value+"Usuario/licencia_bd_alta",
				data:{licencia:licencia, empresa:empresa, diccionario:diccionario, dominio:dominio, usuario_administrador:usuario_administrador, correo_administrador:correo_administrador},
				success: function (respuesta) {
					if(respuesta == "OK"){
						location.href = document.getElementById("base_url").value+"licencias";
					}else{
						alert(respuesta);
					}
				}
			});
		}
	}

	function cancelar(){
		if(confirm("Desea cancelar el ingreso de la licencia?")){
			history.go(-1);
		}
	}

	/*Manejos de int*/
	$(".int").keypress(function (e) {
		if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
			return false;
		}
	});

</script>