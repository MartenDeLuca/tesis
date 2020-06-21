<script src="<?php echo base_url('plugin') ?>/amcharts/amcharts.js"></script>
<script src="<?php echo base_url('plugin') ?>/amcharts/plugins/dataloader/dataloader.min.js" type="text/javascript"></script>
<script src="<?php echo base_url('plugin') ?>/amcharts/serial.js"></script>
<script src="<?php echo base_url('plugin') ?>/amcharts/funnel.js"></script>
<script src="<?php echo base_url('plugin') ?>/amcharts/pie.js"></script>
<script src="<?php echo base_url('plugin') ?>/amcharts/plugins/export/export.min.js"></script>
<link href="<?php echo base_url('plugin') ?>/amcharts/plugins/export/export.css" type="text/css" media="all" rel="stylesheet"/>
<script src="<?php echo base_url('plugin') ?>/amcharts/themes/light.js"></script>
<style type="text/css">
  .form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
      background-color: white !important;  
  }
</style>
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
  </section>
  <section class="content">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a data-step="mails_tab" data-toggle="tab" href="#mails_tab">Correo</a></li>
        <li><a data-step="metricas_tab" data-toggle="tab" href="#metricas_tab">Metricas</a></li>
      </ul>
      <div class="tab-content">
          <div id="mails_tab" class="tab-pane fade in active">
            <div class="row">
              <div class="col-md-12">
                <label class="lab">Cliente</label>
                <input type="text" class="form-control" disabled id="cliente" value="<?php echo $correo["cliente"]; ?>">
                <input type="hidden" id="id_cliente" value="<?php echo $correo["id_cliente"]; ?>">
              </div>
            </div>     
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
                <textarea  disabled class="form-control" placeholder="Contenido Mail" name="contenido_mail" id="contenido_mail">
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
            <div class="row">
              <br>                    
              <?php 
              foreach ($adjuntos as $fila) { 
              ?>
                  <div class="col-md-6">
                    <div class="input-group" onclick="descargarArchivos(<?php echo $fila["id_adjunto"] ?>, <?php echo $id_correo ?>)">
                      <input type="text" class="form-control" readonly style="cursor:pointer" value = "<?php echo $fila["adjunto"] ?>">
                      <span class="input-group-addon add-on">
                        <span class="glyphicon glyphicon-paperclip"></span>
                      </span>
                    </div>
                  </div>
              <?php     
              }
              ?>
            </div>
          </div>        
          <div id="metricas_tab" class="tab-pane fade in">
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
                      <tr>
                        <td><?php echo $filaLeidos['destinatario'] ?></td>
                        <td><?php echo $filaLeidos['fechaCreacion'] ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>        
      </div>    
  </section>
</div>
<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
<script>
$(document).ready(function() {
  var total = '<?php echo $enviados; ?>';
  var leidos = '<?php echo $cantLeidos; ?>';
  var noLeidos = '<?php echo $noLeidos; ?>';
  var chart = AmCharts.makeChart("chartdiv", {
    "type": "pie",
    "theme": "light",
    "dataProvider": [{"title": "Leidos","value": leidos}, {"title": "No Leidos","value": noLeidos}],
    "valueField": "value",
    "titleField": "title",
    "balloon":{
      "fixedPosition":true
    },
    "export": {
      "enabled": true
    },
    "responsive": {
      "enabled": true
    }
  });
});

function descargarArchivos(id_adjunto, id_mail){
  location.href = '<?php echo base_url() ?>seguimiento/descargarArchivos?id_adjunto='+id_adjunto+'&id_mail='+id_mail;
}
</script>