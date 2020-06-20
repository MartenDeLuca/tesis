<div class="content-wrapper" style="background: white !important;">
  	<section class="content-header">
      <h1 id="h1_tablero">
        Plantillas de Mails
      </h1>
	</section>
	<section class="content">
  		<div class="nav-tabs-custom">
		  	<ul class="nav nav-tabs">
		    	<li class="active"><a data-toggle="tab" href="#plantillas">Datos</a></li>
		    	<div class="pull-rigth">
		    		<div class="text-right">
						<a title="Agregar regla de negocio" href="<?php echo base_url(); ?>agregar-plantilla" class="btn btn-primary btn-form">Agregar</a>
					</div>
		    	</div>
		  	</ul>
		  	<div class="tab-content">
	    		<div id="plantillas" class="tab-pane fade in active">		
					<table class="table table-striped table-bordered dt-responsive nowrap" id="tabla">
						<thead>
							<tr>
								<th></th>
								<th>Asunto</th>
								<th>Asunto Mail</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							foreach ($plantillas as $fila) {
							?>
							<tr>
								<td><a onclick="eliminarFila(this, <?php echo $fila["id_plantilla"]; ?>)"><span class="glyphicon glyphicon-trash"></span></a>&nbsp;&nbsp;&nbsp; <a href="<?php echo base_url(); ?>modificar-plantilla?id=<?php echo $fila["id_plantilla"]; ?>"><span class="glyphicon glyphicon-pencil"></span></a></td>
								<td><?php echo $fila["asunto"]; ?></td>
								<td><?php echo $fila["asunto_mail"]; ?></td>
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
	function eliminarFila(objeto, id_plantilla){
		if(confirm("Desea eliminar la plantilla de mail?")){			
			$.ajax({
				url:document.getElementById("base_url").value+"Plantilla/plantilla_eliminar",
				type: "POST",
				data:{id_plantilla},
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
