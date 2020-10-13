<style type="text/css">
	.liColorMenu{
		float:left; width: 33.33333%; padding: 5px;
	}
	.spanColorMenu{
		display:block; width: 100%; float: left; height: 7px;
	}
	.spanColorMenu2{
		display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;
	}
	.spanColorMenu1{
		display:block; width: 20%; float: left; height: 20px; background: #222d32;
	}	
	.spanColorMenuLight1{
		display:block; width: 20%; float: left; height: 20px; background: #f9fafc;
	}		
	.aColorMenu{
		display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4);
	}
</style>
<?php
$menuFijo = $this->session->userdata('menu_fijo');
$menuFijoClase = "sidebar-collapse";
$menuFijoRadioCheck = "value='No'";
if($menuFijo == "Si"){
	$menuFijoClase = "";
	$menuFijoRadioCheck = "value='Si' checked";
}

$certificado_ssl = "1";
$correo = "";
$host = "";
$puerto = "";
if(isset($mails[0])){
	$mails = $mails[0];
	$certificado_ssl = $mails["certificado_ssl"];
	$correo = $mails["correo"];
	$host = $mails["host"];
	$puerto = $mails["puerto"];
}
?>
<div class="content-wrapper" style="background: white !important;">	
    <section class="content-header">
        <h1>
          Configuración
        </h1>
        <ol class="breadcrumb">
          <li class="active">Configuración</li>
        </ol>
  	</section>
  	<section class="content">
  		<div class="formulario">
      		<div class="nav-tabs-custom">
			  	<ul class="nav nav-tabs">
			    	<li class="active"><a data-toggle="tab" href="#general">Configuracion</a></li>
			    	<?php if ($this->session->userdata('permiso') == 'administrador'){ ?>
			    	<li><a data-toggle="tab" href="#mails">Mails</a></li>
			    	<?php } ?>    	
			  	</ul>
			  	<div class="tab-content">
		    		<div id="general" class="tab-pane fade in active">
						<div class="row">
							<div class="col-md-12">
								<label for="menuFijo"> Menu Fijo <label> <input type="radio" class="radio-button minimal" id="menuFijo" <?php echo $menuFijoRadioCheck; ?>>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<ul class="list-unstyled clearfix">
									<li class="liColorMenu">
									  	<a onclick="cambiarMenuColor('blue')" data-skin="skin-blue" class="aColorMenu clearfix full-opacity-hover">
											<div>
												<span class="bg-light-blue spanColorMenu"></span>
											</div>
											<div>
												<span class="spanColorMenu1"></span>
												<span class="spanColorMenu2"></span>
											</div>
									  	</a>
									  	<p class="text-center no-margin">Azul</p>
									</li>
									<li class="liColorMenu">
									  <a onclick="cambiarMenuColor('black')" data-skin="skin-black" class="aColorMenu clearfix full-opacity-hover">
									    <div style="box-shadow: 0 0 2px rgba(0,0,0,0.1)" class="clearfix">
									      	<span class="spanColorMenu" style="background: #fefefe"></span>
									    </div>
									    <div>
									   		<span class="spanColorMenu1"></span>      
									      	<span class="spanColorMenu2"></span>
									    </div>
									  </a>
									  <p class="text-center no-margin">Blanco</p>
									</li>
									<li class="liColorMenu">
									  <a onclick="cambiarMenuColor('purple')" data-skin="skin-purple" class="aColorMenu clearfix full-opacity-hover">
									    <div>
									      	<span class="bg-purple spanColorMenu"></span>
									    </div>
									    <div>
									    	<span class="spanColorMenu1"></span>
									      	<span class="spanColorMenu2"></span>
									    </div>
									  </a>
									  <p class="text-center no-margin">Violeta</p>
									</li>
									<li class="liColorMenu">
									  <a onclick="cambiarMenuColor('green')" data-skin="skin-green" class="aColorMenu clearfix full-opacity-hover">
									    <div>
									      	<span class="bg-green spanColorMenu"></span>
									    </div>
									    <div>
									    	<span class="spanColorMenu1"></span>
									    	<span class="spanColorMenu2"></span>
									    </div>
									  </a>
									  <p class="text-center no-margin">Verde</p>
									</li>
									<li class="liColorMenu">
									  <a onclick="cambiarMenuColor('red')" data-skin="skin-red" class="aColorMenu clearfix full-opacity-hover">
									    <div>
									      	<span class="bg-red spanColorMenu"></span>
									    </div>
									    <div>
									    	<span class="spanColorMenu1"></span>
									      	<span class="spanColorMenu2"></span>
									    </div>
									  </a>
									  <p class="text-center no-margin">Rojo</p>
									</li>
									<li class="liColorMenu">
									  <a onclick="cambiarMenuColor('yellow')" data-skin="skin-yellow" class="aColorMenu clearfix full-opacity-hover">
									    <div>
									      	<span class="bg-yellow spanColorMenu"></span>
									    </div>
									    <div>
									    	<span class="spanColorMenu1"></span>
									      	<span class="spanColorMenu2"></span>
									    </div>
									  </a>
									  <p class="text-center no-margin">Naranja</p>
									</li>
								</ul>
							</div>	
							<div class="col-md-6">
								<ul class="list-unstyled clearfix">	
									<li class="liColorMenu">
									  	<a onclick="cambiarMenuColor('blue-light')" data-skin="skin-blue" class="aColorMenu clearfix full-opacity-hover">
											<div>
												<span class="bg-light-blue spanColorMenu"></span>
											</div>
											<div>												
												<span class="spanColorMenuLight1"></span>
												<span class="spanColorMenu2"></span>
											</div>
									  	</a>
									  	<p class="text-center no-margin">Azul Light</p>
									</li>
									<li class="liColorMenu">
									  <a onclick="cambiarMenuColor('black-light')" data-skin="skin-black" class="aColorMenu clearfix full-opacity-hover">
									    <div style="box-shadow: 0 0 2px rgba(0,0,0,0.1)" class="clearfix">
									      	<span class="spanColorMenu" style="background: #fefefe"></span>
									    </div>
									    <div>
									    	<span class="spanColorMenuLight1"></span>         
									      	<span class="spanColorMenu2"></span>
									    </div>
									  </a>
									  <p class="text-center no-margin">Blanco Light</p>
									</li>
									<li class="liColorMenu">
									  <a onclick="cambiarMenuColor('purple-light')" data-skin="skin-purple" class="aColorMenu clearfix full-opacity-hover">
									    <div>
									    	<span class="bg-purple spanColorMenu"></span>
									    </div>
									    <div>
									    	<span class="spanColorMenuLight1"></span>
									    	<span class="spanColorMenu2"></span>
									    </div>
									  </a>
									  <p class="text-center no-margin">Violeta Light</p>
									</li>
									<li class="liColorMenu">
									  <a onclick="cambiarMenuColor('green-light')" data-skin="skin-green" class="aColorMenu clearfix full-opacity-hover">
									    <div>
									    	<span class="bg-green spanColorMenu"></span>
									    </div>
									    <div>
									    	<span class="spanColorMenuLight1"></span>
									    	<span class="spanColorMenu2"></span>
									    </div>
									  </a>
									  <p class="text-center no-margin">Verde Light</p>
									</li>
									<li class="liColorMenu">
									  <a onclick="cambiarMenuColor('red-light')" data-skin="skin-red" class="aColorMenu clearfix full-opacity-hover">
									    <div>
									      	<span class="bg-red spanColorMenu"></span>
									    </div>
									    <div>
									    	<span class="spanColorMenuLight1"></span>
									    	<span class="spanColorMenu2"></span>
									    </div>
									  </a>
									  <p class="text-center no-margin">Rojo Light</p>
									</li>
									<li class="liColorMenu">
									  <a onclick="cambiarMenuColor('yellow-light')" data-skin="skin-yellow" class="aColorMenu clearfix full-opacity-hover">
									    <div>
									    	<span class="bg-yellow spanColorMenu"></span>
									    </div>
									    <div>
									    	<span class="spanColorMenuLight1"></span>
									    	<span class="spanColorMenu2"></span>
									    </div>
									  </a>
									  <p class="text-center no-margin">Naranja</p>
									</li>									
								</ul>
							</div>
						</div>
					</div>
					<div id="mails" class="tab-pane fade in">
						<div class="row">
							<div class="col-md-6">
		    					<label class="lab">Correo</label>
		    					<input type="text" class="form-control" placeholder="Correo" name="correo" id="correo" maxlength="300" value="<?php echo $correo; ?>">
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
		    					<input type="text" class="form-control int configuracion_mail" placeholder="Puerto" name="puerto" id="puerto" value="<?php echo $puerto; ?>">
		    					<div class="error_color" id="error_puerto"></div>
		    				</div>
		    				<div class="col-md-6">
		    					<label class="lab">Host</label>
		    					<input type="text" class="form-control configuracion_mail" placeholder="Host" name="host" maxlength="300" id="host" value="<?php echo $host; ?>">
		    					<div class="error_color" id="error_host"></div>
		    				</div>
		    			</div>
		    			<div class="row">
		    				<div class="col-md-6">
		    					<label class="lab">Certificado SSL</label>
		    					<select class="form-control select2 input_select2 configuracion_mail" name="certificado_ssl" id="certificado_ssl">
		    						<option <?php if($certificado_ssl == "1"){ echo 'selected'; } ?> value="1">Si</option>
		    						<option <?php if($certificado_ssl == "0"){ echo 'selected'; } ?> value="0">No</option>
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
			</div>
		</div>			
  	</section>
  	<input type="hidden" id="menu_color" value="<?php echo $this->session->userdata("menu_color"); ?>">
