    <div class="row">
      <div class="col-md-12">
        <ul class="timeline">
          <?php 
          $array_colores = array("red", "blue", "green", "yellow");
          $contador = 0;
          $fecha_anterior = "";
          foreach ($alertas as $fila) {
            $mensaje_leido = "";
            if($fila["leido"] != "1"){
              $mensaje_leido = "Primera vez que se lee";
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
            <li title="<?php echo $mensaje_leido; ?>">
              <i class="fa fa-bell bg-yellow"></i>
              <div class="timeline-item">
                <span class="time"><i class="fa fa-clock-o"></i> <?php echo $fila["hora"]; ?></span>
                <div class="timeline-header"><?php echo $fila["descripcion"]; ?></div>
              </div>
            </li>        
          <?php 
          }
          ?>
        </ul>
      </div>
    </div>