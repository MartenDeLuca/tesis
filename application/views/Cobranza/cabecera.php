<?php 
$current_url = current_url();
$active = "";
$li = "";
$descripcion_dropdown = "";
?>
<div class="content-wrapper" style="background: white !important;">		
<section class="content-header">
<h1>
	Cobranza
	<small>Detalle Comprobante</small>
</h1>
</section>		
<section class="content">				
<div class="row fijo">			
	<div class="col-xs-9 col-md-10">
		<h3 style="margin-top:0px !important;"><?php echo $t_comp;?> | <?php echo $n_comp; ?></h3>
	</div>
</div>				

<div class="row">
	<div class="col-md-8">
		<label class="lab">Seleccionar Comprobante</label>
		<div class="input-group">
			<span class="input-group-btn">
				<a onclick="borrar_sel()"  class="btn btn-primary btn-sel" style="cursor: pointer"><span class="glyphicon glyphicon-remove"></span></a>						
			</span>					  
			<input type="text" class="form-control" placeholder="Seleccionar Comprobante" name="b_nombre" id="b_nombre" value="<?php echo htmlspecialchars($t_comp.' | '.$n_comp); ?>" onKeypress="if(event.keyCode == 13) sel();">
			<input type="hidden" id="nombreComprobante" value="<?php echo htmlspecialchars($n_comp .' | '.$cliente); ?>">
			<input type="hidden" id="id_gva12" value="<?php echo $id_gva12; ?>">
			<input id="n_comp" type='hidden' value="<?php echo htmlspecialchars($n_comp); ?>">	
			<input id="idSeleccion" type='hidden' value="<?php echo htmlspecialchars($n_comp); ?>">	
			<input id="t_comp" type='hidden' value="<?php echo htmlspecialchars($t_comp); ?>">	
			<span class="input-group-btn">
				<a onclick="sel()"  class="btn btn-primary btn-sel" style="cursor: pointer; "><span class="glyphicon glyphicon-search"></span></a>
			</span>
		</div>
	</div>
</div>

<hr>

<div class="row">
	<div class="col-md-4">
		<label class="lab">Cliente</label>					
		<div class="input-group">
			<input type="text" style="background-color:white !important;" style="background-color:white !important;" class="form-control" placeholder="Cliente" name="Cliente" id="Cliente" disabled="disabled" value = "<?php echo htmlspecialchars($cliente); ?>">
			<span class="input-group-btn">
				<a style="cursor:pointer;" href='<?php echo base_url() ?>detalle-cliente?id=<?php echo htmlspecialchars($id_cliente);?>'  target="_blank" title="Redicci&oacute;n al Cliente" class="btn btn-primary btn-sel">
					<span class="glyphicon glyphicon-expand"></span>
				</a>
			</span>
		</div>					
	</div>
	<div class="col-md-2">
		<label class="lab">Cod. Cliente</label>
		<input type="text" style="background-color:white !important;" style="background-color:white !important;" class="form-control" placeholder="Cod. Cliente" name="Cod_Cliente" id="Cod_Cliente" disabled="disabled" value = "<?php echo htmlspecialchars($cod_cliente); ?>">
	</div>
	<div class="col-md-2">
		<label class="lab">Fecha</label>
		<input type="text" style="background-color:white !important;" style="background-color:white !important;" class="form-control" placeholder="Fecha" name="Fecha" id="Fecha" disabled="disabled" value = "<?php echo htmlspecialchars($emision); ?>">
	</div>
	
	<div class="col-md-2">
		<label class="lab">Cotizaci&oacute;n</label>
		<input type="text" style="background-color:white !important;" class="form-control" placeholder="Cotizaci&oacute;n" name="Cotizacion" id="Cotizacion" disabled="disabled" value = "<?php echo htmlspecialchars($cotizacion); ?>">
	</div>
	
	<div class="col-md-2">
		<div class="hidden-sm">Moneda</div>
		<select disabled style="background-color:white !important;" name="monedaLocal" class="form-control">
			<option <?php if($moneda === '1') echo "selected"; ?>>Corriente</option>
			<option <?php if($moneda === '0') echo "selected"; ?>>Extranjera</option>
		</select>
	</div>
