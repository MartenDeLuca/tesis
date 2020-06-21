<div class="content-wrapper" style="background: white !important;">	
    <section class="content-header">
        <h1>
          <?php echo $instancia; ?> Plantilla de Mail
        </h1>
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
		    			<div class="row">
		    				<div class="col-md-6">
		    					<label class="lab">Asunto</label>
		    					<input type="text" class="form-control" placeholder="Asunto" name="asunto" id="asunto" value="<?php echo $asunto; ?>" maxlength="100">
		    					<div class="error_color" id="error_asunto"></div>
		    				</div>
		    				<div class="col-md-6">
		    					<label class="lab">Atributos</label>
		    					<select class="form-control select2 input_select2" id="atributos" name="atributos">
		    						<option value=""></option>
		    						<option value="[^*COLUMNA_Cliente*^]">Cliente</option>
		    						<option value="[^*TABLA_Comprobante*^]">Comprobantes pendientes</option>
		    						<option value="[^*COLUMNA_Link*^]">Link de Comprobantes</option>
		    					</select>
		    					<div class="error_color" id="error_atributos"></div>
		    				</div>
		    			</div>
		    			<div class="row">
		    				<div class="col-md-12">
		    					<label class="lab">Asunto Mail</label>
		    					<input type="text" class="form-control" placeholder="Asunto Mail" name="asunto_mail" id="asunto_mail" value="<?php echo $asunto_mail; ?>" maxlength="1000">
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
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<input type="hidden" id="id_plantilla" value ="<?php echo $id_plantilla; ?>">
<input type="hidden" id="instancia" value ="<?php echo $instancia; ?>">
<script type="text/javascript">
	$('document').ready(function(){
		$(".input_select2").select2();
		$(".show-hide").parent().parent().hide();
	});

	function cancelar(){
		if(confirm("Desea cancelar el ingreso de la regla de negocio?")){
			history.go(-1);
		}
	}

	function guardar(){
		var ok = true;
		var instancia = $("#instancia").val();
		var id_plantilla = $("#id_plantilla").val();
		var asunto = $("#asunto").val();
		if(asunto == ""){
			ok = false;
			marcarError("asunto", "Campo obligatorio");
		}
		var asunto_mail = $("#asunto_mail").val();
		var contenido_mail = CKEDITOR.instances["contenido_mail"].getData();
		if(contenido_mail == ""){
			ok = false;
			marcarError("contenido_mail", "Campo obligatorio");
		}
		if(ok){
			$.ajax({
				url: "<?php echo base_url() ?>plantilla/plantilla_bd",
				type: "POST",
				data:{instancia, id_plantilla, asunto, asunto_mail, contenido_mail},
				dataType: "json",
				success: function(respuesta){
					if(respuesta[0] == "ok"){
						location.href = "<?php echo base_url() ?>plantillas";
					}else{
						alert(respuesta[0]);
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
</script>