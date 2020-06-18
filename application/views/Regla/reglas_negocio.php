<div class="content-wrapper" style="background: white !important;">
  	<section class="content-header">
      <h1 id="h1_tablero">
        Reglas de negocio
      </h1>
      <ol class="breadcrumb">
        <li class="active">Reglas de negocio</li>
      </ol>
	</section>
	<section class="content">
  		<div class="nav-tabs-custom">
		  	<ul class="nav nav-tabs">
		    	<li class="active"><a data-toggle="tab" href="#reglas">Datos</a></li>
		    	<div class="pull-rigth">
		    		<div class="text-right">
						<a title="Agregar regla de negocio" href="<?php echo base_url(); ?>agregar-regla" class="btn btn-primary btn-form">Agregar</a>
					</div>
		    	</div>
		  	</ul>
		  	<div class="tab-content">
	    		<div id="reglas" class="tab-pane fade in active">		
					<table class="table table-striped table-bordered dt-responsive nowrap" id="tabla">
						<thead>
							<tr>
								<th></th>
								<th>Asunto</th>
								<th>Intervalo</th>
								<th>Próxima ejecución</th>
								<th>Acción</th>
								<th>Estado</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							foreach ($reglas as $fila) {
								$intervalo = $fila["intervalo"];
								$tipoIntervalo = $fila["tipoIntervalo"];
								if ($tipoIntervalo == "Horas"){
									$intervalo = $intervalo / 60;
								} else if ($tipoIntervalo == "Dias"){
									$intervalo = $intervalo / 60 / 24;
								} else if ($tipoIntervalo == "Semanas"){
									$intervalo = $intervalo / 60 / 24 / 7;
								} else if ($tipoIntervalo == "Meses"){
									$intervalo = $intervalo / 60 / 24 / 30;
								}
								$intervalo = $intervalo.' '.$tipoIntervalo;
							?>
							<tr>
								<td><a onclick="eliminarFila(this, <?php echo $fila["id_regla"]; ?>)"><span class="glyphicon glyphicon-trash"></span></a>&nbsp;&nbsp;&nbsp; <a href="<?php echo base_url(); ?>modificar-regla?id=<?php echo $fila["id_regla"]; ?>"><span class="glyphicon glyphicon-pencil"></span></a></td>
								<td><a href="<?php echo base_url(); ?>detalle-regla?id=<?php echo $fila["id_regla"]; ?>"><?php echo $fila["asunto"]; ?></a></td>
								<td><?php echo $intervalo ?></td>
								<td><?php echo $fila["fecha2"]; ?></td>
								<td><?php echo $fila["accion"]; ?></td>
								<td><?php echo $fila["estado"]; ?></td>
							</tr>
							<?php 
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</section>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("#tabla").DataTable({
	        "aaSorting": [[ 1, "asc" ]], 
			"language": { "info": "_START_ a _END_ de _TOTAL_ registros", "zeroRecords": "", "emptyTable": "", "search": "", "searchPlaceholder": "Buscar...", "paginate": { "previous": "<", "next": ">"}, "sLengthMenu": "_MENU_" }
		});		
	});
	function eliminarFila(objeto, id_regla){
		if(confirm("Desea eliminar la regla de negocio?")){			
			$.ajax({
				url:document.getElementById("base_url").value+"Regla/regla_eliminar",
				type: "POST",
				data:{id_regla},
				dataType: "json",
				success: function(respuesta){
					if(respuesta[0] == "ok") {
						var tabla = $("#tabla").DataTable();
						tabla.row($(objeto).closest("tr")).remove().draw();
					}else{
						alert(respuesta[0]);
					}
				}
			});			
		}
	}
</script>
