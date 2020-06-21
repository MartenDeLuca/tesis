<div class="content-wrapper" style="background: white !important;">
  	<section class="content-header">
      <h1 id="h1_tablero">
        Seguimientos
      </h1>
      <ol class="breadcrumb">
        <li class="active">Datos</li>
      </ol>
	</section>
	<section class="content">
  		<div class="nav-tabs-custom">
		  	<ul class="nav nav-tabs">
		    	<li <?php if($instancia == "actividades"){ ?> class="active" <?php } ?>><a href="<?php echo base_url() ?>seguimiento?tipo=actividades">Actividades</a></li>
		    	<li <?php if($instancia == "mails"){ ?> class="active"<?php } ?>><a href="<?php echo base_url() ?>seguimiento?tipo=mails">Mails</a></li>
		    	<div class="pull-rigth">
		    		<div class="text-right">
						<a href="<?php echo base_url(); ?>agregar-<?php echo $instancia; ?>" class="btn btn-primary btn-form">Agregar</a>
						<a onclick="exportar()" class="btn btn-primary btn-form">Exportar</a>
					</div>
		    	</div>
		  	</ul>
		  	<div class="tab-content">
	    		<div id="reglas" class="tab-pane fade in active">		
					<table class="table table-striped table-bordered dt-responsive nowrap" id="tabla" style="width:100% !important;">
						<thead>
							<tr>
								<?php
									if($instancia == "actividades"){
								?>
										<th></th>
								<?php
									}

	                                for($i = 0; $i < $tamano; $i++){
	                            ?>
	                                    <th><?php echo $columna[$i]; ?></th>
	                            <?php
	                                }
	                            ?>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($datos as $fila) {
							?>
							<tr>
							<?php
								if($instancia == "actividades"){
							?>
									<td>
										<a onclick="eliminarActividad(<?php echo $fila["id_actividad"] ?>, this)"><span class="glyphicon glyphicon-trash"></span></a>&nbsp;&nbsp;&nbsp; 
										<a href="<?php echo base_url().'modificar-actividad?id='.$fila["id_actividad"] ?>"><span class="glyphicon glyphicon-pencil"></span></a>
									</td>
							<?php
								}
							   for($i = 0; $i < $tamano; $i++){
                            ?>
                                <th>
                                	<?php 
                                	$inicio = "";
                                	$fin = "";
                                	if(($instancia == "mails" && $key[$i] == "asunto")){
                                		$inicio = "<a href=".base_url()."detalle-correo?id=".$fila["id_mail"].">";
                                		$fin = "</a>";
                                	}else if($key[$i] == "cliente"){
                                		$inicio = "<a href=".base_url()."detalle-cliente?id=".$fila["id_cliente"].">";
                                		$fin = "</a>";
                                	}
                                	echo $inicio.$fila[$key[$i]].$fin;
                                	?>
                               	</th>
                            <?php
                                }
                            ?>
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
<input type="hidden" id="tipo" value="<?php echo $instancia; ?>">
<?php $this->load->view('menu/mostrar') ?>
<script type="text/javascript">
	$(document).ready(function(){
		$("#tabla").DataTable({
	        "aaSorting": [[ 1, "asc" ]], 
			"language": { "info": "_START_ a _END_ de _TOTAL_ registros", "zeroRecords": "", "emptyTable": "", "search": "<span onclick='modal_buscar()' class='glyphicon glyphicon-chevron-down'></span>", "searchPlaceholder": "Buscar...", "paginate": { "previous": "<", "next": ">"}, "sLengthMenu": "_MENU_" }
		});		
	});

	function eliminarActividad(id, objeto){
		if(confirm("Desea eliminar la actividad?")){
			$.ajax({
				type: "POST",
				url: document.getElementById("base_url").value+"Seguimiento/eliminarActividad",
				data: {id},
				success: function (respuesta) {
					if(respuesta == "OK"){
						$("#tabla").DataTable().row((objeto).closest("tr")).remove().draw();
					}else{
						alert(respuesta);
					}
				}
			})
		}
	}
</script>