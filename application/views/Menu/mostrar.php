<div class="modal fade in" id="modalBusqueda" tabindex="-1" role="dialog" aria-labelledby="formBuscar2" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content" >
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title" id="formBuscar2">Busqueda Avanzada</h4> <a id="a_busqueda_guardadas" style="cursor:pointer" onclick="verBusquedasGuardadas()">Ver busquedas guardadas</a><a id="a_busqueda_model" style="cursor:pointer; display: none;" onclick="volverABusqueda()">Volver a busqueda</a>
			</div>				
			<div class="modal-body">
				<div id="busqueda_model">
					<div class="text-right">
						<a onclick="guardar_busqueda()" title="Buscar" class="btn btn-primary 
						btn-form">Buscar</a>
						<a onclick="modal_busqueda()" class="btn btn-primary 
						btn-form">Guardar y Buscar</a>
						<a onclick="vaciar()" title="Limpiar Busqueda" class="btn btn-primary btn-form boton_vaciar">Limpiar</a>
					</div>	
					<br>
					<div id="mostrar_busqueda">
		                <div class="row">
		                    <div class="col-md-12">
		                        <label class="lab">Columnas</label>
		                        <select class="form-control select2" id="columnasBusqueda">
		                            <option></option>
		                            <?php 
		                                for($i = 0; $i < $tamano; $i++){
		                                    echo "<option data-id='".$sql_columna[$i]."' data-tipo='".$tipo_columna[$i]."'>".$columna[$i]."</option>";
		                                }
		                            ?>
		                        </select>
		                    </div>
		                </div>
		                <br>
		                <div id="busquedaGuardada" <?php if(empty($array_valores["busquedasGuardadas"])){ echo 'style="display:none"'; } ?>>
		                    <label class="lab">Busqueda Guardadas</label>
		                    <input class="form-control" placeholder="Busca las columnas con busqueda" onkeyup="buscarColumnasGuardadas(this.value)">
		                    <?php echo $array_valores["busquedasGuardadas"]; ?>
		                </div>
		                <input type="hidden" id="ultima_posicion_busqueda" value = "<?php echo $array_valores["posicion"]; ?>">			
					</div>
				</div>
				<div id="mostrar_busqueda_guardadas"></div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade in" id="modal_guardar_busqueda" tabindex="-1" role="dialog" aria-labelledby="formBuscar1" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content" >
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title" id="formBuscar1">Guardar Busqueda</h4> 
			</div>				
			<div class="modal-body">
				<div class="text-right">
					<a onclick="guardar_busqueda()" title="Buscar" class="btn btn-primary 
					btn-form">Guardar</a>
					<a data-dismiss="modal" class="btn btn-danger btn-form">Cancelar</a>
				</div>
				<div class="row">
					<div class="col-md-12">
						<label class="lab">Nombre Busqueda</label>
						<input type="text" class="form-control" id="nombre_busqueda">
					</div>
				</div>
			</div>
		</div>
	</div>	