</div>
<script>
	/*cambiar el color del menu --> llama a una menu del header que cambia funciones generales del menu*/
	function cambiarMenuColor(color){
		$("body").removeClass("skin-"+$("#menu_color").val());
      	$("body").addClass("skin-"+color);
	    $("#menu_color").val(color);
		cambiar_menu('menu_color', color);
	}
	
	function cambiar_configuracion(columna, valor){
		$.ajax({
			url:"<?php echo base_url() ?>configuracion/cambiar_configuracion",
			type:"POST",
			data:{columna, valor},
			success: function(){}
		});
	}

	function configuracion_mail(columna, valor, esContrasena){
		$.ajax({
			url:"<?php echo base_url() ?>configuracion/configuracion_mail",
			type:"POST",
			data:{columna, valor, esContrasena},
			success: function(){}
		});
	}	

	$("#contrasena").blur(function(){
		if(this.value != ""){
			configuracion_mail(this.id, this.value, '1');
		}		
	})

	$("#puerto").blur(function(){
		var valor = this.value;
		if (Number.isInteger(parseInt(valor))){
			configuracion_mail(this.id, this.value, '');
		}	
	})

	$(".configuracion_mail").blur(function(){
		configuracion_mail(this.id, this.value, '');
	})

	$(".configuracion_mail").change(function(){
		configuracion_mail(this.id, this.value, '');
	})

	/*Verifica el correo mientras escribe*/
	$(document).on("blur", "#correo", function(e){
		var id = this.id;
		var value = this.value;
		if (value != ''){
			if (validoEmail(value)){
				configuracion_mail(this.id, this.value, '');
			}else {
				marcarError(id, 'El correo esta escrito incorrectamente');
			}
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
					url:document.getElementById("base_url").value+"Configuracion/validarDatosCorreo",
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

	$('.radio-button').on("click", function(event){
		if($(this).val() == "Si"){
			var menuFijo = "No";
			$(this).val(menuFijo);
			$("#menuFijoHidden").val(menuFijo);
			$(this).prop('checked', false);			
			$("body").addClass("sidebar-collapse");
		}else if($(this).val() == "No"){
			var menuFijo = "Si";
			$(this).val(menuFijo);
			$("#menuFijoHidden").val(menuFijo);
			$(this).prop('checked', true);
			$("body").removeClass("sidebar-collapse");
		}
		cambiar_menu('menu_fijo', menuFijo);
	});
</script>
