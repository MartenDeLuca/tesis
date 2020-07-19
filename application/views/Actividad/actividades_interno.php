    <div class="row">
      <div class="col-md-12">
        <ul class="timeline">
          <?php 
          $array_colores = array("red", "blue", "green", "yellow");
          $contador = 0;
          $fecha_anterior = "";
          foreach ($actividades as $fila) {
            $id_actividad = $fila['id_actividad'];
            $estado = $fila['estado'];
            $estadoSpan = '<span class="time"><small class="label pull-right bg-red">Pendiente</small></span>';
            if ($estado == 'Realizada'){
              $estadoSpan ='<span class="time"><small class="label pull-right bg-green">Realizada</small></span>';
            }
            if($fecha_anterior != $fila["fecha"]){
          ?>
              <li class="time-label">
                <span class="bg-<?php echo $array_colores[$contador]; ?>">
                  <?php echo $fila["fecha"]; ?>
                </span>
              </li>
            <?php 
              $fecha_anterior = $fila["fecha"];
              if($contador >= 5){
                $contador = 0;
              }else{
                $contador ++;
              }
            }
            ?>  
            <li style="cursor: pointer" onclick="javascript:location.href='<?php echo base_url() ?>modificar-actividad?id=<?php echo $id_actividad ?>'">
              <i class="fa fa-bell bg-yellow"></i>
              <div class="timeline-item" <?php if($fila["leido"] == "1"){ ?> style="background-color:#f0f0f0;" <?php } ?>>
                <span class="time"><i class="fa fa-clock-o"></i> <?php echo $fila["hora"]; ?></span>
                <?php echo $estadoSpan ?>
                <div class="timeline-header">
                  <b><?php echo $fila["asunto"]; ?></b>
                </div>
              </div>
            </li>        
          <?php 
          }
          ?>
        </ul>
      </div>
    </div>