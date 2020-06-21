<?php 
$seguimientoModel = new seguimientoModel;
?>
<div class="box">
	<div class="box-header with-border">
		Comprobantes (<?php echo count($comprobantes); ?>)
		<div class="box-tools pull-right">
		<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
	</div>
	</div>
	<div class="box-body">
		<a class="btn btn-primary btn-form btn_anotacion" data-tipo="actividades" id="inicio_btn_anotacion" style="display: none;">Anotación</a>
		<div class="table-responsive">
			<table class="table" id="inicio_comprobantes">
				<thead>
					<tr>
						<th>Tipo</th>
						<th>Comprobante</th>
						<th>Estado</th>
						<th>Fecha</th>
						<th>Vencimiento</th>
						<th>Días</th>
						<th>Importe</th>
						<th>Fecha de pago</th>
						<th>Forma de pago</th>
						<th>Observación</th>
					</tr>	
				</thead>
				<tbody>
					<?php
					$total = 0; 
					$contador = 0;
					foreach ($comprobantes as $fila) {
						if($fila["dias"] < 0){
						$estilo_color = 'style="color:red; font-weight: bold;"';
					}else{
						$estilo_color = '';
					}
						$tipo = $fila["tipo"];
						$comprobante = $fila["comprobante"];
     				$acciones = $seguimientoModel->getActividadComprobante($tipo, $comprobante);
     				$fecha_pago = $acciones["fecha_pago"];
     				$forma_pago = $acciones["forma_pago"];
     				$observaciones = $acciones["observaciones"];
     				$comprobantes[$contador]["estado"] = 'Pendiente';
     				$comprobantes[$contador]["fecha_pago"] = $fecha_pago;
     				$comprobantes[$contador]["forma_pago"] = $forma_pago;
     				$comprobantes[$contador]["observaciones"] = $observaciones; 
					?>
						<tr data-id='<?php echo $contador; ?>'>
							<td><?php echo $tipo; ?></td>
							<td><?php echo $comprobante; ?></td>
							<td>Pendiente</td>
							<td><?php echo $fila["fecha"]; ?></td>
							<td><?php echo $fila["vencimiento"]; ?></td>
							<td <?php echo $estilo_color; ?>><?php echo $fila["dias"]; ?></td>
							<td style="text-align: right;"><?php echo $fila["importe"]; ?></td>
							<td><?php echo $fecha_pago; ?></td>
							<td><?php echo $forma_pago; ?></td>
							<td><?php echo $observaciones; ?></td>
						</tr>
					<?php
						$contador++;
						$total = $total + (float) $fila["importe"];
					}
					?>	
					<script> var comprobantes_pendientes = <?php echo json_encode($comprobantes); ?>;</script>
				</tbody>
				<tfoot>
					<tr>
						<td><b>Total<b></td>
						<td colspan="6" style="text-align: right;"><b><?php echo $total; ?></b></td>
						<td colspan="3"></td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>