</div>

<div class="row">
	<div class="col-md-4">
		<label class="lab">Vendedor</label>
		<input type="text" style="background-color:white !important;" class="form-control" placeholder="Vendedor" name="Vendedor" id="Vendedor" disabled="disabled" <?php if(!empty($vendedor)){ ?> value = "<?php echo htmlspecialchars($cod_vendedor) ." - ". htmlspecialchars($vendedor); ?>"<?php } ?>>
	</div>
	<div class="col-md-4">
		<label class="lab">Cond. Vta.</label>
		<input type="text" style="background-color:white !important;" class="form-control" placeholder="Cond. Vta." name="CondVta" id="CondVta" disabled="disabled" <?php if(!empty($desc_cond)){ ?> value = "<?php echo htmlspecialchars($condicion_vta)." - ". htmlspecialchars($desc_cond); ?>" <?php } ?>>
	</div>
	<div class="col-md-4">
		<label class="lab">Talonario</label>
		<input type="text" style="background-color:white !important;" class="form-control" placeholder="Talonario" name="Talonario" id="Talonario" disabled="disabled" <?php if(!empty($talonario)){ ?> value = "<?php echo htmlspecialchars($cod_talonario) ." - ". htmlspecialchars($talonario); ?>" <?php } ?>>
	</div>
</div>

<div class="row">
	<div class="col-md-4">
		<label class="lab">Lista Precios</label>
		<input type="text" style="background-color:white !important;" class="form-control" placeholder="Lista Precios" name="ListaPrecios" id="ListaPrecios" disabled="disabled" <?php if(!empty($lista)){ ?> value = "<?php echo htmlspecialchars($cod_lista) ." - ". htmlspecialchars($lista); ?>"<?php } ?>>
	</div>
	
	<div class="col-md-4">
		<label class="lab">Transporte</label>
		<input type="text" style="background-color:white !important;" class="form-control" placeholder="Transporte" name="Transporte" id="Transporte" disabled="disabled" <?php if(!empty($transporte)){ ?> value = "<?php echo htmlspecialchars($cod_transporte) ." - ". htmlspecialchars($transporte); ?>" <?php } ?>>
	</div>
	
	<div class="col-md-2">
		<label class="lab">O/C</label>
		<input type="text" style="background-color:white !important;" class="form-control" placeholder="O/C" name="OC" id="OC" disabled="disabled"  value = "<?php echo htmlspecialchars($oc); ?>">
	</div>
	
	<div class="col-md-2">
		<label class="lab">Fecha Entrega</label>
		<input type="text" style="background-color:white !important;" class="form-control" placeholder="Fecha Entrega" name="FechaEntrega" id="FechaEntrega" disabled="disabled"  value = "<?php echo htmlspecialchars($fecha_entr); ?>">
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<label class="lab">Horario de Cobranza</label>	
		<input type="text" value="<?php echo $horario_cobranza ?>" style="background-color:white !important;" disabled="disabled" class="form-control" placeholder="Horario de Cobranza" name="horario_cobranza" id="horario_cobranza">
	</div>
	<div class="col-md-6">
		<br>
		<label class="lab">Lunes</label>
		<input type="checkbox" <?php if ($cobra_lunes == 'S'){ echo 'checked'; } ?>  name="cobra_lunes" id="cobra_lunes"disabled> &nbsp;
	
		<label class="lab">Martes</label>
		<input type="checkbox" name="cobra_martes" <?php if ($cobra_martes == 'S'){ echo 'checked';	 } ?> id="cobra_martes" disabled> &nbsp;
	
		<label class="lab">Miercoles</label>
		<input type="checkbox" name="cobra_miercoles" <?php if ($cobra_miercoles == 'S'){ echo 'checked'; } ?> id="cobra_miercoles" disabled>	&nbsp;
	
		<label class="lab">Jueves</label>
		<input type="checkbox" name="cobra_jueves" <?php if ($cobra_jueves == 'S'){ echo 'checked';	 } ?> id="cobra_jueves" disabled> &nbsp;
	
		<label class="lab">Viernes</label>
		<input type="checkbox" name="cobra_viernes" <?php if ($cobra_viernes == 'S'){ echo 'checked'; } ?> id="cobra_viernes" disabled> &nbsp;
	
		<label class="lab">Sabado</label>
		<input type="checkbox" name="cobra_sabado" <?php if ($cobra_sabado == 'S'){ echo 'checked';	 } ?> id="cobra_sabado" disabled> &nbsp;
	
		<label class="lab">Domingo</label>
		<input type="checkbox" name="cobra_domingo" <?php if ($cobra_domingo == 'S'){ echo 'checked'; } ?> id="cobra_domingo" disabled>
	</div>
