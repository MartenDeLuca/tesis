			<hr>
			<div class="row">
				<div class="col-md-3">
					<label class="lab">Gravado</label>
					<input type="text" style="background-color:white !important;" class="form-control" placeholder="Gravado" name="Gravado" id="Gravado" disabled="disabled" value = "<?php echo number_format($gravado,2, ',', '.'); ?>">
				</div>
				
				<div class="col-md-3">
					<label class="lab">Impuestos</label>
					<input type="text" style="background-color:white !important;" class="form-control" placeholder="Impuestos" name="Impuestos" id="Impuestos" disabled="disabled" value = "<?php echo number_format($impuestos,2, ',', '.'); ?>">
				</div>
				
				<div class="col-md-3">
					<label class="lab">Total $</label>
					<input type="text" style="background-color:white !important;" class="form-control" placeholder="Total $" name="Total" id="Total" disabled="disabled" value = "<?php echo number_format($importe_pesos,2, ',', '.'); ?>">
				</div>
				
				<div class="col-md-3">
					<label class="lab">Saldo</label>
					<input type="text" style="background-color:white !important;" class="form-control" placeholder="Saldo" name="Saldo" id="Saldo" disabled="disabled" value = "<?php echo number_format($saldo,2, ',', '.'); ?>">
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-3">
					<label class="lab">Exento</label>
					<input type="text" style="background-color:white !important;" class="form-control" placeholder="Exento" name="Exento" id="Exento" disabled="disabled" value = "<?php echo number_format($exento,2, ',', '.'); ?>">
				</div>
				
				<div class="col-xs-3 col-md-1">
					<label class="lab">Descuento</label>
					<input type="text" style="background-color:white !important;" class="form-control" placeholder="%" name="Descuento" id="Descuento" disabled="disabled" value = "<?php echo number_format($descuento_porc,2, ',', '.'); ?>">
				</div>
				
				<div class="col-xs-9 col-md-2">
					<label class="lab"><br></label>
					<input type="text" style="background-color:white !important;" class="form-control" placeholder="Descuento" name="Descuento1" id="Descuento1" disabled="disabled" value = "<?php echo number_format($descuento_numero,2, ',', '.'); ?>">
				</div>
				
				<div class="col-md-3">
					<label class="lab">Total U$S</label>
					<input type="text" style="background-color:white !important;" class="form-control" placeholder="Total U$S" name="TotalDol" id="TotalDol" disabled="disabled" value = "<?php echo number_format($importe_dol,2, ',', '.'); ?>">
				</div>
			</div>
		</div>
		</section>
	</div>
	<script>
	var empresa = "<?php echo $this->session->userdata('empresa'); ?>";
	$('document').ready(function(){
		$('#b_nombre').autocomplete({
			source: function(request, response){
				$.ajax({
					url: "<?php echo $this->session->userdata('dominio') ?>/api/buscarComprobantesParaSeleccion",
					type: "POST",
					data:{consulta:request.term, empresa},
					success: function(data){
						response(data);
					}
				});
			}, select: function(event, ui){
				$('#n_comp').val(ui.item.n_comp);
				$('#t_comp').val(ui.item.t_comp);
				sel();
			},focus: function(event) {
				event.preventDefault();
			} 
		});				
	});

	$("#b_nombre").on( "keydown", function( event ) {
		if (event.keyCode== $.ui.keyCode.LEFT || event.keyCode== $.ui.keyCode.RIGHT || event.keyCode== $.ui.keyCode.UP || event.keyCode== $.ui.keyCode.DOWN || event.keyCode== $.ui.keyCode.DELETE || event.keyCode== $.ui.keyCode.BACKSPACE ){
			$('#n_comp').val("");
			$('#t_comp').val("");						
		}
		if (event.keyCode==$.ui.keyCode.DELETE){
			$('#n_comp').val("");
			$('#t_comp').val("");						
		}
	});

	function sel(){
		if(document.getElementById("n_comp").value == ''){
			alert('No existe ese Comprobante.');
		}else{
			$(location).attr("href", document.getElementById("base_url").value+"detalle-comprobante?t_comp="+document.getElementById("t_comp").value+"&n_comp="+document.getElementById("n_comp").value);
		}
	}
			
	function borrar_sel(){
		document.getElementById("b_nombre").value = '';
		$('#n_comp').val("");
		$('#t_comp').val("");
	}
	</script>
</body>
</html>