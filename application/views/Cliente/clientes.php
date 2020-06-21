<div class="content-wrapper" style="background: white !important;">
  	<section class="content-header">
      <h1 id="h1_tablero">
        Clientes
      </h1>
      <ol class="breadcrumb">
        <li class="active">Datos</li>
      </ol>
	</section>
	<section class="content">
  		<div class="nav-tabs-custom">
		  	<ul class="nav nav-tabs">
		    	<li class="active"><a href="#datos">Datos</a></li>
		    	<div class="pull-rigth">
		    		<div class="text-right">
						<a onclick="exportar()" class="btn btn-primary btn-form">Exportar</a>
					</div>
		    	</div>
		  	</ul>
		  	<div class="tab-content">
	    		<div id="datos" class="tab-pane fade in active">		
					<table class="table table-striped table-bordered dt-responsive nowrap" id="tabla" style="width:100% !important;">
						<thead>
							<tr>
								<th>Razon social</th>
								<th>Cant. Facturas</th>
								<th>Deuda</th>
								<th>Venc/No Venc</th>
								<th>Cumplimiento</th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($datos as $fila) {
							?>
							<tr>
								<td><a href="<?php echo base_url() ?>detalle-cliente?id=<?php echo $fila["id_gva14"]; ?>"><?php echo $fila["razon_soci"]; ?></a></td>
								<td style="text-align: right;"><?php echo $fila["cant_facturas"]; ?></td>
								<td style="text-align: right;"><?php echo number_format($fila["deuda"],2, ',', '.'); ?></td>
								<td>
									<?php 
									$vencida = number_format((((float) $fila["vencido"]*100) / ((float) $fila["deuda"])), 2, '.', '');
									$no_vencida = number_format((100 - $vencida), 2, '.', '');
									?>
									<div class="progress">
									    <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $vencida; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $vencida; ?>%">
									      <?php echo $vencida; ?>%
									    </div>
									    <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $no_vencida; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $no_vencida; ?>%">
									      <?php echo $no_vencida; ?>%
									    </div>
									</div> 
								</td>
								<td>
									<?php 
									$vencida = 100;
									$no_vencida = 0;
									?>
									<div class="progress">
									    <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $vencida; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $vencida; ?>%">
									      <?php echo $vencida; ?>%
									    </div>
									    <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $no_vencida; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $no_vencida; ?>%">
									      <?php echo $no_vencida; ?>%
									    </div>
									</div>
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
<?php $this->load->view('menu/mostrar') ?>

<input type="hidden" id="tipo" value="<?php echo $instancia; ?>">
<script type="text/javascript">
	$(document).ready(function(){
		$("#tabla").DataTable({
	        "aaSorting": [[ 2, "desc" ]], 
			"language": { "info": "_START_ a _END_ de _TOTAL_ registros", "zeroRecords": "", "emptyTable": "", "search": "<span onclick='modal_buscar()' class='glyphicon glyphicon-chevron-down'></span>", "searchPlaceholder": "Buscar...", "paginate": { "previous": "<", "next": ">"}, "sLengthMenu": "_MENU_" }
		});		
	});	
</script>