</div>

<hr>

<div class="hidden-xs">
	<!-- DETALLES ES PARA TODOS IGUAL -->
	<?php 
	if($t_comp !== 'REC'){
		$url = "onclick=\"detalles('".$t_comp."','".$n_comp."')\"";
		$descripcion = "Detalles"; 
		?>
		<a <?php echo $url; ?> class="btn btn-primary btn-form"><?php echo $descripcion; ?></a>
		<?php 
		$li .=
		'<li role="presentation" '.$active.'>
			<a id="detalle" aria-controls="detalle" role="tab" '.$url.'>'.$descripcion.'</a>
		</li>';
	}
	?>
	<!-- IMPUTACIONES VARIA DEPENDIEDO DEL TIPO -->
	<?php 
	$active = "";
	$descripcion = "Imputaciones";
	if($t_comp==='FAC' || $t_comp_aux === 'F'){
		$url =  "onclick=\"imputaciones('".$t_comp."','".$n_comp."', '_can')\""; 
	}else if($t_comp==='N/C' || $t_comp_aux === 'NC' || $t_comp==='N/D' || $t_comp_aux === 'ND' || $t_comp === 'REC'){
		$url =  "onclick=\"imputaciones('".$t_comp."','".$n_comp."', '')\""; 
	}
	?>
	<a <?php echo $url; ?> id='boton_imputaciones' class="btn btn-primary btn-form"><?php echo $descripcion; ?></a>
	<?php 
	$li .=
	'<li role="presentation" '.$active.'>
		<a id="imputaciones" aria-controls="imputaciones" role="tab" '.$url.'>'.$descripcion.'</a>
	</li>';
	?>

	<!-- RELACIONES VARIA DEPENDIEDO DEL TIPO -->
	<?php 
	if($t_comp==='FAC' || $t_comp_aux === 'F'){	
		$active = "";
		$descripcion = "Relaciones";
		$url =  "onclick=\"fac_relaciones('".$t_comp."','".$n_comp."')\"";
	?>	
		<a <?php echo $url; ?> id='boton_relaciones' class="btn btn-primary btn-form"><?php echo $descripcion; ?></a>
	<?php 
		$li .=
		'<li role="presentation" '.$active.'>
			<a id="relaciones" aria-controls="relaciones" role="tab" '.$url.'>'.$descripcion.'</a>
		</li>';
	}
	?>
	
	<!-- VENCIMIENTOS SOLO ES FACTURA -->
	<?php 
	if($t_comp==='FAC' || $t_comp_aux === 'F'){  
	$active = "";
	$descripcion = "Vencimientos";
	$url =  "onclick=\"fac_vencimientos('".$t_comp."','".$n_comp."')\""; 
	?>	
		<a <?php echo $url; ?> id='boton_vencimientos' class="btn btn-primary btn-form"><?php echo $descripcion; ?></a>
	<?php 
		$li .=
		'<li role="presentation" '.$active.'>
			<a id="vencimientos" aria-controls="vencimientos" role="tab" '.$url.'>'.$descripcion.'</a>
		</li>';
	}
	?>

	<!-- CHEQUES SOLO ES CON RECIBO -->
	<?php 
	if($t_comp==='REC'){
		$active = "";
		$descripcion = "Cheques";
		$url =  "onclick=\"rec_cheques('".$t_comp."','".$n_comp."')\"";
	?>
		<a <?php echo $url; ?> id='boton_cheques' class="btn btn-primary btn-form"><?php echo $descripcion; ?></a>
	<?php 
		$li .=
		'<li role="presentation" '.$active.'>
			<a id="cheques" aria-controls="cheques" role="tab" '.$url.'>'.$descripcion.'</a>
		</li>';
	}
	?>	

	<?php 
	$active = "";
	$descripcion = "Leyendas";
	$url = "onclick=\"leyendas('".$t_comp."','".$n_comp."')\""; 
	?>
	<a <?php echo $url; ?> id='boton_leyendas' class="btn btn-primary btn-form"><?php echo $descripcion; ?></a>
	<?php 
	$li .=
	'<li role="presentation" '.$active.'>
		<a id="leyendas" aria-controls="leyendas" role="tab" '.$url.'>'.$descripcion.'</a>
	</li>';
	?>	

	<?php 
	$active = "";
	$descripcion = "Actividades";
	$url = "onclick=\"actividades('".$t_comp."','".$n_comp."')\"";
	?>
	<a <?php echo $url; ?> id='boton_actividades' class="btn btn-primary btn-form"><?php echo $descripcion; ?></a>
	<?php 
	$li .=
	'<li role="presentation" '.$active.'>
		<a id="actividades" aria-controls="actividades" role="tab" '.$url.'>'.$descripcion.'</a>
	</li>';
	?>	
