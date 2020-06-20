<div class="content-wrapper" style="background: white !important;">	
    <section class="content-header">
        <h1>
          Actividad
        </h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url(); ?>seguimiento">Seguimiento</a></li>
          <li class="active">Formulario Actividad</li>
        </ol>
  	</section>
  	<section class="content">  		
		<?php $this->load->view('actividad/form_actividades_interno'); ?>
	</section>
</div>

<script type="text/javascript">
	var empresa = "<?php echo $this->session->userdata('empresa'); ?>";
	$('document').ready(function(){
		$(".input_select2").select2();
		$(".show-hide").parent().parent().hide();
	});
	
	function campo_dias_reclamo(dias_reclamo){
		if(dias_reclamo != ""){
			var dias_reclamo = dias_reclamo.split(';');
			var tamano = dias_reclamo.length;
			var dias = new Array("Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado", "Domingo");
			for(var j = 0; j < tamano; j++){
				var seleccionada = dias_reclamo[j];
				if(seleccionada == "S"){
					var dia_reclamo = dias[j];
					$("#dias_reclamo option[value='"+dia_reclamo+"']").attr("selected", true);
				}
			}
		}
	}

	function buscarContacto(){
		var id_cliente = $("#cod_cliente").val();
		var consulta = $("#modal-buscar").val();		
		$.ajax({
			url: "<?php echo $this->session->userdata('dominio') ?>/api/buscarContactos",
			type: "POST",
			data:{consulta, id_cliente, empresa},
			dataType: "json",
			success: function(respuesta){
				var html = `<table class='table'><tr><td>Contacto</td><td>Celular</td><td>Correo</td></tr>`;
				var tamano = respuesta.length;
				for(var i = 0; i < tamano; i++){
					html += `<tr style="cursor:pointer" onclick="seleccionarContacto(${respuesta[i]["id"]})"><td>${respuesta[i]["contacto"]}</td><td>${respuesta[i]["celular"]}</td><td>${respuesta[i]["correo"]}</td></tr>`;
				}
				html += `</tbody></table>`;
				$("#modal-datos").html(html);
			}
		});
	}

	function seleccionarContacto(id){
		var id_cliente = $("#id_cliente").val();
		$.ajax({
			url: "<?php echo $this->session->userdata('dominio') ?>/api/seleccionarContacto",
			type: "POST",
			data:{id, id_cliente, empresa},
			dataType: "json",
			success: function(respuesta){
				$("#id_contacto").val(id);
				$("#contacto").val(respuesta[0]["contacto"]);
				$("#correo").val(respuesta[0]["correo"]);
				$("#celular").val(respuesta[0]["celular"]);
				$("#modal").modal("hide");
			}
		});
	}
</script>