</div>	
<script>
	var opciones_string = `<?php echo $this->config->item('opciones_string'); ?>`;
	var opciones_float = `<?php echo $this->config->item('opciones_float'); ?>`;
	var opciones_date = `<?php echo $this->config->item('opciones_date'); ?>`;

	function modal_buscar(){
		$('#modalBusqueda').modal('show');
	}

	function modal_busqueda(){
		$('#modal_guardar_busqueda').modal('show');	
	}

	function buscarColumnasGuardadas(valor){
		if(valor != ""){
		    var array = $(".columnasBuscadas");
		    var tamano = array.length;
		    for(var i = 0; i < tamano; i++){
		        if($(array[i]).html().toLowerCase().indexOf(valor.toLowerCase()) == -1){
		            $(array[i]).parent().hide();
		        }else{
		            $(array[i]).parent().show();
		        }
		    }
		}else{
			var array = $(".columnasBuscadas");
		    var tamano = array.length;
		    for(var i = 0; i < tamano; i++){
		        $(array[i]).parent().show();
		    }
		}
	}

	$("#columnasBusqueda").change(function(){    
	    var objeto = $(this);
	    var opciones = "";	    
	    if(objeto.val() != ""){
	    	$("#busquedaGuardada").show();
	        var valor = objeto.val();
	        var id_select = objeto.attr("id");
	        var id = $("#"+id_select+" option:selected").data("id");
	        var tipo = $("#"+id_select+" option:selected").data("tipo");
	        var clase_input = "";
	        if(tipo == "string"){
	            opciones = opciones_string;
	        }else if(tipo == "date"){
	            opciones = opciones_date;
	            clase_input = "date";
	        }else if(tipo == "float" || tipo == "int"){
	            opciones = opciones_float;
	            clase_input = "float_busqueda";
	        }

	        var array = $(".columnasBuscadas");
	        var tamano = array.length;
	        var bandera = 0;
	        for(var i = 0; i < tamano; i++){
	            if($(array[i]).html() == valor){
	                var id_bandera = $(array[i]).parent().attr("id");
	                bandera = id_bandera.substr(id_bandera.indexOf("_")+1);
	                i = tamano + 1;
	            }
	        }
	        if(bandera == 0){
	            var posicion = parseInt($("#ultima_posicion_busqueda").val()) + 1;
	            $("#ultima_posicion_busqueda").val(posicion);
	            $("#busquedaGuardada").append(
	            `
	            <div id="div_${posicion}" class="div_busquedaGuardada" style="padding-top:10px;">
	                <label class="lab columnasBuscadas">${valor}</label> <a onclick="agregoOtraBusqueda(${posicion})"><span class="glyphicon glyphicon-plus"></span></a>
	                <input type="hidden" id="id_oculto_${posicion}" value="${id}">
	                <div class="row row_${posicion}" id="row_${posicion}">
	                    <div class="col-md-4">
	                        <select id="select_${posicion}" class="form-control select_busquedaGuardada">${opciones}</select>
	                    </div>
	                    <div class="col-md-8">
	                        <div class="input-group">
	                            <input id="input_${posicion}" class="form-control ${clase_input} input_busquedaGuardada">
	                            <span class="input-group-addon add-on" onclick="eliminarBusqueda(${posicion}, ${posicion})">
	                                <span class="glyphicon glyphicon-remove"></span>
	                            </span>
	                        </div>
	                    </div>
	                </div>
	            </div>`); 
	            $(".date").inputmask("datetime",{
	             mask: '1/2/y'
	            });
	        }else{
	            agregoOtraBusqueda(bandera);
	        }
	    
	        objeto.val("");
	    }
	});

	$(document).on('change', '.select_busquedaGuardada', function(){  
		var id = $(this).attr("id");
	    var posicion = id.substr(id.indexOf("_")+1);
	    var objeto = $("#input_"+posicion);
	    
		if(objeto.attr("class").indexOf("date") != -1) {
			objeto.removeClass("int");
		    var valor = $(this).val();		    
		    if(valor == "13"){
		        objeto.inputmask("datetime",{
		            mask: "1/2/y - 1/2/y"		            
		        });
		    }else if(valor == "14"){
		        objeto.inputmask("datetime",{
		            mask: "2/y"
		        });
		    }else if(valor == "10"){        
		        objeto.inputmask('remove');
		        objeto.addClass("int");
		        objeto.val("");
		    }else{
		        objeto.inputmask("datetime",{
		        	mask: "1/2/y"
		        });
		    }
		}
	});

	function eliminarBusqueda(posicion_fija, posicion){
	    if($(".row_"+posicion_fija).length == 1){
	        $("#div_"+posicion_fija).remove();   
	    }else{
	        $("#row_"+posicion).remove();
	    }    
	}

	function agregoOtraBusqueda(posicion_fija){
	    var posicion = parseInt($("#ultima_posicion_busqueda").val()) + 1;
	    $("#ultima_posicion_busqueda").val(posicion);
	    var opciones = $("#select_"+posicion_fija).html();
	    $("#div_"+posicion_fija).append(
	    `<div class="row row_${posicion_fija}" id="row_${posicion}" style="padding-top:5px;">
	        <div class="col-md-4">
	            <select id="select_${posicion}" class="form-control select_busquedaGuardada">${opciones}</select>
	        </div>
	        <div class="col-md-8">
	            <div class="input-group">
	                <input id="input_${posicion}" class="form-control input_busquedaGuardada">
	                <span class="input-group-addon add-on" onclick="eliminarBusqueda(${posicion_fija}, ${posicion})">
	                    <span class="glyphicon glyphicon-remove"></span>
	                </span>
	            </div>
	        </div>
	    </div>`);
	}

	function guardar_busqueda(){	
	    var array_busqueda = $(".div_busquedaGuardada");
	    var tamano = array_busqueda.length;
	    var columnas = "";
	    var tipo_busqueda = "";
	    var busqueda = "";
	    for(var i = 0; i < tamano; i++){
	        var id = $(array_busqueda[i]).attr("id");
	        var posicion = id.substr(id.indexOf("_")+1);
	        var array_select = $("#"+id+" .row .select_busquedaGuardada");
	        var array_input = $("#"+id+" .row .input_busquedaGuardada");
	        var tamano_input = array_input.length;
	        var comienzo = "***";        
	        for(var j = 0; j < tamano_input; j++){
	            if($(array_input[j]).val() != ""){
	                tipo_busqueda += comienzo+$(array_select[j]).val();
	                busqueda += comienzo+$(array_input[j]).val();
	                comienzo = ")))";
	            }
	        }
	        if(comienzo == ")))"){
	            columnas += "***"+$("#id_oculto_"+posicion).val();
	        }
	    }
	    columnas = columnas.substr(3);
	    busqueda = busqueda.substr(3);
	    tipo_busqueda = tipo_busqueda.substr(3);
	    var tipo = $('#tipo').val();
	    var url = "?tipo="+tipo;
	    var nombre_busqueda = $("#nombre_busqueda").val();
	    url += "&no_bu="+nombre_busqueda;
	    if(columnas != ""){
	    	url += "&col="+columnas;
	    	url += "&bus="+busqueda;
	    	url += "&ti_bu="+tipo_busqueda;
	    }
	    location.href = "<?php echo base_url().$url; ?>"+url;
	}

	function vaciar(){
		var tipo = $('#tipo').val();
		var url = "?vaciar=1&tipo="+tipo;
	    location.href = "<?php echo base_url().$url; ?>"+url;
	}

	$(document).on( "keydown", ".input_busquedaGuardada", function(e) {
	    if (e.which == 13) {
	        e.preventDefault();
	        guardar_busqueda();
	    }
	});

	function verBusquedasGuardadas(){
		$.ajax({
			url: document.getElementById("base_url").value+'Seguimiento/getBusquedasGuardadas',
			type: 'POST',
			dataType: 'html',
			data: {tipo:$("#tipo").val()},
			})
		.done(function(respuesta){
			$("#mostrar_busqueda_guardadas").html(respuesta);
		})		
		$("#mostrar_busqueda_guardadas").show();
		$("#busqueda_model").hide();	
		$("#a_busqueda_model").show();
		$("#a_busqueda_guardadas").hide();
	}

	function volverABusqueda(){
		$("#mostrar_busqueda_guardadas").hide();	
		$("#busqueda_model").show();
		$("#a_busqueda_guardadas").show();
		$("#a_busqueda_model").hide();
	}

	function eliminarBusquedaGuardada(id, objeto){
		if(confirm("Deseas eliminar esta busqueda?")){
			$.ajax({
				type: "POST",
				url: document.getElementById("base_url").value+"Seguimiento/eliminarBusquedaGuardada",
				data: {id, tipo:$("#tipo").val()},
				success: function (respuesta) {
					$(objeto).closest("tr").remove();
				}
			})
		}
	}

	function favoritoBusquedaGuardada(id, objeto){
		var elemento = $(objeto).children("i");
		var estado = elemento.data("estado");
		var favorito = "1";
		if(estado == "1"){
			favorito = "0";
			$("#idFavorito_"+id).remove();
			elemento.removeClass("fa-star");
			elemento.addClass("fa-star-o");
		}else{
			var busqueda = $($(objeto).closest("tr").children("td")[0]).children("a").html();
			$("#favoritos").append("<a id='idFavorito_"+id+"' onclick='ejecutarBusquedaGuardada("+id+")' class='btn btn-default'>"+busqueda+"</a>");
			elemento.addClass("fa-star");
			elemento.removeClass("fa-star-o");
		}
		elemento.data('estado', favorito);		
		$.ajax({
			type: "POST",
			url: document.getElementById("base_url").value+"Seguimiento/favoritoBusquedaGuardada",
			data: {id, favorito, tipo:$("#tipo").val()},
			success: function (respuesta) {
				
			}
		})
	}	

	function ejecutarBusquedaGuardada(id){
		var tipo = $('#tipo').val();
		var url = "?guar="+id+"&tipo="+tipo;
	    location.href = "<?php echo base_url().$url; ?>"+url;
	}

	function exportar(){
		var tipo = $('#tipo').val();
		var url = "?tipo="+tipo;
	    location.href = "<?php echo base_url(); ?>seguimiento/exportar_<?php echo $url; ?>"+url;
	}	
</script>