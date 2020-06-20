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
$cantidad_decimales = "2";
$separador_decimales = "";
$separador_miles = "";
$formato_negativo = "";
$ubicacion_unidad = "";
$unidad = "$";

$tamano_texto = "";
$alineacion_texto = "";

$formato_fecha = "d m Y";
$separador_fecha = "/";
$formato_hora = "";
$tamano_fecha = "";
$alineacion_fecha = "";
if(isset($configuracion[0])){
	$configuracion = $configuracion[0];
	$cantidad_decimales = $configuracion["cantidad_decimales"];
	$separador_decimales = $configuracion["separador_decimales"];
	$formato_negativo = $configuracion["formato_negativo"];
	$separador_miles = $configuracion["separador_miles"];
	$ubicacion_unidad = $configuracion["ubicacion_unidad"];
	$unidad = $configuracion["unidad"];

	$tamano_texto = $configuracion["tamano_texto"];
	$alineacion_texto = $configuracion["alineacion_texto"];

	$formato_fecha = $configuracion["formato_fecha"];
	$separador_fecha = $configuracion["separador_fecha"];
	$formato_hora = $configuracion["formato_hora"];
	$tamano_fecha = $configuracion["tamano_fecha"];
	$alineacion_fecha = $configuracion["alineacion_fecha"];
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
          <li><a href="<?php echo base_url(); ?>tablero">Tablero</a></li>
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
			    	<li><a data-toggle="tab" href="#tablero">Tablero</a></li>			    	
			  	</ul>
			  	<div class="tab-content">
		    		<div id="general" class="tab-pane fade in active">
						<div class="acordeon">
							<div class="acordeon__item">
								<input type="checkbox" name="acordeon" class="check-acordeon" id="item0" onchange="cambiar_check(0)" checked>
								<label for="item0" class="acordeon__titulo">
									<div style="text-align:left;">Menu <span style="float:right;"><span id="icon0" class="glyphicon glyphicon glyphicon-chevron-down"></span></span></div>
								</label>
								<div class="acordeon__contenido">
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
							</div>
							<div class="acordeon__item">
								<input type="checkbox" name="acordeon" class="check-acordeon" id="item1" onchange="cambiar_check(1)" checked>
								<label for="item1" class="acordeon__titulo">
									<div style="text-align:left;">Carpeta Principal <span style="float:right;"><span id="icon1" class="glyphicon glyphicon glyphicon-chevron-down"></span></span></div>
								</label>
								<div class="acordeon__contenido">
									<div class="row">
										<div class="col-md-12">
											<label class="lab">Carpeta Principal</label>
											<div class="input-group">
							                  <input type="text" placeholder="Buscar Carpeta" class="form-control" id="buscar_listas_carpetas">
							                  <span class="input-group-addon add-on" id="limpiar_listas_carpetas">
							                    <span class="glyphicon glyphicon-remove"></span>
							                  </span>
							                </div>
							                <br>
											<div id="treeview-searchable"></div>
										</div>
									</div>
								</div>
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
					<div id="tablero" class="tab-pane fade in">
						<div class="acordeon">
							<div class="acordeon__item indicador_numerico">
								<input type="checkbox" name="acordeon" class="check-acordeon" id="item2" onchange="cambiar_check(2)" checked>
								<label for="item2" class="acordeon__titulo">
									<div style="text-align:left;">Indicador númerico <span style="float:right;"><span id="icon2" class="glyphicon glyphicon glyphicon-chevron-down"></span></span></div>
								</label>
								<div class="acordeon__contenido">
									<div class="row">
										<div class="col-md-4">
											<label class="lab">Cantidad de decimales</label>
											<input type="text" maxlength="1" class="form-control" id="cantidad_decimales" value="<?php echo $cantidad_decimales; ?>">
										</div>
										<div class="col-md-4">
											<label class="lab">Separador de decimales</label>
											<select class="form-control cambio_automatico" id="separador_decimales">
												<option <?php if($separador_decimales == ","){ echo 'selected'; } ?>>,</option>
												<option <?php if($separador_decimales == "."){ echo 'selected'; } ?>>.</option>
											</select>
										</div>
										<div class="col-md-4">
											<label class="lab">Seperador de miles</label>
											<select class="form-control cambio_automatico" id="separador_miles">
												<option <?php if($separador_miles == "."){ echo 'selected'; } ?>>.</option>
												<option <?php if($separador_miles == ","){ echo 'selected'; } ?>>,</option>
												<option <?php if($separador_miles == ""){ echo 'selected'; } ?>></option>
											</select>	
										</div>
									</div>
									<div class="row">
										<div class="col-md-4">
											<label class="lab">Formato de valor negativo</label>
											<select class="form-control cambio_automatico" id="formato_negativo">
												<option <?php if($formato_negativo == "1"){ echo 'selected'; } ?> value="1">Normal</option>
												<option <?php if($formato_negativo == "0"){ echo 'selected'; } ?> value="0">Con paréntesis</option>
											</select>
										</div>
										<div class="col-md-4">
											<label class="lab">Ubicación de unidad de medida</label>
											<select class="form-control cambio_automatico" id="ubicacion_unidad">
												<option <?php if($ubicacion_unidad == "D"){ echo 'selected'; } ?> value="D">Derecha</option>
												<option <?php if($ubicacion_unidad == "I"){ echo 'selected'; } ?> value="I">Izquierda</option>
												<option <?php if($ubicacion_unidad == "N"){ echo 'selected'; } ?> value="N">No utilizar</option>
											</select>
										</div>
										<div class="col-md-4">
											<label class="lab">Unidad de medida</label>
											<input type="text" maxlength="5" class="form-control cambio_automatico" id="unidad" value="<?php echo htmlspecialchars($unidad); ?>">
										</div>
									</div>
								</div>
							</div>
							<div class="acordeon__item indicador_texto">
								<input type="checkbox" name="acordeon" class="check-acordeon" id="item3" onchange="cambiar_check(3)" checked>
								<label for="item3" class="acordeon__titulo">
									<div style="text-align:left;">Indicador texto <span style="float:right;"><span id="icon3" class="glyphicon glyphicon glyphicon-chevron-down"></span></span></div>
								</label>
								<div class="acordeon__contenido">
									<div class="row">
										<div class="col-md-4">
											<label class="lab">Tamaño texto</label>
											<select class="form-control cambio_automatico" id="tamano_texto">
												<option <?php if($tamano_texto == "G"){ echo 'selected'; } ?> value="G">Grande</option>
												<option <?php if($tamano_texto == "M"){ echo 'selected'; } ?>  value="M">Mediano</option>
												<option <?php if($tamano_texto == "P"){ echo 'selected'; } ?>  value="P">Pequeño</option>
											</select>
										</div>
										<div class="col-md-4">
											<label class="lab">Alineación texto</label>
											<select class="form-control cambio_automatico" id="alineacion_texto">
												<option <?php if($alineacion_texto == "I"){ echo 'selected'; } ?> value="I">Izquierda</option>
												<option <?php if($alineacion_texto == "D"){ echo 'selected'; } ?> value="D">Derecha</option>
												<option <?php if($alineacion_texto == "C"){ echo 'selected'; } ?> value="C">Centro</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="acordeon__item indicador_fecha">
								<input type="checkbox" name="acordeon" class="check-acordeon" id="item4" onchange="cambiar_check(4)" checked>
								<label for="item4" class="acordeon__titulo">
									<div style="text-align:left;">Indicador fecha <span style="float:right;"><span id="icon4" class="glyphicon glyphicon glyphicon-chevron-down"></span></span></div>
								</label>
								<div class="acordeon__contenido">
									<div class="row">
										<div class="col-md-4">
											<label class="lab">Formato fecha</label>
											<select class="form-control cambio_automatico" id="formato_fecha">
												<option <?php if($formato_fecha == "d m y"){ echo 'selected'; } ?>>dd mm aa</option>
												<option <?php if($formato_fecha == "d m Y"){ echo 'selected'; } ?>>dd mm aaaa</option>
												<option <?php if($formato_fecha == "m d y"){ echo 'selected'; } ?>>mm dd aa</option>
												<option <?php if($formato_fecha == "m d Y"){ echo 'selected'; } ?>>mm dd aaaa</option>
											</select>
										</div>
										<div class="col-md-4">
											<label class="lab">Seperador fecha</label>
											<select class="form-control cambio_automatico" id="separador_fecha">
												<option <?php if($separador_fecha == "/"){ echo 'selected'; } ?>>/</option>
												<option <?php if($separador_fecha == "-"){ echo 'selected'; } ?>>-</option>
											</select>
										</div>
										<div class="col-md-4">
											<label class="lab">Formato hora</label>
											<select class="form-control cambio_automatico" id="formato_hora">
												<option <?php if($formato_hora == "H:i:s"){ echo 'selected'; } ?>>HH:MM:ss</option>
												<option <?php if($formato_hora == "H:i"){ echo 'selected'; } ?>>HH:MM</option>
												<option <?php if($formato_hora == "H:i:s A"){ echo 'selected'; } ?>>HH:MM:ss (AM/PM)</option>
												<option <?php if($formato_hora == "HH:i A"){ echo 'selected'; } ?>>HH:MM (AM/PM)</option>
												<option value="">Sin Hora</option>
											</select>
										</div>
										<div class="col-md-4">
											<label class="lab">Tamaño texto</label>
											<select class="form-control cambio_automatico" id="tamano_fecha">
												<option <?php if($tamano_fecha == "G"){ echo 'selected'; } ?> value="G">Grande</option>
												<option <?php if($tamano_fecha == "M"){ echo 'selected'; } ?>  value="M">Mediano</option>
												<option <?php if($tamano_fecha == "P"){ echo 'selected'; } ?>  value="P">Pequeño</option>
											</select>
										</div>
										<div class="col-md-4">
											<label class="lab">Alineación texto</label>
											<select class="form-control cambio_automatico" id="alineacion_fecha">
												<option <?php if($alineacion_fecha == "I"){ echo 'selected'; } ?> value="I">Izquierda</option>
												<option <?php if($alineacion_fecha == "D"){ echo 'selected'; } ?> value="D">Derecha</option>
												<option <?php if($alineacion_fecha == "C"){ echo 'selected'; } ?> value="C">Centro</option>
											</select>
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

	/*cambia carpeta*/
	function cambiarCarpetaPrincipal(valor){
		cambiar_menu('id_carpeta', valor);
	}

	$("#cantidad_decimales, #puerto").keypress(function(e){
		var key = e.charCode;
	    return key >= 48 && key <= 57;
	})

	$("#cantidad_decimales").blur(function(){
		var valor = this.value;
		if (Number.isInteger(parseInt(valor))){
			if(valor == 0){
				$("#separador_decimales").closest("div").hide();
			}else{
				if(valor > 5){
					valor = 5;
					$(this).val(valor);
				}
				$("#separador_decimales").closest("div").show();
			}
		}else{
			valor = 2;
			$(this).val(valor);
		}
		cambiar_configuracion('cantidad_decimales', valor);
	})

	$("#ubicacion_unidad").change(function(){
		if(this.value == "No utilizar"){
			$("#unidad").closest("div").hide();
		}else{
			$("#unidad").closest("div").show();
		}
	})

	$(".cambio_automatico").blur(function(){
		cambiar_configuracion(this.id, this.value);
	})

	$(".cambio_automatico").change(function(){
		cambiar_configuracion(this.id, this.value);
	})

	var $checkableTree
	$(document).ready(function(){
		var $checkableTree = $('#treeview-searchable').treeview({
	      data: getTree(),
	      showIcon: false,
	      showCheckbox: true,
	      onNodeChecked: function(event, node) {
	      	cambiar_menu('id_carpeta', node.id);
	        $checkableTree.treeview('uncheckNoNode', [ node, { silent: false }]);
	      }
	    });
	})
	
	function cambiar_configuracion(columna, valor){
		$.ajax({
			url:"<?php echo base_url() ?>configuracion/cambiar_configuracion",
			type:"POST",
			data:{columna, valor},
			success: function(){}
		});
	}

	var lastPattern = '';
	var tree = <?php echo json_encode($carpetas); ?>;
	function getTree() {
		return tree;
	}

	function reset(tree) {
		tree.collapseAll();
		tree.enableAll();
	}

	function collectUnrelated(nodes) {
		var unrelated = [];
		$.each(nodes, function (i, n) {
			if (!n.searchResult && !n.state.expanded) {
				unrelated.push(n.nodeId);
			}
			if (!n.searchResult && n.nodes) {
				$.merge(unrelated, collectUnrelated(n.nodes));
			}
		});
		return unrelated;
	}

  	var search = function (e) {
	    var pattern = $('#buscar_listas_carpetas').val();
	    if (pattern === lastPattern) {
	      return;
	    }
	    lastPattern = pattern;
	    var tree = $('#treeview-searchable').treeview(true);
	    reset(tree);
	    if (pattern.length == 0) {
	    	tree.clearSearch();
	    } else {
			tree.search(pattern);
			var roots = tree.getSiblings(0);
			roots.push(tree.getNode(0));
			var unrelated = collectUnrelated(roots);
			tree.disableNode(unrelated, {silent: true});
	    }
  	};

	$('#buscar_listas_carpetas').on('keyup', search);

	$('#limpiar_listas_carpetas').on('click', function (e) {
		$('#buscar_listas_carpetas').val('');
		var tree = $('#treeview-searchable').treeview(true);
		reset(tree);
		tree.clearSearch();
	});

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
</script>
