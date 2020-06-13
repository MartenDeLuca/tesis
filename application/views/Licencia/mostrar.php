

<div class="content-wrapper" style="background: white !important;">
  	<section class="content-header">
      <h1 id="h1_tablero">
        Licencias
      </h1>
	</section>
	<section class="content">
  		<div class="nav-tabs-custom">
		  	<ul class="nav nav-tabs">
		    	<li class="active"><a data-toggle="tab" href="#licencia">Licencias</a></li>
		    	<li ><a data-toggle="tab" href="#usuario">Usuarios</a></li>
		    	<div class="pull-rigth">
		    		<div class="text-right">
						<a title="Agregar regla de negocio" href="<?php echo base_url(); ?>agregar-licencia" class="btn btn-primary btn-form">Agregar Licencia</a>
					</div>
		    	</div>
		  	</ul>
		  	<div class="tab-content">
	    		<div id="licencia" class="tab-pane fade in active">		
					<table class="table table-bordered" id="tabla">
						<thead>
							<tr>
								<th>Licencia</th>
								<th>Dominio</th>
								<th>Diccionario</th>
								<th>Estado</th>
								<th>Empresa</th>
								<th>Accion</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							foreach ($licencias as $fila) {
							?>
							<tr>
								<td><?php echo $fila["licencia"]; ?></td>
								<td><?php echo $fila["dominio"]; ?></td>
								<td><?php echo $fila["diccionario"]; ?></td>
								<td id="estadoLicencia_<?php echo $fila["id_licencia"] ?>"><?php echo $fila["estado"]; ?></td>
								<td><?php echo $fila["empresa"]; ?></td>
								<td align="center">
									<a class="btn btn-success" <?php if ($fila['estado'] == 'Habilitado'){ ?> style='display: none;' <?php } ?> id="habilitarLicencia_<?php echo $fila["id_licencia"] ?>"  onclick="cambiarEstadoLicencia(this, <?php echo $fila["id_licencia"]; ?> , 'Habilitado')">Habilitar</a>
									<a class="btn btn-danger" <?php if ($fila['estado'] == 'Deshabilitado'){ ?> style='display: none;' <?php } ?> id="deshabilitarLicencia_<?php echo $fila["id_licencia"] ?>"  onclick="cambiarEstadoLicencia(this, <?php echo $fila["id_licencia"]; ?>, 'Deshabilitado')">Deshabilitar</a>
								</td>
							</tr>
							<?php 
							}
							?>
						</tbody>
					</table>
				</div>
				<div id="usuario" class="tab-pane fade">		
					<table class="table table-bordered" id="tablaUsuarios">
						<thead>
							<tr>
								<th>Nombre</th>
								<th>Correo</th>
								<th>Permisos</th>
								<th>Estado</th>
								<th>Empresa</th>
								<th>Accion</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							foreach ($usuarios as $filaUsuarios) {
							?>
							<tr>
								<td><?php echo $filaUsuarios["nombre"]; ?></td>
								<td><?php echo $filaUsuarios["correo"]; ?></td>
								<td><?php echo $filaUsuarios["permiso"]; ?></td>
								<td id="estadoUsuario_<?php echo $filaUsuarios["id_usuario"] ?>"><?php echo $filaUsuarios["estado"]; ?></td>
								<td><?php echo $filaUsuarios["empresa"]; ?></td>
								<td align="center">
									<a <?php if ($filaUsuarios['estado'] == 'Habilitado'){ ?> style='display: none;' <?php } ?>  class="btn btn-success" id="habilitarUsuario_<?php echo $filaUsuarios["id_usuario"] ?>" onclick="cambiarEstadoUsuario(this, <?php echo $filaUsuarios["id_usuario"]; ?>,'Habilitado')">Habilitar</a>
									<a <?php if ($filaUsuarios['estado'] == 'Deshabilitado'){ ?> style='display: none;' <?php } ?> class="btn btn-danger" id="deshabilitarUsuario_<?php echo $filaUsuarios["id_usuario"] ?>" onclick="cambiarEstadoUsuario(this, <?php echo $filaUsuarios["id_usuario"]; ?>,'Deshabilitado')">Deshabilitar</a>
								</td>
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
		$("#tablaUsuarios").DataTable({
	        "aaSorting": [[ 1, "asc" ]], 
			"language": { "info": "_START_ a _END_ de _TOTAL_ registros", "zeroRecords": "", "emptyTable": "", "search": "", "searchPlaceholder": "Buscar...", "paginate": { "previous": "<", "next": ">"}, "sLengthMenu": "_MENU_" }
		});		
	});

	function cambiarEstadoUsuario(objeto, id_usuario, estado){
		if(confirm("Desea cambiar el estado a "+estado+" del usuario seleccionado?")){
			$.ajax({
				url:document.getElementById("base_url").value+"Usuario/usuario_bd_modificar",
				type: "POST",
				data:{id_usuario, estado},
				success: function(respuesta){
					if(respuesta == "OK") {
						location.reload();
					}else{
						alert(respuesta[0]);
					}
				}
			});			
		}
	}

	function cambiarEstadoLicencia(objeto, id_licencia, estado){
		if(confirm("Desea cambiar el estado a "+estado+" de la licencia seleccionada? Todos los usuarios pertenecientes a la empresa cambiaran tambien cambiaran de estado")){			
			$.ajax({
				url:document.getElementById("base_url").value+"Usuario/licencia_bd_modificar",
				type: "POST",
				data:{id_licencia, estado},
				success: function(respuesta){
					console.log(respuesta);
					if(respuesta == "OK") {
						location.reload();
					}else{
						alert(respuesta);
					}
				}
			});			
		}
	}
</script>