</div>

<ul class="nav nav-tabs visible-xs" role="tablist">
	<li class="dropdown">
		<a class="dropdown-toggle" data-toggle="dropdown" id="menu" style="background-color: #3091c6; color: #fff;" href="#"><?php echo $descripcion_dropdown; ?> <span class="caret"></span></a>
		<ul class="dropdown-menu">
			<?php echo $li; ?>
		</ul>
	</li>
</ul>

<br>
<div id="div_detalle"></div>

<input id="id_cliente" type='hidden' value = "<?php echo $id_cliente; ?>">

<script type="text/javascript">
	$(document).ready(function(){
		var t_comp = '<?php echo $t_comp;?>';
		var n_comp = '<?php echo $n_comp;?>';
		if(t_comp != "REC"){
			detalles(t_comp, n_comp);	
		}else{
			imputaciones(t_comp, n_comp, '');	
		}
		
	})
	function detalles(t_comp, n_comp){
		$.ajax({
			url: "<?php echo $this->session->userdata('dominio') ?>/api/comprobante_detalles",
			type: "POST",
			data:{n_comp, t_comp, empresa},
			dataType: "json",
			success: function(respuesta){
				var html = 
				`<div class="table-responsive">
					<table class="table table-hover">
						<tr>
							<th>C&oacute;digo</th>
							<th>Descripci&oacute;n</th>
							<th>Cantidad</th>
							<th>UM</th>
							<th>Precio</th>
							<th>Descuento</th>
							<th>Importe</th>
						</tr>`;
				var tamano = respuesta.length;
				var descripcion = "";
				if(tamano > 0){
					for(var i = 0; i < tamano; i++){
						pos = i;
						descripcion = respuesta[i]["DESCRIPCION"]+respuesta[i]["DESC_ADIC"];
						for(var j = i+1; j < tamano; j++){
							if(respuesta[j]["COD_ARTICU"] == ""){
								descripcion += " " + respuesta[j]["DESCRIPCION"]+respuesta[j]["DESC_ADIC"];
								i = j;
							}else{
								j = tamano;
							}
						}

						html += 
						`<tr>
							<td>${respuesta[pos]["COD_ARTICU"]}</td>
							<td>${descripcion}</td>
							<td>${respuesta[pos]["CANTIDAD"]}</td>
							<td>${respuesta[pos]["SIGLA_MEDIDA"]}</td>
							<td>${formatearNumero(respuesta[pos]["PRECIO"])}</td>
							<td>${formatearNumero(respuesta[pos]["DESCUENTO"])}</td>
							<td>${formatearNumero(respuesta[pos]["IMPORTE"])}</td>
						</tr>`;
					}
				}
				html += 
					`</table>
				</div>`;
				$("#div_detalle").html(html);
			}
		});
	}

	function imputaciones(t_comp, n_comp, tipo){
		$.ajax({
			url: "<?php echo $this->session->userdata('dominio') ?>/api/comprobante_imputaciones",
			type: "POST",
			data:{n_comp, t_comp, tipo, empresa},
			dataType: "json",
			success: function(respuesta){
				var html = 
				`<div class="table-responsive">
					<table class="table table-hover">
						<tr>
							<th>Tipo</th>
							<th>N&uacute;mero</th>
							<th>Fecha vencimiento</th>
							<th>Fecha de pago</th>
							<th>Importe a pagar</th>
							<th>Importe pagado</th>
							<th>Importe pendiente pagar</th>
						</tr>`;
				var tamano = respuesta.length;
				if(tamano > 0){
					for(var i = 0; i < tamano; i++){
						html += 
						`<tr>
							<td>${respuesta[i]["t_comp"]}</td>
							<td><a href="<?php echo base_url(); ?>detalle-comprobante?t_comp=${respuesta[i]["t_comp"]}&n_comp=${respuesta[i]["n_comp"]}">${respuesta[i]["n_comp"]}</a></td>
							<td>${respuesta[i]["fecha_vto"]}</td>
							<td>${respuesta[i]["f_comp_can"]}</td>
							<td>${formatearNumero(respuesta[i]["import_vt"])}</td>
							<td>${formatearNumero(respuesta[i]["import_can"])}</td>
							<td>${formatearNumero(respuesta[i]["pendiente"])}</td>
						</tr>`;
					}
				}
				html += 
					`</table>
				</div>`;
				$("#div_detalle").html(html);
			}
		});
	}

	function fac_relaciones(t_comp, n_comp){
		$.ajax({
			url: "<?php echo $this->session->userdata('dominio') ?>/api/comprobante_fac_relaciones",
			type: "POST",
			data:{n_comp, t_comp, empresa},
			dataType: "json",
			success: function(respuesta){
				var html = 
				`<div class="table-responsive">
					<table class="table table-hover">
						<tr>
							<th>Tipo</th>
							<th>N&uacute;mero</th>
							<th>Fecha</th>
						</tr>`;
				var tamano = respuesta.length;
				var inicio_etiqueta_a = "";
				var fin_etiqueta_a = "";
				if(tamano > 0){
					for(var i = 0; i < tamano; i++){
						inicio_etiqueta_a = "";
						fin_etiqueta_a = "";
						if(respuesta[i]["Tipo"] == "REC"){
							inicio_etiqueta_a = `<a href="<?php echo base_url(); ?>detalle-comprobante?t_comp=${respuesta[i]["Tipo"]}&n_comp=${respuesta[i]["Numero"]}">`;
							fin_etiqueta_a = "</a>";
						}
						html += 
						`<tr>							
							<td>${respuesta[i]["Tipo"]}</td>
							<td>${inicio_etiqueta_a+respuesta[i]["Numero"]+fin_etiqueta_a}</td>
							<td>${respuesta[i]["Fecha"]}</td>
						</tr>`;
					}
				}
				html += 
					`</table>
				</div>`;
				$("#div_detalle").html(html);
			}
		});
	}

	function fac_vencimientos(t_comp, n_comp){
		$.ajax({
			url: "<?php echo $this->session->userdata('dominio') ?>/api/comprobante_fac_vencimiento",
			type: "POST",
			data:{n_comp, t_comp, empresa},
			dataType: "json",
			success: function(respuesta){
				var html = 
				`<div class="table-responsive">
					<table class="table table-hover">
						<tr>
							<th>Fecha Vto</th>
							<th>Dias</th>
							<th>Estado</th>
							<th>Dias Vencidos</th>
							<th>Importe a pagar</th>
							<th>Importe pagado</th>
							<th>Importe pendiente pagar</th>
							<th>Tipo de pago</th>
							<th>Comprobante de pago</th>
							<th>Fecha de pago</th>
						</tr>`;
				var tamano = respuesta.length;
				if(tamano > 0){
					for(var i = 0; i < tamano; i++){
						html += 
						`<tr>
							<td>${respuesta[i]["FECHA_VTO"]}</td>
							<td>${respuesta[i]["DIAS"]}</td>
							<td>${respuesta[i]["ESTADO"]}</td>
							<td>${respuesta[i]["DIAS_VENCIDOS"]}</td>
							<td>${formatearNumero(respuesta[i]["IMPORTE A PAGAR"])}</td>
							<td>${formatearNumero(respuesta[i]["IMPORTE PAGADO"])}</td>
							<td>${formatearNumero(respuesta[i]["IMPORTE PENDIENTE A PAGAR"])}</td>
							<td>${respuesta[i]["TIPO DE COMPROBANTE DE PAGO"]}</td>
							<td><a href="<?php echo base_url(); ?>detalle-comprobante?t_comp=${respuesta[i]["TIPO DE COMPROBANTE DE PAGO"]}&n_comp=${respuesta[i]["NRO COMPROBANTE DE PAGO"]}">${respuesta[i]["NRO COMPROBANTE DE PAGO"]}</a></td>
							<td>${respuesta[i]["FECHA DE COMPROBANTE DE PAGO"]}</td>
						</tr>`;
					}
				}
				html += 
					`</table>
				</div>`;
				$("#div_detalle").html(html);
			}
		});
	}

	function rec_cheques(t_comp, n_comp){
		$.ajax({
			url: "<?php echo $this->session->userdata('dominio') ?>/api/comprobante_rec_cheques",
			type: "POST",
			data:{n_comp, t_comp, empresa},
			dataType: "json",
			success: function(respuesta){
				var html = 
				`<div class="table-responsive">
					<table class="table table-hover">
						<tr>
							<th>Banco</th>
							<th>Numero</th>
							<th>Fecha</th>
							<th>Importe</th>
							<th>Estado</th>
							<th>Fecha Salida</th>
							<th>Proveedor</th>
							<th>Nro Interno</th>
						</tr>`;
				var tamano = respuesta.length;
				if(tamano > 0){
					for(var i = 0; i < tamano; i++){
						html += 
						`<tr>
							<td>${respuesta[i]['cod_banco']} - ${respuesta[i]['desc_banco']}</td>
							<td>${respuesta[i]['n_cheque']}</td>
							<td>${respuesta[i]['fecha_cheq']}</td>
							<td>${respuesta[i]['importe_ch']}</td>
							<td>${respuesta[i]['descripcio']}</td>
							<td>${respuesta[i]['fecha_sal']}</td>
							<td>${respuesta[i]['proveedor']}</td>
							<td>${respuesta[i]['N_INTERNO']}</td>
						</tr>`;
					}
				}
				html += 
					`</table>
				</div>`;
				$("#div_detalle").html(html);
			}
		});
	}		

	function actividades(t_comp, n_comp){
		$.ajax({
			url: "<?php echo base_url() ?>/Cobranza/comprobante_actividades",
			type: "POST",
			data:{n_comp, t_comp},
			dataType: "json",
			success: function(respuesta){
				var html = 
				`<div class="table-responsive">
					<table class="table table-hover" id="actividades_comprobantes">
						<thead>
						<tr>
							<th>Asunto</th>
							<th>Fecha</th>
							<th>Tipo</th>
						</tr>
						<tr colspan="3">
							<input class="form-control" type="text" placeholder="Buscar" onkeyup="buscarEnTabla(this.value, 'actividades_comprobantes')">
						</tr>
						</thead>
						<tbody>`;
				var tamano = respuesta.length;
				if(tamano > 0){
					for(var i = 0; i < tamano; i++){
						html += 
						`<tr>
							<td><a href="<?php echo base_url() ?>detalle-${respuesta[i]["tipo"]}?id=${respuesta[i]["id"]}">${respuesta[i]["asunto"]}</a></td>
							<td>${respuesta[i]["fecha"]}</td>
							<td>${respuesta[i]["tipo"]}</td>
						</tr>`;
					}
				}
				html += 
					`</tbody>
					</table>
				</div>`;
				$("#div_detalle").html(html);
			}
		});
	}

	function leyendas(t_comp, n_comp){
		var leyenda_1 = "<?php echo $leyenda_1; ?>";
		var leyenda_2 = "<?php echo $leyenda_2; ?>";
		var leyenda_3 = "<?php echo $leyenda_3; ?>";
		var leyenda_4 = "<?php echo $leyenda_4; ?>";
		var leyenda_5 = "<?php echo $leyenda_5; ?>";
		var html = 
		`<div>
			<label class="lab">Leyenda 1</label>
			<textarea class="form-control" name="Leyenda1" id="Leyenda1" rows="2" disabled="true" style="background-color:white !important; resize:inherit;">${leyenda_1}</textarea>
		</div>
		<div>
			<label class="lab">Leyenda 2</label>
			<textarea class="form-control" name="Leyenda2" id="Leyenda2" rows="2" disabled="true" style="background-color:white !important; resize:inherit;">${leyenda_2}</textarea>
		</div>
		<div>
			<label class="lab">Leyenda 3</label>
			<textarea class="form-control" name="Leyenda3" id="Leyenda3" rows="2" disabled="true" style="background-color:white !important; resize:inherit;">${leyenda_3}</textarea>
		</div>
		<div>
			<label class="lab">Leyenda 4</label>
			<textarea class="form-control" name="Leyenda4" id="Leyenda4" rows="2" disabled="true" style="background-color:white !important; resize:inherit;">${leyenda_4}</textarea>
		</div>
		<div>
			<label class="lab">Leyenda 5</label>
			<textarea class="form-control" name="Leyenda4" id="Leyenda4" rows="2" disabled="true" style="background-color:white !important; resize:inherit;">${leyenda_5}</textarea>
		</div>`;
		$("#div_detalle").html(html);
	}

	function formatearNumero(valor, cant_decimal = 2, decimal = ",", miles = ".") {
	  try {
	    cant_decimal = Math.abs(cant_decimal);
	    cant_decimal = isNaN(cant_decimal) ? 2 : cant_decimal;

	    const signo_negativo = valor < 0 ? "-" : "";

	    let i = parseInt(valor = Math.abs(Number(valor) || 0).toFixed(cant_decimal)).toString();
	    let j = (i.length > 3) ? i.length % 3 : 0;

	    return signo_negativo + (j ? i.substr(0, j) + miles : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + miles) + (cant_decimal ? decimal + Math.abs(valor - i).toFixed(cant_decimal).slice(2) : "");
	  } catch (e) {
	  }
	}	
</script>