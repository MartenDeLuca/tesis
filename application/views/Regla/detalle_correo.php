<div class="content-wrapper" style="background: white !important;">
    <section class="content-header">
      <h1>
        <?php echo $correo['asunto'];
        $enviados =0 ;
        $cantLeidos = 0;
        $noLeidos = 0;
        $cantLeidos = count($leidos);
        $correo['destinatarios'] = explode(";", $correo['destinatarios']);
        $enviados = count($correo['destinatarios']);
        $noLeidos = $enviados - $cantLeidos;
      ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url() ?>reglas">Reglas</a></li>
        <li><a href="<?php echo base_url() ?>detalle-regla?id=<?php echo $correo['id_regla'] ?>"><?php echo $correo['reglaAsunto'] ?></a></li>
        <li class="active">Detalle Correo</li>
      </ol>      
  </section>
  <section class="content">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a data-step="mails_tab" data-toggle="tab" href="#mails_tab">Correo</a></li>
      </ul>
      <div class="tab-content">
        <div id="mails_tab" class="tab-pane fade in active">
          <div class="acordeon">
              <div class="acordeon__item">
                <input type="checkbox" name="acordeon" class="check-acordeon" id="item0" onchange="cambiar_check(0)" checked>
                <label for="item0" class="acordeon__titulo">
                  <div style="text-align:left;">Metricas<span style="float:right;"><span id="icon0" class="glyphicon glyphicon glyphicon-chevron-down"></span></span></div>
                </label>
                <div class="acordeon__contenido">
                   <div class="row">
                    <div class="col-md-8">
                      <table class="table table-bordered">
                        <tr>
                          <th>Enviados<br><?php echo $enviados ?></th>
                          <th>Leidos <br><?php echo $cantLeidos ?> </th>
                          <th>No Leidos<br><?php echo $noLeidos ?></th>
                        </tr>
                      </table>
                      <div id="chartdiv" style="width: 100%;height: 300px;"></div>
                    </div>
                    <div class="col-md-4">
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th colspan="2">Leidos</th>
                          </tr>
                          <tr>
                            <th>Destinatario</th>
                            <th>Fecha</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            
                           foreach ($leidos as $filaLeidos) { ?>
                            <tr><td><?php echo $filaLeidos['destinatario'] ?></td><td><?php echo $filaLeidos['fechaCreacion'] ?></td></tr>
                          <?php } ?>
                        </tbody>
                      </table>
                    </div>
                   </div>
                </div>
              </div>
            </div>
            <div class="acordeon">
              <div class="acordeon__item">
                <input type="checkbox" name="acordeon" class="check-acordeon" id="item2" onchange="cambiar_check(2)" checked>
                <label for="item2" class="acordeon__titulo">
                  <div style="text-align:left;">Contenido del mail <span style="float:right;"><span id="icon2" class="glyphicon glyphicon glyphicon-chevron-down"></span></span></div>
                </label>
                <div class="acordeon__contenido">
                  <div class="row">
                      <div class="col-md-12">
                        <label class="lab">Destinatarios fijos</label>
                        <select disabled="" class="form-control select2" multiple="multiple" name="destinatario_fijos[]" id="destinatario_fijos">
                        </select>
                        <div class="error_color" id="error_destinatario_fijos"></div>
                        <script type="text/javascript">
                          $(document).ready(function(){
                            <?php 
                          if($enviados > 0){
                              
                              foreach ($correo['destinatarios'] as $fila) {
                              ?>
                              var newState = new Option('<?php echo $fila; ?>', '<?php echo $fila; ?>', true, true);
                            $("#destinatario_fijos").append(newState).trigger('change');
                              <?php
                              }
                          }
                            ?>
                            var opciones = [];
                            var array = $('#destinatario_fijos option');
                            var tamano = array.length;
                            for(var i = tamano-1; i >= 0; i--){
                              if(opciones.indexOf($(array[i]).val()) > -1){
                                $(array[i]).remove();
                              }else{
                                  opciones.push($(array[i]).val());
                              }
                          }
                            $('#destinatario_fijos').select2({
                              placeholder:" Destinatarios",
                              tags: true,
                              tokenSeparators: [",", " "],
                              createTag: function (tag) {      
                                if(validoEmail(tag.term)) {
                                    return {
                                      id: tag.term,
                                      text: tag.term
                                    };      
                                }
                                return false;
                              },
                            });
                          });
                        </script>
                      </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <label class="lab">Contenido Mail</label>
                      <textarea class="form-control" placeholder="Contenido Mail" name="contenido_mail" id="contenido_mail">
                        <?php echo htmlspecialchars($contenido_mail) ?>
                      </textarea>
                      <div class="error_color" id="error_contenido_mail"></div>
                    </div>
                   <script>
                    CKEDITOR.replace('contenido_mail');
                    CKEDITOR.add

                    if (CKEDITOR.instances['contenido_mail']) {
                      CKEDITOR.instances['contenido_mail'].on('blur', function(event) {
                        validoCkeditor('contenido_mail');
                      });
                    }

                    CKEDITOR.on('contenido_mail', function(e) {
                      e.CKEDITOR.instances['contenido_mail'].addCss( 'body { background-color: red; }' );
                    });

                    CKEDITOR.instances['contenido_mail'].on('contentDom', function() {
                      CKEDITOR.instances['contenido_mail'].document.on('keyup', function(event) {
                            $('#cke_contenido_mail').children(".cke_inner").children('.cke_top').css('border','1px solid #d2d6de');
                            $('#cke_contenido_mail').children(".cke_inner").children('.cke_contents').css('border-left','1px solid #d2d6de');
                            $('#cke_contenido_mail').children(".cke_inner").children('.cke_contents').css('border-right','1px solid #d2d6de');
                            $('#cke_contenido_mail').children(".cke_inner").children('.cke_contents').css('border-bottom','1px solid #d2d6de'); 
                        document.getElementById("error_contenido_mail").innerHTML = '';
                      });
                    });
                    </script>
                  </div>
                 <!-- <div class="row">
                      <div class="col-md-12">
                        <div id="adjuntos">
                          <?php 
                          $contador = 0;
                          foreach ($adjuntos as $fila) {
                          ?>
                          <div class="row" id='fila_<?php echo $contador ?>'>
                            <div class="col-md-12">
                              <div class="input-group">
                                <input type="text" readonly class="form-control archivos_subidos" value="<?php echo $fila["adjunto"]; ?>" style="margin: 10px 0 0 0;" placeholder="Adjunto" name="archivos_subidos[]" id='archivo_<?php echo $contador ?>' >
                                <span class="input-group-btn">
                                  <a onclick="eliminarAdjunto(this)" class="btn btn-primary btn-sel" style="cursor: pointer; margin: 10px 0 0 0;">
                                    <span class="glyphicon glyphicon-trash"></span>
                                  </a>
                                </span>
                              </div>
                              <div class="error_color" id="error_adjunto_<?php echo $contador ?>"></div>
                            </div>
                          </div>
                          <?php
                            $contador ++;
                          }
                          ?>
                        </div>
                        <br>
                        <input type="hidden" id="tabla_adjunto_id" value="<?php echo $contador; ?>">
                      </div>
                  </div> -->
                </div>
              </div>
            </div>
        </div>
      </div>    
  </section>
</div>
<script type="text/javascript">
</script>


<!-- Resources -->
<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>

<!-- Chart code -->
<script>
am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("chartdiv", am4charts.PieChart);
var total = <?php echo $enviados ?>;
var leidos= <?php echo $cantLeidos ?>;
var noLeidos = <?php echo $noLeidos ?>;
// Add data
chart.data = [ {
  "country": "Leidos",
  "litres": leidos
}, {
  "country": "No Leidos",
  "litres": noLeidos
}];

// Add and configure Series
var pieSeries = chart.series.push(new am4charts.PieSeries());
pieSeries.dataFields.value = "litres";
pieSeries.dataFields.category = "country";
pieSeries.slices.template.stroke = am4core.color("#fff");
pieSeries.slices.template.strokeWidth = 2;
pieSeries.slices.template.strokeOpacity = 1;

// This creates initial animation
pieSeries.hiddenState.properties.opacity = 1;
pieSeries.hiddenState.properties.endAngle = -90;
pieSeries.hiddenState.properties.startAngle = -90;

}); // end am4core.ready()
</script>

<!-- HTML -->
