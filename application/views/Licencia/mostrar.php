

<div class="content-wrapper" style="background: white !important;">
  	<section class="content-header">
      <h1 id="h1_tablero">
        Usuarios
      </h1>
	</section>
	<section class="content">
  		<div class="nav-tabs-custom">
		  	<ul class="nav nav-tabs">
		  		<li class="active"><a data-toggle="tab" href="#usuario">Usuarios</a></li>
		  		<?php if ($this->session->userdata('permiso') == 'licencia'){ ?>
		    	<li><a data-toggle="tab" href="#licencia">Licencias</a></li>
		    	<?php } ?>
		    	<?php if ($this->session->userdata('permiso') == 'licencia'){ ?>
		    	<div class="pull-rigth">
		    		<div class="text-right">
						<a title="Agregar regla de negocio" href="<?php echo base_url(); ?>agregar-licencia" class="btn btn-primary btn-form">Agregar Licencia</a>
					</div>
		    	</div>
		    	<?php } ?>
		  	</ul>
		  	<div class="tab-content">
		  	<?php if ($this->session->userdata('permiso') == 'licencia'){ ?>
	    		<div id="licencia" class="tab-pane fade in">
					<table class="table table-striped table-bordered dt-responsive nowrap" style="width: 100% !important" id="tabla">
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
								$empresas = $fila->getEmpresasLicencia($fila->id_licencia);
							?>
							<tr>
								<td><?php echo $fila->licencia ?></td>
								<td><?php echo $fila->dominio ?></td>
								<td><?php echo $fila->diccionario ?></td>
								<td id="estadoLicencia_<?php echo $fila->id_licencia?>"><?php echo $fila->estado ?></td>
								<td>
								<?php foreach ($empresas as $filaEmpresas) {
									echo '<span class="time"><small class="label bg-primary">'.$filaEmpresas['empresa'].'</small></span> ';;
								} ?>
								</td>
								<td align="center">
									<a class="btn btn-success" <?php if ($fila->estado == 'Habilitado'){ ?> style='display: none;' <?php } ?> id="habilitarLicencia_<?php echo $fila->id_licencia ?>"  onclick="cambiarEstadoLicencia(this, <?php echo $fila->id_licencia; ?> , 'Habilitado')">Habilitar</a>
									<a class="btn btn-danger" <?php if ($fila->estado == 'Deshabilitado'){ ?> style='display: none;' <?php } ?> id="deshabilitarLicencia_<?php echo $fila->id_licencia ?>"  onclick="cambiarEstadoLicencia(this, <?php echo $fila->id_licencia; ?>, 'Deshabilitado')">Deshabilitar</a>
								</td>
							</tr>
							<?php 
							}
							?>
						</tbody>
					</table>
				</div>
			<?php } ?>
				<div id="usuario" class="tab-pane fade in active">		
					<table class="table table-striped table-bordered dt-responsive nowrap" style="width: 100% !important" id="tablaUsuarios">
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
								$empresasUsuarios = $filaUsuarios->empresasPorUsuario($filaUsuarios->id_usuario);
							?>
							<tr>
								<td><?php echo $filaUsuarios->nombre; ?></td>
								<td><?php echo $filaUsuarios->correo; ?></td>
								<td><?php echo $filaUsuarios->permiso; ?></td>
								<td id="estadoUsuario_<?php echo $filaUsuarios->id_usuario ?>"><?php echo $filaUsuarios->estado; ?></td>
								<?php if ($this->session->userdata('permiso') == 'licencia'){ ?>
									<td>
									<?php foreach ($empresasUsuarios as $filaEmpresas) {
										echo '<span class="time"><small class="label bg-primary">'.$filaEmpresas['empresa'].'</small></span> ';
									} ?>
									</td>
									<td align="center">
										
										<a <?php if ($filaUsuarios->estado == 'Habilitado'){ ?> style='display: none;' <?php } ?>  class="btn btn-success" id="habilitarUsuario_<?php echo $filaUsuarios->id_usuario ?>" onclick="cambiarEstadoUsuario(this, <?php echo $filaUsuarios->id_usuario; ?>,'Habilitado')">Habilitar</a>
										<a <?php if ($filaUsuarios->estado == 'Deshabilitado'){ ?> style='display: none;' <?php } ?> class="btn btn-danger" id="deshabilitarUsuario_<?php echo $filaUsuarios->id_usuario ?>" onclick="cambiarEstadoUsuario(this, <?php echo $filaUsuarios->id_usuario; ?>,'Deshabilitado')">Deshabilitar</a>
										
									</td>
								<?php } else { ?>
									<td><select class="form-control select2" id="empre_<?php echo $filaUsuarios->id_usuario ?>" multiple><option value=" "></option>
										<?php foreach ($empresasGeneral as $filaGenerales) { ?>
											<option
											<?php foreach ($empresasUsuarios as $filaEmpresas) { 
												if ($filaEmpresas['empresa'] ==  $filaGenerales['empresa']){
													echo 'selected';
													break;
												}
											} ?>
											 value="<?php echo $filaGenerales['id_empresa'] ?>">
												<?php echo $filaGenerales['empresa'] ?>
											</option>
										<?php } ?>	
									</select>
									
									</td>
									<td align="center">
										
										<a  class="btn btn-success" id="boton_<?php echo $filaUsuarios->id_usuario ?>" onclick="guardarEmpresas('<?php echo $filaUsuarios->id_usuario ?>')">Guardar</a>
									</td>
								<?php } ?>
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

	function guardarEmpresas(id_usuario){
		$('#boton_'+id_usuario).text('Guardando...');
		let empresas = $('#empre_'+id_usuario).val();
		$.ajax({
			type: "POST",
			url: document.getElementById("base_url").value+"Usuario/guardar_empresas",
			data:{empresas, id_usuario},
			success: function (respuesta) {
				if(respuesta == "OK"){
					$('#boton_'+id_usuario).text('Guardar');
				}
			}
		});
	}

	$('document').ready(function(){
		$(".select2").select2();
	});
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
