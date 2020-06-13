<div class="content-wrapper" style="background: white !important;">
    <section class="content-header">
      <h1>
        <?php echo $asunto; ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url() ?>reglas">Reglas de negocio</a></li>
        <li><a href="<?php echo base_url() ?>modificar-regla?id=<?php echo $id_regla; ?>">Regla</a></li>
        <li class="active">Detalle Regla</li>
      </ol>      
  </section>
  <section class="content">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a data-step="alertas_tab" data-toggle="tab" href="#alertas_tab">Alertas</a></li>
        <li><a data-step="mails_tab" data-toggle="tab" href="#mails_tab">Mails</a></li>
      </ul>
      <div class="tab-content">
        <div id="alertas_tab" class="tab-pane fade in active">
          <?php 
          $datos["alertas"] = $alertas;
          $this->load->view('alertas/alertas_interno', $datos);
          ?>
        </div>
        <div id="mails_tab" class="tab-pane fade in">
          <table class="table" id="tabla_mails">
            <thead>
              <tr>
                <th>Asunto</th>
                <th>Destinatarios</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              foreach ($mails as $fila) {
              ?>
              <tr>
                <td><a href="<?php echo base_url() ?>detalle-correo?id=<?php echo $fila["id_mail"]; ?>"><?php echo $fila["asunto"]; ?></a></td>
                <td><?php echo $fila["destinatarios"]; ?></td>
              </tr>
              <?php 
              }
              ?>
            </tbody>
          </table>          
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
</script>
