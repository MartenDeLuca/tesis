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
	    					<label class="lab">Empresa</label>
	    					<input type="text" class="form-control" placeholder="Empresa" name="empresa" id="empresa">
	    					<div class="error_color" id="error_empresa		"></div>
	    				</div>
	    			</div>
	    			<div class="row">
	    				<div class="col-md-6">
	    					<label class="lab">Dominio</label>
	    					<input type="text" class="form-control" placeholder="Dominio" name="dominio" id="dominio" >
	    					<div class="error_color" id="error_licencia"></div>
	    				</div>
	    				<div class="col-md-6">
	    					<label class="lab">Diccionario</label>
	    					<input type="text" class="form-control" placeholder="Diccionario" name="diccionario" id="diccionario">
	    					<div class="error_color" id="error_empresa		"></div>
	    				</div>
	    			</div>
	    			
				</div>
			</div>
		</div>
	</section>
</div>
<script type="text/javascript">

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
		if(ok){
			$.ajax({
				type: "POST",
				url: document.getElementById("base_url").value+"Usuario/licencia_bd_alta",
				data:{licencia:licencia, empresa:empresa, diccionario:diccionario, dominio:dominio},
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