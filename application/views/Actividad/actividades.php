<div class="content-wrapper" style="background: white !important;">
    <section class="content-header">
      <h1 id="h1_tablero">
        Actividades
      </h1>
  </section>
  <section class="content">
    <?php 
    $datos["actividades"] = $actividades; 
    $this->load->view('actividad/actividades_interno', $datos); ?>
  </section>
</div>    