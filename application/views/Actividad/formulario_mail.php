<div class="content-wrapper" style="background: white !important;">	
    <section class="content-header">
        <h1>
          Mails
        </h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url(); ?>seguimiento">Seguimiento</a></li>
          <li class="active">Formulario Mail</li>
        </ol>
  	</section>
  	<section class="content">
		<?php $this->load->view('actividad/form_mails_interno'); ?>
	  </section>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    modal("Plantilla");
  })
</script>