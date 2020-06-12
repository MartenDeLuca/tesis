<div class="content-wrapper" style="background: white !important;">
    <section class="content-header">
      <h1 id="h1_tablero">
        Alertas
      </h1>
  </section>
  <section class="content">
    <?php 
    $datos["alertas"] = $alertas; 
    $this->load->view('alertas/alertas_interno', $datos); ?>
  </section>
</div>    